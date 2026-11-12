<?php
/** @var array $config */
$contact = $config['contact'];
$zones   = $config['zones'];
$zoneMap = [];
foreach ($zones as $z) {
    $zoneMap[$z['code']] = $z;
}
?>
<section class="zone section" id="zone" data-theme="light" aria-labelledby="zone-title">
    <div class="container zone__grid">
        <div class="zone__map-col">
            <div class="zone__map" data-zone-map>
                <div class="zone__map-head">
                    <span class="mono">Desserte · Île-de-France</span>
                    <span class="mono zone__map-count">08 départements</span>
                </div>

                <svg class="zone__svg" viewBox="0 0 620 520" role="group" aria-label="Carte schématique de la zone d'intervention de DST en Île-de-France">
                    <g class="zone__petals">
                        <rect class="zone__petal" data-code="78" tabindex="0" role="button" x="70" y="170" width="110" height="170" rx="34" transform="rotate(-8 125 255)" aria-label="Yvelines (78), livraison J+1"/>
                        <rect class="zone__petal" data-code="95" tabindex="0" role="button" x="205" y="57" width="210" height="95" rx="34" aria-label="Val-d'Oise (95), livraison J+1"/>
                        <rect class="zone__petal" data-code="77" tabindex="0" role="button" x="425" y="150" width="130" height="210" rx="38" aria-label="Seine-et-Marne (77), livraison J+1"/>
                        <rect class="zone__petal" data-code="91" tabindex="0" role="button" x="195" y="370" width="230" height="100" rx="36" aria-label="Essonne (91), livraison J+1"/>
                        <rect class="zone__petal" data-code="92" tabindex="0" role="button" x="182" y="219" width="86" height="66" rx="22" transform="rotate(-8 225 252)" aria-label="Hauts-de-Seine (92), livraison J ou J+1"/>
                        <rect class="zone__petal" data-code="93" tabindex="0" role="button" x="319" y="164" width="82" height="64" rx="22" transform="rotate(-32 360 196)" aria-label="Seine-Saint-Denis (93), livraison J ou J+1"/>
                        <rect class="zone__petal" data-code="94" tabindex="0" role="button" x="317" y="297" width="86" height="66" rx="22" transform="rotate(28 360 330)" aria-label="Val-de-Marne (94), livraison J ou J+1"/>
                        <circle class="zone__petal zone__petal--paris" data-code="75" tabindex="0" role="button" cx="310" cy="260" r="30" aria-label="Paris (75), livraison J ou J+1"/>
                    </g>

                    <g class="zone__labels" aria-hidden="true">
                        <text x="310" y="265">75</text>
                        <text x="225" y="257">92</text>
                        <text x="360" y="201">93</text>
                        <text x="362" y="335">94</text>
                        <text x="125" y="260">78</text>
                        <text x="310" y="110">95</text>
                        <text x="490" y="260">77</text>
                        <text x="310" y="426">91</text>
                    </g>

                    <g class="zone__routes" data-zone-routes aria-hidden="true">
                        <?php
                        $origin = [332, 306];
                        $targets = ['78' => [125, 255], '95' => [310, 110], '77' => [490, 260], '91' => [310, 426], '92' => [225, 257], '93' => [360, 201], '94' => [362, 335]];
                        foreach ($targets as $code => $pt):
                            $cx = ($origin[0] + $pt[0]) / 2 + 18;
                            $cy = ($origin[1] + $pt[1]) / 2 - 12;
                        ?>
                            <path class="zone__route" data-route="<?= $code ?>"
                                  d="M<?= $origin[0] ?> <?= $origin[1] ?> Q<?= (int) $cx ?> <?= (int) $cy ?> <?= $pt[0] ?> <?= $pt[1] ?>"
                                  stroke="#00A870" stroke-width="1.2" fill="none" stroke-dasharray="3 5"/>
                        <?php endforeach; ?>
                    </g>

                    <g class="zone__hub" aria-hidden="true">
                        <circle class="zone__hub-wave" cx="332" cy="306" r="18"/>
                        <circle class="zone__hub-wave zone__hub-wave--2" cx="332" cy="306" r="18"/>
                        <circle class="zone__hub-core" cx="332" cy="306" r="6"/>
                        <text class="zone__hub-label" x="348" y="311">Arcueil</text>
                    </g>
                </svg>

                <div class="zone__tooltip" data-zone-tooltip aria-hidden="true">
                    <strong data-zone-tooltip-name>Paris (75)</strong>
                    <span class="mono" data-zone-tooltip-delay>Livraison J ou J+1</span>
                </div>

                <p class="zone__caption mono">Schéma de desserte — les contours sont stylisés.</p>
            </div>

            <ul class="zone__list" aria-label="Départements desservis">
                <?php foreach ($zones as $zone): ?>
                    <li data-zone-item="<?= $zone['code'] ?>" data-zone-name="<?= htmlspecialchars($zone['name'], ENT_QUOTES, 'UTF-8') ?>" data-zone-delay="<?= htmlspecialchars($zone['delay'], ENT_QUOTES, 'UTF-8') ?>">
                        <span class="zone__bullet" aria-hidden="true"></span>
                        <span class="zone__name"><?= htmlspecialchars($zone['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="zone__code mono">(<?= $zone['code'] ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="zone__copy">
            <p class="eyebrow"><span class="eyebrow__dash"></span>04 — Zone d'intervention</p>
            <h2 class="h2" id="zone-title">
                Paris et toute
                <span class="line-mask"><span class="line"><em class="serif">l'Île-de-France.</em></span></span>
            </h2>
            <p class="lede">
                Basés à Arcueil, aux portes de Paris, nous rayonnons sur les huit départements franciliens.
                Une position idéale pour rejoindre le périphérique, l'A6 et Rungis en quelques minutes.
            </p>

            <p class="zone__note">
                Livraisons hors Île-de-France possibles sur demande.
            </p>

            <address class="zone__address">
                <span class="zone__address-label mono">Siège &amp; dépôt</span>
                <p><?= htmlspecialchars($contact['address_line'], ENT_QUOTES, 'UTF-8') ?><br>
                   <?= htmlspecialchars($contact['postcode'] . ' ' . $contact['city'], ENT_QUOTES, 'UTF-8') ?></p>
                <a class="link-arrow" href="<?= htmlspecialchars($contact['maps_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                    <span>Itinéraire</span>
                    <?= dst_icon('arrow-up-right', '', 16) ?>
                </a>
            </address>
        </div>
    </div>
</section>
