<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/aggiornamenti.php';
admin_require();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    admin_csrf_check();
    $id = (int) ($_POST['id'] ?? 0);
    try {
        if ($id > 0) {
            aggiornamento_delete($id);
        }
        header('Location: /admin/aggiornamenti.php?ok=deleted');
        exit;
    } catch (Throwable $e) {
        header('Location: /admin/aggiornamenti.php?err=' . rawurlencode($e->getMessage()));
        exit;
    }
}

$items = aggiornamenti_all(false);
$dinamici = array_values(array_filter($items, static fn ($r) => !aggiornamento_is_fisso($r)));
$fissi = array_values(array_filter($items, static fn ($r) => aggiornamento_is_fisso($r)));
$pub = count(array_filter($dinamici, static fn ($r) => !empty($r['pubblicato'])));
$csrf = admin_csrf();

$iconEdit = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M4 16.8V20h3.2L17.4 9.8l-3.2-3.2zM19.7 7.5a1 1 0 0 0 0-1.4l-1.8-1.8a1 1 0 0 0-1.4 0l-1.3 1.3 3.2 3.2z"/></svg>';
$iconExternal = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M14 3h7v7h-2V6.41l-9.29 9.3-1.42-1.42L17.59 5H14V3zM5 5h6v2H7v10h10v-4h2v6H5V5z"/></svg>';
$iconEyeOn = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 6.5C7.5 6.5 3.7 9.2 2 12c1.7 2.8 5.5 5.5 10 5.5s8.3-2.7 10-5.5c-1.7-2.8-5.5-5.5-10-5.5zm0 9a3.5 3.5 0 1 1 3.5-3.5A3.5 3.5 0 0 1 12 15.5zm0-5.5a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"/></svg>';
$iconEyeOff = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M2 4.27 3.28 3 21 20.72 19.73 22l-2.2-2.2A11.7 11.7 0 0 1 12 17.5C7.5 17.5 3.7 14.8 2 12a12.5 12.5 0 0 1 4.05-4.3L2 4.27zM12 6.5c4.5 0 8.3 2.7 10 5.5a12.3 12.3 0 0 1-3.2 3.4l-1.46-1.46A3.5 3.5 0 0 0 12 8.5a3.4 3.4 0 0 0-1.1.18L9.2 7A8.2 8.2 0 0 1 12 6.5zm0 9a3.5 3.5 0 0 1-3.45-2.95l4.5 4.5A3.5 3.5 0 0 1 12 15.5z"/></svg>';
$iconTrash = '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6 7h12v2H6zm2 3h8l-1 9H9zm3-6h2l1 2H10z"/></svg>';

admin_layout_start('Aggiornamenti', 'aggiornamenti');
?>
    <div class="page-head">
      <div>
        <p class="section-kicker section-kicker-news">Contenuti</p>
        <h1>Aggiornamenti</h1>
        <p class="sub"><?= (int) count($dinamici) ?> gestibili · <?= (int) $pub ?> pubblicati · <?= (int) count($fissi) ?> fissi</p>
      </div>
      <a class="btn primary" href="/admin/aggiornamento-edit.php">Nuovo aggiornamento</a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
      <p class="flash flash-ok">Operazione completata.</p>
    <?php endif; ?>
    <?php if (isset($_GET['err'])): ?>
      <p class="flash flash-err"><?= e((string) $_GET['err']) ?></p>
    <?php endif; ?>

    <section class="panel panel-accent-news">
      <h2>Come funziona</h2>
      <p class="hint" style="margin:0">Usa l’occhio per pubblicare o oscurare un articolo sul sito. L’icona link apre la pagina pubblica. I 4 blocchi originari restano fissi in fondo e non sono modificabili.</p>
    </section>

    <h2 class="admin-list-title">Articoli gestibili</h2>
    <div class="crm-table-wrap">
      <table>
        <thead>
          <tr>
            <th></th>
            <th>Titolo</th>
            <th>Data</th>
            <th>Stato</th>
            <th>Azioni</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($dinamici === []): ?>
          <tr><td colspan="5">Nessun articolo gestibile. <a href="/admin/aggiornamento-edit.php">Creane uno</a>.</td></tr>
        <?php endif; ?>
        <?php foreach ($dinamici as $row):
            $id = (int) $row['id'];
            $slug = (string) $row['slug'];
            $isPub = !empty($row['pubblicato']);
            $img = trim((string) ($row['immagine'] ?? ''));
            ?>
          <tr>
            <td>
              <?php if ($img !== ''): ?>
                <img class="thumb-sm" src="<?= e($img) ?>" alt="">
              <?php else: ?>
                <span class="thumb-placeholder">Nessuna foto</span>
              <?php endif; ?>
            </td>
            <td>
              <strong><?= e((string) $row['titolo']) ?></strong><br>
              <span class="muted-path">/aggiornamento/<?= e($slug) ?></span>
            </td>
            <td><?= e(aggiornamento_data_label((string) $row['data_pub'])) ?></td>
            <td>
              <?php if ($isPub): ?>
                <span class="badge badge-on">Pubblicato</span>
              <?php else: ?>
                <span class="badge badge-off">Oscurato</span>
              <?php endif; ?>
            </td>
            <td>
              <div class="row-actions">
                <a class="icon-btn" href="/admin/aggiornamento-edit.php?id=<?= $id ?>" title="Modifica" aria-label="Modifica"><?= $iconEdit ?></a>
                <a class="icon-btn" href="/aggiornamento/<?= e($slug) ?>" target="_blank" rel="noopener" title="Apri pagina pubblica" aria-label="Apri pagina pubblica"><?= $iconExternal ?></a>
                <form method="post" action="/admin/aggiornamento-visibilita-save.php" class="inline-form"<?= $isPub ? ' onsubmit="return confirm(\'Oscurare questo aggiornamento? Sparisce dalla lista pubblica e la scheda risponde 404.\');"' : '' ?>>
                  <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <input type="hidden" name="published" value="<?= $isPub ? '0' : '1' ?>">
                  <button type="submit" class="icon-btn" title="<?= $isPub ? 'Oscura sul sito' : 'Pubblica sul sito' ?>" aria-label="<?= $isPub ? 'Oscura' : 'Pubblica' ?>">
                    <?= $isPub ? $iconEyeOn : $iconEyeOff ?>
                  </button>
                </form>
                <form method="post" class="inline-form" onsubmit="return confirm('Eliminare questo aggiornamento?');">
                  <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $id ?>">
                  <button type="submit" class="icon-btn icon-btn-danger" title="Elimina" aria-label="Elimina"><?= $iconTrash ?></button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <h2 class="admin-list-title">Blocchi fissi (non modificabili)</h2>
    <div class="crm-table-wrap">
      <table>
        <thead>
          <tr>
            <th>Titolo</th>
            <th>Stato</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($fissi as $row):
            $slug = (string) $row['slug'];
            ?>
          <tr>
            <td>
              <strong><?= e((string) $row['titolo']) ?></strong><br>
              <span class="muted-path">/aggiornamento/<?= e($slug) ?></span>
            </td>
            <td><span class="badge badge-off">Fisso</span></td>
            <td>
              <a class="icon-btn" href="/aggiornamento/<?= e($slug) ?>" target="_blank" rel="noopener" title="Apri pagina pubblica" aria-label="Apri pagina pubblica"><?= $iconExternal ?></a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
<?php admin_layout_end(); ?>
