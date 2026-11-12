<?php
/**
 * Sécurité partagée : session, jeton CSRF, limitation d'envoi.
 * Aucune dépendance, aucun framework.
 */
declare(strict_types=1);

const DST_STORAGE = __DIR__ . '/storage';

function dst_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    $secure = (($_SERVER['HTTPS'] ?? '') === 'on') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('dst_session');
    @session_start();
}

function csrf_token(): string
{
    dst_session_start();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(?string $token): bool
{
    dst_session_start();
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

/** IP cliente (sans se fier aveuglément aux en-têtes de proxy). */
function client_ip(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ?: '0.0.0.0';
}

/** Répertoire de stockage inscriptible (créé au besoin). */
function storage_dir(): ?string
{
    if (!is_dir(DST_STORAGE)) {
        @mkdir(DST_STORAGE, 0775, true);
    }
    if (!is_dir(DST_STORAGE) || !is_writable(DST_STORAGE)) {
        return null;
    }
    $ht = DST_STORAGE . '/.htaccess';
    if (!is_file($ht)) {
        @file_put_contents($ht, "Require all denied\n<IfModule !mod_authz_core.c>\n  Deny from all\n</IfModule>\n");
    }
    return DST_STORAGE;
}

/**
 * Limitation d'envoi par IP (fichier JSON, verrou exclusif).
 * @return array{allowed:bool, retry:int}
 */
function rate_limit(string $ip, int $maxPerHour): array
{
    $dir = storage_dir();
    if ($dir === null) {
        return ['allowed' => true, 'retry' => 0]; // ne jamais bloquer un envoi légitime
    }
    $file = $dir . '/ratelimit.json';
    $fh = @fopen($file, 'c+');
    if (!$fh) {
        return ['allowed' => true, 'retry' => 0];
    }
    flock($fh, LOCK_EX);
    $raw = stream_get_contents($fh) ?: '{}';
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        $data = [];
    }
    $now = time();
    $key = hash('sha256', $ip . '|dst');
    $hits = array_values(array_filter(
        array_map('intval', (array) ($data[$key] ?? [])),
        static fn(int $t): bool => $t > $now - 3600
    ));
    $allowed = count($hits) < $maxPerHour;
    $retry = 0;
    if ($allowed) {
        $hits[] = $now;
    } else {
        $retry = max(1, 3600 - ($now - min($hits)));
    }
    $data[$key] = $hits;
    // purge des entrées anciennes
    foreach ($data as $k => $v) {
        if (!is_array($v)) {
            unset($data[$k]);
            continue;
        }
        $v = array_filter(array_map('intval', $v), static fn(int $t): bool => $t > $now - 7200);
        if (!$v) {
            unset($data[$k]);
        } else {
            $data[$k] = array_values($v);
        }
    }
    rewind($fh);
    ftruncate($fh, 0);
    fwrite($fh, json_encode($data, JSON_UNESCAPED_SLASHES));
    fflush($fh);
    flock($fh, LOCK_UN);
    fclose($fh);
    return ['allowed' => $allowed, 'retry' => $retry];
}
