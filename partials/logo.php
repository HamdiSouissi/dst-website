<?php
/**
 * Logo officiel DST — cercle « Distribution Services Transports ».
 * Déclinaison citron pour les fonds sombres ; une version noire est utilisée
 * pour l'habillage des camionnettes et les favicons.
 */

if (!function_exists('dst_logo_mark')) {
    function dst_logo_mark(string $class = '', string $idSuffix = ''): string
    {
        $classes = 'logo-mark ' . $class;
        return '<img class="' . htmlspecialchars(trim($classes), ENT_QUOTES, 'UTF-8') . '"'
            . ' src="assets/img/logo-dst-citron.svg"'
            . ' alt="" aria-hidden="true" width="64" height="64" decoding="async" draggable="false"'
            . ($idSuffix !== '' ? ' data-logo-instance="' . htmlspecialchars($idSuffix, ENT_QUOTES, 'UTF-8') . '"' : '')
            . '>';
    }
}

if (!function_exists('dst_logo')) {
    /**
     * Lockup cliquable (header, footer, preloader).
     * Le nom complet figure déjà dans le cercle : on garde seulement une
     * baseline courte pour préciser l'activité sur les petits écrans.
     */
    function dst_logo(string $href = '#top', string $class = '', string $idSuffix = 'header'): string
    {
        return '<a class="logo ' . htmlspecialchars($class, ENT_QUOTES, 'UTF-8') . '" href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" aria-label="Distribution Services Transports — retour en haut de page">'
            . dst_logo_mark('logo__mark', $idSuffix)
            . '<span class="logo__word">'
            . '<span class="logo__tagline">Transport frigorifique</span>'
            . '</span>'
            . '</a>';
    }
}
