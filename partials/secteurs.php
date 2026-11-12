<?php
/**
 * 03 — Pour qui ? Barre de pastilles + scène photo (transition Flip).
 * @var array $config
 */
$s = $config['sectors'];
?>
<section class="section sectors" id="secteurs" aria-labelledby="sectors-title" data-section="sectors">
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span><?= e($s['number']) ?></span><?= e($s['label']) ?></p>
      <h2 class="h2" id="sectors-title">
        <span class="h2__line"><?= e($s['title_a']) ?></span>
        <span class="h2__line"><?= hl($s['highlight']) ?><span aria-hidden="true">.</span></span>
      </h2>
    </header>

    <div class="sectors__bar" role="tablist" aria-label="Secteurs clients" data-sectors-tabs>
      <?php foreach ($s['items'] as $i => $sec): ?>
        <button class="chip<?= $i === 0 ? ' is-active' : '' ?>" type="button" role="tab"
                id="sector-tab-<?= e($sec['slug']) ?>"
                aria-selected="<?= $i === 0 ? 'true' : 'false' ?>"
                aria-controls="sector-panel-<?= e($sec['slug']) ?>"
                tabindex="<?= $i === 0 ? '0' : '-1' ?>"
                style="--chip:<?= e($sec['color']) ?>"
                data-sector="<?= e($sec['slug']) ?>">
          <span class="chip__dot" aria-hidden="true"></span>
          <span class="chip__label"><?= e($sec['name']) ?></span>
          <span class="chip__progress" aria-hidden="true"><i data-progress></i></span>
        </button>
      <?php endforeach; ?>
    </div>

    <div class="sectors__scenes" data-sectors-scenes>
      <?php foreach ($s['items'] as $i => $sec): ?>
        <div class="scene<?= $i === 0 ? ' is-active' : '' ?>" id="sector-panel-<?= e($sec['slug']) ?>"
             role="tabpanel" aria-labelledby="sector-tab-<?= e($sec['slug']) ?>"
             <?= $i === 0 ? '' : 'hidden' ?> data-scene="<?= e($sec['slug']) ?>" style="--scene:<?= e($sec['color']) ?>">
          <div class="scene__media" data-scene-media>
            <?= picture($sec['image'], $sec['name'] . ' — livraison de produits frais DST', 'scene__img', 60) ?>
          </div>
          <div class="scene__card">
            <span class="scene__badge"><i aria-hidden="true"></i><?= e($sec['name']) ?></span>
            <p class="scene__text"><?= e($sec['text']) ?></p>
            <ul class="scene__tags">
              <?php foreach ($sec['tags'] as $tag): ?><li><?= e($tag) ?></li><?php endforeach; ?>
            </ul>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
