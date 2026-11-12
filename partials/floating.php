<?php
/** @var array $config */
$contact = $config['contact'];
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$whatsapp = $config['socials']['whatsapp'] !== '' ? $config['socials']['whatsapp'] : $contact['whatsapp'];
$waUrl = '';
if ($whatsapp !== '') {
    $waUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsapp);
}
?>
<div class="floating" aria-label="Contact rapide">
    <?php if ($waUrl !== ''): ?>
        <a class="floating__btn floating__btn--wa" href="<?= htmlspecialchars($waUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener" aria-label="Nous écrire sur WhatsApp">
            <?= dst_social('whatsapp', 24) ?>
        </a>
    <?php endif; ?>
    <a class="floating__btn floating__btn--call" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>" aria-label="Appeler DST au <?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?>">
        <span class="floating__pulse" aria-hidden="true"></span>
        <?= dst_icon('phone', '', 22) ?>
    </a>
</div>
