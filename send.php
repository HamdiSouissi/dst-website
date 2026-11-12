<?php
declare(strict_types=1);

/**
 * Traitement de la demande de devis.
 * - POST uniquement, jeton CSRF, honeypot, délai minimal, limitation par IP
 * - validation stricte et listes blanches
 * - email HTML (mail() ou SMTP natif), accusé de réception, sauvegarde CSV de secours
 * - réponse JSON : { "ok": true } ou { "ok": false, "errors": {…}, "message": "…" }
 */

date_default_timezone_set('Europe/Paris');

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, max-age=0');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: same-origin');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

$config = require __DIR__ . '/config.php';

/** Réponse JSON et fin de script. */
function respond(bool $ok, array $errors = [], string $message = '', array $extra = []): never
{
    $payload = ['ok' => $ok];
    if ($errors) {
        $payload['errors'] = $errors;
    }
    if ($message !== '') {
        $payload['message'] = $message;
    }
    echo json_encode($payload + $extra, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function clean(string $value, int $max = 500): string
{
    $value = trim($value);
    $value = str_replace(["\r", "\n", "\0"], ' ', $value);
    $value = strip_tags($value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $max, 'UTF-8');
    }
    return substr($value, 0, $max);
}

function cleanText(string $value, int $max = 1500): string
{
    $value = trim($value);
    $value = str_replace("\0", '', $value);
    if (function_exists('mb_substr')) {
        return mb_substr(strip_tags($value), 0, $max, 'UTF-8');
    }
    return substr(strip_tags($value), 0, $max);
}

function isPostcode(string $value): bool
{
    return (bool) preg_match('/^[0-9]{5}$/', $value);
}

function isPhone(string $value): bool
{
    return (bool) preg_match('/^\+?[0-9 \.\-\(\)]{6,25}$/', $value);
}

function clientIp(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/** Neutralise les formules dans les cellules CSV (injection Excel). */
function csvCell(string $value): string
{
    $value = str_replace(["\r", "\n"], ' ', $value);
    return preg_match('/^[=+\-@\t]/', $value) ? "'" . $value : $value;
}

function storageDir(): string
{
    $dir = __DIR__ . '/storage';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    return $dir;
}

/* ------------------------------------------------------------------ */
/* 1. Méthode                                                          */
/* ------------------------------------------------------------------ */
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    respond(false, [], 'Méthode non autorisée.');
}

/* ------------------------------------------------------------------ */
/* 2. CSRF                                                             */
/* ------------------------------------------------------------------ */
$token = (string) ($_POST['csrf_token'] ?? '');
$sessionToken = (string) ($_SESSION['csrf_token'] ?? '');
if ($sessionToken === '' || $token === '' || !hash_equals($sessionToken, $token)) {
    http_response_code(403);
    respond(false, ['global' => 'Votre session a expiré. Rechargez la page puis réessayez.'], 'Session expirée.');
}

/* ------------------------------------------------------------------ */
/* 3. Honeypot + délai minimal                                         */
/* ------------------------------------------------------------------ */
if (!empty($_POST['website'])) {
    respond(false, ['global' => 'L\'envoi n\'a pas abouti. Réessayez ou appelez-nous directement.'], 'Envoi refusé.');
}

$formTime = (int) ($_SESSION['form_time'] ?? 0);
$elapsed  = time() - $formTime;
if ($formTime <= 0 || $elapsed < 4 || $elapsed > 60 * 60 * 48) {
    respond(false, ['global' => 'L\'envoi n\'a pas abouti. Rechargez la page et réessayez.'], 'Délai invalide.');
}

/* ------------------------------------------------------------------ */
/* 4. Limitation : 5 envois / IP / heure                               */
/* ------------------------------------------------------------------ */
$rateFile = storageDir() . '/rate_limits.json';
$rateData = [];
if (is_file($rateFile)) {
    $decoded = json_decode((string) @file_get_contents($rateFile), true);
    if (is_array($decoded)) {
        $rateData = $decoded;
    }
}
$ipKey = hash('sha256', clientIp());
$now   = time();
$rateData[$ipKey] = array_values(array_filter(
    $rateData[$ipKey] ?? [],
    static fn ($ts) => is_int($ts) && ($now - $ts) < 3600
));
if (count($rateData[$ipKey]) >= (int) $config['form']['rate_limit_hour']) {
    http_response_code(429);
    respond(false, ['global' => 'Trop de demandes envoyées depuis cette connexion. Réessayez plus tard ou appelez-nous.'], 'Limite atteinte.');
}

/* ------------------------------------------------------------------ */
/* 5. Validation                                                       */
/* ------------------------------------------------------------------ */
$frozen = !empty($config['services_options']['frozen']);

$serviceTypes = ['Livraison ponctuelle', 'Tournée régulière', 'Express', 'Véhicule avec chauffeur', 'Autre'];
$goodsList    = ['Fruits & légumes', 'Viandes', 'Poissons & fruits de mer', 'Produits laitiers', 'Plats cuisinés', 'Pâtisseries', 'Boissons', 'Autre'];
$temperatures = ['0 à +4 °C', '+4 à +8 °C', 'Ambiante'];
if ($frozen) {
    $temperatures[] = 'Surgelé -18 °C';
}
$frequencies = ['Une fois', 'Plusieurs fois par semaine', 'Tous les jours', 'À définir'];

$errors = [];
$data   = [];

$data['service_type'] = clean((string) ($_POST['service_type'] ?? ''), 40);
if (!in_array($data['service_type'], $serviceTypes, true)) {
    $errors['service_type'] = 'Choisissez le type de prestation qui vous intéresse.';
}

$goodsRaw = $_POST['goods'] ?? [];
$goods = [];
if (is_array($goodsRaw)) {
    foreach ($goodsRaw as $g) {
        if (is_string($g) && in_array($g, $goodsList, true)) {
            $goods[] = $g;
        }
    }
}
$goods = array_slice(array_values(array_unique($goods)), 0, count($goodsList));
$data['goods'] = $goods;
if (!$goods) {
    $errors['goods'] = 'Indiquez au moins un type de marchandises.';
}

$data['temperature'] = clean((string) ($_POST['temperature'] ?? ''), 30);
if (!in_array($data['temperature'], $temperatures, true)) {
    $data['temperature'] = $temperatures[0];
}

$data['pickup_postcode'] = clean((string) ($_POST['pickup_postcode'] ?? ''), 5);
if (!isPostcode($data['pickup_postcode'])) {
    $errors['pickup_postcode'] = 'Indiquez un code postal à 5 chiffres.';
}

$data['delivery_postcode'] = clean((string) ($_POST['delivery_postcode'] ?? ''), 5);
if (!isPostcode($data['delivery_postcode'])) {
    $errors['delivery_postcode'] = 'Indiquez un code postal à 5 chiffres.';
}

$data['frequency'] = clean((string) ($_POST['frequency'] ?? 'À définir'), 40);
if (!in_array($data['frequency'], $frequencies, true)) {
    $data['frequency'] = 'À définir';
}

$startDate = clean((string) ($_POST['start_date'] ?? ''), 10);
$startTs = $startDate !== '' ? strtotime($startDate) : false;
if ($startDate !== '' && ($startTs === false || !preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $startDate))) {
    $errors['start_date'] = 'La date souhaitée n\'est pas valide.';
    $data['start_date'] = '';
} else {
    $data['start_date'] = $startDate;
}

