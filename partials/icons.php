<?php
/**
 * Icônes au trait (Lucide, 24×24, trait 1.5) + pictogrammes maison.
 * Aucune police d'icônes, aucun emoji : tout est SVG inline.
 */

if (!function_exists('dst_icon')) {
    /**
     * @param string $name  snowflake|thermometer|truck|clock|map-pin|shield-check|route|package|leaf|phone|mail|calendar-clock|arrow-right|arrow-up-right|check|plus|x|menu|chevron-left|chevron-right|quote|spark|box|wheel
     */
    function dst_icon(string $name, string $class = '', int $size = 24): string
    {
        $paths = [
            'snowflake'        => '<path d="M12 2v20M4.2 6.5l15.6 11M19.8 6.5L4.2 17.5"/><path d="M12 6.5 9 4.2M12 6.5l3-2.3M12 17.5l-3 2.3M12 17.5l3 2.3"/><path d="M6.6 9.4l-.4-3.8M9.9 12.9l-3.7 1.3M17.4 9.4l.4-3.8M14.1 12.9l3.7 1.3M6.6 14.6l-.4 3.8M9.9 11.1l-3.7-1.3M17.4 14.6l.4 3.8M14.1 11.1l3.7-1.3"/>',
            'thermometer'      => '<path d="M14 4.5a2 2 0 1 0-4 0v8.2a4.5 4.5 0 1 0 4 0Z"/><path d="M12 9v7.2"/><path d="M8.5 6h2M8.5 9h2M8.5 12h2"/>',
            'truck'            => '<path d="M2 6h11v10H2zM13 9h4.5l3.5 3.5V16h-8"/><circle cx="6.5" cy="18" r="2"/><circle cx="17.5" cy="18" r="2"/>',
            'clock'            => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5.2l3.4 2"/>',
            'map-pin'          => '<path d="M20 10.5c0 5.3-8 11.5-8 11.5S4 15.8 4 10.5a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10.5" r="2.8"/>',
            'shield-check'     => '<path d="M12 2.5 20 6v5.5c0 5-3.4 8.9-8 10.5-4.6-1.6-8-5.5-8-10.5V6Z"/><path d="m8.2 12 2.6 2.6 5-5.2"/>',
            'route'            => '<circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="5.5" r="2.5"/><path d="M8 18.5h6.5a3.5 3.5 0 0 0 0-7h-5a3.5 3.5 0 0 1 0-7H16"/>',
            'package'          => '<path d="m12 2.8 9 4.7v9L12 21.2 3 16.5v-9Z"/><path d="m3.4 7.6 8.6 4.5 8.6-4.5M12 21V12"/><path d="M7.5 4.9 16.5 9.7"/>',
            'leaf'             => '<path d="M20 4C10 4 4 9 4 16c0 1.2.2 2.3.6 3.3C8 15 12.5 12 18 10.5c-4.5 2.9-7.7 6.3-9.3 11 1 .4 2 .6 3.2.6C19 22.1 20.5 13 20 4Z"/>',
            'phone'            => '<path d="M6.6 3.5h3l1.4 4-2 1.5a12 12 0 0 0 6 6l1.5-2 4 1.4v3a2.5 2.5 0 0 1-2.7 2.5C10.7 19.3 4.7 13.3 4.1 6.2A2.5 2.5 0 0 1 6.6 3.5Z"/>',
            'mail'             => '<rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="m3.2 6.3 8.8 6.2 8.8-6.2"/>',
            'calendar-clock'   => '<path d="M21 12V6.5A2.5 2.5 0 0 0 18.5 4h-13A2.5 2.5 0 0 0 3 6.5v12A2.5 2.5 0 0 0 5.5 21H12"/><path d="M3 9.5h18M8 2.5V6M16 2.5V6"/><circle cx="17.5" cy="17.5" r="4.5"/><path d="M17.5 15.6v2.1l1.5 1"/>',
            'arrow-right'      => '<path d="M3.5 12h17M14 5.5l6.5 6.5-6.5 6.5"/>',
            'arrow-up-right'   => '<path d="M6.5 17.5 17.5 6.5M8 6.5h9.5V16"/>',
            'check'            => '<path d="m4.5 12.5 5 5L20 7"/>',
            'plus'             => '<path d="M12 4.5v15M4.5 12h15"/>',
            'x'                => '<path d="M5.5 5.5l13 13M18.5 5.5l-13 13"/>',
            'menu'             => '<path d="M3.5 8h17M3.5 16h17"/>',
            'chevron-left'     => '<path d="M14.5 5.5 8 12l6.5 6.5"/>',
            'chevron-right'    => '<path d="M9.5 5.5 16 12l-6.5 6.5"/>',
            'chevron-down'     => '<path d="M5.5 9.5 12 16l6.5-6.5"/>',
            'quote'            => '<path d="M9.5 6C6.5 7.5 5 10 5 13.5V18h5v-5H7.5c0-2 .7-3.5 2.5-4.5ZM19 6c-3 1.5-4.5 4-4.5 7.5V18h5v-5H17c0-2 .7-3.5 2.5-4.5Z" fill="currentColor" stroke="none"/>',
            'spark'            => '<path d="M12 3v4M12 17v4M3 12h4M17 12h4M6.2 6.2l2.6 2.6M15.2 15.2l2.6 2.6M17.8 6.2l-2.6 2.6M8.8 15.2l-2.6 2.6"/>',
            'wheel'            => '<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="3"/><path d="M12 3v6M12 15v6M3.8 7.5l5.2 3M15 13.5l5.2 3M20.2 7.5l-5.2 3M9 13.5l-5.2 3"/>',
            'gauge'            => '<path d="M4 17a9 9 0 1 1 16 0"/><path d="m12 13 4-3.5"/><circle cx="12" cy="14" r="1.3"/>',
            'arrow-down'       => '<path d="M12 3.5v17M5.5 14l6.5 6.5 6.5-6.5"/>',
            'lock'             => '<rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/><path d="M8 10.5V8a4 4 0 0 1 8 0v2.5"/>',
        ];

        $body = $paths[$name] ?? $paths['snowflake'];
        $cls  = $class !== '' ? ' class="' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '"' : '';

        return '<svg' . $cls . ' viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $body . '</svg>';
    }
}

