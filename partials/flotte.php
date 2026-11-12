<?php
/** @var array $config */
$fleet = $config['fleet'];
$first = $fleet[0];
$frozen = !empty($config['services_options']['frozen']);
?>
<section class="fleet section" id="flotte" data-theme="dark" aria-labelledby="fleet-title">
    <div class="container">
        <header class="section-head section-head--dark">
            <p class="eyebrow"><span class="eyebrow__dash"></span>03 — Notre flotte</p>
            <div class="section-head__row">
                <h2 class="h2 section-head__title" id="fleet-title">
                    Une flotte
                    <span class="line-mask"><span class="line"><em class="serif">taillée</em> pour la ville.</span></span>
                </h2>
                <p class="lede section-head__lede">
                    Du petit utilitaire qui se faufile dans le Marais au grand fourgon pour vos tournées complètes :
                    nous choisissons le véhicule adapté à chaque livraison.
                </p>
            </div>
        </header>

        <div class="fleet__showroom" data-fleet>
            <div class="fleet__stage" data-fleet-stage>
                <div class="fleet__backdrop" aria-hidden="true">
                    <span class="fleet__name-outline" data-fleet-name>Renault Master</span>
                    <span class="fleet__plate"></span>
                </div>

                <div class="fleet__van-holder" data-fleet-van-holder data-cursor="view">
                    <div class="fleet__van" data-fleet-van>
                        <?= dst_van($first['slug'], 'van--fleet') ?>
                    </div>
                    <span class="fleet__van-tag mono" data-fleet-tag><?= htmlspecialchars($first['tag'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>

                <aside class="fleet__specs" aria-live="polite" aria-atomic="true">
                    <p class="fleet__specs-title mono">Fiche technique</p>
                    <div class="fleet__spec">
                        <span class="fleet__spec-label">Gabarit</span>
                        <span class="fleet__spec-value" data-fleet-gabarit><?= htmlspecialchars($first['gabarit'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="fleet__spec">
                        <span class="fleet__spec-label">Volume utile frigorifique</span>
                        <span class="fleet__spec-value mono" data-fleet-volume><?= htmlspecialchars($first['volume_label'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="fleet__gauge" aria-hidden="true"><span data-fleet-gauge style="width:100%"></span></span>
                    </div>
                    <div class="fleet__spec">
                        <span class="fleet__spec-label">Charge utile</span>
                        <span class="fleet__spec-value mono" data-fleet-charge><?= htmlspecialchars($first['charge'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="fleet__spec">
                        <span class="fleet__spec-label">Idéal pour</span>
                        <span class="fleet__spec-value fleet__spec-value--soft" data-fleet-usage><?= htmlspecialchars($first['usage'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="fleet__meta mono">
                        <span>Température : <?= $frozen ? '-18 à +4 °C' : '0 à +4 °C' ?></span>
                        <span>Groupe frigorifique autonome</span>
                        <span>Caisson conforme ATP</span>
                    </div>
                </aside>
            </div>

            <div class="fleet__controls">
                <div class="fleet__tabs" role="group" aria-label="Choisir un véhicule">
                    <?php foreach ($fleet as $i => $vehicle): ?>
                        <button class="fleet-tab<?= $i === 0 ? ' is-active' : '' ?>" type="button"
                                id="fleet-tab-<?= $vehicle['slug'] ?>"
                                aria-pressed="<?= $i === 0 ? 'true' : 'false' ?>"
                                data-fleet-tab="<?= $i ?>"
                                data-slug="<?= $vehicle['slug'] ?>">
                            <span class="fleet-tab__van" aria-hidden="true"><?= dst_van($vehicle['slug'], 'van--tab') ?></span>
                            <span class="fleet-tab__name"><?= htmlspecialchars($vehicle['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        </button>
                    <?php endforeach; ?>
                    <span class="fleet__tab-indicator" data-fleet-indicator aria-hidden="true"></span>
                </div>

                <div class="fleet__nav">
                    <span class="fleet__nav-hint mono" data-fleet-counter>01 / 0<?= count($fleet) ?></span>
                    <button class="round-btn" type="button" data-fleet-prev aria-label="Véhicule précédent"><?= dst_icon('chevron-left', '', 20) ?></button>
                    <button class="round-btn" type="button" data-fleet-next aria-label="Véhicule suivant"><?= dst_icon('chevron-right', '', 20) ?></button>
                </div>
            </div>

            <div class="fleet__foot">
                <p class="fleet__hint mono">Glissez horizontalement pour parcourir la flotte</p>
                <a class="link-arrow" href="#devis" data-quote-link data-vehicle-cta>
                    <span>Quel véhicule pour mon besoin ?</span>
                    <?= dst_icon('arrow-up-right', '', 18) ?>
                </a>
            </div>
        </div>
    </div>

    <script type="application/json" id="fleet-data"><?= json_encode($fleet, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?></script>
</section>