$volume = filter_var($_POST['volume'] ?? 5, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 10]]);
$data['volume'] = $volume === false ? 5 : (int) $volume;

$data['name'] = clean((string) ($_POST['name'] ?? ''), 80);
if (strlen($data['name']) < 2) {
    $errors['name'] = 'Indiquez votre nom et prénom.';
}

$data['company'] = clean((string) ($_POST['company'] ?? ''), 120);
if (strlen($data['company']) < 2) {
    $errors['company'] = 'Indiquez le nom de votre entreprise.';
}

$data['phone'] = clean((string) ($_POST['phone'] ?? ''), 25);
if (!isPhone($data['phone'])) {
    $errors['phone'] = 'Indiquez un numéro pour que nous puissions vous rappeler.';
}

$data['email'] = clean((string) ($_POST['email'] ?? ''), 120);
if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Cet email ne semble pas valide. Vérifiez la saisie.';
}

$data['message'] = cleanText((string) ($_POST['message'] ?? ''), 1500);

if (($_POST['consent'] ?? '') !== '1') {
    $errors['consent'] = 'Merci de cocher cette case pour que nous puissions traiter votre demande.';
}

if ($errors) {
    respond(false, $errors, 'Certains champs demandent une correction.');
}

/* ------------------------------------------------------------------ */
/* 6. Véhicule conseillé (calculé côté serveur)                        */
/* ------------------------------------------------------------------ */
$fleet = $config['fleet'];
usort($fleet, static fn ($a, $b) => $a['volume'] <=> $b['volume']);
$recommended = $fleet[0]['name'];
foreach ($fleet as $vehicle) {
    if ($data['volume'] <= $vehicle['volume']) {
        $recommended = $vehicle['name'];
        break;
    }
    $recommended = $vehicle['name'];
}
$data['vehicle'] = $recommended;