if (!function_exists('dst_social')) {
    /**
     * Pictogrammes réseaux sociaux (SVG inline, monochromes).
     */
    function dst_social(string $name, int $size = 20): string
    {
        $paths = [
            'linkedin'  => '<path d="M5.4 8.6H2.6V21h2.8V8.6ZM4 3a1.7 1.7 0 1 0 0 3.4A1.7 1.7 0 0 0 4 3ZM21.4 14c0-3.8-2-5.6-4.7-5.6-2.2 0-3.2 1.2-3.7 2V8.6h-2.9V21H13v-6.6c0-1.7.6-2.8 2.1-2.8 1.4 0 2 1 2 2.8V21h3.9v-7Z"/>',
            'facebook'  => '<path d="M14.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.5 1.6-1.5h1.7V3.9c-.3 0-1.3-.1-2.5-.1-2.5 0-4.2 1.5-4.2 4.2V10H8.4v3h2.7v8h3.4Z"/>',
            'instagram' => '<path d="M12 7.4A4.6 4.6 0 1 0 12 16.6 4.6 4.6 0 0 0 12 7.4Zm0 7.6a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/><path d="M17.5 6.8a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/><path d="M7.8 2.5h8.4a5.3 5.3 0 0 1 5.3 5.3v8.4a5.3 5.3 0 0 1-5.3 5.3H7.8a5.3 5.3 0 0 1-5.3-5.3V7.8a5.3 5.3 0 0 1 5.3-5.3Zm0 2a3.3 3.3 0 0 0-3.3 3.3v8.4a3.3 3.3 0 0 0 3.3 3.3h8.4a3.3 3.3 0 0 0 3.3-3.3V7.8a3.3 3.3 0 0 0-3.3-3.3H7.8Z"/>',
            'whatsapp'  => '<path d="M12 2.5a9.4 9.4 0 0 0-8 14.4L2.6 21.5l4.7-1.3A9.4 9.4 0 1 0 12 2.5Zm0 2a7.4 7.4 0 0 1 0 14.8 7.4 7.4 0 0 1-3.8-1l-.4-.2-2.6.7.7-2.5-.3-.4A7.4 7.4 0 0 1 12 4.5Zm-3.2 3.4c-.2 0-.5.1-.7.4-.3.3-.8.9-.8 1.9s.7 2.1 1 2.5c.4.5 1.5 2.2 3.7 3 1.8.7 2.2.6 2.6.5.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.1-.2-.2-.5-.3l-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.2 6.2 0 0 1-1.9-1.1 7 7 0 0 1-1.3-1.6c-.1-.2 0-.3.1-.4l.5-.6c.1-.2.1-.3.2-.5 0-.1 0-.2-.1-.4l-.7-1.7c-.2-.4-.3-.4-.5-.4h-.5Z"/>',
            'google'    => '<path d="M21.5 12.2c0-.7-.1-1.4-.2-2H12v3.9h5.4a4.6 4.6 0 0 1-2 3v2.5h3.2c1.9-1.7 2.9-4.3 2.9-7.4Z"/><path d="M12 21.9c2.7 0 4.9-.9 6.6-2.4l-3.2-2.5c-.9.6-2 1-3.4 1a6 6 0 0 1-5.6-4.1H3.1v2.6A10 10 0 0 0 12 21.9Z"/><path d="M6.4 13.9a6 6 0 0 1 0-3.8V7.5H3.1a10 10 0 0 0 0 9l3.3-2.6Z"/><path d="M12 6c1.5 0 2.8.5 3.8 1.5l2.8-2.8A10 10 0 0 0 3.1 7.5l3.3 2.6A6 6 0 0 1 12 6Z"/>',
        ];
        $body = $paths[$name] ?? '';
        return '<svg viewBox="0 0 24 24" width="' . $size . '" height="' . $size . '" fill="currentColor" aria-hidden="true" focusable="false">' . $body . '</svg>';
    }
}

