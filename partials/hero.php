<?php
/**
 * Hero cinématographique.
 *
 * Un film en boucle (plusieurs plans : rue parisienne, trois-quarts, chargement
 * au marché, arrière de caisse sur les quais) occupe toute la largeur. Le mot
 * géant est découpé dans un voile crème posé sur la vidéo : les lettres laissent
 * voir l'image en pleine lumière, le reste est légèrement assombri.
 *
 * Le mot devient cinétique (FRAIS → DIRECT → À L'HEURE) : les trois découpes
 * sont superposées dans le même masque, on les fait se fondre l'une dans
 * l'autre. Un seul fichier vidéo, aucun décalage possible.
 *
 * @var array $config
 */
require_once __DIR__ . '/hero-glyphs.php';
$hero     = $config['hero'];
$words    = hero_words();
$box      = hero_box();
$viewBox  = sprintf('0 %s %s %s', $box['top'], $box['w'], $box['h']);
$hasVideo = is_file(__DIR__ . '/../' . $hero['video']) && is_file(__DIR__ . '/../' . $hero['poster']);
$hasPoster = is_file(__DIR__ . '/../' . $hero['poster']);
?>
<section class="hero" id="top" aria-labelledby="hero-title" data-hero>
  <div class="hero__stage" data-hero-stage>
    <?php if ($hasVideo): ?>
    <video class="hero__film" data-hero-video muted loop playsinline preload="none"
           poster="<?= e(asset($hero['poster'])) ?>" tabindex="-1" aria-hidden="true">
      <source src="<?= e(asset($hero['video'])) ?>" type="video/mp4">
    </video>
    <?php elseif ($hasPoster): ?>
    <img class="hero__film" src="<?= e(asset($hero['poster'])) ?>" alt="" aria-hidden="true" decoding="async">
    <?php endif; ?>

    <div class="hero__veil" aria-hidden="true"></div>

    <div class="hero__word-wrap<?= $hasVideo || $hasPoster ? ' has-film' : '' ?>">
      <div class="hero__word" data-hero-word aria-hidden="true">
        <svg class="hero__sign" viewBox="<?= e($viewBox) ?>" preserveAspectRatio="xMidYMid meet" data-hero-sign>
          <defs>
            <?= hero_glyph_defs() ?>
            <mask id="word-mask" maskUnits="userSpaceOnUse" x="-40" y="<?= (int) $box['top'] - 20 ?>"
                  width="<?= (int) $box['w'] + 80 ?>" height="<?= (int) $box['h'] + 40 ?>">
              <rect x="-40" y="<?= (int) $box['top'] - 20 ?>" width="<?= (int) $box['w'] + 80 ?>"
                    height="<?= (int) $box['h'] + 40 ?>" fill="#fff"/>
              <?php foreach ($words as $i => $w): ?>
              <g class="hero__hole<?= $i === 0 ? ' is-on' : '' ?>" data-hole="<?= (int) $i ?>"
                 fill="#000" style="opacity:<?= $i === 0 ? '1' : '0' ?>"><?= hero_letters($i) ?></g>
              <?php endforeach; ?>
            </mask>
          </defs>
          <!-- le bandeau crème : les lettres sont les seules fenêtres sur le film -->
          <g mask="url(#word-mask)">
            <rect class="hero__band" x="-40" y="<?= (int) $box['top'] - 20 ?>" width="<?= (int) $box['w'] + 80 ?>"
                  height="<?= (int) $box['h'] + 40 ?>" fill="#FAF6EE" opacity=".985"/>
          </g>
          <?php foreach ($words as $i => $w): ?>
          <g class="hero__outline<?= $i === 0 ? ' is-on' : '' ?>" data-outline="<?= (int) $i ?>"
             fill="none" stroke="#2B1A2B" stroke-width="3" stroke-linejoin="round"
             opacity="<?= $i === 0 ? '0' : '0' ?>"><?= hero_letters($i) ?></g>
          <?php endforeach; ?>
        </svg>
      </div>

      <?php if ($hasVideo): ?>
      <p class="hero__video-credit"><?= e($hero['video_credit']) ?></p>
      <?php endif; ?>
    </div>

    <?= sticker('sticker sticker--hero', 'DST', true) ?>
  </div>

  <div class="hero__content">
    <div class="hero__col hero__col--a">
      <p class="kicker"><span class="kicker__dot" aria-hidden="true"></span><?= e($hero['kicker']) ?></p>
      <h1 class="hero__title" id="hero-title">
        <span class="hero__title-line"><?= e($hero['title_a']) ?></span>
        <span class="hero__title-line"><?= hl($hero['highlight']) ?></span>
      </h1>
      <p class="hero__text"><?= e($hero['text']) ?></p>
    </div>

    <div class="hero__col hero__col--b">
      <div class="hero__ctas">
        <a class="btn btn--basil btn--lg" href="#devis">
          <span><?= e($hero['cta']) ?></span><?= icon('arrow-up-right', 'ph ph--arrow') ?>
        </a>
        <a class="link-underline" href="#circuit"><?= e($hero['cta_secondary']) ?> <?= icon('arrow-down', 'ph ph--down') ?></a>
      </div>
      <ul class="hero__pills">
        <?php foreach ($hero['pills'] as $pill): ?>
          <li><?= e($pill) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
</section>
