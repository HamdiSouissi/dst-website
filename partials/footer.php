<?php
/**
 * Bande d'appel + footer + éléments flottants + panneaux légaux.
 * @var array $config
 */
require_once __DIR__ . '/logo.php';
require_once __DIR__ . '/socials.php';
$ho = $config['head_office'];
$wa = whatsapp_href($config);
?>
<section class="callband" id="appeler" aria-labelledby="callband-title">
  <?= sticker('sticker sticker--spin', 'DST', true) ?>
  <div class="wrap callband__inner">
    <h2 class="callband__title" id="callband-title"><?= e($config['call_band']['text']) ?></h2>
    <div class="callband__action">
      <a class="btn btn--aubergine btn--lg" href="<?= e(tel_href($config)) ?>">
        <?= icon('phone') ?><span><?= e($config['call_band']['cta']) ?></span>
      </a>
      <a class="callband__number" href="<?= e(tel_href($config)) ?>"><?= e($config['contact']['phone_display']) ?></a>
    </div>
  </div>
</section>

<footer class="footer" id="footer">
  <div class="footer__word" aria-hidden="true" data-footer-word>DST</div>

  <div class="wrap footer__cols">
    <div class="footer__col footer__col--brand">
      <?= brand_mark('footer__mark', 'cream') ?>
      <?= sticker('sticker sticker--footer', 'DST') ?>
      <p class="footer__claim"><?= e($config['company']['tagline']) ?></p>
      <p class="footer__desc"><?= e($config['footer']['tagline']) ?></p>
    </div>

    <div class="footer__col">
      <h3 class="footer__h"><?= e($config['footer']['columns'][0]) ?></h3>
      <address class="footer__address">
        <?= e($ho['address_line']) ?><br>
        <?= e($ho['postcode']) ?> <?= e($ho['city']) ?>
      </address>
      <p class="footer__small"><?= e($ho['note']) ?></p>
      <p class="footer__hours"><?= e($config['contact']['hours']) ?></p>
    </div>

    <div class="footer__col">
      <h3 class="footer__h"><?= e($config['footer']['columns'][1]) ?></h3>
      <ul class="footer__contact">
        <li><a href="<?= e(tel_href($config)) ?>"><?= icon('phone') ?><?= e($config['contact']['phone_display']) ?></a></li>
        <li><a href="mailto:<?= e($config['contact']['email']) ?>"><?= icon('envelope-simple') ?><?= e($config['contact']['email']) ?></a></li>
        <?php if ($wa): ?>
        <li><a href="<?= e($wa) ?>" target="_blank" rel="noopener noreferrer"><?= icon('whatsapp-logo') ?>WhatsApp</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="footer__col">
      <h3 class="footer__h"><?= e($config['footer']['columns'][2]) ?></h3>
      <?= social_links($config['socials'], 'socials socials--footer') ?>
      <p class="footer__small"><?= e($config['contact']['response_time']) ?> pour vous répondre.</p>
    </div>
  </div>

  <div class="wrap footer__bottom">
    <p class="footer__copy">© <?= date('Y') ?> <?= e($config['company']['name']) ?></p>
    <ul class="footer__legal">
      <li><button class="link-inline" type="button" data-open-panel="legal"><?= e($config['footer']['legal_links'][0]) ?></button></li>
      <li><button class="link-inline" type="button" data-open-panel="privacy"><?= e($config['footer']['legal_links'][1]) ?></button></li>
      <li><button class="link-inline" type="button" data-open-panel="cookies"><?= e($config['footer']['legal_links'][2]) ?></button></li>
    </ul>
  </div>

  <div class="footer__credit">
    <a class="footer__agency" href="<?= e($config['credits']['agency_url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($config['credits']['agency_label']) ?></a>
    <p><?= e($config['credits']['agency_text']) ?></p>
  </div>
</footer>

<!-- Barre mobile -->
<div class="mobile-bar" data-mobile-bar>
  <a class="mobile-bar__call" href="<?= e(tel_href($config)) ?>"><?= icon('phone') ?><span><?= e($config['call_band']['mobile_cta']) ?></span></a>
  <a class="mobile-bar__quote" href="#devis"><?= icon('chat-circle-dots') ?><span>Devis</span></a>
</div>

<!-- WhatsApp flottant -->
<?php if ($wa): ?>
<a class="wa-float" href="<?= e($wa) ?>" target="_blank" rel="noopener noreferrer" aria-label="Écrire à DST sur WhatsApp">
  <?= icon('whatsapp-logo') ?>
</a>
<?php endif; ?>

<!-- Notice cookies -->
<div class="cookies" data-cookies hidden>
  <p><?= e($config['footer']['cookie_notice']) ?></p>
  <button class="cookies__ok" type="button" data-cookies-ok><?= e($config['footer']['cookie_ok']) ?></button>
</div>

<!-- Curseur custom (desktop) -->
<div class="cursor" data-cursor aria-hidden="true">
  <span class="cursor__dot"></span>
  <span class="cursor__pill"><span data-cursor-label></span></span>
</div>

<?php require __DIR__ . '/panel-legal.php'; ?>
<?php require __DIR__ . '/panel-privacy.php'; ?>
<?php require __DIR__ . '/panel-cookies.php'; ?>
