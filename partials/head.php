<?php
/** @var array $config */
$c        = $config['company'];
$contact  = $config['contact'];
$seo      = $config['seo'];
$phoneE164 = preg_replace('/[^0-9+]/', '', $contact['phone']);
$addr     = $contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city'];

$sameAs = [];
foreach ($config['socials'] as $network => $url) {
    if ($url !== '' && $network !== 'whatsapp') {
        $sameAs[] = $url;
    }
}

$areaServed = [];
foreach ($config['zones'] as $zone) {
    $areaServed[] = ['@type' => 'AdministrativeArea', 'name' => $zone['name'] . ' (' . $zone['code'] . ')'];
}

$faqEntities = [];
foreach ($config['faq'] as $item) {
    $faqEntities[] = [
        '@type'          => 'Question',
        'name'           => $item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
    ];
}

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'LocalBusiness',
            '@id'         => rtrim($seo['url'], '/') . '/#business',
            'name'        => $c['name'],
            'alternateName' => $c['short'],
            'description' => $seo['description'],
            'url'         => $seo['url'],
            'telephone'   => $phoneE164,
            'email'       => $contact['email'],
            'image'       => rtrim($seo['url'], '/') . '/assets/img/og-image.jpg',
            'logo'        => rtrim($seo['url'], '/') . '/assets/img/favicon-180.png',
            'priceRange'  => 'Sur devis',
            'address'     => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $contact['address_line'],
                'addressLocality' => $contact['city'],
                'postalCode'      => $contact['postcode'],
                'addressCountry'  => 'FR',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $contact['geo']['lat'],
                'longitude' => $contact['geo']['lng'],
            ],
            'areaServed'     => $areaServed,
            'openingHoursSpecification' => [[
                '@type'     => 'OpeningHoursSpecification',
                'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'opens'     => '05:00',
                'closes'    => '19:00',
            ]],
            'sameAs' => $sameAs,
        ],
        [
            '@type'      => 'FAQPage',
            '@id'        => rtrim($seo['url'], '/') . '/#faq',
            'mainEntity' => $faqEntities,
        ],
    ],
];
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?></title>
<meta name="description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
<link rel="canonical" href="<?= htmlspecialchars($seo['url'], ENT_QUOTES, 'UTF-8') ?>">
<meta name="theme-color" content="#0E0D0B">
<meta name="color-scheme" content="dark light">
<meta name="author" content="Prime Vision Solutions">
<meta name="format-detection" content="telephone=no">

<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:locale" content="fr_FR">
<meta property="og:title" content="<?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:url" content="<?= htmlspecialchars($seo['url'], ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image" content="<?= htmlspecialchars(rtrim($seo['url'], '/') . '/' . $seo['og_image'], ENT_QUOTES, 'UTF-8') ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="Camionnette frigorifique DST et route lumineuse — Le frais, livré à l'heure.">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($seo['description'], ENT_QUOTES, 'UTF-8') ?>">
<meta name="twitter:image" content="<?= htmlspecialchars(rtrim($seo['url'], '/') . '/' . $seo['og_image'], ENT_QUOTES, 'UTF-8') ?>">

<link rel="icon" href="assets/img/favicon.svg" type="image/svg+xml">
<link rel="icon" href="assets/img/favicon-32.png" sizes="32x32" type="image/png">
<link rel="apple-touch-icon" href="assets/img/favicon-180.png">
<link rel="manifest" href="site.webmanifest">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://api.fontshare.com">
<link rel="preconnect" href="https://cdn.fontshare.com" crossorigin>
<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
<link rel="preload" as="image" href="assets/img/hero-poster.webp" fetchpriority="high">
<link rel="preload" as="image" href="assets/img/flotte/master.webp" imagesrcset="assets/img/flotte/master-sm.webp 700w, assets/img/flotte/master.webp 1270w" imagesizes="(max-width: 899px) 92vw, 54vw" type="image/webp" fetchpriority="high">

<link rel="stylesheet" href="https://api.fontshare.com/v2/css?f[]=cabinet-grotesk@500,700,800&amp;display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@1&amp;family=Inter:wght@400;500;600&amp;family=IBM+Plex+Mono:wght@400;500&amp;display=swap">
<link rel="stylesheet" href="assets/css/style.css?v=1.1.5">

<script>
/* JS disponible ? (dégradation gracieuse) */
document.documentElement.classList.remove('no-js');
document.documentElement.classList.add('js');
try {
  var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (!reduce && !sessionStorage.getItem('dst-preloader')) {
    document.documentElement.classList.add('preloading');
    /* Filet de sécurité si le CDN GSAP ne répond pas */
    setTimeout(function () {
      document.documentElement.classList.remove('preloading');
      document.documentElement.classList.add('preloader-done');
    }, 4500);
  }
} catch (e) {}
</script>

<script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?></script>
