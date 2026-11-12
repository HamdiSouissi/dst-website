<?php
/** @var array $config */
$testimonials = $config['testimonials'] ?? [];
if (empty($testimonials)) {
    return; // Aucun faux avis : la section n'existe pas tant que la config est vide.
}
$rating = $config['google_rating'] ?? null;
?>
<section class="testimonials section" id="temoignages" data-theme="light" aria-labelledby="testimonials-title">
    <div class="container">
        <header class="section-head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>08 — Témoignages</p>
            <div class="section-head__row">
                <h2 class="h2 section-head__title" id="testimonials-title">
                    Ce qu'ils en
                    <span class="line-mask"><span class="line"><em class="serif">disent.</em></span></span>
                </h2>
                <?php if ($rating): ?>
                    <a class="rating" href="<?= htmlspecialchars($rating['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <span class="rating__score mono"><?= htmlspecialchars((string) $rating['score'], ENT_QUOTES, 'UTF-8') ?>/5</span>
                        <span class="rating__label"><?= (int) $rating['count'] ?> avis Google</span>
                    </a>
                <?php endif; ?>
            </div>
        </header>

        <div class="quotes" data-quotes>
            <?php foreach ($testimonials as $i => $t): ?>
                <figure class="quote<?= $i === 0 ? ' is-active' : '' ?>" data-quote="<?= $i ?>" <?= $i === 0 ? '' : 'hidden' ?>>
                    <blockquote>
                        <p><?= htmlspecialchars($t['text'], ENT_QUOTES, 'UTF-8') ?></p>
                    </blockquote>
                    <figcaption>
                        <span class="quote__name"><?= htmlspecialchars($t['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="quote__role"><?= htmlspecialchars(trim(($t['role'] ?? '') . ' · ' . ($t['company'] ?? ''), ' ·'), ENT_QUOTES, 'UTF-8') ?></span>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>

        <div class="quotes__nav" data-quotes-nav>
            <?php foreach ($testimonials as $i => $t): ?>
                <button type="button" class="quotes__dot<?= $i === 0 ? ' is-active' : '' ?>" data-quote-dot="<?= $i ?>" aria-label="Afficher le témoignage <?= $i + 1 ?>"></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>
