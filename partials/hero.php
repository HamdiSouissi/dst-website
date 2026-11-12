<?php
/**
 * Hero typographique.
 *
 * Un seul SVG porte le mot géant :
 *  - l'intérieur des lettres est rempli par la vidéo de produits frais
 *    (clipPath exact des contours de Bricolage Grotesque) ;
 *  - la camionnette traverse le mot, masquée par les lettres du mot courant :
 *    elle passe donc derrière elles et devant le fond ;
 *  - le mot devient cinétique (FRAIS → DIRECT → À L'HEURE) : les trois mots
 *    occupent la même largeur, seule la hauteur change.
 *
 * @var array $config
 */
require_once __DIR__ . '/hero-glyphs.php';
$hero     = $config['hero'];
$words    = hero_words();
$box      = hero_box();
$viewBox  = sprintf('0 %s %s %s', $box['top'], $box['w'], $box['h']);
$hasVideo = is_file(__DIR__ . '/../' . $hero['video']) && is_file(__DIR__ . '/../' . $hero['poster']);
?>
<section class="hero" id="top" aria-labelledby="hero-title" data-hero>
  <div class="hero__grid" aria-hidden="true" data-grid-lines></div>

  <div class="hero__stage" data-hero-stage>
    <div class="hero__word-wrap">
      <div class="hero__word" data-hero-word aria-hidden="true">
        <svg class="hero__sign" viewBox="<?= e($viewBox) ?>" preserveAspectRatio="xMidYMid meet" data-hero-sign>
          <defs>
            <?= hero_glyph_defs() ?>
            <?php foreach ($words as $i => $w): ?>
            <clipPath id="word-clip-<?= (int) $i ?>" clipPathUnits="userSpaceOnUse"><?= hero_letters($i) ?></clipPath>
            <?php endforeach; ?>
            <mask id="van-mask" maskUnits="userSpaceOnUse" x="-200" y="<?= (int) $box['top'] - 260 ?>" width="<?= (int) $box['w'] + 400 ?>" height="<?= (int) $box['h'] + 560 ?>">
              <rect x="-200" y="<?= (int) $box['top'] - 260 ?>" width="<?= (int) $box['w'] + 400 ?>" height="<?= (int) $box['h'] + 560 ?>" fill="#fff"/>
              <g fill="#000" id="van-cut"><?= hero_letters(0) ?></g>
            </mask>
            <linearGradient id="frais-fallback" x1="0" y1="0" x2=".35" y2="1">
              <stop offset="0" stop-color="#1F7A4C"/>
              <stop offset=".55" stop-color="#2C8A58"/>
              <stop offset="1" stop-color="#163F2A"/>
            </linearGradient>
            <linearGradient id="frais-sheen" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0" stop-color="#FAF6EE" stop-opacity=".16"/>
              <stop offset=".45" stop-color="#FAF6EE" stop-opacity="0"/>
              <stop offset="1" stop-color="#2B1A2B" stop-opacity=".18"/>
            </linearGradient>
            <pattern id="frais-leaves" width="150" height="150" patternUnits="userSpaceOnUse" patternTransform="rotate(18)">
              <path d="M30,112 C18,74 44,48 70,36 C72,70 58,104 30,112 Z" fill="#CFE8C9" opacity=".18"/>
              <path d="M100,60 C90,34 106,12 128,4 C132,32 122,54 100,60 Z" fill="#CFE8C9" opacity=".11"/>
            </pattern>
          </defs>

          <?php foreach ($words as $i => $w): ?>
          <!-- mot <?= (int) $i ?> : <?= e($w['text']) ?> -->
          <g class="hero__w<?= $i === 0 ? ' is-on' : '' ?>" data-word="<?= (int) $i ?>"
             style="visibility:<?= $i === 0 ? 'visible' : 'hidden' ?>">
            <g clip-path="url(#word-clip-<?= (int) $i ?>)">
              <rect x="0" y="<?= $box['top'] ?>" width="<?= $box['w'] ?>" height="<?= $box['h'] ?>" fill="<?= $i === 0 ? 'url(#frais-fallback)' : '#1F7A4C' ?>"/>
              <rect x="0" y="<?= $box['top'] ?>" width="<?= $box['w'] ?>" height="<?= $box['h'] ?>" fill="url(#frais-leaves)"/>
              <rect x="0" y="<?= $box['top'] ?>" width="<?= $box['w'] ?>" height="<?= $box['h'] ?>" fill="url(#frais-sheen)"/>
              <?php if ($i === 0 && $hasVideo): ?>
              <foreignObject class="hero__video-holder" x="0" y="<?= $box['top'] ?>" width="<?= $box['w'] ?>" height="<?= $box['h'] ?>">
                <video class="hero__video" data-hero-video muted loop playsinline preload="none"
                       poster="<?= e(asset($hero['poster'])) ?>" tabindex="-1">
                  <source src="<?= e(asset($hero['video'])) ?>" type="video/mp4">
                </video>
              </foreignObject>
              <?php endif; ?>
            </g>

            <?php if ($i === 0): ?>
            <!-- la camionnette : derrière chaque lettre, devant le fond -->
            <g class="hero__van" mask="url(#van-mask)" data-hero-van>
              <image class="hero__van-shot" href="<?= e(asset('assets/img/flotte/master.svg')) ?>"
                     x="0" y="-292" width="600" height="300" preserveAspectRatio="xMaxYMax meet"/>
              <g class="hero__frost" aria-hidden="true">
                <circle cx="8" cy="-150" r="7"/>
                <circle cx="26" cy="-104" r="5"/>
                <circle cx="-6" cy="-124" r="10"/>
                <circle cx="44" cy="-170" r="4"/>
                <circle cx="34" cy="-78" r="6"/>
              </g>
            </g>
            <?php endif; ?>

            <g class="hero__outline" fill="none" stroke="#2B1A2B" stroke-width="4" stroke-linejoin="round" opacity="0"><?= hero_letters($i) ?></g>
          </g>
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
