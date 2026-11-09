<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
$id = trim((string) ($_GET['id'] ?? ''));
$p = prodotto_by_id($id);
if ($p === null) {
    http_response_code(404);
    $page = 'prodotti';
    $title = 'Prodotto non trovato | iCash';
    $description = 'La scheda richiesta non è nel catalogo iCash.';
    $canonicalPath = '/prodotti';
    $robots = 'noindex,follow';
    require __DIR__ . '/inc/header.php';
    render_pagetitle('Scheda non disponibile', '', [['Home', '/'], ['Prodotti', '/prodotti'], ['Non trovata', null]], 'prodotti');
    echo '<section class="section"><div class="wrap"><p><a href="/prodotti" title="Catalogo prodotti">Torna al catalogo</a></p></div></section>';
    require __DIR__ . '/inc/footer.php';
    exit;
}
$label = prodotto_label($p);
$msg = "Buongiorno, vorrei ricevere un preventivo per il prodotto {$label}, che ho visto sul vostro sito.";
$page = 'prodotti';
$metaTitle = trim((string) ($p['meta_title'] ?? ''));
$metaDescription = trim((string) ($p['meta_description'] ?? ''));
$title = $metaTitle !== '' ? $metaTitle : ($label . ' | iCash');
$description = $metaDescription !== '' ? $metaDescription : (string) $p['summary'];
$canonicalPath = '/prodotto/' . rawurlencode($id);
require __DIR__ . '/inc/header.php';
$cats = categorie();
$cover = trim((string) ($p['image'] ?? ''));
$coverAlt = (string) ($p['alt'] ?? $label);
$gallery = [];
if ($cover !== '') {
    $gallery[] = ['src' => $cover, 'alt' => $coverAlt];
}
foreach ($p['gallery'] ?? [] as $gItem) {
    $src = trim((string) ($gItem['src'] ?? ''));
    if ($src === '' || $src === $cover) {
        continue;
    }
    $gallery[] = [
        'src' => $src,
        'alt' => (string) ($gItem['alt'] ?? $label),
    ];
}
$main = $gallery[0] ?? null;
$pdf = trim((string) ($p['pdf'] ?? ''));
?>
<?php render_pagetitle((string) $p['model'], (string) $p['brand'] . ' · ' . ($cats[$p['category']] ?? ''), [['Home', '/'], ['Prodotti', '/prodotti'], [(string) $p['model'], null]], 'prodotti'); ?>
<section class="section">
  <div class="wrap detail" data-motion="pair">
    <aside class="detail-side enter enter-left">
      <?php if ($main): ?>
        <div class="detail-gallery" data-gallery data-lightbox-group="prodotto">
          <figure class="detail-photo">
            <button type="button" class="gallery-main-btn" data-lightbox-open data-lightbox-src="<?= e($main['src']) ?>" data-lightbox-alt="<?= e($main['alt']) ?>" aria-label="Ingrandisci la foto">
              <img data-gallery-main src="<?= e($main['src']) ?>" alt="<?= e($main['alt']) ?>" title="<?= e($main['alt']) ?>">
            </button>
          </figure>
          <?php if (count($gallery) > 1): ?>
            <div class="gallery-thumbs" role="list">
              <?php foreach ($gallery as $i => $gItem): ?>
                <button type="button" class="gallery-thumb<?= $i === 0 ? ' is-on' : '' ?>" role="listitem" data-gallery-thumb data-src="<?= e($gItem['src']) ?>" data-alt="<?= e($gItem['alt']) ?>" data-lightbox-src="<?= e($gItem['src']) ?>" data-lightbox-alt="<?= e($gItem['alt']) ?>" aria-label="Foto <?= (int) ($i + 1) ?>">
                  <img src="<?= e($gItem['src']) ?>" alt="">
                </button>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
      <div class="detail-actions">
        <a class="btn btn-wa-solid" data-track="whatsapp" href="<?= e(wa_link($msg)) ?>" target="_blank" rel="noopener">Scrivi su WhatsApp</a>
        <a class="btn" href="/contatti?prodotto=<?= e(rawurlencode($label)) ?>" title="Preventivo per <?= e($label) ?>">Richiedi un preventivo</a>
        <?php if ($pdf !== ''): ?>
          <a class="btn btn-line" href="<?= e($pdf) ?>" title="Scarica la scheda tecnica PDF" target="_blank" rel="noopener">Scarica scheda tecnica PDF</a>
        <?php endif; ?>
      </div>
    </aside>
    <div class="detail-body enter enter-right">
      <?php render_blocchi($p); ?>
      <?php if ($pdf !== ''): ?>
        <section class="block block-scheda">
          <h2 class="block-title">Scheda tecnica PDF</h2>
          <div class="block-panel">
            <p class="scheda-pdf-actions"><a class="btn btn-line" href="<?= e($pdf) ?>" target="_blank" rel="noopener">Scarica scheda tecnica PDF</a></p>
            <?php
            $sheetPages = array_values(array_filter(
                $p['gallery'] ?? [],
                static fn ($g): bool => str_contains((string) ($g['src'] ?? ''), '/gallery/')
            ));
            if ($sheetPages !== []): ?>
              <div class="scheda-preview" data-lightbox-group="scheda">
                <?php foreach ($sheetPages as $i => $pageImg): ?>
                  <button type="button" class="scheda-preview-btn" data-lightbox-open data-lightbox-src="<?= e((string) $pageImg['src']) ?>" data-lightbox-alt="<?= e((string) ($pageImg['alt'] ?? 'Pagina scheda tecnica')) ?>" aria-label="Ingrandisci pagina <?= (int) ($i + 1) ?>">
                    <img src="<?= e((string) $pageImg['src']) ?>" alt="<?= e((string) ($pageImg['alt'] ?? 'Pagina scheda tecnica')) ?>">
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </div>
        </section>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