/* ------------------------------------------------------------------ */
/* 7. Construction des emails                                          */
/* ------------------------------------------------------------------ */
$company   = $config['company'];
$contact   = $config['contact'];
$siteUrl   = rtrim($config['seo']['url'], '/');
$dateLabel = date('d/m/Y à H:i');

$esc = static fn (string $v): string => htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
$rows = [
    'Type de prestation' => $data['service_type'],
    'Marchandises'       => implode(', ', $data['goods']),
    'Température'        => $data['temperature'],
    'Enlèvement (CP)'    => $data['pickup_postcode'],
    'Livraison (CP)'     => $data['delivery_postcode'],
    'Fréquence'          => $data['frequency'],
    'Démarrage souhaité' => $data['start_date'] !== '' ? date('d/m/Y', (int) strtotime($data['start_date'])) : 'À définir',
    'Volume estimé'      => $data['volume'] . ' m³',
    'Véhicule conseillé' => $data['vehicle'],
    'Nom'                => $data['name'],
    'Entreprise'         => $data['company'],
    'Téléphone'          => $data['phone'],
    'Email'              => $data['email'],
    'Message'            => $data['message'] !== '' ? $data['message'] : '—',
];

$rowsHtml = '';
foreach ($rows as $label => $value) {
    $rowsHtml .= '<tr>'
        . '<td style="padding:9px 16px;border-bottom:1px solid #26302B;color:#7E8A83;font-size:12px;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;vertical-align:top">' . $esc($label) . '</td>'
        . '<td style="padding:9px 16px;border-bottom:1px solid #26302B;color:#F2F5F0;font-size:14px">' . nl2br($esc((string) $value)) . '</td>'
        . '</tr>';
}