if (!function_exists('dst_van')) {
    /**
     * Camionnette frigorifique dessinée en SVG (aucune photo requise).
     * Cabine vers la droite. ViewBox 1000×420.
     *
     * @param string $variant master|transit|jumpy|caddy
     */
    function dst_van(string $variant = 'master', string $class = ''): string
    {
        $v = [
            'master' => [
                'rear' => 82, 'nose' => 936, 'top' => 116, 'hood' => 296,
                'r' => 54, 'w1' => 292, 'w2' => 760, 'fridge' => true,
            ],
            'transit' => [
                'rear' => 138, 'nose' => 894, 'top' => 146, 'hood' => 302,
                'r' => 50, 'w1' => 312, 'w2' => 736, 'fridge' => true,
            ],
            'jumpy' => [
                'rear' => 186, 'nose' => 852, 'top' => 172, 'hood' => 306,
                'r' => 47, 'w1' => 330, 'w2' => 710, 'fridge' => true,
            ],
            'caddy' => [
                'rear' => 226, 'nose' => 806, 'top' => 190, 'hood' => 308,
                'r' => 43, 'w1' => 344, 'w2' => 684, 'fridge' => false,
            ],
        ][$variant] ?? [
            'rear' => 82, 'nose' => 936, 'top' => 116, 'hood' => 296,
            'r' => 54, 'w1' => 292, 'w2' => 760, 'fridge' => true,
        ];

        $rear  = $v['rear'];
        $nose  = $v['nose'];
        $top   = $v['top'];
        $hood  = $v['hood'];
        $r     = $v['r'];
        $w1    = $v['w1'];
        $w2    = $v['w2'];
        $floor = 336;
        $cabTop = $nose - 158;
        $archR  = $r + 9;
        $windowY = $top + 34;
        $brandX = ($rear + $cabTop) / 2 - 20;
        $brandY = $top + 104;
        $uid = $variant . '-' . substr(md5($class), 0, 4);

        $body = 'M' . $rear . ' ' . $floor
            . ' V' . ($top + 26)
            . ' Q' . $rear . ' ' . $top . ' ' . ($rear + 30) . ' ' . $top
            . ' H' . $cabTop
            . ' L' . ($nose - 52) . ' ' . ($hood - 24)
            . ' Q' . ($nose - 30) . ' ' . ($hood - 26) . ' ' . ($nose - 12) . ' ' . ($hood - 8)
            . ' L' . $nose . ' ' . ($hood + 2)
            . ' V' . ($floor - 16)
            . ' Q' . $nose . ' ' . ($floor - 2) . ' ' . ($nose - 20) . ' ' . ($floor - 1)
            . ' H' . ($w2 + $archR)
            . ' A' . $archR . ' ' . $archR . ' 0 0 0 ' . ($w2 - $archR) . ' ' . ($floor - 1)
            . ' H' . ($w1 + $archR)
            . ' A' . $archR . ' ' . $archR . ' 0 0 0 ' . ($w1 - $archR) . ' ' . ($floor - 1)
            . ' H' . $rear . ' Z';

        $window = 'M' . ($cabTop + 26) . ' ' . $windowY
            . ' H' . ($nose - 104)
            . ' L' . ($nose - 54) . ' ' . ($hood - 52)
            . ' H' . ($cabTop + 26) . ' Z';

        $fridge = '';
        if ($v['fridge']) {
            $fw = 196;
            $fx = $cabTop - $fw - 26;
            $fridge = '<rect x="' . $fx . '" y="' . ($top - 32) . '" width="' . $fw . '" height="27" rx="7" class="van-fridge"/>'
                . '<path d="M' . ($fx + 20) . ' ' . ($top - 18) . 'h' . ($fw - 40) . '" class="van-detail"/>'
                . '<path d="M' . ($fx + 32) . ' ' . ($top - 24) . 'v12M' . ($fx + 46) . ' ' . ($top - 24) . 'v12M' . ($fx + 60) . ' ' . ($top - 24) . 'v12" class="van-detail"/>';
        }

        $wheels = '';
        foreach ([$w1, $w2] as $i => $cx) {
            $cy = $floor - 1;
            $spokes = '';
            for ($s = 0; $s < 3; $s++) {
                $a = deg2rad($s * 60);
                $dx = cos($a) * ($r * 0.52);
                $dy = sin($a) * ($r * 0.52);
                $spokes .= '<path d="M' . round($cx - $dx, 1) . ' ' . round($cy - $dy, 1) . 'L' . round($cx + $dx, 1) . ' ' . round($cy + $dy, 1) . '" class="van-detail"/>';
            }
            $wheels .= '<g class="van-wheel" data-wheel="' . $i . '">'
                . '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . $r . '" class="van-tyre"/>'
                . '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . round($r * 0.62, 1) . '" class="van-rim"/>'
                . $spokes
                . '<circle cx="' . $cx . '" cy="' . $cy . '" r="' . round($r * 0.16, 1) . '" class="van-hub"/>'
                . '</g>';
        }

        return '<svg class="van-svg ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" viewBox="0 0 1000 420" role="img" aria-label="Camionnette frigorifique ' . htmlspecialchars(ucfirst($variant), ENT_QUOTES, 'UTF-8') . ' de Distribution Services Transports">'
            . '<defs>'
            . '<linearGradient id="vanBody-' . $uid . '" x1="0" y1="0" x2="0" y2="1">'
            . '<stop offset="0" stop-color="#1b211e"/><stop offset="1" stop-color="#0f1211"/>'
            . '</linearGradient>'
            . '<linearGradient id="vanGlow-' . $uid . '" x1="0" y1="0" x2="1" y2="0">'
            . '<stop offset="0" stop-color="#5CF2B0" stop-opacity="0"/><stop offset="1" stop-color="#5CF2B0" stop-opacity=".55"/>'
            . '</linearGradient>'
            . '</defs>'
            . '<ellipse cx="' . (($rear + $nose) / 2) . '" cy="' . ($floor + 38) . '" rx="' . (($nose - $rear) / 2 + 30) . '" ry="18" fill="#000" opacity=".55" filter="url(#vanShadow-' . $uid . ')"/>'
            . '<defs><filter id="vanShadow-' . $uid . '" x="-20%" y="-200%" width="140%" height="500%"><feGaussianBlur stdDeviation="14"/></filter></defs>'
            . $fridge
            . '<path d="' . $body . '" fill="url(#vanBody-' . $uid . ')" class="van-body"/>'
            . '<path d="' . $body . '" fill="none" class="van-line"/>'
            . '<path d="' . $window . '" class="van-glass"/>'
            . '<path d="M' . ($cabTop + 14) . ' ' . ($top + 18) . 'V' . ($floor - 26) . '" class="van-detail"/>'
            . '<path d="M' . ($rear + 34) . ' ' . ($top + 24) . 'V' . ($floor - 26) . '" class="van-detail"/>'
            . '<path d="M' . ($rear + 34) . ' ' . ($top + 24) . 'H' . ($cabTop + 14) . '" class="van-detail"/>'
            . '<path d="M' . ($rear + 116) . ' ' . ($top + 24) . 'V' . ($floor - 26) . '" class="van-detail van-detail--door"/>'
            . '<rect x="' . ($rear + 60) . '" y="' . ($top + 60) . '" width="34" height="6" rx="3" class="van-handle"/>'
            . '<rect x="' . ($nose - 128) . '" y="' . ($top + 56) . '" width="6" height="26" rx="3" class="van-detail"/>'
            . '<rect x="' . ($nose - 142) . '" y="' . ($top + 50) . '" width="18" height="10" rx="4" class="van-mirror"/>'
            . '<rect x="' . ($rear + 4) . '" y="' . ($top + 128) . '" width="9" height="26" rx="4" class="van-light van-light--rear"/>'
            . '<path d="M' . ($nose - 26) . ' ' . ($hood + 12) . 'h22a6 6 0 0 1 6 6v10h-28z" class="van-light"/>'
            . '<text x="' . $brandX . '" y="' . $brandY . '" class="van-brand" text-anchor="middle">DST</text>'
            . '<text x="' . $brandX . '" y="' . ($brandY + 24) . '" class="van-brand-sub" text-anchor="middle">FROID POSITIF · 0/+4 °C</text>'
            . '<path d="M' . $rear . ' ' . ($floor - 36) . 'H' . ($nose - 8) . '" class="van-detail van-detail--rocker"/>'
            . $wheels
            . '</svg>';
    }
}
