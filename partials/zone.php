<?php
/** @var array $config */
$contact = $config['contact'];
$zones   = $config['zones'];
$zoneMap = [];
foreach ($zones as $z) {
    $zoneMap[$z['code']] = $z;
}
?>
<section class="zone section" id="zone" data-theme="light" aria-labelledby="zone-title">
    <div class="container zone__grid">
        <div class="zone__map-col">
            <div class="zone__map" data-zone-map>
                <div class="zone__map-head">
                    <span class="mono">Desserte · Île-de-France</span>
                    <span class="mono zone__map-count">08 départements</span>
                </div>

                <svg class="zone__svg" viewBox="0 0 620 520" role="group" aria-label="Carte de l'Île-de-France : Paris et les sept départements desservis">
<path class="zone__petal" data-code="78" tabindex="0" role="button" d="M228.9 165.5 L220.2 171.1 L216.1 180.0 L214.2 195.2 L215.7 202.9 L222.5 204.3 L224.2 211.0 L229.5 210.5 L235.9 219.6 L229.5 221.6 L223.9 227.2 L215.5 228.8 L211.2 235.5 L201.3 236.7 L204.6 244.8 L197.0 256.6 L187.2 255.8 L179.7 274.3 L191.1 284.3 L184.2 290.1 L184.5 296.0 L179.5 304.0 L179.6 308.8 L169.2 310.4 L158.1 306.6 L157.1 312.7 L166.1 318.8 L160.3 325.8 L156.9 339.6 L152.0 343.0 L153.9 349.6 L149.7 356.7 L140.3 356.9 L133.1 353.0 L129.6 345.5 L121.9 343.5 L114.8 328.5 L114.5 321.0 L117.6 310.4 L111.6 304.0 L96.5 300.5 L94.6 297.6 L98.1 285.6 L84.9 285.9 L81.0 275.9 L67.9 266.0 L70.3 255.0 L61.6 250.0 L74.2 231.1 L63.8 220.7 L60.6 213.8 L64.7 203.8 L61.0 191.8 L62.1 187.5 L52.6 180.3 L56.2 176.4 L50.5 166.4 L50.6 160.2 L42.1 157.8 L40.6 152.3 L39.5 136.6 L34.0 136.9 L34.0 121.4 L28.8 117.6 L26.0 109.8 L29.6 102.7 L36.1 107.3 L50.4 98.8 L55.5 99.9 L67.6 93.8 L69.5 96.5 L79.4 95.1 L87.0 96.4 L92.4 105.0 L100.4 109.9 L110.1 102.4 L119.5 104.5 L120.1 98.8 L129.1 97.5 L138.6 104.9 L134.8 114.7 L139.6 120.8 L145.6 112.3 L151.3 111.2 L158.1 119.4 L172.7 119.7 L181.2 129.1 L197.8 122.3 L209.1 124.3 L207.4 131.0 L216.6 134.1 L222.2 139.5 L222.6 147.2 L230.3 148.8 Z" aria-label="Yvelines (78)"/>
<path class="zone__petal" data-code="95" tabindex="0" role="button" d="M334.0 95.8 L329.0 103.2 L333.3 107.9 L322.4 118.7 L323.9 124.3 L318.3 126.2 L313.6 135.5 L300.8 142.8 L298.7 146.6 L284.6 146.2 L278.5 140.1 L267.6 141.2 L264.7 146.5 L255.4 142.0 L253.3 148.3 L247.3 150.1 L228.9 165.5 L230.3 148.8 L222.6 147.2 L222.2 139.5 L216.6 134.1 L207.4 131.0 L209.1 124.3 L197.8 122.3 L181.2 129.1 L172.7 119.7 L158.1 119.4 L151.3 111.2 L145.6 112.3 L139.6 120.8 L134.8 114.7 L138.6 104.9 L129.1 97.5 L120.1 98.8 L119.5 104.5 L110.1 102.4 L100.4 109.9 L92.4 105.0 L87.0 96.4 L79.4 95.1 L69.5 96.5 L76.5 81.6 L82.1 75.2 L84.4 65.7 L87.1 42.5 L95.2 33.5 L103.2 38.0 L105.4 54.8 L108.9 57.1 L119.8 52.7 L133.3 59.0 L141.7 58.3 L144.0 61.9 L156.4 57.2 L164.3 57.5 L167.8 53.3 L175.0 56.6 L195.1 43.1 L201.0 50.9 L210.6 50.2 L223.9 57.4 L233.8 54.6 L242.9 65.9 L252.0 63.0 L258.6 52.2 L268.2 62.3 L280.3 67.3 L286.2 66.1 L301.9 73.0 L310.5 80.3 L306.9 84.9 L325.3 88.1 Z" aria-label="Val-d'Oise (95)"/>
<path class="zone__petal" data-code="77" tabindex="0" role="button" d="M328.9 254.0 L328.1 247.8 L332.5 239.6 L332.6 233.7 L338.4 220.3 L334.5 206.9 L334.5 199.3 L327.9 183.0 L333.0 171.6 L334.5 158.9 L333.7 147.2 L328.9 141.1 L331.2 131.7 L323.9 124.3 L322.4 118.7 L333.3 107.9 L329.0 103.2 L334.0 95.8 L345.5 84.1 L356.2 91.2 L365.3 101.5 L377.9 103.5 L386.9 97.6 L388.2 91.5 L402.6 93.7 L405.5 99.7 L416.0 96.8 L417.8 93.5 L437.5 97.8 L446.6 90.9 L457.3 93.1 L463.7 80.3 L472.3 84.3 L481.0 84.5 L488.8 87.6 L485.7 94.3 L493.3 103.5 L489.6 121.5 L506.0 133.0 L512.2 139.6 L516.5 153.4 L526.4 149.2 L528.7 160.4 L533.3 165.5 L543.0 159.8 L547.4 173.7 L546.8 179.4 L553.3 183.8 L566.1 186.9 L568.4 193.8 L575.0 188.7 L575.0 199.7 L560.7 204.9 L563.6 215.5 L554.6 216.5 L551.0 226.5 L560.2 227.5 L564.0 235.7 L570.9 235.2 L569.4 247.7 L570.8 256.2 L563.0 265.5 L568.4 269.9 L583.7 273.8 L584.2 277.8 L594.0 283.2 L580.1 289.5 L578.3 295.6 L569.7 303.5 L573.5 314.1 L568.0 319.8 L558.4 318.6 L553.5 320.9 L561.5 333.3 L548.9 340.3 L553.8 351.7 L549.9 363.2 L558.0 367.7 L556.1 377.1 L547.6 373.2 L543.2 375.4 L542.7 384.4 L526.5 384.2 L520.4 382.3 L512.8 387.4 L507.0 385.2 L498.7 387.8 L482.0 384.3 L470.9 390.3 L457.6 389.4 L454.2 397.6 L455.5 401.9 L448.6 410.7 L456.1 425.3 L445.7 451.6 L437.4 452.5 L436.5 457.1 L426.7 463.4 L427.1 469.7 L408.7 472.5 L396.0 483.4 L389.8 478.9 L390.1 467.7 L374.6 471.1 L378.3 476.9 L369.0 480.5 L365.2 485.4 L353.6 486.5 L347.2 479.7 L337.3 482.7 L328.6 478.9 L319.7 479.1 L315.4 484.4 L307.0 484.7 L298.8 480.5 L305.1 469.2 L311.3 472.5 L312.1 461.9 L315.8 456.8 L311.4 444.1 L301.3 432.2 L296.8 434.3 L289.0 430.1 L288.3 414.5 L283.4 405.5 L289.5 398.7 L290.7 388.6 L301.3 382.7 L315.4 371.1 L317.0 366.7 L311.7 361.1 L310.1 338.5 L313.7 329.8 L309.5 322.0 L314.9 306.8 L314.5 298.3 L321.4 289.9 L316.6 286.2 L318.6 268.1 L321.7 262.5 L332.6 259.1 Z" aria-label="Seine-et-Marne (77)"/>
<path class="zone__petal" data-code="91" tabindex="0" role="button" d="M235.9 219.6 L237.6 223.7 L246.3 225.6 L259.6 238.3 L261.3 230.8 L270.4 235.0 L274.8 242.5 L286.5 243.4 L297.5 241.6 L310.5 236.3 L313.8 238.9 L318.0 250.8 L324.1 255.5 L328.9 254.0 L332.6 259.1 L321.7 262.5 L318.6 268.1 L316.6 286.2 L321.4 289.9 L314.5 298.3 L314.9 306.8 L309.5 322.0 L313.7 329.8 L310.1 338.5 L311.7 361.1 L317.0 366.7 L315.4 371.1 L301.3 382.7 L290.7 388.6 L289.5 398.7 L283.4 405.5 L272.8 410.0 L263.2 400.4 L259.1 401.3 L254.5 410.6 L239.0 407.2 L235.2 399.1 L229.4 395.7 L223.6 409.1 L216.8 412.1 L196.8 416.5 L189.1 415.8 L186.7 419.4 L168.2 419.1 L163.9 410.4 L169.5 406.4 L166.8 397.7 L171.3 388.6 L167.7 377.7 L168.6 373.4 L154.9 367.9 L158.9 362.8 L152.0 343.0 L156.9 339.6 L160.3 325.8 L166.1 318.8 L157.1 312.7 L158.1 306.6 L169.2 310.4 L179.6 308.8 L179.5 304.0 L184.5 296.0 L184.2 290.1 L191.1 284.3 L179.7 274.3 L187.2 255.8 L197.0 256.6 L204.6 244.8 L201.3 236.7 L211.2 235.5 L215.5 228.8 L223.9 227.2 L229.5 221.6 Z" aria-label="Essonne (91)"/>
<path class="zone__petal" data-code="92" tabindex="0" role="button" d="M253.3 148.3 L263.0 150.6 L265.5 155.9 L261.0 162.6 L261.1 168.9 L249.6 178.1 L244.5 177.1 L237.3 181.7 L235.3 188.1 L250.1 196.6 L264.3 203.0 L260.7 214.8 L262.7 217.3 L258.0 228.1 L261.3 230.8 L259.6 238.3 L246.3 225.6 L237.6 223.7 L235.9 219.6 L229.5 210.5 L224.2 211.0 L222.5 204.3 L215.7 202.9 L214.2 195.2 L216.1 180.0 L220.2 171.1 L228.9 165.5 L247.3 150.1 Z" aria-label="Hauts-de-Seine (92)"/>
<path class="zone__petal" data-code="93" tabindex="0" role="button" d="M323.9 124.3 L331.2 131.7 L328.9 141.1 L333.7 147.2 L334.5 158.9 L333.0 171.6 L327.9 183.0 L334.5 199.3 L334.5 206.9 L318.7 191.5 L304.6 184.8 L287.0 189.8 L286.4 181.0 L278.6 168.3 L261.1 168.9 L261.0 162.6 L265.5 155.9 L263.0 150.6 L253.3 148.3 L255.4 142.0 L264.7 146.5 L267.6 141.2 L278.5 140.1 L284.6 146.2 L298.7 146.6 L300.8 142.8 L313.6 135.5 L318.3 126.2 Z" aria-label="Seine-Saint-Denis (93)"/>
<path class="zone__petal" data-code="94" tabindex="0" role="button" d="M264.3 203.0 L272.9 203.2 L282.8 198.0 L293.0 202.5 L299.5 202.1 L299.4 192.5 L287.0 189.8 L304.6 184.8 L318.7 191.5 L334.5 206.9 L338.4 220.3 L332.6 233.7 L332.5 239.6 L328.1 247.8 L328.9 254.0 L324.1 255.5 L318.0 250.8 L313.8 238.9 L310.5 236.3 L297.5 241.6 L286.5 243.4 L274.8 242.5 L270.4 235.0 L261.3 230.8 L258.0 228.1 L262.7 217.3 L260.7 214.8 Z" aria-label="Val-de-Marne (94)"/>
<path class="zone__petal zone__petal--paris" data-code="75" tabindex="0" role="button" d="M287.0 189.8 L299.4 192.5 L299.5 202.1 L293.0 202.5 L282.8 198.0 L272.9 203.2 L264.3 203.0 L250.1 196.6 L235.3 188.1 L237.3 181.7 L244.5 177.1 L249.6 178.1 L261.1 168.9 L278.6 168.3 L286.4 181.0 Z" aria-label="Paris (75)"/>
<g class="zone__routes" data-zone-routes="" aria-hidden="true">
<path class="zone__route zone__route--paris" data-route="75" d="M265.4 207.4 Q274.2 198.4 267.0 188.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="78" d="M265.4 207.4 Q193.9 204.0 128.0 232.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="95" d="M265.4 207.4 Q248.8 150.3 205.0 110.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="77" d="M265.4 207.4 Q336.1 257.3 420.0 278.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="91" d="M265.4 207.4 Q237.9 273.5 242.0 345.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="92" d="M265.4 207.4 Q253.4 184.2 228.0 190.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="93" d="M265.4 207.4 Q296.1 198.9 304.0 168.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
<path class="zone__route" data-route="94" d="M265.4 207.4 Q276.5 229.4 300.0 222.0" stroke="#9A6B00" stroke-width="1.15" fill="none" stroke-dasharray="3 5"/>
</g>
<g class="zone__labels" aria-hidden="true">
<text x="120" y="245">78</text>
<text x="205" y="105">95</text>
<text x="440" y="280">77</text>
<text x="245" y="365">91</text>
<text class="zone__label-word zone__label-paris" x="267" y="163">Paris</text>
<path d="M267 168 L267 176" stroke="currentColor" stroke-width="1" fill="none"/>
</g>
<g class="zone__hub">
<circle class="zone__hub-wave" cx="265.4" cy="207.4" r="13"/>
<circle class="zone__hub-wave zone__hub-wave--2" cx="265.4" cy="207.4" r="13"/>
<circle class="zone__hub-core" cx="265.4" cy="207.4" r="5"/>
<path d="M268.4 213.4 L286 230" stroke="currentColor" stroke-width="1" fill="none"/>
<text class="zone__hub-label" x="290" y="236">Arcueil · siège</text>
</g>
                </svg>

                <div class="zone__tooltip" data-zone-tooltip aria-hidden="true">
                    <strong data-zone-tooltip-name>Paris (75)</strong>
                    <span class="mono" data-zone-tooltip-delay>Livraison J ou J+1</span>
                </div>

                <p class="zone__caption mono">Carte de l’Île-de-France — contours administratifs réels.</p>
            </div>

            <ul class="zone__list" aria-label="Départements desservis">
                <?php foreach ($zones as $zone): ?>
                    <li data-zone-item="<?= $zone['code'] ?>" data-zone-name="<?= htmlspecialchars($zone['name'], ENT_QUOTES, 'UTF-8') ?>" data-zone-delay="<?= htmlspecialchars($zone['delay'], ENT_QUOTES, 'UTF-8') ?>">
                        <span class="zone__bullet" aria-hidden="true"></span>
                        <span class="zone__name"><?= htmlspecialchars($zone['name'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="zone__code mono">(<?= $zone['code'] ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="zone__copy">
            <p class="eyebrow"><span class="eyebrow__dash"></span>04 — Zone d'intervention</p>
            <h2 class="h2" id="zone-title">
                Paris et toute
                <span class="line-mask"><span class="line"><em class="serif">l'Île-de-France.</em></span></span>
            </h2>
            <p class="lede">
                Basés à Arcueil, aux portes de Paris, nous rayonnons sur les huit départements franciliens.
                Une position idéale pour rejoindre le périphérique, l'A6 et Rungis en quelques minutes.
            </p>

            <p class="zone__note">
                Livraisons hors Île-de-France possibles sur demande.
            </p>

            <address class="zone__address">
                <span class="zone__address-label mono">Siège social</span>
                <p><?= htmlspecialchars($contact['address_line'], ENT_QUOTES, 'UTF-8') ?><br>
                   <?= htmlspecialchars($contact['postcode'] . ' ' . $contact['city'], ENT_QUOTES, 'UTF-8') ?></p>
                <a class="link-arrow" href="<?= htmlspecialchars($contact['maps_url'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                    <span>Itinéraire</span>
                    <?= dst_icon('arrow-up-right', '', 16) ?>
                </a>
            </address>
        </div>
    </div>
</section>
