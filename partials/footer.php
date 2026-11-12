<?php
/** @var array $config */
$contact  = $config['contact'];
$credits  = $config['credits'];
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$year     = date('Y');
$socials  = [];
foreach ($config['socials'] as $network => $url) {
    if ($url !== '') {
        $socials[$network] = $url;
    }
}
$navItems = [
    'services' => 'Services',
    'flotte' => 'Flotte',
    'zone' => 'Zone d\'intervention',
    'secteurs' => 'Secteurs',
    'engagements' => 'Engagements',
    'methode' => 'Comment ça marche',
    'faq' => 'FAQ',
    'devis' => 'Demander un devis',
];
?>
<footer class="footer" data-theme="dark">
    <div class="container footer__grid">
        <div class="footer__brand">
            <?= dst_logo('#top', 'logo--footer', 'footer') ?>
            <p class="footer__tagline">Le frais, <em class="serif">livré à l'heure.</em></p>
            <p class="footer__text">Transport frigorifique et distribution de produits frais à Paris et en Île-de-France.</p>
        </div>

        <nav class="footer__nav" aria-label="Navigation de pied de page">
            <p class="footer__label mono">Navigation</p>
            <ul>
                <?php foreach ($navItems as $id => $label): ?>
                    <li><a href="#<?= $id ?>"><?= $label ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <div class="footer__contact">
            <p class="footer__label mono">Contact</p>
            <address>
                <?= htmlspecialchars($contact['address_line'], ENT_QUOTES, 'UTF-8') ?><br>
                <?= htmlspecialchars($contact['postcode'] . ' ' . $contact['city'], ENT_QUOTES, 'UTF-8') ?><br>
                <a href="<?= htmlspecialchars($contact['maps_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Itinéraire</a>
            </address>
            <a class="footer__link mono" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></a>
            <a class="footer__link" href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?></a>
            <p class="footer__hours mono"><?= htmlspecialchars($contact['hours'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <div class="footer__follow">
            <?php if ($socials): ?>
                <p class="footer__label mono">Suivez-nous</p>
                <ul class="socials">
                    <?php foreach ($socials as $network => $url): ?>
                        <li>
                            <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="<?= ucfirst($network) ?> — DST">
                                <?= dst_social($network, 18) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <a class="footer__cta" href="#devis" data-quote-link>
                <span>Demander un devis</span>
                <?= dst_icon('arrow-up-right', '', 15) ?>
            </a>
        </div>
    </div>

    <div class="footer__giant" aria-hidden="true">
        <span class="footer__giant-text" data-footer-dst>DST</span>
    </div>

    <div class="container footer__bottom">
        <div class="footer__legal">
            <span>© <?= $year ?> <?= htmlspecialchars($config['company']['name'], ENT_QUOTES, 'UTF-8') ?> — Tous droits réservés</span>
            <button type="button" class="footer__legal-link" data-open-modal="modal-legal">Mentions légales</button>
            <button type="button" class="footer__legal-link" data-open-modal="modal-privacy">Politique de confidentialité</button>
            <button type="button" class="footer__legal-link" data-open-modal="modal-cookies">Gestion des cookies</button>
        </div>

        <div class="footer__credits">
            <a class="footer__agency" href="<?= htmlspecialchars($credits['agency_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                <?= htmlspecialchars($credits['agency_label'], ENT_QUOTES, 'UTF-8') ?>
            </a>
            <span class="footer__agency-text"><?= htmlspecialchars($credits['agency_text'], ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    </div>
</footer>
