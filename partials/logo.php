<?php
/**
 * Logotype DST + sticker fruit.
 *
 * - logo_full()   : « DST » + les 3 lignes Distribution / Services / Transports
 *                   (les lignes se replient au scroll — voir main.js)
 * - sticker()     : l'étiquette ovale crénelée, signature de la marque
 */
declare(strict_types=1);

/**
 * Le sticker fruit : étiquette ovale crénelée, texte circulaire, petite feuille.
 */
function sticker(string $class = 'sticker', string $label = 'DST', bool $rotating = false): string
{
    $id = 'st' . substr(md5($class . $label . ($rotating ? 'r' : '')), 0, 6);
    ob_start(); ?>
<svg class="<?= htmlspecialchars($class, ENT_QUOTES) ?>" viewBox="0 0 200 200" role="img" aria-label="Étiquette DST — transport frigorifique, Île-de-France">
  <defs>
    <path id="<?= $id ?>-circle" d="M100,100 m-62,0 a62,62 0 1,1 124,0 a62,62 0 1,1 -124,0"/>
    <filter id="<?= $id ?>-grain" x="-20%" y="-20%" width="140%" height="140%">
      <feTurbulence type="fractalNoise" baseFrequency=".9" numOctaves="2" seed="7" result="n"/>
      <feColorMatrix in="n" type="saturate" values="0"/>
      <feComponentTransfer><feFuncA type="linear" slope=".14"/></feComponentTransfer>
      <feComposite in2="SourceGraphic" operator="in"/>
    </filter>
  </defs>
  <g class="sticker__body">
    <!-- ombre de décollement (grandit au survol) -->
    <ellipse class="sticker__shadow" cx="102" cy="104" rx="88" ry="92" fill="#2B1A2B" opacity=".16"/>
    <!-- bord crénelé -->
    <g class="sticker__crimp">
      <?php for ($i = 0; $i < 44; $i++):
          $a = $i * (360 / 44);
          $x = 100 + 88 * cos(deg2rad($a));
          $y = 100 + 90 * sin(deg2rad($a)); ?>
        <circle cx="<?= round($x, 2) ?>" cy="<?= round($y, 2) ?>" r="7.5" fill="#1F7A4C"/>
      <?php endfor; ?>
    </g>
    <ellipse cx="100" cy="100" rx="88" ry="90" fill="#1F7A4C"/>
    <ellipse cx="100" cy="100" rx="88" ry="90" fill="#000" filter="url(#<?= $id ?>-grain)" opacity=".5"/>
    <ellipse cx="100" cy="100" rx="79" ry="81" fill="none" stroke="#FAF6EE" stroke-width="1.4" stroke-dasharray="3 4" opacity=".55"/>
    <!-- texte circulaire -->
    <text font-family="Instrument Sans, sans-serif" font-size="12.4" font-weight="600" letter-spacing="2.55" fill="#FAF6EE">
      <textPath href="#<?= $id ?>-circle" startOffset="4%">TRANSPORT FRIGORIFIQUE · ÎLE-DE-FRANCE ·</textPath>
    </text>
    <!-- centre -->
    <text x="100" y="107" text-anchor="middle" font-family="Bricolage Grotesque, sans-serif" font-size="42" font-weight="800" letter-spacing="-1.5" fill="#FAF6EE"><?= htmlspecialchars($label) ?></text>
    <text x="100" y="127" text-anchor="middle" font-family="Fragment Mono, monospace" font-size="9.4" letter-spacing="1.6" fill="#CFE8C9">0 → +4 °C</text>
    <!-- petite feuille -->
    <g class="sticker__leaf" transform="translate(100 12)">
      <path d="M0,-9 C11,-11 18,-3 15,5 C12,13 2,13 -2,6 C-5,0 -4,-6 0,-9 Z" fill="#CFE8C9" stroke="#1F7A4C" stroke-width="1.1"/>
      <path d="M-1,7 C1,3 4,-1 8,-4" fill="none" stroke="#1F7A4C" stroke-width="1.1" stroke-linecap="round"/>
    </g>
  </g>
</svg>
<?php
    return (string) ob_get_clean();
}

/**
 * Logotype complet : DST + 3 lignes qui se replient au scroll.
 */
function logo_markup(string $class = 'logo', string $id = '', bool $asLink = true, string $href = '#top'): string
{
    $lines = ['Distribution', 'Services', 'Transports'];
    ob_start(); ?>
<span class="<?= htmlspecialchars($class, ENT_QUOTES) ?>"<?= $id ? ' id="' . htmlspecialchars($id, ENT_QUOTES) . '"' : '' ?>>
  <span class="logo__word" aria-hidden="true">DST</span>
  <span class="logo__lines" aria-hidden="true">
    <?php foreach ($lines as $l): ?><span class="logo__line"><?= $l ?></span><?php endforeach; ?>
  </span>
  <span class="sr-only">Distribution Services Transports</span>
</span>
<?php
    $inner = (string) ob_get_clean();
    if (!$asLink) {
        return $inner;
    }
    return '<a class="logo-link" href="' . htmlspecialchars($href, ENT_QUOTES) . '" aria-label="Distribution Services Transports — retour en haut">' . $inner . '</a>';
}
