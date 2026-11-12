<?php
/**
 * 05 — Zone : carte typographique de l'Île-de-France.
 * Chaque département est un polygone simplifié ; les communes sont des mots
 * placés en PHP (positions précalculées dans config.php).
 * @var array $config
 */
$z = $config['zone'];
?>
<section class="section zone" id="zone" aria-labelledby="zone-title" data-section="zone">
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span><?= e($z['number']) ?></span><?= e($z['label']) ?></p>
      <div class="section__head-main">
        <h2 class="h2" id="zone-title">
          <span class="h2__line"><?= e($z['title_a']) ?></span>
          <span class="h2__line"><?= hl($z['highlight']) ?><span aria-hidden="true">.</span></span>
        </h2>
        <p class="section__intro"><?= e($z['text']) ?></p>
      </div>
    </header>

    <div class="zone__layout">
      <div class="zone__map" data-zone-map>
        <svg class="zone__svg" viewBox="0 0 1000 800" role="group" aria-label="Carte typographique de l'Île-de-France — survolez un département">
          <g class="zone__departments">
            <?php foreach ($z['maps'] as $dep): ?>
              <g class="zone__dep" data-dep="<?= e($dep['code']) ?>" tabindex="0" role="button"
                 aria-label="<?= e($dep['name']) ?> (<?= e($dep['code']) ?>) — Livraison <?= e($dep['delay']) ?>" data-delay="<?= e($dep['delay']) ?>">
                <polygon class="zone__shape" points="<?= e($dep['shape']) ?>" />
                <?php foreach ($dep['labels'] as $label): ?>
                  <text class="zone__label zone__label--<?= $label['s'] >= 17 ? 'lg' : ($label['s'] >= 15 ? 'md' : 'sm') ?>"
                        x="<?= e((string) $label['x']) ?>" y="<?= e((string) $label['y']) ?>"
                        font-size="<?= e((string) $label['s']) ?>"><?= e($label['n']) ?></text>
                <?php endforeach; ?>
              </g>
            <?php endforeach; ?>
          </g>

          <!-- Arcueil : siège social (aucune mention de dépôt) -->
          <g class="zone__hq" transform="translate(437 528)">
            <line x1="0" y1="0" x2="18" y2="-26" class="zone__hq-line" />
            <circle cx="0" cy="0" r="3.4" class="zone__hq-dot" />
            <g class="zone__hq-badge" transform="translate(20 -56)">
              <rect width="118" height="26" rx="13" />
              <text x="59" y="17.5" text-anchor="middle">Siège social</text>
            </g>
          </g>
        </svg>

        <div class="zone__tooltip" data-zone-tooltip aria-hidden="true">
          <strong data-tip-name></strong>
          <span data-tip-delay></span>
          <span data-tip-list></span>
        </div>
      </div>

      <div class="zone__aside">
        <ul class="zone__list">
          <?php foreach ($z['maps'] as $dep):
              $meta = null;
              foreach ($z['list'] as $l) { if ($l[0] === $dep['code']) { $meta = $l; } }
              $names = array_map(static fn(array $l): string => $l['n'], $dep['labels']); ?>
            <li>
              <button class="zone__link" type="button" data-zone-link="<?= e($dep['code']) ?>">
                <span class="zone__link-code"><?= e($dep['code']) ?></span>
                <span class="zone__link-name"><?= e($dep['name']) ?></span>
                <span class="zone__link-delay"><?= e($dep['delay']) ?></span>
              </button>
            </li>
          <?php endforeach; ?>
        </ul>
        <p class="zone__notice"><?= e($z['notice']) ?></p>
        <p class="zone__hq-note">
          <span class="zone__hq-pin" aria-hidden="true"></span>
          <?= e($config['head_office']['label']) ?> · <?= e($config['head_office']['address_line']) ?>, <?= e($config['head_office']['postcode']) ?> <?= e($config['head_office']['city']) ?>
          <em><?= e($config['head_office']['note']) ?></em>
        </p>
      </div>
    </div>
  </div>
</section>
