<?php
/** @var array $config */
$contact = $config['contact'];
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$navItems = [
    'services'    => 'Services',
    'flotte'      => 'Flotte',
    'zone'        => 'Zone',
    'secteurs'    => 'Secteurs',
    'engagements' => 'Engagements',
    'faq'         => 'FAQ',
];
$socials = [];
foreach ($config['socials'] as $network => $url) {
    if ($url !== '') {
        $socials[$network] = $url;
    }
}
?>
<a class="skip-link" href="#contenu">Aller au contenu</a>

<header class="site-header" id="site-header" data-header>
    <div class="site-header__inner">
        <?= dst_logo('#top', 'logo--header', 'header') ?>

        <nav class="nav" aria-label="Navigation principale">
            <ul class="nav__list">
                <?php foreach ($navItems as $id => $label): ?>
                    <li><a class="nav__link" href="#<?= $id ?>" data-nav="<?= $id ?>"><?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
            <span class="nav__underline" aria-hidden="true"></span>
        </nav>

        <div class="header-actions">
            <a class="header-phone" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>" aria-label="Appeler DST au <?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?>">
                <span class="header-phone__icon"><?= dst_icon('phone', '', 17) ?></span>
                <span class="header-phone__number mono"><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>

            <a class="btn btn--primary btn--sm header-cta" href="#devis" data-quote-link>
                <span class="btn__label">Demander un devis</span>
                <span class="btn__ico" aria-hidden="true">
                    <?= dst_icon('arrow-right', 'ico-a', 17) ?><?= dst_icon('arrow-right', 'ico-b', 17) ?>
                </span>
            </a>

            <button class="burger" type="button" aria-expanded="false" aria-controls="mobile-menu" aria-label="Ouvrir le menu" data-burger>
                <span></span><span></span>
            </button>
        </div>
    </div>
    <div class="read-progress" aria-hidden="true"><span data-progress></span></div>
</header>

<div class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu__inner">
        <p class="mobile-menu__label mono">Navigation</p>
        <nav aria-label="Navigation mobile">
            <ul class="mobile-menu__list">
                <?php $i = 1; foreach ($navItems as $id => $label): ?>
                    <li style="--i:<?= $i++ ?>"><a href="#<?= $id ?>" data-menu-link><span class="mono">0<?= $i - 1 ?></span><?= $label ?></a></li>
                <?php endforeach; ?>
                <li style="--i:<?= $i++ ?>"><a href="#devis" data-menu-link><span class="mono">07</span>Demander un devis</a></li>
            </ul>
        </nav>
        <div class="mobile-menu__foot">
            <a class="mobile-menu__phone" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>">
                <?= dst_icon('phone', '', 20) ?><span><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
            <a class="mobile-menu__mail" href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>">
                <?= dst_icon('mail', '', 20) ?><span><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
            <p class="mobile-menu__hours"><?= htmlspecialchars($contact['hours'], ENT_QUOTES, 'UTF-8') ?></p>
            <?php if ($socials): ?>
                <ul class="socials socials--menu">
                    <?php foreach ($socials as $network => $url): ?>
                        <li>
                            <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="<?= ucfirst($network) ?>">
                                <?= dst_social($network, 18) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
