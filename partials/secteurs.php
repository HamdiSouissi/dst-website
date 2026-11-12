<?php
/** @var array $config */
$sectors = $config['sectors'];
$areas = ['restaurant', 'traiteur', 'boulangerie', 'primeur', 'poissonnerie', 'epicerie', 'dark-kitchen', 'grossiste'];
?>
<section class="sectors section" id="secteurs" data-theme="light" aria-labelledby="sectors-title">
    <div class="container">
        <header class="section-head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>05 — Secteurs</p>
            <div class="section-head__row">
                <h2 class="h2 section-head__title" id="sectors-title">
                    Ils nous confient
                    <span class="line-mask"><span class="line"><em class="serif">leur fraîcheur.</em></span></span>
                </h2>
                <p class="lede section-head__lede">
                    Restaurateurs, traiteurs, commerces de bouche, grossistes, dark kitchens :
                    chacun a ses horaires, ses volumes, ses exigences. Nous nous y tenons.
                </p>
            </div>
        </header>

        <div class="sectors__grid">
            <?php foreach ($sectors as $i => $sector):
                $slug = $sector['slug'];
                $area = in_array($slug, $areas, true) ? $slug : 'grossiste';
                ?>
                <article class="sector sector--<?= $area ?>" data-sector data-doors data-cursor="view">
                    <div class="sector__media">
                        <img src="assets/img/secteurs/<?= $slug ?>.webp"
                             srcset="assets/img/secteurs/<?= $slug ?>-sm.webp 700w, assets/img/secteurs/<?= $slug ?>.webp 1400w"
                             sizes="(max-width: 760px) 92vw, (max-width: 1100px) 48vw, 33vw"
                             alt="<?= htmlspecialchars($sector['name'], ENT_QUOTES, 'UTF-8') ?> — livraison frigorifique DST à Paris et en Île-de-France"
                             width="1400" height="933" loading="lazy" decoding="async">
                        <span class="sector__tint" aria-hidden="true"></span>
                        <span class="sector__frost" aria-hidden="true"></span>
                        <span class="sector__shutter sector__shutter--left" aria-hidden="true"></span>
                        <span class="sector__shutter sector__shutter--right" aria-hidden="true"></span>
                    </div>
                    <div class="sector__body">
                        <span class="sector__count mono"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <h3 class="sector__name"><?= htmlspecialchars($sector['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="sector__desc"><?= htmlspecialchars($sector['desc'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
