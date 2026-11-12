<?php
/**
 * Envoi SMTP minimaliste (sans Composer) : socket + STARTTLS + AUTH LOGIN.
 * Optionnel — activé depuis config.php si l'hébergement ne permet pas mail().
 */
declare(strict_types=1);

final class SmtpMailer
{
    private array $cfg;
    /** @var resource|null */
    private $sock = null;

    public function __construct(array $cfg)
    {
        $this->cfg = $cfg;
    }

    public function send(string $to, string $subject, string $html, string $replyToEmail = '', string $replyToName = '', string $replyToCompany = ''): bool
    {
        $host = (string) ($this->cfg['host'] ?? '');
        $port = (int) ($this->cfg['port'] ?? 587);
        $user = (string) ($this->cfg['user'] ?? '');
        $pass = (string) ($this->cfg['pass'] ?? '');
        $from = (string) ($this->cfg['from'] ?? $user);
        $fromName = (string) ($this->cfg['from_name'] ?? 'DST');

        if ($host === '' || $from === '') {
            throw new RuntimeException('SMTP mal configuré');
        }

        $ctx = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true]]);
        $this->sock = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
        if (!$this->sock) {
            throw new RuntimeException("Connexion SMTP impossible : {$errstr}");
        }
        stream_set_timeout($this->sock, 20);

        $this->expect('220');
        $this->cmd('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), '250');

        if (!empty($this->cfg['secure'])) {
            $this->cmd('STARTTLS', '220');
            $ok = @stream_socket_enable_crypto($this->sock, true, STREAM_CRYPTO_METHOD_TLS_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
            if ($ok !== true) {
                throw new RuntimeException('STARTTLS refusé');
            }
            $this->cmd('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), '250');
        }

        if ($user !== '') {
            $this->cmd('AUTH LOGIN', '334');
            $this->cmd(base64_encode($user), '334');
            $this->cmd(base64_encode($pass), '235');
        }

        $this->cmd('MAIL FROM:<' . $from . '>', '250');
        $this->cmd('RCPT TO:<' . $to . '>', ['250', '251']);
        $this->cmd('DATA', '354');

        $boundary = 'dst' . bin2hex(random_bytes(6));
        $subjectEnc = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = [
            'From: ' . $this->enc($fromName) . ' <' . $from . '>',
            'To: <' . $to . '>',
            'Subject: ' . $subjectEnc,
            'Date: ' . date('r'),
            'Message-ID: <' . bin2hex(random_bytes(8)) . '@' . (parse_url('http://' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), PHP_URL_HOST) ?: 'localhost') . '>',
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
        ];
        if ($replyToEmail !== '') {
            $headers[] = 'Reply-To: ' . $this->enc(trim($replyToCompany . ' — ' . $replyToName)) . ' <' . $replyToEmail . '>';
        }

        $data = implode("\r\n", $headers) . "\r\n\r\n" . chunk_split(base64_encode($html), 76, "\r\n") . ".";
        $this->write($data);
        $this->expect('250');
        $this->cmd('QUIT', '221');
        fclose($this->sock);
        $this->sock = null;
        return true;
    }

    private function enc(string $s): string
    {
        return '=?UTF-8?B?' . base64_encode($s) . '?=';
    }

    private function write(string $s): void
    {
        fwrite($this->sock, $s . "\r\n");
    }

    /** @param string|string[] $expect */
    private function expect(string|array $expect): string
    {
        $expect = (array) $expect;
        $line = '';
        do {
            $line = fgets($this->sock, 1024);
            if ($line === false) {
                throw new RuntimeException('Réponse SMTP absente');
            }
        } while (isset($line[3]) && $line[3] === '-'); // réponses multi-lignes
        $code = substr($line, 0, 3);
        if (!in_array($code, $expect, true)) {
            throw new RuntimeException('SMTP : ' . trim($line));
        }
        return $line;
    }

    private function cmd(string $cmd, string|array $expect): void
    {
        $this->write($cmd);
        $this->expect($expect);
    }
}
