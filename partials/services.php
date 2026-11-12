<?php
/** @var array $config */
$services = $config['services'];
$images = ['primeur', 'grossiste', 'dark-kitchen', 'primeur', 'traiteur', 'chargement'];
if (!empty($config['services_options']['frozen'])) {
    $services[] = [
        'name' => 'Produits surgelés',
        'desc' => 'Transport en froid négatif jusqu\'à -18 °C.',
    ];
    $images[] = 'grossiste';
}
?>
<section class="services section" id="services" data-theme="light" aria-labelledby="services-title">
    <div class="container">
        <header class="section-head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>02 — Services</p>
            <div class="section-head__row">
                <h2 class="h2 section-head__title" id="services-title">
                    Ce que nous
                    <span class="line-mask"><span class="line"><em class="serif">transportons</em> pour vous.</span></span>
                </h2>
                <p class="lede section-head__lede">
                    Une offre resserrée, maîtrisée de bout en bout. Pas de sous-traitance en cascade :
                    nos véhicules, nos chauffeurs, notre engagement.
                </p>
            </div>
        </header>

        <div class="services__list" data-services>
            <?php foreach ($services as $i => $service):
                $num = str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT);
                $img = $images[$i] ?? 'primeur';
                ?>
                <?php
                $imgPath = $img === 'chargement' ? 'assets/img/chargement.webp' : 'assets/img/secteurs/' . $img . '.webp';
                $imgSm = $img === 'chargement' ? 'assets/img/chargement-sm.webp' : 'assets/img/secteurs/' . $img . '-sm.webp';
                ?>
                <article class="service" data-service data-image="<?= $imgPath ?>" data-image-alt="<?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?>">
                    <button class="service__head" type="button" aria-expanded="false" aria-controls="service-panel-<?= $i + 1 ?>" data-service-toggle>
                        <span class="service__num mono"><?= $num ?></span>
                        <span class="service__name"><?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="service__arrow" aria-hidden="true"><?= dst_icon('arrow-up-right', '', 26) ?></span>
                    </button>
                    <div class="service__panel" id="service-panel-<?= $i + 1 ?>" data-service-panel>
                        <div class="service__panel-inner">
                            <div class="service__mobile-media">
                                <img src="<?= $imgSm ?>" alt="<?= htmlspecialchars($service['name'], ENT_QUOTES, 'UTF-8') ?> — DST" width="700" height="467" loading="lazy" decoding="async">
                            </div>
                            <p class="service__desc"><?= htmlspecialchars($service['desc'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="service-preview" data-service-preview aria-hidden="true">
        <img src="assets/img/secteurs/primeur.webp" alt="" width="700" height="467" loading="lazy" data-service-preview-img>
        <span class="service-preview__tag mono" data-service-preview-tag>01</span>
    </div>
</section>
