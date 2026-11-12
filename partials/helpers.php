<?php
/**
 * Petits utilitaires partagés par les templates.
 */
declare(strict_types=1);

/** Échappement HTML court. */
function e(?string $s): string
{
    return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** URL de ressource avec cache-busting basé sur le mtime. */
function asset(string $path): string
{
    $path = ltrim($path, '/');
    $abs  = __DIR__ . '/../' . $path;
    $v    = is_file($abs) ? (string) filemtime($abs) : '2';
    return $path . '?v=' . substr($v, 0, 6);
}

/** Numéro de version du site (cache CSS/JS global). */
function site_version(array $config): string
{
    return $config['site']['version'] ?? '2';
}

/** Lien téléphonique propre. */
function tel_href(array $config): string
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', $config['contact']['phone']);
}

/** URL WhatsApp (masquée si non renseignée). */
function whatsapp_href(array $config): string
{
    $wa = $config['contact']['whatsapp'] ?? '';
    if ($wa === '') {
        return '';
    }
    $digits = preg_replace('/[^0-9]/', '', $wa);
    return 'https://wa.me/' . $digits . '?text=' . rawurlencode($config['contact']['whatsapp_text'] ?? '');
}

/**
 * Surlignage « feutre » : le mot est enveloppé d'une marque citron
 * qui se dessine de gauche à droite (voir .hl dans style.css / main.js).
 */
function hl(string $word, string $modifier = ''): string
{
    return '<span class="hl' . ($modifier ? ' hl--' . $modifier : '') . '"><span class="hl__mark" aria-hidden="true"></span><span class="hl__text">'
        . $word . '</span></span>';
}

/** Rendu d'un titre H2 avec la partie surlignée. */
function h2(string $a, string $b, string $highlight, string $class = 'h2'): string
{
    $bHtml = $b;
    if ($highlight !== '' && mb_stripos($b, $highlight) !== false) {
        $bHtml = preg_replace(
            '/' . preg_quote($highlight, '/') . '/iu',
            hl($highlight),
            $b,
            1
        ) ?? $b;
    }
    return '<h2 class="' . $class . '"><span class="h2__line">' . e($a) . '</span> <span class="h2__line h2__line--b">' . $bHtml . '</span></h2>';
}

/** Jeu d'images responsive + LQIP à partir d'un chemin .jpg. */
function picture(string $src, string $alt, string $classes = '', int $sizesFactor = 100, bool $eager = false, string $extra = ''): string
{
    $src = ltrim($src, '/');
    $base = preg_replace('/\.(jpe?g|webp|png)$/i', '', $src);
    $lqip = $base . '-lqip.jpg';
    $w640 = $base . '-640.webp';
    $w1100 = $base . '-1100.webp';
    $w1600 = $base . '-1600.webp';
    $hasWebp = is_file(__DIR__ . '/../' . $w640);
    $loading = $eager ? 'eager" fetchpriority="high' : 'lazy';
    ob_start(); ?>
<picture class="pic <?= e($classes) ?>">
  <?php if ($hasWebp): ?>
  <source type="image/webp" srcset="<?= e(asset($w640)) ?> 640w, <?= e(asset($w1100)) ?> 1100w, <?= e(asset($w1600)) ?> 1600w" sizes="(max-width: 720px) 92vw, <?= (int) $sizesFactor ?>vw">
  <?php endif; ?>
  <img src="<?= e(asset($src)) ?>" alt="<?= e($alt) ?>" loading="<?= $loading ?>" decoding="async" <?= $extra ?> style="background-image:url(<?= e(asset($lqip)) ?>);background-size:cover;background-position:center">
</picture>
<?php
    return (string) ob_get_clean();
}

/** JSON-LD LocalBusiness. */
function jsonld_localbusiness(array $config): string
{
    $ho = $config['head_office'];
    $data = [
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => rtrim($config['seo']['url'], '/') . '/#entreprise',
        'name' => $config['company']['name'],
        'alternateName' => $config['company']['short'],
        'description' => $config['seo']['description'],
        'url' => rtrim($config['seo']['url'], '/') . '/',
        'telephone' => $config['contact']['phone'],
        'email' => $config['contact']['email'],
        'image' => rtrim($config['seo']['url'], '/') . '/' . ltrim($config['seo']['og_image'], '/'),
        'priceRange' => 'Sur devis',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $ho['address_line'],
            'postalCode' => $ho['postcode'],
            'addressLocality' => $ho['city'],
            'addressCountry' => 'FR',
        ],
        'areaServed' => array_map(
            static fn(array $d): array => ['@type' => 'AdministrativeArea', 'name' => $d[1]],
            $config['zone']['list']
        ),
        'openingHoursSpecification' => [
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '05:00', 'closes' => '19:00'],
            ['@type' => 'OpeningHoursSpecification', 'dayOfWeek' => 'Saturday', 'opens' => '05:00', 'closes' => '13:00'],
        ],
        'knowsAbout' => $config['seo']['keywords'],
    ];
    $sameAs = array_values(array_filter($config['socials']));
    if ($sameAs) {
        $data['sameAs'] = $sameAs;
    }
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . '</script>';
}

/** JSON-LD FAQPage. */
function jsonld_faq(array $config): string
{
    $items = [];
    foreach ($config['faq']['items'] as $item) {
        $items[] = [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $item['a']],
        ];
    }
    $data = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items];
    return '<script type="application/ld+json">' . json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . '</script>';
}

/** Couleur de secteur → classes utilitaires. */
function sector_color(array $config, string $slug): string
{
    foreach ($config['sectors']['items'] as $s) {
        if ($s['slug'] === $slug) {
            return $s['color'];
        }
    }
    return '#1F7A4C';
}
