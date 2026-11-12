<?php
/**
 * Monogramme DST — dessiné en SVG.
 * La barre du T se prolonge en route ; un flocon à six branches occupe
 * le contre-poinçon du D. Les classes servent aux animations (DrawSVG).
 */

if (!function_exists('dst_logo_mark')) {
    function dst_logo_mark(string $class = '', string $idSuffix = ''): string
    {
        $u = $idSuffix !== '' ? '-' . preg_replace('/[^a-z0-9]/i', '', $idSuffix) : '';

        return '<svg class="logo-mark ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 64 64" role="img" aria-label="Monogramme DST" fill="none">'
            . '<defs>'
            . '<linearGradient id="logoMint' . $u . '" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" stop-color="#FFE98A"/><stop offset="1" stop-color="#FFD23F"/>'
            . '</linearGradient>'
            . '<filter id="logoGlow' . $u . '" x="-40%" y="-40%" width="180%" height="180%">'
            . '<feGaussianBlur stdDeviation="1.6" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>'
            . '</filter>'
            . '</defs>'
            . '<rect class="logo-frame" x="2.25" y="2.25" width="59.5" height="59.5" rx="13.5" stroke="url(#logoMint' . $u . ')" stroke-width="2.5"/>'
            . '<g class="logo-letters" stroke="url(#logoMint' . $u . ')" stroke-width="3.1" stroke-linecap="square" stroke-linejoin="round">'
            . '<path class="logo-letter logo-letter--d" d="M11 23V41h7a9 9 0 0 0 0-18h-7Z"/>'
            . '<path class="logo-letter logo-letter--s" d="M37.3 24.6c-1.1-1.3-2.7-2-4.5-2-2.7 0-4.6 1.6-4.6 3.8 0 2.1 1.7 3 4.6 3.7 2.9.7 4.7 1.6 4.7 3.8 0 2.2-1.9 3.8-4.8 3.8-1.9 0-3.6-.7-4.8-2"/>'
            . '<path class="logo-letter logo-letter--t" d="M39 23h14M46 23v18"/>'
            . '</g>'
            . '<path class="logo-road" d="M53 23h5.6" stroke="#FFD23F" stroke-width="3.1" stroke-linecap="round"/>'
            . '<g class="logo-flake" filter="url(#logoGlow' . $u . ')" stroke="#FFE98A" stroke-width="1.05" stroke-linecap="round">'
            . '<path d="M21 28.7v6.6M18.14 30.35l5.72 3.3M23.86 30.35l-5.72 3.3"/>'
            . '<circle cx="21" cy="32" r=".9" fill="#FFE98A" stroke="none"/>'
            . '</g>'
            . '</svg>';
    }
}

if (!function_exists('dst_logo')) {
    /**
     * Lockup cliquable (header, footer, preloader).
     */
    function dst_logo(string $href = '#top', string $class = '', string $idSuffix = 'header'): string
    {
        return '<a class="logo ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" aria-label="Distribution Services Transports — retour en haut de page">'
            . dst_logo_mark('logo__mark', $idSuffix)
            . '<span class="logo__word">'
            . '<span class="logo__name">DST</span>'
            . '<span class="logo__baseline">Distribution Services Transports</span>'
            . '<span class="logo__mobile">Transport frigorifique</span>'
            . '</span>'
            . '</a>';
    }
}
