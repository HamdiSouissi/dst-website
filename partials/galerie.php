<?php
/**
 * 08 — Sur la route : galerie draggable (Draggable + Inertia).
 * @var array $config
 */
$g = $config['gallery'];
?>
<section class="section gallery" id="galerie" aria-labelledby="gallery-title" data-section="gallery">
  <div class="wrap">
    <header class="section__head section__head--row">
      <div>
        <p class="section__num"><span><?= e($g['number']) ?></span><?= e($g['label']) ?></p>
        <h2 class="h2" id="gallery-title">
          <span class="h2__line"><?= e($g['title_a']) ?></span>
          <span class="h2__line"><?= hl($g['highlight']) ?><span aria-hidden="true">.</span></span>
        </h2>
      </div>
      <p class="gallery__hint" aria-hidden="true">← <?= e($g['hint']) ?> →</p>
    </header>
  </div>

  <div class="gallery__viewport" data-gallery-viewport>
    <ul class="gallery__rail" data-gallery-rail tabindex="0" aria-label="Galerie de photos — utilisez les flèches pour faire défiler">
      <?php foreach ($g['items'] as $i => $item): ?>
        <li class="gallery__item" style="--ratio:<?= e($item['ratio']) ?>">
          <figure>
            <div class="gallery__media">
              <?= picture($item['src'], $item['caption'] . ' — ' . $item['time'], 'gallery__img', 34) ?>
            </div>
            <figcaption>
              <span class="gallery__time"><?= e($item['time']) ?></span>
              <span class="gallery__caption"><?= e($item['caption']) ?></span>
            </figcaption>
          </figure>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="wrap">
    <div class="gallery__bar">
      <div class="gallery__progress" aria-hidden="true"><i data-gallery-progress></i></div>
      <p class="gallery__note"><?= e($g['note']) ?></p>
    </div>
  </div>
</section>
