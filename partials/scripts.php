<?php
/** @var array $config */
$v = site_version($config);
$vehicles = array_map(static fn(array $x): array => [
    'slug' => $x['slug'], 'name' => $x['name'], 'volume' => $x['volume'],
    'min' => $x['volume_min'], 'max' => $x['volume_max'],
], $config['fleet']['vehicles']);
?>
<script type="application/json" id="dst-data"><?= json_encode([
    'vehicles' => $vehicles,
    'phone' => $config['contact']['phone_display'],
    'phoneHref' => tel_href($config),
    'words' => $config['hero_words'],
    'postcodes' => $config['zone']['postcodes'],
    'sendUrl' => 'send.php',
    'reduced' => false,
], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES) ?></script>

<script src="assets/js/vendor/gsap.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/ScrollTrigger.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/SplitText.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/Flip.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/Draggable.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/InertiaPlugin.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/MotionPathPlugin.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/DrawSVGPlugin.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/CustomEase.min.js?v=<?= e($v) ?>"></script>
<script src="assets/js/vendor/lenis.min.js?v=<?= e($v) ?>"></script>
<script type="module" src="assets/js/main.js?v=<?= e($v) ?>"></script>
