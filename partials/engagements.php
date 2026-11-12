<?php
/**
 * 07 — Nos engagements : bon de livraison qui se coche et se signe.
 * @var array $config
 */
$eng = $config['commitments'];
$date = new DateTimeImmutable('now', new DateTimeZone($config['site']['timezone'] ?? 'Europe/Paris'));
$months = [1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
$dateFr = $date->format('j') . ' ' . $months[(int) $date->format('n')] . ' ' . $date->format('Y');
?>
<section class="section engagements" id="engagements" aria-labelledby="engagements-title" data-section="engagements">
  <div class="wrap">
    <header class="section__head">
      <p class="section__num"><span><?= e($eng['number']) ?></span><?= e($eng['label']) ?></p>
      <h2 class="h2" id="engagements-title">
        <span class="h2__line"><?= e($eng['title_a']) ?></span>
        <span class="h2__line"><?= hl($eng['highlight']) ?><span aria-hidden="true">.</span></span>
      </h2>
    </header>

    <div class="bl" data-bl>
      <div class="bl__head">
        <div class="bl__brand">
          <span class="bl__logo" aria-hidden="true">DST</span>
          <span class="bl__brand-lines">Distribution<br>Services<br>Transports</span>
        </div>
        <div class="bl__meta">
          <p><span>Document</span><b><?= e($eng['doc_title']) ?></b></p>
          <p><span>N°</span><b><?= e($eng['doc_number']) ?></b></p>
          <p><span>Date</span><b><?= e($dateFr) ?></b></p>
        </div>
      </div>

      <table class="bl__table">
        <caption class="sr-only">Engagements de livraison DST</caption>
        <thead>
          <tr>
            <th scope="col" class="bl__th-check"><span class="sr-only">Coché</span></th>
            <th scope="col"><?= e($eng['columns'][0]) ?></th>
            <th scope="col"><?= e($eng['columns'][1]) ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($eng['items'] as $i => $item): ?>
            <tr data-bl-line="<?= $i ?>">
              <td class="bl__check">
                <span class="bl__box" aria-hidden="true">
                  <svg viewBox="0 0 24 24"><path d="M4.5 12.6 L9.6 17.6 L19.6 6.6"/></svg>
                </span>
                <span class="sr-only"><?= $i === 0 ? 'Engagement' : '' ?> à valider</span>
              </td>
              <th scope="row"><?= e($item['title']) ?></th>
              <td><?= e($item['text']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="bl__foot">
        <div class="bl__sign">
          <svg class="bl__signature" viewBox="0 0 320 90" aria-hidden="true">
            <path data-sign-path d="M8,68 C26,34 40,20 46,30 C52,42 38,66 50,68 C60,70 70,44 80,44 C90,44 86,66 96,66 C108,66 112,40 122,42 C132,44 124,68 138,66 C150,64 156,36 168,38 C180,40 170,66 186,64 C200,62 206,44 216,46 C226,48 218,64 232,62 C244,60 250,48 262,50 C274,52 280,58 292,52"/>
          </svg>
          <p class="bl__sign-label"><?= e($eng['doc_sign']) ?></p>
        </div>
        <div class="bl__client">
          <p class="bl__client-label"><?= e($eng['doc_client']) ?></p>
          <p class="bl__client-line"></p>
          <p class="bl__client-hint">Signature à la livraison</p>
        </div>
      </div>
    </div>
  </div>
</section>
