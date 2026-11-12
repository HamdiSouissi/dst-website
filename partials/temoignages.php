<?php
/**
 * 09 — Témoignages : n'apparaît que si de vrais avis sont renseignés.
 * @var array $config
 */
$items = $config['testimonials'] ?? [];
if (!$items) {
    return; // aucune section tant qu'il n'y a pas d'avis réels
}
$rating = $config['google_rating'] ?? null;
?>
<section class="section testimonials" id="temoignages" aria-labelledby="testimonials-title" data-section="testimonials">
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span>09</span>Témoignages</p>
      <h2 class="h2" id="testimonials-title"><?= e($config['testimonials_title']) ?></h2>
      <?php if ($rating): ?>
        <p class="testimonials__rating">
          <b><?= e(number_format((float) $rating['value'], 1, ',', '')) ?>/5</b>
          <span>Google · <?= e((string) $rating['count']) ?> avis</span>
          <a href="<?= e($rating['url']) ?>" target="_blank" rel="noopener noreferrer">Voir la fiche</a>
        </p>
      <?php endif; ?>
    </header>
  </div>
  <div class="testimonials__viewport" data-testimonials-viewport>
    <ul class="testimonials__rail" data-testimonials-rail>
      <?php foreach ($items as $t): ?>
        <li class="postcard" style="--pc:<?= e(sector_color($config, $t['sector'] ?? '')) ?>">
          <blockquote><p><?= e($t['quote']) ?></p></blockquote>
          <footer>
            <span class="postcard__dot" aria-hidden="true"></span>
            <span class="postcard__name"><?= e($t['name']) ?></span>
            <span class="postcard__role"><?= e($t['role']) ?> · <?= e($t['company']) ?></span>
          </footer>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
