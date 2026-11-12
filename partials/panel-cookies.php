<?php
/**
 * Panneau — Cookies.
 * @var array $config
 */
ob_start(); ?>
<section class="panel__section">
  <h3>Ce site n'utilise que des cookies techniques</h3>
  <p>Aucun cookie publicitaire, aucun traceur social, aucune mesure d'audience n'est déposé sans votre accord. Voici précisément ce qui peut être enregistré&nbsp;:</p>
  <table class="panel__table">
    <thead><tr><th>Nom</th><th>Rôle</th><th>Durée</th></tr></thead>
    <tbody>
      <tr><td><code>dst_session</code></td><td>Jeton de sécurité (CSRF) du formulaire de devis — cookie de session, non persistant</td><td>Session</td></tr>
      <tr><td><code>dst_cookie_ok</code></td><td>Mémorise que vous avez vu la notice cookies</td><td>6 mois</td></tr>
      <tr><td><code>dst_chat</code></td><td>Conserve vos réponses dans le formulaire (stockage local du navigateur, pas un cookie)</td><td>Session</td></tr>
    </tbody>
  </table>
</section>
<section class="panel__section">
  <h3>Comment les supprimer&nbsp;?</h3>
  <p>Vous pouvez vider ces données à tout moment depuis les réglages de votre navigateur (paramètres de confidentialité, «&nbsp;effacer les données de navigation&nbsp;»). Le site continue de fonctionner normalement.</p>
</section>
<section class="panel__section">
  <h3>Et si une mesure d'audience est ajoutée&nbsp;?</h3>
  <p>Elle sera soumise à votre consentement préalable, via une bannière dédiée, et pourra être refusée sans conséquence sur votre navigation.</p>
</section>
<?php
$body = (string) ob_get_clean();
$id = 'cookies';
$title = 'Cookies';
require __DIR__ . '/panel-shell.php';
