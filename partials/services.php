<?php
/**
 * 02 — Services : étiquettes de cagette.
 * Inclinaison 3D + reflet, retournement recto/verso.
 * @var array $config
 */
$s = $config['services'];
$layout = ['a', 'b', 'c', 'd', 'e', 'f'];
?>
<section class="section services" id="services" aria-labelledby="services-title" data-section="services">
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span><?= e($s['number']) ?></span><?= e($s['label']) ?></p>
      <h2 class="h2" id="services-title">
        <span class="h2__line"><?= e($s['title_a']) ?></span>
        <span class="h2__line"><?= hl($s['highlight']) ?><span aria-hidden="true">.</span></span>
      </h2>
    </header>

    <ul class="labels" data-labels>
      <?php foreach ($s['items'] as $i => $item): ?>
        <li class="label label--<?= e($layout[$i]) ?> label--<?= e($item['tone']) ?>" style="--label-color:<?= e($item['color']) ?>">
          <div class="label__tilt" data-tilt>
            <div class="label__sheen" aria-hidden="true"></div>
            <span class="label__pin" aria-hidden="true"></span>
            <button class="label__btn" type="button" aria-pressed="false" aria-expanded="false"
                    aria-controls="label-detail-<?= $i ?>" data-flip>
              <span class="label__face label__face--front">
                <span class="label__num"><?= e($item['num']) ?></span>
                <span class="label__icon"><?= icon($item['icon'] === 'map' ? 'map-pin' : $item['icon']) ?></span>
                <span class="label__title"><?= e($item['title']) ?></span>
                <span class="label__lead"><?= e($item['lead']) ?></span>
                <span class="label__meta"><?= e($item['meta']) ?></span>
                <span class="label__more" aria-hidden="true">Détail <?= icon('arrow-up-right') ?></span>
              </span>
              <span class="label__face label__face--back" id="label-detail-<?= $i ?>">
                <span class="label__num"><?= e($item['num']) ?></span>
                <span class="label__detail"><?= e($item['detail']) ?></span>
                <span class="label__back-title"><?= e($item['title']) ?></span>
              </span>
            </button>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
    <p class="services__hint">Cliquez une étiquette pour lire le détail.</p>
  </div>
</section>
