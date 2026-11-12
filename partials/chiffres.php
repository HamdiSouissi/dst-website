<?php
/**
 * 06 — En chiffres : grille suisse (bento) avec impression ligne par ligne.
 * @var array $config
 */
$st = $config['stats'];
?>
<section class="section stats" id="chiffres" aria-labelledby="stats-title" data-section="stats">
  <div class="wrap">
    <header class="section__head section__head--tight">
      <p class="section__num"><span><?= e($st['number']) ?></span><?= e($st['label']) ?></p>
      <h2 class="h2 h2--visually-hidden" id="stats-title">DST en chiffres</h2>
    </header>

    <div class="stats__grid">
      <?php foreach ($st['items'] as $i => $it): ?>
        <div class="stat stat--<?= (int) $i ?><?= $it['certain'] ? ' stat--certain' : '' ?>">
          <p class="stat__value" data-print><span data-value="<?= e(strip_tags($it['value'])) ?>"><?= e($it['value']) ?></span><em><?= e($it['unit']) ?></em></p>
          <p class="stat__label"><?= e($it['label']) ?></p>
          <p class="stat__text"><?= e($it['text']) ?></p>
          <?php if (!$it['certain']): ?><span class="stat__todo" title="Chiffre à valider par le client">à valider</span><?php endif; ?>
        </div>
      <?php endforeach; ?>

      <figure class="stat stat--photo">
        <?= picture($st['photo'], 'Cagette de légumes frais livrée par DST', 'stat__img', 30) ?>
        <figcaption>
          <?= sticker('sticker sticker--mini', 'DST') ?>
          <span>Chaque matin, directement chez vous.</span>
        </figcaption>
      </figure>
    </div>
  </div>
</section>
