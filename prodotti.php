<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
$page = 'prodotti';
$title = 'Catalogo registratori di cassa e POS | iCash';
$description = 'Catalogo iCash: registratori telematici, sistemi touch, casse automatiche e periferiche. Schede prodotto e preventivo, senza acquisto online.';
$canonicalPath = '/prodotti';
require __DIR__ . '/inc/header.php';
$cats = categorie();
?>
<?php render_pagetitle('Il catalogo, senza carrello.', 'Schede prodotto e preventivo, senza prezzi. WhatsApp con marca e modello già nel messaggio.', [['Home', '/'], ['Prodotti', null]], 'prodotti'); ?>
<section class="section catalog">
  <div class="wrap">
    <div class="filters" role="toolbar" aria-label="Filtra prodotti">
      <button type="button" class="chip is-on" data-filter="all">Tutti</button>
      <?php foreach ($cats as $key => $label): ?>
        <button type="button" class="chip" data-filter="<?= e($key) ?>"><?= e($label) ?></button>
      <?php endforeach; ?>
    </div>
    <div class="cards cards-soft product-grid" data-motion="stagger">
      <?php foreach (prodotti() as $p):
          $label = prodotto_label($p);
          $msg = "Buongiorno, vorrei ricevere un preventivo per il prodotto {$label}, che ho visto sul vostro sito.";
          ?>
        <article class="card soft product-card enter enter-up" id="<?= e($p['id']) ?>" data-cat="<?= e($p['category']) ?>" data-audience="<?= e(implode(' ', $p['audience'] ?? [])) ?>">
          <a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>"><img src="<?= e((string) $p['image']) ?>" alt="<?= e((string) ($p['alt'] ?? $label)) ?>" title="<?= e((string) ($p['alt'] ?? $label)) ?>" width="640" height="480"></a>
          <p class="brand"><?= e((string) $p['brand']) ?> · <?= e($cats[$p['category']] ?? '') ?></p>
          <h2><a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>"><?= e((string) $p['model']) ?></a></h2>
          <p><?= e(mb_strimwidth((string) $p['summary'], 0, 120, '…')) ?></p>
          <div class="product-actions">
            <a class="btn btn-small" href="/prodotto/<?= e((string) $p['id']) ?>" title="Apri la scheda di <?= e($label) ?>">Apri scheda prodotto</a>
            <a class="btn btn-small" data-track="whatsapp" href="<?= e(wa_link($msg)) ?>" target="_blank" rel="noopener">Chiedi preventivo su WhatsApp</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
    <p class="filter-empty" hidden>Nessun prodotto in questa categoria.</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
