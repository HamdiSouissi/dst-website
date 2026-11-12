<?php
/** @var array $config */
$seo = $config['seo'];
$url = rtrim($seo['url'], '/') . '/';
?>
<!DOCTYPE html>
<html lang="fr" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= e($seo['title']) ?></title>
<meta name="description" content="<?= e($seo['description']) ?>">
<link rel="canonical" href="<?= e($url) ?>">
<meta name="theme-color" content="<?= e($seo['theme_color']) ?>">
<meta name="robots" content="index, follow, max-image-preview:large">
<meta name="author" content="Prime Vision Solutions">

<meta property="og:type" content="website">
<meta property="og:locale" content="<?= e($seo['locale']) ?>">
<meta property="og:site_name" content="<?= e($config['company']['name']) ?>">
<meta property="og:title" content="<?= e($seo['title']) ?>">
<meta property="og:description" content="<?= e($seo['description']) ?>">
<meta property="og:url" content="<?= e($url) ?>">
<meta property="og:image" content="<?= e(rtrim($seo['url'], '/') . '/' . ltrim($seo['og_image'], '/')) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="DST — du fournisseur à votre porte, produits frais en camionnettes frigorifiques">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($seo['title']) ?>">
<meta name="twitter:description" content="<?= e($seo['description']) ?>">
<meta name="twitter:image" content="<?= e(rtrim($seo['url'], '/') . '/' . ltrim($seo['og_image'], '/')) ?>">

<link rel="icon" href="assets/img/sticker.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="manifest" href="site.webmanifest">

<link rel="preload" href="<?= e(asset('assets/fonts/bricolage-grotesque-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(asset('assets/fonts/instrument-sans-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?= e(asset('assets/img/flotte/master.svg')) ?>" as="image" type="image/svg+xml" fetchpriority="high">
<link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">

<script>document.documentElement.classList.replace('no-js','js');</script>
<?= jsonld_localbusiness($config) ?>
<?= jsonld_faq($config) ?>
</head>
<body>
<a class="skip-link" href="#contenu">Aller au contenu</a>
