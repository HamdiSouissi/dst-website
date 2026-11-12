<?php
/**
 * 01 — Le circuit direct : comparaison animée de deux circuits.
 * @var array $config
 */
$c = $config['circuit'];
$classic = $c['classic'];
$direct  = $c['direct'];
?>
<section class="section circuit" id="circuit" aria-labelledby="circuit-title" data-section="circuit">
  <div class="section__grid" aria-hidden="true" data-grid-lines></div>
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span><?= e($c['number']) ?></span><?= e($c['label']) ?></p>
      <div class="section__head-main">
        <h2 class="h2" id="circuit-title">
          <span class="h2__line"><?= e($c['title_a']) ?></span>
          <span class="h2__line"><?= hl($c['highlight']) ?><span aria-hidden="true">.</span></span>
        </h2>
        <p class="section__intro"><?= e($c['intro']) ?></p>
      </div>
    </header>
  </div>

  <div class="circuit__stage" data-circuit-stage>
    <div class="wrap">
      <div class="circuit__board" data-circuit-board>

        <!-- Ligne 1 : circuit classique -->
        <div class="circuit__row circuit__row--classic" data-row="classic">
          <div class="circuit__row-head">
            <span class="circuit__legend"><i aria-hidden="true"></i><?= e($classic['label']) ?></span>
            <span class="circuit__summary"><?= e($classic['summary']) ?></span>
          </div>
          <div class="circuit__lane">
            <div class="circuit__line" aria-hidden="true"></div>
            <ol class="circuit__steps">
              <?php foreach ($classic['steps'] as $i => $step): ?>
                <li class="circuit__step" data-step="<?= $i ?>">
                  <span class="circuit__dot" aria-hidden="true"><i></i></span>
                  <span class="circuit__step-label"><?= e($step) ?></span>
                  <?php if ($i > 0 && $i < count($classic['steps']) - 1): ?>
                    <span class="circuit__manip" aria-hidden="true">+1 manipulation</span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>

            <!-- Le produit qui voyage + sa jauge de fraîcheur -->
            <div class="circuit__traveler" data-traveler="classic" aria-hidden="true">
              <span class="circuit__gauge">
                <b data-gauge-val>100 %</b>
                <i><span data-gauge-fill></span></i>
              </span>
              <img src="<?= e(asset('assets/img/photos/flotte-01.jpg')) ?>" alt="" width="64" height="64" loading="lazy" decoding="async">
            </div>
          </div>
        </div>

        <!-- Ligne 2 : circuit DST -->
        <div class="circuit__row circuit__row--direct" data-row="direct">
          <div class="circuit__row-head">
            <span class="circuit__legend"><i aria-hidden="true"></i><?= e($direct['label']) ?></span>
            <span class="circuit__summary"><?= e($direct['summary']) ?></span>
          </div>
          <div class="circuit__lane">
            <div class="circuit__line" aria-hidden="true"></div>
            <ol class="circuit__steps circuit__steps--direct">
              <?php foreach ($direct['steps'] as $i => $step): ?>
                <li class="circuit__step<?= $i === 1 ? ' circuit__step--van' : '' ?>" data-step="<?= $i ?>">
                  <?php if ($i === 1): ?>
                    <span class="circuit__van" aria-hidden="true">
                      <img src="<?= e(asset('assets/img/flotte/master.svg')) ?>" alt="" width="555" height="249" loading="lazy" decoding="async">
                    </span>
                    <span class="circuit__step-label"><?= e($step) ?></span>
                  <?php else: ?>
                    <span class="circuit__dot" aria-hidden="true"><i></i></span>
                    <span class="circuit__step-label"><?= e($step) ?></span>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>

            <div class="circuit__traveler circuit__traveler--bright" data-traveler="direct" aria-hidden="true">
              <span class="circuit__gauge circuit__gauge--ok">
                <b data-gauge-val>100 %</b>
                <i><span data-gauge-fill></span></i>
                <?= icon('snowflake', 'ph ph--flake') ?>
              </span>
              <img src="<?= e(asset('assets/img/photos/flotte-01.jpg')) ?>" alt="" width="64" height="64" loading="lazy" decoding="async">
            </div>
          </div>
        </div>

      </div>

      <div class="circuit__foot">
        <p class="circuit__note"><?= e($c['note']) ?></p>
        <button class="btn btn--ghost circuit__replay" type="button" data-circuit-replay>
          <?= e($c['replay']) ?> <?= icon('arrow-clockwise', 'ph ph--replay') ?>
        </button>
      </div>
    </div>
  </div>

  <div class="wrap">
    <ul class="benefits">
      <?php foreach ($c['benefits'] as $b): ?>
        <li class="benefit">
          <span class="benefit__icon"><?= icon($b['icon'] === 'snow' ? 'snowflake' : $b['icon'], 'ph ph--draw') ?></span>
          <h3 class="benefit__title"><?= e($b['title']) ?></h3>
          <p class="benefit__text"><?= e($b['text']) ?></p>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