$htmlBody = '<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>Nouvelle demande de devis</title></head>'
    . '<body style="margin:0;background:#0D0F0E;font-family:Arial,Helvetica,sans-serif">'
    . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#0D0F0E;padding:24px 12px">'
    . '<tr><td align="center">'
    . '<table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#161A18;border:1px solid #26302B;border-radius:14px;overflow:hidden">'
    . '<tr><td style="padding:24px 24px 8px">'
    . '<div style="font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:#5CF2B0">' . $esc($company['short']) . ' · Nouvelle demande</div>'
    . '<div style="font-size:24px;font-weight:bold;color:#F2F5F0;margin-top:8px">Demande de devis</div>'
    . '<div style="font-size:13px;color:#7E8A83;margin-top:6px">Reçue le ' . $esc($dateLabel) . '</div>'
    . '</td></tr>'
    . '<tr><td style="padding:8px 8px 0"><table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse">' . $rowsHtml . '</table></td></tr>'
    . '<tr><td style="padding:20px 24px 26px">'
    . '<a href="mailto:' . $esc($data['email']) . '" style="display:inline-block;background:#FF7A1A;color:#111412;font-weight:bold;text-decoration:none;padding:11px 18px;border-radius:8px;font-size:14px">Répondre à ' . $esc($data['name']) . '</a>'
    . '<div style="margin-top:16px;font-size:12px;color:#7E8A83">' . $esc($contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city']) . ' · ' . $esc($contact['phone_display']) . ' · <a href="' . $esc($siteUrl) . '" style="color:#5CF2B0;text-decoration:none">' . $esc(preg_replace('#^https?://#', '', $siteUrl)) . '</a></div>'
    . '</td></tr></table>'
    . '</td></tr></table></body></html>';

$ackBody = '<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>Votre demande de devis</title></head>'
    . '<body style="margin:0;background:#F2F5F0;font-family:Arial,Helvetica,sans-serif;color:#111412">'
    . '<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 12px">'
    . '<tr><td align="center"><table role="presentation" width="560" cellpadding="0" cellspacing="0" style="max-width:560px;width:100%;background:#ffffff;border:1px solid #E3E9E2;border-radius:14px">'
    . '<tr><td style="padding:26px 28px 4px"><div style="font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:#00A870">' . $esc($company['short']) . '</div>'
    . '<div style="font-size:22px;font-weight:bold;margin-top:10px">Demande bien reçue.</div></td></tr>'
    . '<tr><td style="padding:10px 28px 22px;font-size:14px;line-height:1.65">'
    . '<p>Bonjour ' . $esc($data['name']) . ',</p>'
    . '<p>Nous avons bien reçu votre demande concernant <strong>' . $esc($data['service_type']) . '</strong>'
    . ' (' . $esc(implode(', ', $data['goods'])) . ').</p>'
    . '<p>Nous revenons vers vous sous 24 h ouvrées avec une proposition adaptée. Pour une urgence, appelez-nous au '
    . '<a href="tel:' . $esc(preg_replace('/[^0-9+]/', '', $contact['phone'])) . '" style="color:#00A870;font-weight:bold;text-decoration:none">' . $esc($contact['phone_display']) . '</a>.</p>'
    . '<p style="margin-top:22px">À très vite,<br><strong>' . $esc($company['name']) . '</strong><br>'
    . $esc($contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city']) . '<br>'
    . $esc($contact['hours']) . '</p>'
    . '</td></tr></table></td></tr></table></body></html>';

/* ------------------------------------------------------------------ */
/* 8. SMTP natif (optionnel)                                           */
/* ------------------------------------------------------------------ */
final class DstSmtp
{
    private $socket = null;
    private array $log = [];

    public function __construct(private array $conf)
    {
    }

    private function read(): string
    {
        $data = '';
        while (($line = fgets($this->socket, 515)) !== false) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $this->log[] = '< ' . trim($data);
        return $data;
    }

    private function write(string $command): void
    {
        $this->log[] = '> ' . preg_replace('/(AUTH LOGIN|^[A-Za-z0-9+\/=]{16,}$)/', '[masqué]', $command);
        fwrite($this->socket, $command . "\r\n");
    }

    private function expect(string $command, array $codes): string
    {
        if ($command !== '') {
            $this->write($command);
        }
        $response = $this->read();
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $codes, true)) {
            throw new RuntimeException('SMTP: réponse inattendue ' . $code);
        }
        return $response;
    }

    public function send(string $to, string $subject, string $html, string $fromEmail, string $fromName, string $replyTo): bool
    {
        $host = (string) $this->conf['host'];
        $port = (int) $this->conf['port'];
        $secure = (string) ($this->conf['secure'] ?? 'tls');
        $prefix = $secure === 'ssl' ? 'ssl://' : '';

        $context = stream_context_create(['ssl' => ['verify_peer' => true, 'verify_peer_name' => true]]);
        $this->socket = @stream_socket_client($prefix . $host . ':' . $port, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
        if (!$this->socket) {
            throw new RuntimeException('SMTP: connexion impossible (' . $errstr . ')');
        }
        stream_set_timeout($this->socket, 15);

        $this->read();
        $this->expect('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);

        if ($secure === 'tls') {
            $this->expect('STARTTLS', [220]);
            if (!stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('SMTP: STARTTLS refusé');
            }
            $this->expect('EHLO ' . ($_SERVER['SERVER_NAME'] ?? 'localhost'), [250]);
        }

        $this->expect('AUTH LOGIN', [334]);
        $this->expect(base64_encode((string) $this->conf['user']), [334]);
        $this->expect(base64_encode((string) $this->conf['pass']), [235]);

        $this->expect('MAIL FROM:<' . $fromEmail . '>', [250]);
        $this->expect('RCPT TO:<' . $to . '>', [250, 251]);
        $this->expect('DATA', [354]);

        $headers = [
            'From: ' . $this->encodeHeader($fromName) . ' <' . $fromEmail . '>',
            'To: <' . $to . '>',
            'Reply-To: <' . $replyTo . '>',
            'Subject: ' . $this->encodeHeader($subject),
            'Date: ' . date('r'),
            'Message-ID: <' . bin2hex(random_bytes(10)) . '@' . ($_SERVER['SERVER_NAME'] ?? 'localhost') . '>',
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: base64',
            'X-Mailer: DST-Site',
        ];
        $body = chunk_split(base64_encode($html), 76, "\r\n");
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $body . "\r\n.";
        $this->expect($message, [250]);
        $this->expect('QUIT', [221]);
        fclose($this->socket);
        return true;
    }

    private function encodeHeader(string $value): string
    {
        $value = str_replace(["\r", "\n"], '', $value);
        return function_exists('mb_encode_mimeheader')
            ? mb_encode_mimeheader($value, 'UTF-8', 'B', "\r\n")
            : '=?UTF-8?B?' . base64_encode($value) . '?=';
    }
}

function sendMail(
    array $config,
    string $to,
    string $subject,
    string $html,
    string $replyTo,
    string $replyName,
    string $fromName
): bool {
    $fromEmail = $config['smtp']['from'] !== ''
        ? $config['smtp']['from']
        : ('no-reply@' . (parse_url($config['seo']['url'], PHP_URL_HOST) ?: 'localhost'));

    if (!empty($config['smtp']['enabled']) && $config['smtp']['host'] !== '' && $config['smtp']['user'] !== '') {
        try {
            $smtp = new DstSmtp($config['smtp']);
            return $smtp->send($to, $subject, $html, $fromEmail, $fromName, $replyTo);
        } catch (Throwable $e) {
            error_log('[DST] SMTP : ' . $e->getMessage());
            /* On retombe sur mail() si disponible. */
        }
    }

    $host = parse_url($config['seo']['url'], PHP_URL_HOST) ?: 'localhost';
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . '=?UTF-8?B?' . base64_encode($fromName) . '?= <' . $fromEmail . '>',
        'Reply-To: ' . '=?UTF-8?B?' . base64_encode($replyName) . '?= <' . $replyTo . '>',
        'Return-Path: ' . $fromEmail,
        'X-Mailer: DST-Site',
    ];

    return @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $html, implode("\r\n", $headers), '-f' . $fromEmail);
}

