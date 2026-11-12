<?php
/**
 * Distribution Services Transports — site one-page.
 * PHP natif 8.1+, aucune base de données, aucune compilation.
 */
declare(strict_types=1);

require __DIR__ . '/security.php';
// la session démarre avant le premier octet : sinon le cookie CSRF ne part pas
dst_session_start();
require __DIR__ . '/partials/helpers.php';
require __DIR__ . '/partials/icons.php';

$config = require __DIR__ . '/config.php';
date_default_timezone_set($config['site']['timezone'] ?? 'Europe/Paris');

// petite protection : refuse les injections de chemin dans les paramètres éventuels
$_GET = array_map(static fn($v): string => is_scalar($v) ? (string) $v : '', $_GET);

require __DIR__ . '/partials/head.php';
require __DIR__ . '/partials/header.php';
?>
<main id="contenu" tabindex="-1">
<?php
require __DIR__ . '/partials/hero.php';
require __DIR__ . '/partials/circuit.php';
require __DIR__ . '/partials/services.php';
require __DIR__ . '/partials/secteurs.php';
require __DIR__ . '/partials/flotte.php';
require __DIR__ . '/partials/zone.php';
require __DIR__ . '/partials/chiffres.php';
require __DIR__ . '/partials/engagements.php';
require __DIR__ . '/partials/galerie.php';
require __DIR__ . '/partials/temoignages.php';
require __DIR__ . '/partials/devis.php';
require __DIR__ . '/partials/faq.php';
?>
</main>
<?php
require __DIR__ . '/partials/footer.php';

require __DIR__ . '/partials/scripts.php';
?>
</body>
</html>
