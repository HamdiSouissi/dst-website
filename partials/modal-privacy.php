<?php
/** @var array $config */
$c = $config['company'];
$contact = $config['contact'];
?>
<div class="modal" id="modal-privacy" role="dialog" aria-modal="true" aria-labelledby="modal-privacy-title" hidden>
    <div class="modal__backdrop" data-close-modal></div>
    <div class="modal__dialog">
        <button type="button" class="modal__close" data-close-modal aria-label="Fermer la politique de confidentialité">
            <span></span><span></span>
        </button>
        <div class="modal__head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>Vos données</p>
            <h2 class="modal__title" id="modal-privacy-title">Politique de confidentialité</h2>
        </div>
        <div class="modal__body">
            <p class="modal__updated mono">Dernière mise à jour : <?= date('d/m/Y') ?></p>

            <h3>Qui collecte vos données ?</h3>
            <p>
                Le responsable de traitement est <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>,
                <?= htmlspecialchars($contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city'], ENT_QUOTES, 'UTF-8') ?>.
                Pour toute question relative à vos données : <a href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></a>.
            </p>

            <h3>Données collectées</h3>
            <p>
                Le formulaire de demande de devis collecte uniquement les informations nécessaires au traitement de votre demande :
                nom et prénom, entreprise, téléphone, email, message, ainsi que les informations logistiques
                (type de prestation, marchandises, températures, codes postaux, fréquence, date souhaitée, volume).
            </p>
            <p>
                Les données techniques suivantes peuvent être enregistrées : adresse IP (pour la limitation des envois
                et la sécurité du formulaire) et date de la demande. Aucune donnée de navigation publicitaire n'est collectée.
            </p>

            <h3>Finalité et base légale</h3>
            <p>
                Vos données sont traitées dans le seul but de répondre à votre demande de devis et d'organiser
                une prestation de transport. Ce traitement repose sur votre consentement (case d'acceptation du formulaire)
                et sur les mesures précontractuelles prises à votre demande.
            </p>

            <h3>Destinataires</h3>
            <p>
                Les données sont destinées exclusivement à <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>
                et à ses collaborateurs chargés du traitement des demandes. Aucune donnée n'est vendue, louée
                ou transmise à des tiers à des fins commerciales. L'hébergeur du site peut accéder aux données
                techniques dans le cadre de son service.
            </p>

            <h3>Durée de conservation</h3>
            <p>
                Les demandes de devis sont conservées <strong>3 ans après le dernier contact</strong>, puis supprimées.
                Les données de facturation éventuelles sont conservées conformément aux obligations comptables et fiscales.
            </p>

            <h3>Vos droits</h3>
            <p>
                Vous disposez d'un droit d'accès, de rectification, d'effacement, d'opposition, de limitation
                et de portabilité de vos données. Pour exercer ces droits, écrivez à
                <a href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></a>
                ou par courrier à <?= htmlspecialchars($contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city'], ENT_QUOTES, 'UTF-8') ?>.
                Une réponse vous sera apportée dans un délai maximal d'un mois.
            </p>
            <p>
                Si vous estimez que vos droits ne sont pas respectés, vous pouvez adresser une réclamation à la
                <a href="https://www.cnil.fr/fr/plaintes" target="_blank" rel="noopener">CNIL</a>
                (3 place de Fontenoy, TSA 80715, 75334 Paris Cedex 07).
            </p>

            <h3>Cookies</h3>
            <p>
                Ce site n'utilise que des cookies techniques nécessaires à son fonctionnement : mémorisation de
                votre choix concernant le bandeau d'information et protection du formulaire (jeton anti-falsification).
                Aucun cookie de mesure d'audience ou de publicité n'est déposé. Si un outil de mesure d'audience
                était ajouté ultérieurement, il ne serait chargé qu'après votre consentement explicite.
            </p>

            <h3>Sécurité</h3>
            <p>
                Le formulaire est protégé contre les envois automatisés (jeton CSRF, champ leurre, délai minimal,
                limitation du nombre d'envois). L'accès aux données collectées est restreint aux personnes habilitées.
            </p>
        </div>
    </div>
</div>
