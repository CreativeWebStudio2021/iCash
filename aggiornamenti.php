<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
require_once __DIR__ . '/inc/aggiornamenti.php';
$page = 'aggiornamenti';
$title = 'Aggiornamenti su registratori e adempimenti | iCash';
$description = 'Novità e orientamento iCash su registratori telematici, pagamenti elettronici, corrispettivi e fatturazione.';
$canonicalPath = '/aggiornamenti';
// Unico flusso: prima i gestibili (per data), poi i 4 fissi in coda
$items = array_merge(aggiornamenti_dinamici(true), aggiornamenti_fissi());
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Aggiornamenti per chi ha il punto cassa', 'Gli argomenti su cui confrontarci prima di scegliere o aggiornare il registratore. Le scadenze si verificano sul caso concreto.', [['Home', '/'], ['Aggiornamenti', null]], 'info'); ?>
<section class="section">
  <div class="wrap">
    <?php if ($items === []): ?>
      <p>Non ci sono aggiornamenti pubblicati al momento.</p>
    <?php else: ?>
      <div class="news-grid" data-motion="stagger">
        <?php foreach ($items as $item):
            $slug = (string) $item['slug'];
            $titolo = (string) $item['titolo'];
            $excerpt = aggiornamento_card_text($item);
            $isFisso = aggiornamento_is_fisso($item);
            $dataLabel = $isFisso ? '' : aggiornamento_data_label((string) $item['data_pub']);
            ?>
          <a class="news-card news-card-text enter enter-up" href="/aggiornamento/<?= e($slug) ?>" title="<?= e($titolo) ?>">
            <span class="news-card-body">
              <?php if ($dataLabel !== ''): ?>
                <time datetime="<?= e((string) $item['data_pub']) ?>"><?= e($dataLabel) ?></time>
              <?php endif; ?>
              <strong><?= e($titolo) ?></strong>
              <span class="news-card-excerpt"><?= e($excerpt) ?></span>
              <span class="text-link">Leggi</span>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
