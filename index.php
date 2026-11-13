<?php
declare(strict_types=1);

/**
 * DST — Distribution Services Transports
 * Site vitrine one-page · PHP natif 8.1+ · aucune base de données.
 */

date_default_timezone_set('Europe/Paris');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
    ]);
    session_start();
}

$config = require __DIR__ . '/config.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf = $_SESSION['csrf_token'];

/* Horodatage affiché : sert au contrôle de délai minimal côté serveur. */
$formTime = time();
$_SESSION['form_time'] = $formTime;

require __DIR__ . '/partials/icons.php';
require __DIR__ . '/partials/logo.php';

$contact = $config['contact'];
?>
<!DOCTYPE html>
<html lang="fr" class="no-js">
<head>
    <?php include __DIR__ . '/partials/head.php'; ?>
</head>
<body>
<?php include __DIR__ . '/partials/preloader.php'; ?>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="route-layer" data-route-layer aria-hidden="true">
    <svg viewBox="0 0 100 1000" preserveAspectRatio="none" fill="none">
        <path data-route-path
              d="M50 0 C18 84 82 156 50 248 S18 402 50 496 S82 650 50 744 S18 902 50 1000"
              pathLength="1" stroke-dasharray="1" stroke-dashoffset="1"
              stroke="currentColor" stroke-width="1.2" vector-effect="non-scaling-stroke"/>
    </svg>
    <span class="route-layer__dot" data-route-dot></span>
</div>

<main id="contenu">
    <?php include __DIR__ . '/partials/hero.php'; ?>
    <?php include __DIR__ . '/partials/marquee.php'; ?>
    <?php include __DIR__ . '/partials/chaine-froid.php'; ?>
    <?php include __DIR__ . '/partials/services.php'; ?>
    <?php include __DIR__ . '/partials/flotte.php'; ?>
    <?php include __DIR__ . '/partials/zone.php'; ?>
    <?php include __DIR__ . '/partials/secteurs.php'; ?>
    <?php include __DIR__ . '/partials/engagements.php'; ?>
    <?php include __DIR__ . '/partials/methode.php'; ?>
    <?php include __DIR__ . '/partials/temoignages.php'; ?>
    <?php include __DIR__ . '/partials/devis.php'; ?>
    <?php include __DIR__ . '/partials/faq.php'; ?>
    <?php include __DIR__ . '/partials/contact-band.php'; ?>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
<?php include __DIR__ . '/partials/floating.php'; ?>

<?php include __DIR__ . '/partials/modal-legal.php'; ?>
<?php include __DIR__ . '/partials/modal-privacy.php'; ?>
<?php include __DIR__ . '/partials/modal-cookies.php'; ?>
<?php include __DIR__ . '/partials/cookie-banner.php'; ?>

<noscript>
    <style>
        .preloader { display: none !important; }
        [data-reveal], .line-mask > .line { opacity: 1 !important; transform: none !important; }
        .sector__shutter { display: none !important; }
        .service__panel { display: block !important; }
        .quote-step:not([data-step="1"]) { display: block !important; }
        .cookie-banner { display: none !important; }
    </style>
</noscript>

<svg class="svg-sprite" aria-hidden="true" focusable="false" width="0" height="0" style="position:absolute">
    <defs>
        <filter id="grainFilter">
            <feTurbulence type="fractalNoise" baseFrequency="0.9" numOctaves="2" stitchTiles="stitch"/>
            <feColorMatrix type="saturate" values="0"/>
        </filter>
    </defs>
</svg>

<!-- Bibliothèques (CDN, defer). Les animations n'utilisent que transform / opacity. -->
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/gsap.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrollTrigger.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/SplitText.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/DrawSVGPlugin.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/MotionPathPlugin.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/Flip.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/gsap@3.13.0/dist/ScrambleTextPlugin.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/lenis@1.1.20/dist/lenis.min.js"></script>
<script type="module" src="assets/js/main.js?v=1.1.6"></script>
</body>
</html>
