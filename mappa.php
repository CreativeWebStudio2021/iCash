<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
require_once __DIR__ . '/inc/sitemap.php';
$page = 'mappa';
$title = 'Mappa del sito | iCash';
$description = 'Tutte le pagine del sito iCash: azienda, soluzioni, catalogo prodotti, assistenza, contatti e informazioni.';
$canonicalPath = '/mappa-del-sito';
require __DIR__ . '/inc/header.php';
$groups = [];
foreach (sitemap_entries() as $entry) {
    $groups[$entry['group']][] = $entry;
}
?>
<?php render_pagetitle('Mappa del sito', 'Le pagine pubblicate, nello stesso ordine in cui sono elencate per i motori di ricerca.', [['Home', '/'], ['Mappa del sito', null]], 'info'); ?>
<section class="section">
  <div class="wrap prose">
    <?php foreach ($groups as $name => $items): ?>
      <h2><?= e($name) ?></h2>
      <ul>
        <?php foreach ($items as $item): ?>
          <li><a href="<?= e($item['path']) ?>" title="<?= e($item['title']) ?>"><?= e($item['title']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    <?php endforeach; ?>
    <h2>File per Search Console</h2>
    <ul>
      <li><a href="/sitemap.xml" title="Indice delle sitemap">Indice sitemap</a></li>
      <li><a href="/sitemap-pagine.xml" title="Sitemap delle pagine">Sitemap delle pagine</a></li>
      <li><a href="/sitemap-immagini.xml" title="Sitemap delle immagini">Sitemap delle immagini</a></li>
    </ul>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';