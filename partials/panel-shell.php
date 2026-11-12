<?php
/**
 * Coquille commune des panneaux latéraux : en-tête, contenu, pied.
 * @var string $id
 * @var string $title
 * @var string $body
 */
?>
<div class="panel" id="panel-<?= e($id) ?>" role="dialog" aria-modal="true" aria-labelledby="panel-<?= e($id) ?>-title" hidden>
  <div class="panel__backdrop" data-panel-close></div>
  <div class="panel__sheet" role="document">
    <header class="panel__head">
      <p class="panel__kicker"><?= e($config['company']['name']) ?></p>
      <h2 class="panel__title" id="panel-<?= e($id) ?>-title"><?= e($title) ?></h2>
      <button class="panel__close" type="button" data-panel-close>
        <span>Fermer</span><?= icon('x') ?>
      </button>
    </header>
    <div class="panel__body">
      <?= $body ?>
    </div>
    <footer class="panel__foot">
      <p>Dernière mise à jour : <?= date('d/m/Y') ?></p>
      <button class="link-inline" type="button" data-panel-close>Retour au site</button>
    </footer>
  </div>
</div>
