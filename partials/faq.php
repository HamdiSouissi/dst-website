<?php
/**
 * 11 — Questions fréquentes : liste sticky à gauche, réponse en grand à droite.
 * Sur mobile : accordéon.
 * @var array $config
 */
$faq = $config['faq'];
?>
<section class="section faq" id="faq" aria-labelledby="faq-title" data-section="faq">
  <div class="wrap">
    <header class="section__head section__head--tight">
      <p class="section__num"><span><?= e($faq['number']) ?></span><?= e($faq['label']) ?></p>
      <h2 class="h2 h2--visually-hidden" id="faq-title">Questions fréquentes</h2>
    </header>

    <div class="faq__layout" data-faq>
      <div class="faq__list">
        <ol>
          <?php foreach ($faq['items'] as $i => $item): ?>
            <li>
              <button class="faq__q<?= $i === 0 ? ' is-active' : '' ?>" type="button"
                      aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>"
                      aria-controls="faq-a-<?= $i ?>" id="faq-q-<?= $i ?>"
                      data-faq-q="<?= $i ?>">
                <span class="faq__num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                <span class="faq__q-text"><?= e($item['q']) ?></span>
                <span class="faq__plus" aria-hidden="true"></span>
              </button>
              <div class="faq__a-mobile" id="faq-a-<?= $i ?>" <?= $i === 0 ? '' : 'hidden' ?>>
                <p><?= e($item['a']) ?></p>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>

      <div class="faq__panel" aria-live="polite">
        <?php foreach ($faq['items'] as $i => $item): ?>
          <article class="faq__answer<?= $i === 0 ? ' is-active' : '' ?>" data-faq-a="<?= $i ?>" <?= $i === 0 ? '' : 'hidden' ?>>
            <p class="faq__answer-num"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?> — <?= e($faq['label']) ?></p>
            <p class="faq__answer-text"><?= e($item['a']) ?></p>
            <p class="faq__answer-cta">
              <a class="link-underline" href="#devis">Poser votre question dans le formulaire <?= icon('arrow-up-right') ?></a>
            </p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
