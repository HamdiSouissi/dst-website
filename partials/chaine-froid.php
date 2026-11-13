<?php
$steps = [
    ['Enlèvement', 'Nous récupérons vos marchandises chez vous, sur votre plateforme ou chez votre fournisseur, au créneau convenu.'],
    ['Chargement à température', 'Le caisson est pré-refroidi avant chargement. Vos produits ne quittent jamais la plage de froid requise.'],
    ['Transport suivi', 'Itinéraires optimisés pour éviter les ruptures, température contrôlée tout au long de la tournée.'],
    ['Livraison & réception', 'Remise en main propre, contrôle à la livraison, bon de livraison signé. Vous êtes prévenu en cas d\'imprévu.'],
];
?>
<section class="cold" id="chaine" data-theme="dark" aria-labelledby="cold-title">
    <div class="cold__pin" data-cold-pin>
        <img class="cold__watermark" src="assets/img/logo-dst-givre.svg" alt=""
             aria-hidden="true" decoding="async" data-cold-watermark>
        <div class="container cold__grid">
            <div class="cold__copy">
                <p class="eyebrow"><span class="eyebrow__dash"></span>01 — La chaîne du froid</p>
                <h2 class="h2 cold__title" id="cold-title">
                    La chaîne du froid,
                    <span class="line-mask"><span class="line"><em class="serif">sans rupture.</em></span></span>
                </h2>

                <ol class="cold__steps" data-cold-steps>
                    <?php foreach ($steps as $i => $step): ?>
                        <li class="cold-step<?= $i === 0 ? ' is-active' : '' ?>" data-cold-step="<?= $i ?>">
                            <span class="cold-step__num mono">0<?= $i + 1 ?></span>
                            <div class="cold-step__body">
                                <h3 class="cold-step__name"><?= htmlspecialchars($step[0], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="cold-step__text"><?= htmlspecialchars($step[1], ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <span class="cold-step__bar" aria-hidden="true"></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="cold__scene-wrap">
                <div class="cold__scene" data-cold-scene>
                    <div class="cold__scene-head">
                        <span class="mono">Caisson · relevé continu</span>
                        <span class="cold__scene-zone mono"><i></i> Zone conforme 0 → +4 °C</span>
                    </div>

                    <svg class="cold__chart" viewBox="0 0 760 330" fill="none" aria-hidden="true" data-cold-chart>
                        <g class="cold__grid" stroke="#2A2620" stroke-width="1" stroke-dasharray="2 6">
                            <path d="M84 90h636M84 126h636M84 162h636M84 198h636M84 234h636M84 270h636"/>
                        </g>
                        <rect class="cold__zone" x="84" y="198" width="636" height="72" fill="#FFD23F" fill-opacity=".055"/>
                        <path class="cold__zone-line" d="M84 198h636M84 270h636" stroke="#FFD23F" stroke-opacity=".28" stroke-width="1"/>
                        <g class="cold__axis mono" fill="#8B8475" font-size="11">
                            <text x="60" y="94">8 °C</text>
                            <text x="60" y="130">6</text>
                            <text x="60" y="166">4</text>
                            <text x="60" y="202">2</text>
                            <text x="60" y="274">0</text>
                        </g>
                        <path class="cold__curve-glow" d="M84 240 C130 232 160 246 206 238 S280 224 330 232 S410 244 456 234 S540 220 588 228 S680 238 720 230"
                              stroke="#FFD23F" stroke-opacity=".22" stroke-width="9" stroke-linecap="round"/>
                        <path class="cold__curve" data-cold-curve pathLength="1"
                              d="M84 240 C130 232 160 246 206 238 S280 224 330 232 S410 244 456 234 S540 220 588 228 S680 238 720 230"
                              stroke="#FFD23F" stroke-width="2.2" stroke-linecap="round" stroke-dasharray="1" stroke-dashoffset="1"/>
                        <circle class="cold__curve-dot" data-cold-dot cx="720" cy="230" r="4.5" fill="#FFD23F"/>
                        <g class="cold__curve-van" data-cold-van transform="translate(84 240)" aria-hidden="true">
                            <circle r="17" fill="#0E0D0B" stroke="#FFD23F" stroke-width="1.6"/>
                            <g transform="translate(-11.5 -10.5) scale(.96)" fill="none" stroke="#FFD23F" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 6h11v10H2zM13 9h4.5l3.5 3.5V16h-8"/>
                                <circle cx="6.5" cy="18" r="2"/>
                                <circle cx="17.5" cy="18" r="2"/>
                            </g>
                        </g>

                        <g class="cold__road" aria-hidden="true">
                            <path d="M20 330 L330 250 H610 L748 330 Z" fill="#171512"/>
                            <path d="M20 330 L330 250 M748 330 L610 250" stroke="#3A4740" stroke-width="1.4"/>
                            <path d="M376 250 L352 330" stroke="#FFD23F" stroke-opacity=".35" stroke-width="1.4" stroke-dasharray="8 10"/>
                            <path d="M610 250 L678 330" stroke="#FFD23F" stroke-opacity=".2" stroke-width="1.4" stroke-dasharray="8 10"/>
                            <path d="M120 330 H700" stroke="#2A2620" stroke-width="1"/>
                        </g>
                    </svg>

                    <div class="cold__delivery" data-cold-delivery aria-hidden="true">
                        <span class="cold__flag"><?= dst_icon('map-pin', '', 18) ?></span>
                        <span class="cold__delivery-label mono">Livré · 07:12</span>
                    </div>

                    <div class="cold__temp-float" data-cold-float aria-hidden="true">
                        <span class="mono">2,8 °C</span><span class="cold__temp-state">conforme</span>
                    </div>
                </div>

                <div class="cold__proof" data-cold-proof>
                    <span class="cold__proof-check"><?= dst_icon('check', '', 16) ?></span>
                    <span>Température conforme du départ à la livraison</span>
                </div>
            </div>
        </div>
    </div>
</section>
