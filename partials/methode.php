<?php
$steps = [
    ['Vous nous contactez', 'Par téléphone ou via le formulaire : marchandises, volumes, fréquence, adresses.'],
    ['Nous vous proposons une solution', 'Un devis clair sous 24 h ouvrées, avec le véhicule et l\'organisation adaptés.'],
    ['Nous livrons', 'Premier enlèvement planifié, et une tournée qui s\'ajuste à votre activité.'],
];
?>
<section class="method section" id="methode" data-theme="dark" aria-labelledby="method-title">
    <div class="section__bg" aria-hidden="true">
        <video class="bg-video" data-bg-video muted loop playsinline preload="none"
               poster="assets/img/methode-poster.webp?v=e07184b7" tabindex="-1" disablepictureinpicture>
            <source src="assets/video/methode.mp4?v=94546c5e" type="video/mp4">
        </video>
        <span class="section__bg-scrim section__bg-scrim--strong"></span>
    </div>

    <div class="container">
        <header class="section-head section-head--dark">
            <p class="eyebrow"><span class="eyebrow__dash"></span>07 — Comment ça marche</p>
            <h2 class="h2 method__title" id="method-title">
                Trois étapes,
                <span class="line-mask"><span class="line"><em class="serif">puis on roule.</em></span></span>
            </h2>
        </header>

        <div class="method__track">
            <svg class="method__route" viewBox="0 0 1200 120" fill="none" aria-hidden="true" data-method-route>
                <path d="M40 78 C180 78 220 30 360 34 S520 88 660 84 S860 26 1000 34 S1120 62 1160 54"
                      stroke="#FFD23F" stroke-width="1.6" pathLength="1" stroke-dasharray="1" stroke-dashoffset="1" data-method-path/>
                <circle data-method-dot cx="40" cy="78" r="4" fill="#FFD23F"/>
            </svg>

            <ol class="method__steps">
                <?php foreach ($steps as $i => $step): ?>
                    <li class="method-step" data-method-step>
                        <span class="method-step__num mono">0<?= $i + 1 ?></span>
                        <h3 class="method-step__title"><?= htmlspecialchars($step[0], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="method-step__text"><?= htmlspecialchars($step[1], ENT_QUOTES, 'UTF-8') ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>

        <div class="method__cta">
            <a class="btn btn--primary" href="#devis" data-quote-link>
                <span class="btn__label">Démarrer ma demande</span>
                <span class="btn__ico" aria-hidden="true">
                    <?= dst_icon('arrow-right', 'ico-a', 18) ?><?= dst_icon('arrow-right', 'ico-b', 18) ?>
                </span>
            </a>
            <p class="method__cta-note mono">Réponse sous 24 h ouvrées · Devis gratuit</p>
        </div>
    </div>
</section>
