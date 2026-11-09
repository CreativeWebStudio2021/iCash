<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
require_once __DIR__ . '/inc/aggiornamenti.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$item = aggiornamento_by_slug($slug, true);
if ($item === null) {
    http_response_code(404);
    $page = 'aggiornamenti';
    $title = 'Aggiornamento non trovato | iCash';
    $description = 'L’articolo richiesto non è disponibile.';
    $canonicalPath = '/aggiornamenti';
    $robots = 'noindex,follow';
    require __DIR__ . '/inc/header.php';
    render_pagetitle('Aggiornamento non trovato', '', [['Home', '/'], ['Aggiornamenti', '/aggiornamenti'], ['Non trovato', null]], 'info');
    echo '<section class="section"><div class="wrap"><p><a href="/aggiornamenti" title="Aggiornamenti">Torna agli aggiornamenti</a></p></div></section>';
    require __DIR__ . '/inc/footer.php';
    exit;
}

$titolo = (string) $item['titolo'];
$testo = (string) $item['testo'];
$img = trim((string) ($item['immagine'] ?? ''));
$isFisso = aggiornamento_is_fisso($item);
$dataLabel = $isFisso ? '' : aggiornamento_data_label((string) $item['data_pub']);
$altri = aggiornamenti_altri((string) $item['slug'], 5);
$evidenza = prodotti_evidenza();

$page = 'aggiornamenti';
$title = $titolo . ' | iCash';
$description = aggiornamento_excerpt($testo, 155);
$canonicalPath = '/aggiornamento/' . rawurlencode((string) $item['slug']);
require __DIR__ . '/inc/header.php';

?>
<?php render_pagetitle($titolo, $dataLabel, [['Home', '/'], ['Aggiornamenti', '/aggiornamenti'], [$titolo, null]], 'info'); ?>
<section class="section">
  <div class="wrap news-detail-layout">
    <article class="news-detail">
      <?php if ($img !== '' && !$isFisso): ?>
        <figure class="news-detail-cover">
          <img src="<?= e($img) ?>" alt="<?= e($titolo) ?>" width="960" height="540">
        </figure>
      <?php endif; ?>
      <?php if (!$isFisso): ?>
        <time class="news-detail-date" datetime="<?= e((string) $item['data_pub']) ?>"><?= e($dataLabel) ?></time>
      <?php endif; ?>
      <div class="news-detail-body">
        <?= rich_text_html($testo) ?>
      </div>
      <p class="news-detail-back">
        <a class="btn btn-line" href="/aggiornamenti" title="Tutti gli aggiornamenti">Tutti gli aggiornamenti</a>
        <a class="btn" href="/contatti" title="Contatti">Contattaci</a>
      </p>
    </article>

    <aside class="news-sidebar">
      <section class="news-side-block">
        <h2>Ultimi aggiornamenti</h2>
        <?php if ($altri === []): ?>
          <p class="news-side-empty">Nessun altro articolo.</p>
        <?php else: ?>
          <ul class="news-side-list">
            <?php foreach ($altri as $a): ?>
              <li>
                <a href="/aggiornamento/<?= e((string) $a['slug']) ?>" title="<?= e((string) $a['titolo']) ?>">
                  <time datetime="<?= e((string) $a['data_pub']) ?>"><?= e(aggiornamento_data_label((string) $a['data_pub'])) ?></time>
                  <strong><?= e((string) $a['titolo']) ?></strong>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <a class="text-link" href="/aggiornamenti">Vedi tutti</a>
      </section>

      <section class="news-side-block">
        <h2>Prodotti in evidenza</h2>
        <?php if ($evidenza === []): ?>
          <p class="news-side-empty">Nessun prodotto in evidenza.</p>
        <?php else: ?>
          <ul class="news-side-products">
            <?php foreach ($evidenza as $p):
                $label = prodotto_label($p);
                ?>
              <li>
                <a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>">
                  <?php if (!empty($p['image'])): ?>
                    <img src="<?= e((string) $p['image']) ?>" alt="<?= e((string) ($p['alt'] ?? $label)) ?>" width="72" height="54" loading="lazy">
                  <?php endif; ?>
                  <span>
                    <em><?= e((string) $p['brand']) ?></em>
                    <strong><?= e((string) $p['model']) ?></strong>
                  </span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <a class="text-link" href="/prodotti">Catalogo completo</a>
      </section>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