/* ------------------------------------------------------------------ */
/* 9. Envoi + sauvegarde                                               */
/* ------------------------------------------------------------------ */
$subject = 'Nouvelle demande de devis — ' . $data['company'] . ' — ' . $data['service_type'];
$mailSent = sendMail($config, $config['form']['to_email'], $subject, $htmlBody, $data['email'], $data['name'], $company['short'] . ' — Site');

$ackSent = false;
if (!empty($config['form']['send_ack'])) {
    $ackSubject = 'Votre demande de devis — ' . $company['name'];
    $ackSent = sendMail($config, $data['email'], $ackSubject, $ackBody, $config['form']['to_email'], $company['name'], $company['short']);
}

/* Sauvegarde CSV de secours (toujours écrite, même si mail() échoue). */
$csvPath = storageDir() . '/demandes.csv';
$csvExists = is_file($csvPath);
$handle = @fopen($csvPath, 'a');
if ($handle) {
    if (flock($handle, LOCK_EX)) {
        if (!$csvExists) {
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Date', 'IP', 'Prestation', 'Marchandises', 'Température', 'CP enlèvement', 'CP livraison', 'Fréquence', 'Démarrage', 'Volume', 'Véhicule', 'Nom', 'Entreprise', 'Téléphone', 'Email', 'Message', 'Email interne', 'Accusé'], ';', '"', '');
        }
        fputcsv($handle, [
            date('Y-m-d H:i:s'),
            clientIp(),
            csvCell($data['service_type']),
            csvCell(implode(', ', $data['goods'])),
            csvCell($data['temperature']),
            csvCell($data['pickup_postcode']),
            csvCell($data['delivery_postcode']),
            csvCell($data['frequency']),
            csvCell($data['start_date']),
            $data['volume'],
            csvCell($data['vehicle']),
            csvCell($data['name']),
            csvCell($data['company']),
            csvCell($data['phone']),
            csvCell($data['email']),
            csvCell($data['message']),
            $mailSent ? 'oui' : 'non',
            $ackSent ? 'oui' : 'non',
        ], ';', '"', '');
        flock($handle, LOCK_UN);
    }
    fclose($handle);
}

/* Mise à jour de la limitation de débit. */
$rateData[$ipKey][] = $now;
if (count($rateData) > 500) {
    $rateData = array_slice($rateData, -500, null, true);
}
@file_put_contents($rateFile, json_encode($rateData), LOCK_EX);

if (!$mailSent) {
    error_log('[DST] Échec mail() pour la demande de ' . $data['email'] . ' — sauvegarde CSV effectuée.');
}

/* Renouvellement du jeton (usage unique). */
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

respond(true, [], '', [
    'first_name' => explode(' ', $data['name'])[0] ?: $data['name'],
]);
