<?php
/**
 * 10 — Demande de devis : formulaire conversationnel.
 * Les questions sont des <fieldset> avec <legend> ; le JS les révèle
 * une à une et recopie les réponses en bulles dans le fil de discussion.
 * Un formulaire classique (tous les champs d'un coup) est proposé en
 * alternative, avec le même traitement côté serveur.
 *
 * @var array $config
 */
$f = $config['form'];
$transport = ['Fruits & légumes', 'Viandes', 'Poissons', 'Produits laitiers & fromages', 'Plats cuisinés', 'Pâtisseries', 'Autre'];
$transportColors = ['#F3C300', '#E5334B', '#E7C97F', '#F5DC4A', '#F39A55', '#6B4A6B', '#7A6873'];
$frequencies = ['Une fois', 'Plusieurs fois par semaine', 'Tous les jours', 'À définir'];
$types = ['Tournée régulière', 'Livraison ponctuelle', 'Express aujourd\'hui', 'Camionnette + chauffeur', 'Autre'];
$token = csrf_token();
?>
<section class="section quote" id="devis" aria-labelledby="quote-title" data-section="quote">
  <div class="wrap quote__wrap">
    <div class="quote__aside">
      <p class="section__num"><span><?= e($f['number']) ?></span><?= e($f['label']) ?></p>
      <h2 class="h2" id="quote-title">
        <span class="h2__line"><?= e($f['title_a']) ?></span>
        <span class="h2__line"><?= hl($f['highlight']) ?><span aria-hidden="true">.</span></span>
      </h2>

      <ul class="quote__promises">
        <?php foreach ($f['reassurance'] as $p): ?>
          <li><?= icon('check-circle') ?><?= e($p) ?></li>
        <?php endforeach; ?>
      </ul>

      <div class="quote__direct">
        <p class="quote__direct-label">Ou directement</p>
        <a class="quote__phone" href="<?= e(tel_href($config)) ?>"><?= e($config['contact']['phone_display']) ?></a>
        <a class="quote__mail" href="mailto:<?= e($config['contact']['email']) ?>"><?= e($config['contact']['email']) ?></a>
        <p class="quote__hours"><?= e($config['contact']['hours']) ?></p>
      </div>

      <?php if (whatsapp_href($config)): ?>
        <a class="btn btn--whatsapp" href="<?= e(whatsapp_href($config)) ?>" target="_blank" rel="noopener noreferrer">
          <?= icon('whatsapp-logo') ?><span>Écrire sur WhatsApp</span>
        </a>
      <?php endif; ?>
    </div>

    <div class="quote__chat-wrap">
      <div class="chat" data-chat>
        <div class="chat__head">
          <?= sticker('sticker sticker--chat', 'DST') ?>
          <div class="chat__id">
            <span class="chat__name"><?= e($config['company']['short']) ?> · <?= e($f['hint']) ?></span>
            <span class="chat__status"><i aria-hidden="true"></i>En ligne</span>
          </div>
          <button class="chat__restart" type="button" data-chat-restart title="Recommencer la conversation">
            <?= icon('arrow-clockwise') ?><span class="sr-only">Recommencer</span>
          </button>
        </div>

        <div class="chat__log" data-chat-log role="log" aria-live="polite" aria-relevant="additions text">
          <div class="chat__day">Aujourd'hui</div>
          <div class="chat__msg chat__msg--dst" data-intro>
            <p>Bonjour&nbsp;! Quel type de livraison vous faut-il&nbsp;?</p>
          </div>
        </div>

        <div class="chat__typing" data-chat-typing hidden>
          <span><?= e($f['typing']) ?></span><i></i><i></i><i></i>
        </div>

        <form class="chat__panel" data-chat-form novalidate autocomplete="on">
          <input type="hidden" name="csrf" value="<?= e($token) ?>">
          <input type="hidden" name="elapsed" value="" data-elapsed>
          <div class="hp" aria-hidden="true">
            <label for="website">Site web</label>
            <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="chat__steps" data-steps>
            <!-- 1. Type de livraison -->
            <fieldset class="step is-active" data-step="1" data-question="Quel type de livraison vous faut-il&nbsp;?">
              <legend class="step__legend">Quel type de livraison vous faut-il&nbsp;?</legend>
              <div class="chips-group">
                <?php foreach ($types as $i => $t): ?>
                  <label class="pill-radio">
                    <input type="radio" name="type" value="<?= e($t) ?>" <?= $i === 0 ? 'checked' : '' ?>>
                    <span><?= e($t) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <div class="step__nav">
                <button class="btn btn--basil btn--sm" type="button" data-next>Continuer</button>
              </div>
            </fieldset>

            <!-- 2. Produits -->
            <fieldset class="step" data-step="2" data-question="Qu'allons-nous transporter&nbsp;?" hidden>
              <legend class="step__legend">Qu'allons-nous transporter&nbsp;?</legend>
              <p class="step__hint">Plusieurs choix possibles.</p>
              <div class="chips-group chips-group--multi">
                <?php foreach ($transport as $i => $t): ?>
                  <label class="pill-check" style="--pill:<?= e($transportColors[$i]) ?>">
                    <input type="checkbox" name="goods[]" value="<?= e($t) ?>">
                    <span><?= e($t) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Retour</button>
                <button class="btn btn--basil btn--sm" type="button" data-next>Valider</button>
              </div>
            </fieldset>

            <!-- 3. Trajet -->
            <fieldset class="step" data-step="3" data-question="D'où partent les marchandises, et où arrivent-elles&nbsp;?" hidden>
              <legend class="step__legend">D'où partent les marchandises, et où arrivent-elles&nbsp;?</legend>
              <div class="field-row">
                <div class="field">
                  <label for="pc_from">Enlèvement <span>code postal</span></label>
                  <input id="pc_from" name="pc_from" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" placeholder="75013" required>
                </div>
                <div class="field">
                  <label for="pc_to">Livraison <span>code postal</span></label>
                  <input id="pc_to" name="pc_to" inputmode="numeric" pattern="[0-9]{5}" maxlength="5" placeholder="94110" required>
                </div>
              </div>
              <p class="step__error" data-error="pc" hidden></p>
              <p class="step__note" data-outzone hidden></p>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Retour</button>
                <button class="btn btn--basil btn--sm" type="button" data-next>Continuer</button>
              </div>
            </fieldset>

            <!-- 4. Fréquence -->
            <fieldset class="step" data-step="4" data-question="À quelle fréquence&nbsp;?" hidden>
              <legend class="step__legend">À quelle fréquence&nbsp;?</legend>
              <div class="chips-group">
                <?php foreach ($frequencies as $i => $t): ?>
                  <label class="pill-radio">
                    <input type="radio" name="frequency" value="<?= e($t) ?>" <?= $i === 0 ? 'checked' : '' ?>>
                    <span><?= e($t) ?></span>
                  </label>
                <?php endforeach; ?>
              </div>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Retour</button>
                <button class="btn btn--basil btn--sm" type="button" data-next>Continuer</button>
              </div>
            </fieldset>

            <!-- 5. Volume -->
            <fieldset class="step" data-step="5" data-question="Et à peu près quel volume&nbsp;?" hidden>
              <legend class="step__legend">Et à peu près quel volume&nbsp;?</legend>
              <div class="volume">
                <input class="volume__range" type="range" name="volume" min="1" max="10" step="0.5" value="4" data-volume aria-describedby="volume-live">
                <output class="volume__out" id="volume-live" data-volume-live>4 m³</output>
              </div>
              <p class="volume__hint" data-volume-vehicle>Nous vous conseillerons le gabarit adapté.</p>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Retour</button>
                <button class="btn btn--basil btn--sm" type="button" data-next>Continuer</button>
              </div>
            </fieldset>

            <!-- 6. Coordonnées -->
            <fieldset class="step" data-step="6" data-question="Pour vous envoyer le devis, j'ai besoin de vos coordonnées." hidden>
              <legend class="step__legend">Pour vous envoyer le devis, j'ai besoin de vos coordonnées.</legend>
              <div class="field-grid">
                <div class="field">
                  <label for="q_name">Nom et prénom <em>*</em></label>
                  <input id="q_name" name="name" autocomplete="name" required maxlength="80">
                </div>
                <div class="field">
                  <label for="q_company">Entreprise <em>*</em></label>
                  <input id="q_company" name="company" autocomplete="organization" required maxlength="120">
                </div>
                <div class="field">
                  <label for="q_phone">Téléphone <em>*</em></label>
                  <input id="q_phone" name="phone" type="tel" autocomplete="tel" required maxlength="24" inputmode="tel">
                </div>
                <div class="field">
                  <label for="q_email">Email <em>*</em></label>
                  <input id="q_email" name="email" type="email" autocomplete="email" required maxlength="120">
                </div>
                <div class="field field--full">
                  <label for="q_message">Un détail à ajouter ? <span>facultatif</span></label>
                  <textarea id="q_message" name="message" rows="2" maxlength="1200"></textarea>
                </div>
              </div>
              <label class="consent">
                <input type="checkbox" name="consent" value="1" required>
                <span><?= e($f['consent_label']) ?>
                  <button class="link-inline" type="button" data-open-panel="privacy">Politique de confidentialité</button>
                </span>
              </label>
              <p class="step__error" data-error="identity" hidden></p>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Retour</button>
                <button class="btn btn--basil btn--sm" type="button" data-next>Vérifier ma demande</button>
              </div>
            </fieldset>

            <!-- 7. Récapitulatif -->
            <fieldset class="step step--recap" data-step="7" data-question="Tout est bon&nbsp;?" hidden>
              <legend class="step__legend">Tout est bon&nbsp;?</legend>
              <ul class="recap" data-recap></ul>
              <div class="step__nav">
                <button class="btn btn--ghost btn--sm" type="button" data-back>Modifier</button>
                <button class="btn btn--basil btn--sm" type="submit" data-submit>Envoyer ma demande</button>
              </div>
              <p class="step__legal">
                <?= icon('check-circle') ?><?= e($f['privacy_notice']) ?>
              </p>
            </fieldset>
          </div>

          <p class="chat__switch">
            <button class="link-inline" type="button" data-classic-toggle><?= e($f['classic_link']) ?></button>
          </p>
        </form>

        <div class="chat__fallback" data-classic hidden>
          <form class="classic-form" data-classic-form novalidate>
            <input type="hidden" name="csrf" value="<?= e($token) ?>">
            <input type="hidden" name="elapsed" value="" data-elapsed2>
            <div class="hp" aria-hidden="true">
              <label for="website2">Site web</label>
              <input type="text" id="website2" name="website" tabindex="-1" autocomplete="off">
            </div>
            <h3 class="classic-form__title">Formulaire classique</h3>
            <div class="field-grid">
              <div class="field">
                <label for="c_name">Nom et prénom <em>*</em></label>
                <input id="c_name" name="name" required maxlength="80" autocomplete="name">
              </div>
              <div class="field">
                <label for="c_company">Entreprise <em>*</em></label>
                <input id="c_company" name="company" required maxlength="120" autocomplete="organization">
              </div>
              <div class="field">
                <label for="c_phone">Téléphone <em>*</em></label>
                <input id="c_phone" name="phone" type="tel" required maxlength="24" autocomplete="tel">
              </div>
              <div class="field">
                <label for="c_email">Email <em>*</em></label>
                <input id="c_email" name="email" type="email" required maxlength="120" autocomplete="email">
              </div>
              <div class="field">
                <label for="c_type">Type de livraison</label>
                <select id="c_type" name="type">
                  <?php foreach ($types as $t): ?><option value="<?= e($t) ?>"><?= e($t) ?></option><?php endforeach; ?>
                </select>
              </div>
              <div class="field">
                <label for="c_freq">Fréquence</label>
                <select id="c_freq" name="frequency">
                  <?php foreach ($frequencies as $t): ?><option value="<?= e($t) ?>"><?= e($t) ?></option><?php endforeach; ?>
                </select>
              </div>
              <div class="field">
                <label for="c_from">Enlèvement <span>code postal</span></label>
                <input id="c_from" name="pc_from" inputmode="numeric" maxlength="5" pattern="[0-9]{5}">
              </div>
              <div class="field">
                <label for="c_to">Livraison <span>code postal</span></label>
                <input id="c_to" name="pc_to" inputmode="numeric" maxlength="5" pattern="[0-9]{5}">
              </div>
              <div class="field field--full">
                <label for="c_goods">Marchandises</label>
                <select id="c_goods" name="goods[]" multiple size="4">
                  <?php foreach ($transport as $t): ?><option value="<?= e($t) ?>"><?= e($t) ?></option><?php endforeach; ?>
                </select>
              </div>
              <div class="field field--full">
                <label for="c_message">Votre besoin</label>
                <textarea id="c_message" name="message" rows="3" maxlength="1200"></textarea>
              </div>
            </div>
            <label class="consent">
              <input type="checkbox" name="consent" value="1" required>
              <span><?= e($f['consent_label']) ?>
                <button class="link-inline" type="button" data-open-panel="privacy">Politique de confidentialité</button>
              </span>
            </label>
            <div class="step__nav">
              <button class="btn btn--basil" type="submit">Envoyer ma demande</button>
            </div>
            <p class="step__error" data-classic-error hidden></p>
            <p class="chat__switch"><button class="link-inline" type="button" data-classic-toggle><?= e($f['chat_link']) ?></button></p>
          </form>
        </div>

        <div class="chat__stamp" data-stamp aria-hidden="true">
          <?= sticker('sticker sticker--stamp', 'DST') ?>
        </div>
      </div>
    </div>
  </div>
</section>
