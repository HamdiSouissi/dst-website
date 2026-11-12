<?php
/** @var array $config */
$sectors = $config['sectors'];
?>
<section class="marquee" data-theme="dark" aria-label="Secteurs desservis par DST">
    <div class="marquee__viewport">
        <div class="marquee__track" data-marquee>
            <?php for ($copy = 0; $copy < 2; $copy++): ?>
                <div class="marquee__group" <?= $copy === 1 ? 'aria-hidden="true"' : '' ?>>
                    <?php foreach ($sectors as $sector): ?>
                        <span class="marquee__item">
                            <?= htmlspecialchars($sector['name'], ENT_QUOTES, 'UTF-8') ?>
                            <span class="marquee__flake" aria-hidden="true"><?= dst_icon('snowflake', '', 22) ?></span>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
