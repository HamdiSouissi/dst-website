<?php
/** @var array $config */
require_once __DIR__ . '/logo.php';
require_once __DIR__ . '/socials.php';

$nav = [
    ['#circuit', 'Le circuit'],
    ['#services', 'Services'],
    ['#flotte', 'Flotte'],
    ['#zone', 'Zone'],
    ['#faq', 'FAQ'],
];
?>
<div class="topbar" id="topbar">
  <div class="topbar__inner">
    <p class="topbar__text"><?= e($config['topbar']) ?></p>
    <a class="topbar__phone" href="<?= e(tel_href($config)) ?>">
      <?= icon('phone') ?><span><?= e($config['contact']['phone_display']) ?></span>
    </a>
  </div>
</div>

<header class="header" id="header" data-header>
  <div class="header__inner">
    <div class="header__brand">
      <?= logo_markup('logo', 'header-logo') ?>
      <?= sticker('sticker sticker--header', 'DST') ?>
    </div>

    <nav class="header__nav" aria-label="Navigation principale">
      <ul>
        <?php foreach ($nav as [$href, $label]): ?>
          <li><a href="<?= e($href) ?>" data-nav><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="header__actions">
      <a class="btn btn--basil header__cta" href="#devis">
        <span><?= e($config['hero']['cta']) ?></span>
        <?= icon('arrow-up-right', 'ph ph--arrow') ?>
      </a>
      <button class="menu-btn" type="button" aria-expanded="false" aria-controls="mobile-panel" data-menu-open>
        <span class="menu-btn__label">Menu</span>
      </button>
    </div>
  </div>
</header>

<div class="mobile-panel" id="mobile-panel" role="dialog" aria-modal="true" aria-label="Menu" hidden>
  <div class="mobile-panel__head">
    <?= logo_markup('logo logo--panel', '', true) ?>
    <button class="mobile-panel__close" type="button" data-menu-close>
      <span>Fermer</span><?= icon('x') ?>
    </button>
  </div>
  <nav class="mobile-panel__nav" aria-label="Navigation mobile">
    <ul>
      <?php foreach ($nav as $i => [$href, $label]):
          $dot = ['#E5334B', '#F39A55', '#F3C300', '#F5DC4A', '#6B4A6B', '#E7C97F'][$i % 6]; ?>
        <li><a href="<?= e($href) ?>" data-menu-link><span class="mobile-panel__dot" style="--dot:<?= e($dot) ?>" aria-hidden="true"></span><?= e($label) ?></a></li>
      <?php endforeach; ?>
      <li class="mobile-panel__last"><a href="#devis" data-menu-link><span class="mobile-panel__dot" style="--dot:#F3C300" aria-hidden="true"></span>Devis</a></li>
    </ul>
  </nav>
  <div class="mobile-panel__foot">
    <a class="mobile-panel__phone" href="<?= e(tel_href($config)) ?>"><?= e($config['contact']['phone_display']) ?></a>
    <a class="mobile-panel__mail" href="mailto:<?= e($config['contact']['email']) ?>"><?= e($config['contact']['email']) ?></a>
    <p class="mobile-panel__hours"><?= e($config['contact']['hours']) ?></p>
    <?= social_links($config['socials'], 'socials socials--panel') ?>
  </div>
</div>
