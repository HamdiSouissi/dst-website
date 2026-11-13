<?php
/** @var array $config */
$contact   = $config['contact'];
$stats     = $config['stats']['hero'];
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$frozen    = !empty($config['services_options']['frozen']);
?>
<section class="hero" id="top" data-theme="dark" aria-labelledby="hero-title">
    <div class="hero__bg" aria-hidden="true">
        <video class="hero__video" data-bg-video autoplay muted loop playsinline preload="none"
               poster="assets/img/hero-poster.webp">
            <source src="assets/video/hero.mp4" type="video/mp4">
        </video>
        <div class="hero__grid"></div>
        <div class="hero__grain"></div>
        <div class="hero__vignette"></div>
        <div class="hero__road" data-hero-road></div>
    </div>

    <div class="hero__inner container">
        <div class="hero__copy">
            <p class="eyebrow hero__eyebrow" data-reveal>
                <span class="eyebrow__dash" aria-hidden="true"></span>
                Transport frigorifique · Paris &amp; Île-de-France
            </p>

            <h1 class="hero__title" id="hero-title">
                <span class="line-mask"><span class="line" data-hero-line>Le frais,</span></span>
                <span class="line-mask">
                    <span class="line" data-hero-line>livré <em class="serif">à l'heure.</em></span>
                </span>
            </h1>

            <p class="hero__sub" data-reveal>
                Distribution Services Transports livre vos produits frais en camionnettes frigorifiques,
                partout en Île-de-France. Température maîtrisée, créneaux respectés, un interlocuteur unique.
            </p>

            <div class="hero__cta" data-reveal>
                <a class="btn btn--primary" href="#devis" data-quote-link>
                    <span class="btn__label">Demander un devis</span>
                    <span class="btn__ico" aria-hidden="true">
                        <?= dst_icon('arrow-right', 'ico-a', 18) ?><?= dst_icon('arrow-right', 'ico-b', 18) ?>
                    </span>
                </a>
                <a class="btn btn--ghost" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>">
                    <span class="btn__label">Appeler le <span class="mono"><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></span></span>
                    <?= dst_icon('phone', 'btn__phone', 16) ?>
                </a>
            </div>
        </div>

        <div class="hero__stage" data-hero-stage>
            <div class="hero__glow" aria-hidden="true"></div>

            <div class="hero__trails" aria-hidden="true">
                <span class="trail trail--1" data-trail></span>
                <span class="trail trail--2" data-trail></span>
                <span class="trail trail--3" data-trail></span>
            </div>

            <div class="hero__van" data-hero-van>
                <img class="van-photo"
                     src="assets/img/flotte/master.webp"
                     srcset="assets/img/flotte/master-sm.webp 700w, assets/img/flotte/master.webp 1270w"
                     sizes="(max-width: 899px) 92vw, 54vw"
                     width="1270" height="682"
                     alt="Camionnette frigorifique blanche Renault Master de DST, vue trois quarts avant"
                     fetchpriority="high" decoding="async">
            </div>

            <div class="temp-badge" data-temp-badge aria-hidden="true">
                <span class="temp-badge__leader"></span>
                <span class="temp-badge__head">
                    <span class="temp-badge__pulse"></span>
                    Caisson
                </span>
                <span class="temp-badge__value">
                    <span class="mono" data-temp-value>2,8</span><span class="temp-badge__unit">°C</span>
                </span>
                <svg class="temp-badge__spark" viewBox="0 0 92 26" fill="none" aria-hidden="true">
                    <polyline data-spark points="2,17 10,14 18,16 26,10 34,13 42,9 50,12 58,8 66,11 74,7 82,10 90,6"
                              stroke="#FFD23F" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="temp-badge__note mono"><?= $frozen ? '-18 → +4 °C' : '0 → +4 °C' ?></span>
            </div>

            <div class="tour-badge" data-reveal>
                <span class="tour-badge__dot" aria-hidden="true"></span>
                <span>En tournée · Paris 11<sup>e</sup> → Arcueil</span>
            </div>
        </div>

        <div class="hero__stats" data-reveal>
            <?php foreach ($stats as $index => $stat): ?>
                <div class="hstat">
                    <span class="hstat__value mono" <?= ctype_digit((string) $stat['value']) ? 'data-odometer="' . (int) $stat['value'] . '"' : '' ?>><?= htmlspecialchars($stat['value'], ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="hstat__label"><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <a class="hero__scroll" href="#chaine" aria-label="Faites défiler pour découvrir la chaîne du froid">
            <span class="hero__scroll-label">Faites défiler</span>
            <span class="hero__scroll-route" aria-hidden="true">
                <span class="hero__scroll-dot"></span>
            </span>
        </a>
    </div>
</section>
