<?php
/**
 * Panneau — Politique de confidentialité (RGPD).
 * @var array $config
 */
ob_start(); ?>
<section class="panel__section">
  <h3>Qui est responsable&nbsp;?</h3>
  <p><?= e($config['company']['name']) ?>, <?= e($config['head_office']['label']) ?> : <?= e($config['head_office']['address_line']) ?>, <?= e($config['head_office']['postcode']) ?> <?= e($config['head_office']['city']) ?>.
     Contact : <a href="mailto:<?= e($config['contact']['email']) ?>"><?= e($config['contact']['email']) ?></a>.</p>
</section>

<section class="panel__section">
  <h3>Quelles données sont collectées&nbsp;?</h3>
  <p>Uniquement celles que vous saisissez dans le formulaire de devis&nbsp;:</p>
  <ul class="panel__list">
    <li>nom et prénom, entreprise, téléphone, email ;</li>
    <li>le détail de votre besoin (type de livraison, marchandises, codes postaux d'enlèvement et de livraison, fréquence, volume, message libre) ;</li>
    <li>l'horodatage de l'envoi et votre adresse IP (uniquement pour limiter les envois abusifs).</li>
  </ul>
</section>

<section class="panel__section">
  <h3>Pourquoi, et sur quelle base&nbsp;?</h3>
  <p>Ces données servent à <strong>répondre à votre demande de devis</strong> et à organiser une éventuelle livraison. La base légale est l'exécution de mesures précontractuelles prises à votre demande (article 6.1.b du RGPD) et notre intérêt légitime à sécuriser le formulaire (article 6.1.f).</p>
</section>

<section class="panel__section">
  <h3>Combien de temps&nbsp;?</h3>
  <p><strong>3 ans</strong> après le dernier contact, puis suppression. Les données techniques de limitation d'envoi sont conservées au maximum 1 heure.</p>
</section>

<section class="panel__section">
  <h3>Qui reçoit ces données&nbsp;?</h3>
  <p>Uniquement DST et son hébergeur, strictement pour les besoins techniques du site. Aucune donnée n'est vendue, louée ni transmise à des fins publicitaires. Aucun outil de mesure d'audience n'est activé par défaut.</p>
</section>

<section class="panel__section">
  <h3>Vos droits</h3>
  <p>Vous pouvez demander l'accès à vos données, leur rectification, leur effacement, la limitation du traitement, vous opposer à leur traitement et demander leur portabilité. Écrivez à <a href="mailto:<?= e($config['contact']['email']) ?>"><?= e($config['contact']['email']) ?></a> : nous répondons sous un mois.</p>
  <p>Si vous estimez que vos droits ne sont pas respectés, vous pouvez introduire une réclamation auprès de la <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer">CNIL</a> (3 place de Fontenoy, TSA 80715, 75334 Paris Cedex 07).</p>
</section>

<section class="panel__section">
  <h3>Cookies</h3>
  <p>Ce site n'utilise que des cookies techniques strictement nécessaires (protection du formulaire). Aucun cookie publicitaire ni traceur tiers n'est déposé. Si une mesure d'audience est ajoutée plus tard, elle ne sera chargée qu'après votre consentement explicite.</p>
</section>
<?php
$body = (string) ob_get_clean();
$id = 'privacy';
$title = 'Confidentialité';
require __DIR__ . '/panel-shell.php';
