<?php
/**
 * 04 — La flotte : bascule aubergine + comparateur de gabarits à l'échelle.
 * Les véhicules sont des SVG dessinés à l'échelle (1 unité = 1 m),
 * alignés sur une règle graduée en mètres.
 * @var array $config
 */
$f = $config['fleet'];
$vehicles = $f['vehicles'];
// échelle : le plus long véhicule (5,55 m) tient dans ~72 % de la colonne
$maxLen = max(array_column($vehicles, 'length'));
$scale = 0.72 / $maxLen; // fraction de la largeur de piste par mètre
?>
<section class="section fleet" id="flotte" aria-labelledby="fleet-title" data-section="fleet">
  <div class="fleet__bg" aria-hidden="true" data-fleet-bg></div>

  <div class="wrap fleet__inner" data-fleet-inner>
    <header class="section__head section__head--dark">
      <p class="section__num"><span><?= e($f['number']) ?></span><?= e($f['label']) ?></p>
      <div class="section__head-main">
        <h2 class="h2" id="fleet-title">
          <span class="h2__line"><?= e($f['title_a']) ?></span>
          <span class="h2__line"><?= hl($f['highlight']) ?><span aria-hidden="true">.</span></span>
        </h2>
        <p class="section__intro"><?= e($f['text']) ?></p>
      </div>
    </header>

    <div class="fleet__control">
      <label class="fleet__slider-label" for="volume-slider"><?= e($f['slider_label']) ?></label>
      <div class="fleet__slider-row">
        <input class="fleet__slider" id="volume-slider" type="range" min="1" max="10" step="0.5" value="4"
               aria-describedby="volume-out" data-volume>
        <output class="fleet__volume" id="volume-out" for="volume-slider" data-volume-out>4 m³</output>
      </div>
      <div class="fleet__ticks" aria-hidden="true">
        <?php for ($v = 1; $v <= 10; $v++): ?><span><?= $v ?></span><?php endfor; ?>
      </div>
    </div>

    <div class="fleet__ruler" aria-hidden="true">
      <span class="fleet__ruler-track" data-ruler>
        <?php for ($m = 0; $m <= 6; $m++): ?>
          <span class="fleet__tick<?= $m === 0 || $m === 6 ? ' is-edge' : '' ?>" style="left:<?= round(($m / 6) * 100, 3) ?>%"><b><?= $m ?></b><?= $m < 6 ? ' m' : ' m' ?></span>
        <?php endfor; ?>
      </span>
    </div>
    <p class="fleet__scale-note" aria-hidden="true">Échelle 1&nbsp;:&nbsp;1 — longueurs réelles des véhicules</p>

    <ul class="fleet__list" data-fleet-list>
      <?php foreach ($vehicles as $i => $v): ?>
        <li class="fleet__item<?= $v['slug'] === 'transit-custom' ? ' is-lit' : '' ?>" data-vehicle="<?= e($v['slug']) ?>"
            data-min="<?= e((string) $v['volume_min']) ?>" data-max="<?= e((string) $v['volume_max']) ?>">
          <button class="fleet__row" type="button" aria-pressed="<?= $v['slug'] === 'transit-custom' ? 'true' : 'false' ?>"
                  data-vehicle-btn aria-label="<?= e($v['name']) ?> — <?= e($v['gabarit']) ?>, <?= e($v['volume']) ?> de volume utile">
            <span class="fleet__lane">
              <span class="fleet__rules" aria-hidden="true">
                <?php for ($m = 0; $m < 7; $m++): ?>
                  <i style="left:<?= round(($m / $maxLen) * 100, 2) ?>%"></i>
                <?php endfor; ?>
              </span>
              <span class="fleet__van" style="width:<?= round($scale * 100, 2) ?>%" data-van>
                <img src="<?= e(asset($v['image'])) ?>" alt="" width="<?= (int) round($v['length'] * 100) ?>" height="<?= (int) round($v['height'] * 100) ?>" loading="lazy" decoding="async">
                <span class="fleet__badge"><?= e($f['ideal_badge']) ?></span>
              </span>
              <span class="fleet__len"><?= e(number_format($v['length'], 2, ',', '')) ?> m</span>
            </span>
            <span class="fleet__data">
              <span class="fleet__name"><?= e($v['name']) ?></span>
              <span class="fleet__gabarit"><?= e($v['gabarit']) ?></span>
              <span class="fleet__specs">
                <span><b><?= e($v['volume']) ?></b> volume</span>
                <span><b><?= e($v['charge']) ?></b> charge</span>
              </span>
              <span class="fleet__usage"><?= e($v['usage']) ?></span>
            </span>
          </button>
        </li>
      <?php endforeach; ?>
    </ul>

    <ul class="fleet__notes">
      <?php foreach ($f['notes'] as $n): ?><li><?= e($n) ?></li><?php endforeach; ?>
    </ul>
  </div>
</section>
