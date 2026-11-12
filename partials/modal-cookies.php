<?php
/** @var array $config */
?>
<div class="modal" id="modal-cookies" role="dialog" aria-modal="true" aria-labelledby="modal-cookies-title" hidden>
    <div class="modal__backdrop" data-close-modal></div>
    <div class="modal__dialog modal__dialog--narrow">
        <button type="button" class="modal__close" data-close-modal aria-label="Fermer la gestion des cookies">
            <span></span><span></span>
        </button>
        <div class="modal__head">
            <p class="eyebrow"><span class="eyebrow__dash"></span>Préférences</p>
            <h2 class="modal__title" id="modal-cookies-title">Gestion des cookies</h2>
        </div>
        <div class="modal__body">
            <p>
                Ce site n'utilise que des cookies techniques nécessaires à son fonctionnement.
                Ils permettent de mémoriser votre choix sur ce bandeau et de sécuriser le formulaire de devis.
            </p>

            <ul class="cookie-list">
                <li>
                    <div>
                        <strong>Cookies techniques</strong>
                        <span>Nécessaires au fonctionnement du site et à la sécurité du formulaire.</span>
                    </div>
                    <span class="cookie-state cookie-state--on">Toujours actifs</span>
                </li>
                <li>
                    <div>
                        <strong>Mesure d'audience</strong>
                        <span>Aucun outil de mesure d'audience n'est installé actuellement.</span>
                    </div>
                    <span class="cookie-state">Non utilisé</span>
                </li>
                <li>
                    <div>
                        <strong>Publicité et réseaux sociaux</strong>
                        <span>Aucun cookie publicitaire ni traceur social n'est déposé.</span>
                    </div>
                    <span class="cookie-state">Non utilisé</span>
                </li>
            </ul>

            <div class="cookie-actions">
                <button type="button" class="btn btn--primary" data-cookie-accept>J'ai compris</button>
                <button type="button" class="btn btn--line" data-cookie-reset>Réinitialiser mon choix</button>
            </div>
        </div>
    </div>
</div>
