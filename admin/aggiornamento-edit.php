<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/aggiornamenti.php';
admin_require();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$row = $id > 0 ? aggiornamento_by_id($id) : null;
if ($id > 0 && $row === null) {
    http_response_code(404);
    exit('Aggiornamento non trovato');
}
if ($row !== null && aggiornamento_is_fisso($row)) {
    header('Location: /admin/aggiornamenti.php?err=' . rawurlencode('Questo blocco è fisso e non può essere modificato.'));
    exit;
}

$error = '';
$titolo = (string) ($row['titolo'] ?? '');
$riassunto = (string) ($row['riassunto'] ?? '');
$testo = (string) ($row['testo'] ?? '');
$dataPub = (string) ($row['data_pub'] ?? date('Y-m-d'));
$immagine = (string) ($row['immagine'] ?? '');
$pubblicato = $row ? !empty($row['pubblicato']) : true;
$slug = (string) ($row['slug'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();
    $titolo = trim((string) ($_POST['titolo'] ?? ''));
    $riassunto = trim((string) ($_POST['riassunto'] ?? ''));
    $testo = trim((string) ($_POST['testo'] ?? ''));
    $dataPub = trim((string) ($_POST['data_pub'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    $pubblicato = isset($_POST['pubblicato']);
    $removeImage = isset($_POST['remove_image']);
    $immagine = $removeImage ? '' : (string) ($row['immagine'] ?? $immagine);

    $upload = aggiornamento_upload_image($_FILES['immagine'] ?? []);
    if (!$upload['ok']) {
        $error = (string) ($upload['error'] ?? 'Errore upload');
    } else {
        if (!empty($upload['path'])) {
            $old = (string) ($row['immagine'] ?? '');
            $immagine = $upload['path'];
            if ($old !== '' && $old !== $immagine && str_starts_with($old, '/assets/img/upload/aggiornamenti/')) {
                $oldPath = dirname(__DIR__) . $old;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }
        try {
            $savedId = aggiornamento_save($row ? (int) $row['id'] : null, [
                'titolo' => $titolo,
                'riassunto' => $riassunto,
                'testo' => $testo,
                'data_pub' => $dataPub,
                'immagine' => $immagine !== '' ? $immagine : null,
                'pubblicato' => $pubblicato,
                'slug' => $slug,
            ]);
            header('Location: /admin/aggiornamenti.php?ok=saved');
            exit;
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }
}

$csrf = admin_csrf();
admin_layout_start($row ? 'Modifica aggiornamento' : 'Nuovo aggiornamento', 'aggiornamenti');
?>
    <div class="page-head">
      <div>
        <p class="section-kicker section-kicker-news">Contenuti</p>
        <h1><?= $row ? 'Modifica aggiornamento' : 'Nuovo aggiornamento' ?></h1>
        <p class="sub"><a href="/admin/aggiornamenti.php">← Torna all’elenco</a></p>
      </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
      <p class="flash flash-ok">Salvato.</p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
      <p class="flash flash-err"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="editor-layout" id="aggiornamento-form">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
      <div>
        <section class="panel panel-accent-news">
          <h2>Contenuto</h2>
          <label class="field">
            <span>Titolo</span>
            <input type="text" name="titolo" required maxlength="200" value="<?= e($titolo) ?>">
          </label>
          <label class="field">
            <span>Riassunto card <span class="opt">(opzionale)</span></span>
            <textarea name="riassunto" rows="3" maxlength="320" placeholder="Se vuoto, in elenco viene mostrato il testo tagliato"><?= e($riassunto) ?></textarea>
            <em class="field-hint">Testo breve mostrato sulla card in elenco. Se lasciato vuoto, si usa un estratto automatico del testo.</em>
          </label>
          <div class="field">
            <span>Testo</span>
            <textarea id="testo" name="testo" hidden><?= e($testo) ?></textarea>
            <div class="rich-wrap">
              <div class="rich-toolbar" role="toolbar" aria-label="Formattazione testo">
                <button type="button" class="tiny ghost" data-rich-cmd="bold" title="Grassetto"><strong>G</strong></button>
                <button type="button" class="tiny ghost" data-rich-cmd="italic" title="Corsivo"><em>C</em></button>
                <button type="button" class="tiny ghost" data-rich-cmd="insertUnorderedList" title="Elenco puntato">• Lista</button>
                <button type="button" class="tiny ghost" data-rich-cmd="insertOrderedList" title="Elenco numerato">1. Lista</button>
                <button type="button" class="tiny ghost" data-rich-cmd="createLink" title="Inserisci link">Link</button>
                <button type="button" class="tiny ghost" data-rich-cmd="unlink" title="Togli link">Togli link</button>
                <button type="button" class="tiny ghost" data-rich-cmd="removeFormat" title="Pulisci formato">Pulisci</button>
              </div>
              <div class="rich-area" contenteditable="true" data-rich-area aria-label="Testo aggiornamento"></div>
            </div>
            <em class="field-hint">Grassetto, corsivo, elenchi e link. Il testo viene salvato in formato semplificato.</em>
          </div>
          <label class="field">
            <span>Slug URL <span class="opt">(opzionale)</span></span>
            <input type="text" name="slug" maxlength="180" value="<?= e($slug) ?>" placeholder="generato-dal-titolo">
          </label>
        </section>
      </div>
      <aside class="editor-side">
        <section class="panel">
          <h2>Pubblicazione</h2>
          <label class="field">
            <span>Data</span>
            <input type="date" name="data_pub" required value="<?= e($dataPub) ?>">
          </label>
          <label class="check-row">
            <input type="checkbox" name="pubblicato" value="1"<?= $pubblicato ? ' checked' : '' ?>>
            <span>Pubblicato sul sito</span>
          </label>
          <p class="save-row">
            <button type="submit" class="btn primary">Salva</button>
          </p>
        </section>
        <section class="panel">
          <h2>Immagine <span class="opt">(facoltativa)</span></h2>
          <?php if ($immagine !== ''): ?>
            <img class="preview-lg" src="<?= e($immagine) ?>" alt="">
            <label class="check-row">
              <input type="checkbox" name="remove_image" value="1">
              <span>Rimuovi immagine attuale</span>
            </label>
          <?php endif; ?>
          <div class="field">
            <span><?= $immagine !== '' ? 'Sostituisci' : 'Carica' ?></span>
            <label class="file-pick">
              <input type="file" name="immagine" accept="image/jpeg,image/png,image/webp,image/gif" data-file-pick>
              <span class="file-pick-btn">Scegli immagine</span>
              <span class="file-pick-name" data-file-name>Nessun file selezionato</span>
            </label>
          </div>
        </section>
      </aside>
    </form>
<?php admin_layout_end('<script src="/assets/js/admin-rich.js?v=20261109a"></script><script src="/assets/js/admin-filepick.js?v=20261109a"></script>'); ?>
