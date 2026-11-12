<?php
/**
 * Traitement du formulaire de devis — POST JSON uniquement.
 *
 * Sécurité : CSRF, honeypot, délai minimal, limitation par IP,
 * listes blanches, validation stricte, neutralisation des en-têtes.
 * Sortie : {"ok":true} ou {"ok":false,"errors":{…}}.
 */
declare(strict_types=1);

require __DIR__ . '/security.php';
$config = require __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function fail(array $errors, int $status = 422): never
{
    http_response_code($status);
    echo json_encode(['ok' => false, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['ok' => false, 'errors' => ['form' => 'Méthode non autorisée.']]);
    exit;
}

$raw  = file_get_contents('php://input') ?: '';
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

/* ---------- 1. Anti-bot ---------- */

// honeypot : un robot remplit ce champ caché
if (!empty($data['website'])) {
    echo json_encode(['ok' => true]); // on ne lui dit rien
    exit;
}

// délai minimal de 4 s entre l'affichage et l'envoi
$elapsed = isset($data['elapsed']) ? (float) $data['elapsed'] : 0.0;
$min     = (int) ($config['form']['min_seconds'] ?? 4);
if ($elapsed > 0 && $elapsed < $min) {
    fail(['form' => 'Envoi trop rapide. Merci de réessayer.']);
}

// CSRF
if (!csrf_check($data['csrf'] ?? null)) {
    fail(['form' => 'Session expirée. Rechargez la page et réessayez.'], 419);
}

// limitation par IP
$ip = client_ip();
$lim = rate_limit($ip, (int) ($config['form']['rate_limit_hour'] ?? 5));
if (!$lim['allowed']) {
    fail(['form' => 'Trop de demandes envoyées récemment. Merci de nous appeler au ' . $config['contact']['phone_display'] . '.'], 429);
}

/* ---------- 2. Validation ---------- */

$types       = ['Tournée régulière', 'Livraison ponctuelle', 'Express aujourd\'hui', 'Camionnette + chauffeur', 'Autre'];
$frequencies = ['Une fois', 'Plusieurs fois par semaine', 'Tous les jours', 'À définir'];
$goodsList   = ['Fruits & légumes', 'Viandes', 'Poissons', 'Produits laitiers & fromages', 'Plats cuisinés', 'Pâtisseries', 'Autre'];

$errors = [];
$clean  = static function (mixed $v, int $max = 200): string {
    $v = is_scalar($v) ? (string) $v : '';
    $v = str_replace(["\r", "\n", "\0"], ' ', $v);
    $v = strip_tags($v);
    $v = trim(preg_replace('/\s+/u', ' ', $v) ?? '');
    return mb_substr($v, 0, $max);
};

$name    = $clean($data['name'] ?? '', 80);
$company = $clean($data['company'] ?? '', 120);
$phone   = $clean($data['phone'] ?? '', 24);
$email   = trim((string) ($data['email'] ?? ''));
$message = mb_substr(trim(strip_tags((string) ($data['message'] ?? ''))), 0, 1200);
$type    = in_array($data['type'] ?? '', $types, true) ? $data['type'] : 'Autre';
$freq    = in_array($data['frequency'] ?? '', $frequencies, true) ? $data['frequency'] : 'À définir';
$volume  = min(10.0, max(1.0, (float) ($data['volume'] ?? 2)));
$goods   = array_values(array_intersect($goodsList, array_map('strval', (array) ($data['goods'] ?? []))));

$pcFrom = preg_replace('/\D/', '', (string) ($data['pc_from'] ?? ''));
$pcTo   = preg_replace('/\D/', '', (string) ($data['pc_to'] ?? ''));

if (mb_strlen($name) < 2) {
    $errors['name'] = 'Merci d\'indiquer votre nom.';
}
if (mb_strlen($company) < 2) {
    $errors['company'] = 'Merci d\'indiquer le nom de votre entreprise.';
}
if (!preg_match('/^\+?[0-9][0-9 ().\-]{7,20}$/', $phone)) {
    $errors['phone'] = 'Numéro de téléphone invalide.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 120) {
    $errors['email'] = 'Adresse email invalide.';
}
if (strlen($pcFrom) !== 5) {
    $errors['pc_from'] = 'Code postal d\'enlèvement à 5 chiffres.';
}
if (strlen($pcTo) !== 5) {
    $errors['pc_to'] = 'Code postal de livraison à 5 chiffres.';
}
if (empty($data['consent'])) {
    $errors['consent'] = 'Merci d\'accepter l\'utilisation de vos données pour traiter la demande.';
}
if ($errors) {
    fail($errors);
}

/* ---------- 3. Composition du message ---------- */

$id = sprintf('DST-%s-%s', gmdate('Ymd'), strtoupper(bin2hex(random_bytes(3))));
$record = [
    'id' => $id,
    'date' => date('c'),
    'name' => $name,
    'company' => $company,
    'phone' => $phone,
    'email' => $email,
    'type' => $type,
    'frequency' => $freq,
    'volume_m3' => $volume,
    'goods' => implode(' / ', $goods),
    'pc_from' => $pcFrom,
    'pc_to' => $pcTo,
    'message' => $message,
    'ip' => $ip,
    'user_agent' => mb_substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200),
];

