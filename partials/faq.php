<?php
/** @var array $config */
$faq = $config['faq'];
?>
<section class="faq section" id="faq" data-theme="light" aria-labelledby="faq-title">
    <div class="container faq__grid">
        <div class="faq__intro">
            <p class="eyebrow"><span class="eyebrow__dash"></span>10 — Questions fréquentes</p>
            <h2 class="h2" id="faq-title">
                Vous vous
                <span class="line-mask"><span class="line"><em class="serif">demandez…</em></span></span>
            </h2>
            <p class="faq__note">
                Une autre question ? Appelez-nous, nous répondons directement.
            </p>
            <a class="link-arrow" href="#devis" data-quote-link>
                <span>Parlons de votre besoin</span>
                <?= dst_icon('arrow-up-right', '', 16) ?>
            </a>
        </div>

        <div class="accordion" data-accordion>
            <?php foreach ($faq as $i => $item): ?>
                <div class="accordion__item">
                    <h3 class="accordion__heading">
                        <button class="accordion__trigger" type="button" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>" aria-controls="faq-panel-<?= $i ?>" id="faq-trigger-<?= $i ?>" data-accordion-trigger>
                            <span class="accordion__index mono"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <span class="accordion__question"><?= htmlspecialchars($item['q'], ENT_QUOTES, 'UTF-8') ?></span>
                            <span class="accordion__icon" aria-hidden="true">
                                <span></span><span></span>
                            </span>
                        </button>
                    </h3>
                    <div class="accordion__panel" id="faq-panel-<?= $i ?>" role="region" aria-labelledby="faq-trigger-<?= $i ?>" data-accordion-panel>
                        <div class="accordion__panel-inner">
                            <p><?= htmlspecialchars($item['a'], ENT_QUOTES, 'UTF-8') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
