<?php
/** @var array $config */
$contact = $config['contact'];
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$words = ['Une livraison à organiser ?', 'Appelez-nous', 'Une livraison à organiser ?', 'Appelez-nous'];
?>
<section class="contact-band" aria-labelledby="contact-band-title">
    <div class="contact-band__marquee" aria-hidden="true">
        <div class="contact-band__track" data-band-track>
            <?php for ($copy = 0; $copy < 2; $copy++): ?>
                <div class="contact-band__group">
                    <?php foreach ($words as $word): ?>
                        <span class="contact-band__word"><?= $word ?><span class="contact-band__arrow">→</span></span>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="container contact-band__inner">
        <p class="contact-band__meta mono" id="contact-band-title">Standard ouvert du lundi au samedi · 5h – 19h</p>
        <a class="contact-band__phone" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>" data-band-phone>
            <span class="contact-band__phone-label">Appeler</span>
            <span class="contact-band__number"><?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?></span>
            <span class="contact-band__phone-ico" aria-hidden="true"><?= dst_icon('phone', '', 28) ?></span>
        </a>
    </div>
</section>