$subject = sprintf(
    'Devis — %s — %s — %s → %s',
    $company,
    $type,
    $pcFrom,
    $pcTo
);

$esc = static fn(string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');

ob_start(); ?>
<!doctype html>
<html lang="fr"><head><meta charset="utf-8"><title><?= $esc($subject) ?></title></head>
<body style="margin:0;padding:0;background:#F1EADC;font-family:Helvetica,Arial,sans-serif;color:#2B1A2B">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F1EADC;padding:24px 12px">
  <tr><td align="center">
    <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#FAF6EE;border-radius:18px;overflow:hidden">
      <tr><td style="background:#1F7A4C;padding:20px 28px;color:#FAF6EE">
        <span style="font-size:26px;font-weight:800;letter-spacing:-.04em">DST</span>
        <span style="font-size:11px;letter-spacing:.18em;text-transform:uppercase;opacity:.85">Distribution Services Transports</span>
      </td></tr>
      <tr><td style="padding:28px 28px 8px">
        <p style="margin:0 0 6px;font-size:12px;letter-spacing:.16em;text-transform:uppercase;color:#7A6873">Nouvelle demande de devis · <?= $esc($id) ?></p>
        <h1 style="margin:0 0 18px;font-size:26px;line-height:1.1"><?= $esc($type) ?> · <?= $esc($pcFrom) ?> → <?= $esc($pcTo) ?></h1>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.5">
          <tr><td style="padding:6px 0;color:#7A6873;width:150px">Contact</td><td><?= $esc($name) ?></td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Entreprise</td><td><b><?= $esc($company) ?></b></td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Téléphone</td><td><a href="tel:<?= $esc(preg_replace('/[^0-9+]/', '', $phone)) ?>" style="color:#1F7A4C"><?= $esc($phone) ?></a></td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Email</td><td><a href="mailto:<?= $esc($email) ?>" style="color:#1F7A4C"><?= $esc($email) ?></a></td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Fréquence</td><td><?= $esc($freq) ?></td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Volume estimé</td><td><?= $esc(number_format($volume, 1, ',', '')) ?> m³</td></tr>
          <tr><td style="padding:6px 0;color:#7A6873">Marchandises</td><td><?= $esc($goods ? implode(', ', $goods) : 'Non précisé') ?></td></tr>
        </table>
      </td></tr>
      <?php if ($message !== ''): ?>
      <tr><td style="padding:0 28px 20px">
        <div style="background:#FFF;border-left:3px solid #F5DC4A;border-radius:8px;padding:14px 16px;font-size:14px;line-height:1.6"><?= nl2br($esc($message)) ?></div>
      </td></tr>
      <?php endif; ?>
      <tr><td style="padding:0 28px 28px">
        <a href="mailto:<?= $esc($email) ?>" style="display:inline-block;background:#1F7A4C;color:#FAF6EE;text-decoration:none;padding:12px 22px;border-radius:999px;font-size:14px;font-weight:600">Répondre à <?= $esc(explode(' ', $name)[0]) ?></a>
        <a href="tel:<?= $esc(preg_replace('/[^0-9+]/', '', $phone)) ?>" style="display:inline-block;margin-left:8px;background:#2B1A2B;color:#FAF6EE;text-decoration:none;padding:12px 22px;border-radius:999px;font-size:14px;font-weight:600">Appeler</a>
      </td></tr>
      <tr><td style="padding:16px 28px 24px;background:#F1EADC;color:#7A6873;font-size:11px;line-height:1.6">
        Reçu le <?= date('d/m/Y à H:i') ?> depuis le formulaire du site. IP : <?= $esc($ip) ?>.<br>
        Siège social : 48 Avenue du Docteur Durand, 94110 Arcueil — adresse administrative, aucune réception de marchandises.
      </td></tr>
    </table>
  </td></tr>
</table>
</body></html>
<?php
$html = (string) ob_get_clean();

/* ---------- 4. Envoi ---------- */

$sent = false;
$to   = $config['form']['to_email'];

$boundary = 'dst' . bin2hex(random_bytes(8));
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
    'From: DST Site <' . ($config['smtp']['from'] ?: ('no-reply@' . preg_replace('/^www\./', '', (string) parse_url($config['seo']['url'], PHP_URL_HOST)))) . '>',
    'Reply-To: ' . sprintf('"%s" <%s>', str_replace(['"', "\r", "\n"], '', $company . ' — ' . $name), $email),
    'X-Mailer: DST-site',
];

