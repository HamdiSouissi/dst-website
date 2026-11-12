<?php
/** @var array $config */
$c = $config['company'];
$contact = $config['contact'];
$hosting = $config['hosting'];
$credits = $config['credits'];
$imageCredits = $config['image_credits'];
?>
<div class="modal" id="modal-legal" role="dialog" aria-modal="true" aria-labelledby="modal-legal-title" hidden>
    <div class="modal__backdrop" data-close-modal></div>
    <div class="modal__dialog">
        <button type="button" class="modal__close" data-close-modal aria-label="Fermer les mentions légales">
            <span></span><span></span>
        </button>
        <div class="modal__head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>Informations légales</p>
            <h2 class="modal__title" id="modal-legal-title">Mentions légales</h2>
        </div>
        <div class="modal__body">
            <h3>Éditeur du site</h3>
            <dl class="legal-dl">
                <div><dt>Raison sociale</dt><dd><?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>Forme juridique</dt><dd><?= htmlspecialchars($c['legal_form'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>Capital social</dt><dd><?= htmlspecialchars($c['capital'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>Siège social</dt><dd><?= htmlspecialchars($contact['address_line'] . ', ' . $contact['postcode'] . ' ' . $contact['city'] . ', ' . $contact['country'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>SIRET</dt><dd class="mono"><?= htmlspecialchars($c['siret'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>RCS</dt><dd><?= htmlspecialchars($c['rcs'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>TVA intracommunautaire</dt><dd class="mono"><?= htmlspecialchars($c['vat'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>Directeur de la publication</dt><dd><?= htmlspecialchars($c['publisher'], ENT_QUOTES, 'UTF-8') ?>, <?= htmlspecialchars($c['publisher_role'], ENT_QUOTES, 'UTF-8') ?></dd></div>
                <div><dt>Téléphone</dt><dd><a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $contact['phone']), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></a></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></a></dd></div>
            </dl>

            <?php if ($hosting['name'] !== ''): ?>
                <h3>Hébergement</h3>
                <p>
                    <?= htmlspecialchars($hosting['name'], ENT_QUOTES, 'UTF-8') ?><br>
                    <?= htmlspecialchars($hosting['address'], ENT_QUOTES, 'UTF-8') ?><br>
                    <?= htmlspecialchars($hosting['phone'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <h3>Conception et réalisation</h3>
            <p>
                Site conçu et développé par
                <a href="<?= htmlspecialchars($credits['agency_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($credits['agency_label'], ENT_QUOTES, 'UTF-8') ?></a>.
            </p>

            <h3>Propriété intellectuelle</h3>
            <p>
                L'ensemble des contenus présents sur ce site (textes, illustrations, logo, photographies, animations)
                est protégé par le droit d'auteur. Toute reproduction, même partielle, est interdite sans autorisation
                écrite préalable de <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>.
            </p>

            <?php if ($imageCredits): ?>
                <h3>Crédits visuels</h3>
                <ul class="legal-credits">
                    <?php foreach ($imageCredits as $credit): ?>
                        <li>
                            <?= htmlspecialchars($credit['file'], ENT_QUOTES, 'UTF-8') ?> —
                            <?= htmlspecialchars($credit['author'], ENT_QUOTES, 'UTF-8') ?>,
                            <a href="<?= htmlspecialchars($credit['url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($credit['license'], ENT_QUOTES, 'UTF-8') ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <h3>Responsabilité</h3>
            <p>
                <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?> s'efforce d'assurer l'exactitude des informations
                publiées sur ce site, notamment les horaires et les zones desservies, sans pouvoir garantir l'absence
                d'erreur. Les informations tarifaires sont données à titre indicatif et ne constituent pas une offre contractuelle.
            </p>
        </div>
    </div>
</div>
