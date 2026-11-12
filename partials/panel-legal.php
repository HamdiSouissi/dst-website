<?php
/**
 * Panneau — Mentions légales (LCEN).
 * @var array $config
 */
$co = $config['company'];
$ho = $config['head_office'];
$h  = $config['hosting'];
$cr = $config['credits'];
ob_start(); ?>
<section class="panel__section">
  <h3>Éditeur du site</h3>
  <dl class="dl">
    <dt>Raison sociale</dt><dd><?= e($co['name']) ?></dd>
    <dt>Nom commercial</dt><dd><?= e($co['short']) ?></dd>
    <dt>Forme juridique</dt><dd><?= $co['legal_form'] !== '' ? e($co['legal_form']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Capital social</dt><dd><?= $co['capital'] !== '' ? e($co['capital']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Siège social</dt><dd><?= e($ho['address_line']) ?><br><?= e($ho['postcode']) ?> <?= e($ho['city']) ?>, France<br><small><?= e($ho['note']) ?></small></dd>
    <dt>SIRET</dt><dd><?= $co['siret'] !== '' ? e($co['siret']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>RCS</dt><dd><?= $co['rcs'] !== '' ? e($co['rcs']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>TVA intracommunautaire</dt><dd><?= $co['vat'] !== '' ? e($co['vat']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Directeur de la publication</dt><dd><?= $co['publisher'] !== '' ? e($co['publisher']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Contact</dt><dd><a href="<?= e(tel_href($config)) ?>"><?= e($config['contact']['phone_display']) ?></a> · <a href="mailto:<?= e($config['contact']['email']) ?>"><?= e($config['contact']['email']) ?></a></dd>
  </dl>
</section>

<section class="panel__section">
  <h3>Hébergeur</h3>
  <dl class="dl">
    <dt>Nom</dt><dd><?= $h['name'] !== '' ? e($h['name']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Adresse</dt><dd><?= $h['address'] !== '' ? e($h['address']) : '<span class="todo">À compléter</span>' ?></dd>
    <dt>Téléphone</dt><dd><?= $h['phone'] !== '' ? e($h['phone']) : '<span class="todo">À compléter</span>' ?></dd>
  </dl>
</section>

<section class="panel__section">
  <h3>Conception et réalisation</h3>
  <p>Site conçu et réalisé par <a href="<?= e($cr['agency_url']) ?>" target="_blank" rel="noopener noreferrer">Prime Vision Solutions</a> — <a href="<?= e($cr['agency_url']) ?>" target="_blank" rel="noopener noreferrer">primevisionsolutions.fr</a>.</p>
</section>

<section class="panel__section">
  <h3>Crédits photographiques</h3>
  <p class="panel__small"><?= e($config['photos_note']) ?></p>
  <?php if ($config['image_credits']): ?>
    <ul class="panel__credits">
      <?php foreach ($config['image_credits'] as $c): ?>
        <li>
          <?= e($c['label']) ?> —
          <?php if (!empty($c['url'])): ?><a href="<?= e($c['url']) ?>" target="_blank" rel="noopener noreferrer"><?= e($c['author']) ?></a><?php else: ?><?= e($c['author']) ?><?php endif; ?>
          · <?= e($c['license']) ?>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</section>

<section class="panel__section">
  <h3>Propriété intellectuelle</h3>
  <p>L'ensemble des contenus de ce site (textes, visuels, logotype, étiquette DST, mise en page) est protégé. Toute reproduction, même partielle, est interdite sans autorisation écrite préalable.</p>
</section>
<?php
$body = (string) ob_get_clean();
$id = 'legal';
$title = 'Mentions légales';
require __DIR__ . '/panel-shell.php';
