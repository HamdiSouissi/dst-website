<?php
/** @var array $config */
$commitments = $config['commitments'];
$trust = $config['stats']['trust'];
?>
<section class="commit section" id="engagements" data-theme="light" aria-labelledby="commit-title">
    <div class="container commit__grid">
        <figure class="commit__visual" data-doors>
            <div class="commit__media">
                <img src="assets/img/chargement.webp" alt="Camionnette frigorifique DST propre, caisson ouvert et chargée de cartons de produits frais"
                     width="1200" height="2150" loading="lazy" decoding="async">
                <span class="sector__shutter sector__shutter--left" aria-hidden="true"></span>
                <span class="sector__shutter sector__shutter--right" aria-hidden="true"></span>
            </div>
            <figcaption class="commit__caption mono">
                <span>Chargement · 05h40</span>
                <span>Arcueil (94)</span>
            </figcaption>
        </figure>

        <div class="commit__content">
            <p class="eyebrow"><span class="eyebrow__dash"></span>06 — Nos engagements</p>
            <h2 class="h2" id="commit-title">
                Petite entreprise,
                <span class="line-mask"><span class="line"><em class="serif">grandes exigences.</em></span></span>
            </h2>

            <ul class="commit__list">
                <?php foreach ($commitments as $i => $commit): ?>
                    <li class="commit-item" data-commit>
                        <span class="commit-item__icon" aria-hidden="true"><?= dst_icon($commit['icon'], '', 22) ?></span>
                        <div class="commit-item__body">
                            <h3 class="commit-item__title"><?= htmlspecialchars($commit['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <p class="commit-item__text"><?= htmlspecialchars($commit['text'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                        <span class="commit-item__num mono">0<?= $i + 1 ?></span>
                        <span class="commit-item__line" aria-hidden="true"><span data-commit-line></span></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="trust-band">
        <div class="container trust-band__inner">
            <?php foreach ($trust as $stat): ?>
                <div class="trust-stat">
                    <span class="trust-stat__value mono">
                        <?php if (!empty($stat['prefix'])): ?><span class="trust-stat__prefix"><?= htmlspecialchars($stat['prefix'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                        <span data-odometer="<?= (int) $stat['value'] ?>"><?= (int) $stat['value'] ?></span>
                        <?php if (!empty($stat['suffix'])): ?><span class="trust-stat__suffix"><?= htmlspecialchars($stat['suffix'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    </span>
                    <span class="trust-stat__label"><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
