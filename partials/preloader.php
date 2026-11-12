<?php
?>
<div class="preloader" data-preloader aria-hidden="true">
    <div class="preloader__content">
        <div class="preloader__logo">
            <?= dst_logo_mark('logo-mark--preloader', 'preloader') ?>
        </div>

        <div class="preloader__thermo">
            <div class="preloader__thermo-head">
                <span class="mono">Pré-refroidissement caisson</span>
                <span class="mono preloader__temp" data-preloader-temp>20,0 °C</span>
            </div>
            <div class="preloader__gauge">
                <span class="preloader__gauge-fill" data-preloader-fill></span>
                <span class="preloader__gauge-mark"></span>
            </div>
            <div class="preloader__status mono">
                <span data-preloader-status>Initialisation</span>
                <span class="preloader__dot" aria-hidden="true"></span>
            </div>
        </div>
    </div>

    <div class="preloader__doors" aria-hidden="true">
        <span class="preloader__door preloader__door--left"></span>
        <span class="preloader__door preloader__door--right"></span>
    </div>
</div>
