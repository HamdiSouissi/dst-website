<?php
/** @var array $config */
/** @var string $csrf */
/** @var int $formTime */
$contact = $config['contact'];
$frozen  = !empty($config['services_options']['frozen']);
$phoneHref = 'tel:' . preg_replace('/[^0-9+]/', '', $contact['phone']);
$today = date('Y-m-d');

$services = ['Livraison ponctuelle', 'Tournée régulière', 'Express', 'Véhicule avec chauffeur', 'Autre'];
$goods = ['Fruits & légumes', 'Viandes', 'Poissons & fruits de mer', 'Produits laitiers', 'Plats cuisinés', 'Pâtisseries', 'Boissons', 'Autre'];
$temperatures = ['0 à +4 °C', '+4 à +8 °C', 'Ambiante'];
if ($frozen) {
    $temperatures[] = 'Surgelé -18 °C';
}
$frequencies = ['Une fois', 'Plusieurs fois par semaine', 'Tous les jours', 'À définir'];
?>
<section class="quote section" id="devis" data-theme="dark" aria-labelledby="quote-title" data-phone="<?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?>" data-phone-href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>">
    <div class="section__bg" aria-hidden="true">
        <video class="bg-video" data-bg-video autoplay muted loop playsinline preload="none"
               poster="assets/img/devis-poster.webp" tabindex="-1" disablepictureinpicture>
            <source src="assets/video/devis.mp4" type="video/mp4">
        </video>
        <span class="section__bg-scrim"></span>
    </div>
    <div class="quote__glow" aria-hidden="true"></div>
    <div class="container quote__grid">
        <div class="quote__intro">
            <p class="eyebrow"><span class="eyebrow__dash"></span>09 — Demande de devis</p>
            <h2 class="h2" id="quote-title">
                Parlons de
                <span class="line-mask"><span class="line"><em class="serif">vos livraisons.</em></span></span>
            </h2>
            <p class="lede">
                Décrivez votre besoin en moins de deux minutes.
                Nous revenons vers vous rapidement avec une proposition adaptée.
            </p>

            <ul class="quote__args">
                <li><?= dst_icon('check', '', 15) ?> Devis gratuit et sans engagement</li>
                <li><?= dst_icon('check', '', 15) ?> Réponse sous 24 h ouvrées</li>
                <li><?= dst_icon('check', '', 15) ?> Interlocuteur dédié</li>
            </ul>

            <div class="quote__direct">
                <span class="quote__direct-label mono">Directement</span>
                <a class="quote__phone" href="<?= htmlspecialchars($phoneHref, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($contact['phone_display'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                <a class="quote__mail" href="mailto:<?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>">
                    <?= dst_icon('mail', '', 15) ?> <?= htmlspecialchars($contact['email'], ENT_QUOTES, 'UTF-8') ?>
                </a>
                <span class="quote__hours mono"><?= htmlspecialchars($contact['hours'], ENT_QUOTES, 'UTF-8') ?></span>
            </div>
        </div>

        <div class="quote__card" data-quote>
            <div class="quote-progress">
                <div class="quote-progress__head">
                    <span class="mono">Étape <span data-quote-current>1</span> / 4</span>
                    <span class="mono" data-quote-stepname>Votre besoin</span>
                </div>
                <div class="quote-progress__route" aria-hidden="true">
                    <span class="quote-progress__rail"></span>
                    <span class="quote-progress__fill" data-quote-fill></span>
                    <span class="quote-progress__stop is-active" data-quote-stop="1"></span>
                    <span class="quote-progress__stop" data-quote-stop="2"></span>
                    <span class="quote-progress__stop" data-quote-stop="3"></span>
                    <span class="quote-progress__stop" data-quote-stop="4"></span>
                    <span class="quote-progress__flag" data-quote-flag><?= dst_icon('map-pin', '', 16) ?></span>
                    <span class="quote-progress__van" data-quote-van><?= dst_van('caddy', 'van--progress') ?></span>
                </div>
            </div>

            <p class="sr-only" aria-live="polite" data-quote-announce></p>

            <form class="quote-form" id="quote-form" action="send.php" method="post" novalidate data-quote-form>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form_time" value="<?= (int) $formTime ?>">
                <div class="hp-field" aria-hidden="true">
                    <label for="website">Ne pas remplir ce champ</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <!-- Étape 1 -->
                <fieldset class="quote-step is-active" data-step="1">
                    <legend class="quote-step__title"><span class="mono">01</span> Votre besoin</legend>

                    <div class="field">
                        <span class="field__label" id="label-service">Type de prestation</span>
                        <div class="pills" role="radiogroup" aria-labelledby="label-service">
                            <?php foreach ($services as $i => $s): ?>
                                <label class="pill">
                                    <input type="radio" name="service_type" value="<?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?>" <?= $i === 0 ? 'checked' : '' ?> data-required-group="service_type">
                                    <span><?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="field__error" data-error-for="service_type" id="err-service_type"></p>
                    </div>

                    <div class="field">
                        <span class="field__label" id="label-goods">Type de marchandises <span class="field__hint">plusieurs choix possibles</span></span>
                        <div class="pills" role="group" aria-labelledby="label-goods">
                            <?php foreach ($goods as $g): ?>
                                <label class="pill">
                                    <input type="checkbox" name="goods[]" value="<?= htmlspecialchars($g, ENT_QUOTES, 'UTF-8') ?>">
                                    <span><?= htmlspecialchars($g, ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <p class="field__error" data-error-for="goods" id="err-goods"></p>
                    </div>

                    <div class="field">
                        <span class="field__label" id="label-temperature">Température requise</span>
                        <div class="pills" role="radiogroup" aria-labelledby="label-temperature">
                            <?php foreach ($temperatures as $i => $t): ?>
                                <label class="pill">
                                    <input type="radio" name="temperature" value="<?= htmlspecialchars($t, ENT_QUOTES, 'UTF-8') ?>" <?= $i === 0 ? 'checked' : '' ?>>
                                    <span><?= htmlspecialchars($t, ENT_QUOTES, 'UTF-8') ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </fieldset>

                <!-- Étape 2 -->
                <fieldset class="quote-step" data-step="2">
                    <legend class="quote-step__title"><span class="mono">02</span> Logistique</legend>

                    <div class="field-row">
                        <div class="field">
                            <label class="field__label" for="pickup_postcode">Code postal d'enlèvement</label>
                            <input class="input mono" type="text" id="pickup_postcode" name="pickup_postcode" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="postal-code" placeholder="94110" aria-describedby="hint-postcode" data-validate="postcode">
                            <p class="field__error" data-error-for="pickup_postcode" id="err-pickup_postcode"></p>
                        </div>
                        <div class="field">
                            <label class="field__label" for="delivery_postcode">Code postal de livraison</label>
                            <input class="input mono" type="text" id="delivery_postcode" name="delivery_postcode" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" autocomplete="postal-code" placeholder="75011" aria-describedby="hint-postcode" data-validate="postcode">
                            <p class="field__error" data-error-for="delivery_postcode" id="err-delivery_postcode"></p>
                        </div>
                    </div>
                    <p class="field__hint field__hint--block" id="hint-postcode">Hors Île-de-France ? Précisez-le dans votre message, nous étudierons votre demande.</p>

                    <div class="field">
                        <label class="field__label" for="frequency">Fréquence</label>
                        <div class="select-wrap">
                            <select class="input" id="frequency" name="frequency">
                                <?php foreach ($frequencies as $f): ?>
                                    <option value="<?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($f, ENT_QUOTES, 'UTF-8') ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?= dst_icon('chevron-down', 'select-wrap__ico', 16) ?>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="start_date">Date souhaitée de démarrage <span class="field__hint">optionnel</span></label>
                        <input class="input mono" type="date" id="start_date" name="start_date" min="<?= $today ?>">
                    </div>

                    <div class="field field--volume">
                        <div class="field__label-row">
                            <label class="field__label" for="volume">Volume estimé</label>
                            <output class="volume-output mono" for="volume" data-volume-output>5 m³</output>
                        </div>
                        <input class="range" type="range" id="volume" name="volume" min="1" max="10" step="1" value="5" aria-describedby="vehicle-suggestion">
                        <div class="range__scale mono" aria-hidden="true"><span>&lt; 1 m³</span><span>5 m³</span><span>10 m³ et +</span></div>
                        <div class="vehicle-suggestion" id="vehicle-suggestion">
                            <span class="vehicle-suggestion__van" data-suggest-van aria-hidden="true">
                                <img src="assets/img/flotte/transit-sm.webp" width="1176" height="669" alt="" loading="lazy" decoding="async">
                            </span>
                            <span class="vehicle-suggestion__text">
                                Véhicule conseillé : <strong data-suggest-name>Ford Transit Custom</strong>
                            </span>
                        </div>
                    </div>
                </fieldset>

                <!-- Étape 3 -->
                <fieldset class="quote-step" data-step="3">
                    <legend class="quote-step__title"><span class="mono">03</span> Vos coordonnées</legend>

                    <div class="field-row">
                        <div class="field">
                            <label class="field__label" for="name">Nom et prénom <span class="req" aria-hidden="true">*</span></label>
                            <input class="input" type="text" id="name" name="name" autocomplete="name" required aria-describedby="err-name" data-validate="name">
                            <p class="field__error" data-error-for="name" id="err-name"></p>
                        </div>
                        <div class="field">
                            <label class="field__label" for="company">Entreprise <span class="req" aria-hidden="true">*</span></label>
                            <input class="input" type="text" id="company" name="company" autocomplete="organization" required aria-describedby="err-company" data-validate="company">
                            <p class="field__error" data-error-for="company" id="err-company"></p>
                        </div>
                    </div>

                    <div class="field-row">
                        <div class="field">
                            <label class="field__label" for="phone">Téléphone <span class="req" aria-hidden="true">*</span></label>
                            <input class="input mono" type="tel" id="phone" name="phone" autocomplete="tel" inputmode="tel" required aria-describedby="err-phone" data-validate="phone" placeholder="06 00 00 00 00">
                            <p class="field__error" data-error-for="phone" id="err-phone"></p>
                        </div>
                        <div class="field">
                            <label class="field__label" for="email">Email <span class="req" aria-hidden="true">*</span></label>
                            <input class="input" type="email" id="email" name="email" autocomplete="email" required aria-describedby="err-email" data-validate="email" placeholder="vous@entreprise.fr">
                            <p class="field__error" data-error-for="email" id="err-email"></p>
                        </div>
                    </div>

                    <div class="field">
                        <label class="field__label" for="message">Message <span class="field__hint">optionnel</span></label>
                        <textarea class="input" id="message" name="message" rows="4" maxlength="1500" placeholder="Précisez vos adresses, horaires de réception, contraintes…"></textarea>
                    </div>

                    <div class="field field--consent">
                        <label class="check">
                            <input type="checkbox" name="consent" id="consent" value="1" required aria-describedby="err-consent">
                            <span class="check__box" aria-hidden="true"><?= dst_icon('check', '', 12) ?></span>
                            <span>J'accepte que mes données soient utilisées pour traiter ma demande, conformément à la
                                <button type="button" class="link-inline" data-open-modal="modal-privacy">politique de confidentialité</button>.
                            </span>
                        </label>
                        <p class="field__error" data-error-for="consent" id="err-consent"></p>
                    </div>
                </fieldset>

                <!-- Étape 4 -->
                <fieldset class="quote-step" data-step="4">
                    <legend class="quote-step__title"><span class="mono">04</span> Récapitulatif</legend>

                    <div class="recap" data-recap>
                        <div class="recap__block">
                            <div class="recap__head"><span class="mono">Besoin</span><button type="button" class="recap__edit" data-goto-step="1">Modifier</button></div>
                            <dl>
                                <div><dt>Prestation</dt><dd data-recap-service>—</dd></div>
                                <div><dt>Marchandises</dt><dd data-recap-goods>—</dd></div>
                                <div><dt>Température</dt><dd data-recap-temperature>—</dd></div>
                            </dl>
                        </div>
                        <div class="recap__block">
                            <div class="recap__head"><span class="mono">Logistique</span><button type="button" class="recap__edit" data-goto-step="2">Modifier</button></div>
                            <dl>
                                <div><dt>Trajet</dt><dd data-recap-route>—</dd></div>
                                <div><dt>Fréquence</dt><dd data-recap-frequency>—</dd></div>
                                <div><dt>Démarrage</dt><dd data-recap-date>—</dd></div>
                                <div><dt>Volume</dt><dd data-recap-volume>—</dd></div>
                            </dl>
                        </div>
                        <div class="recap__block">
                            <div class="recap__head"><span class="mono">Contact</span><button type="button" class="recap__edit" data-goto-step="3">Modifier</button></div>
                            <dl>
                                <div><dt>Nom</dt><dd data-recap-name>—</dd></div>
                                <div><dt>Entreprise</dt><dd data-recap-company>—</dd></div>
                                <div><dt>Téléphone</dt><dd data-recap-phone>—</dd></div>
                                <div><dt>Email</dt><dd data-recap-email>—</dd></div>
                            </dl>
                        </div>
                    </div>

                    <button class="btn btn--primary btn--block quote-submit" type="submit" data-quote-submit>
                        <span class="btn__label">Envoyer ma demande</span>
                        <span class="btn__ico" aria-hidden="true"><?= dst_icon('arrow-right', '', 18) ?></span>
                    </button>
                    <p class="quote-form__legal">Vos données servent uniquement à traiter votre demande. Aucun démarchage, aucune revente.</p>
                </fieldset>

                <div class="quote-form__nav">
                    <button type="button" class="btn btn--line" data-quote-prev hidden>
                        <?= dst_icon('chevron-left', '', 16) ?><span class="btn__label">Retour</span>
                    </button>
                    <button type="button" class="btn btn--primary" data-quote-next>
                        <span class="btn__label">Continuer</span>
                        <span class="btn__ico" aria-hidden="true"><?= dst_icon('arrow-right', '', 16) ?></span>
                    </button>
                </div>
            </form>

            <div class="quote-result" data-quote-result hidden>
                <div class="quote-result__flag" aria-hidden="true"><?= dst_icon('check', '', 30) ?></div>
                <h3 class="quote-result__title" data-quote-result-title>Demande envoyée.</h3>
                <p class="quote-result__text" data-quote-result-text></p>
                <button type="button" class="btn btn--line" data-quote-reset>Faire une nouvelle demande</button>
            </div>
        </div>
    </div>
</section>