if (!empty($config['smtp']['enabled'])) {
    require __DIR__ . '/lib/SmtpMailer.php';
    try {
        $mailer = new SmtpMailer($config['smtp']);
        $sent = $mailer->send($to, $subject, $html, $email, $name, $company);
    } catch (Throwable $e) {
        error_log('[DST] SMTP: ' . $e->getMessage());
        $sent = false;
    }
}
if (!$sent) {
    $body = "--{$boundary}\r\nContent-Type: text/html; charset=UTF-8\r\nContent-Transfer-Encoding: 8bit\r\n\r\n{$html}\r\n--{$boundary}--";
    $sent = @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, implode("\r\n", $headers));
}

/* ---------- 5. Trace de secours + accusé ---------- */

if ($dir = storage_dir()) {
    $fh = @fopen($dir . '/demandes.csv', 'a');
    if ($fh) {
        if (flock($fh, LOCK_EX)) {
            $isNew = ftell($fh) === 0;
            if ($isNew) {
                fputcsv($fh, array_keys($record), ';');
            }
            fputcsv($fh, array_map(static fn($v): string => is_array($v) ? implode(' / ', $v) : (string) $v, $record), ';');
            flock($fh, LOCK_UN);
        }
        fclose($fh);
    }
}

if (!empty($config['form']['send_ack']) && $sent) {
    $ack = "Bonjour " . explode(' ', $name)[0] . ",\n\n"
        . "Nous avons bien reçu votre demande de devis (" . $id . ").\n"
        . "Nous vous répondons " . $config['contact']['response_time'] . ".\n\n"
        . "Pour une urgence, appelez-nous au " . $config['contact']['phone_display'] . ".\n\n"
        . "Distribution Services Transports\n"
        . "48 Avenue du Docteur Durand, 94110 Arcueil — siège social (adresse administrative)\n";
    @mail(
        $email,
        '=?UTF-8?B?' . base64_encode('Votre demande de devis — DST') . '?=',
        $ack,
        implode("\r\n", [
            'From: DST <' . $to . '>',
            'Reply-To: ' . $to,
            'Content-Type: text/plain; charset=UTF-8',
        ])
    );
}

if (!$sent) {
    fail(['form' => 'L\'envoi n\'a pas fonctionné. Réessayez ou appelez-nous au ' . $config['contact']['phone_display'] . '.'], 500);
}

echo json_encode(['ok' => true, 'id' => $id], JSON_UNESCAPED_UNICODE);
