<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/prodotti.php';
admin_require();

$id = trim((string) ($_GET['id'] ?? ''));
$items = prodotti_all();
$index = null;
$product = [
    'id' => '',
    'brand' => '',
    'model' => '',
    'category' => 'registratori',
    'audience' => [],
    'image' => '',
    'alt' => '',
    'summary' => '',
    'pdf' => '',
    'gallery' => [],
    'published' => true,
    'blocks' => [
        ['type' => 'testo', 'titolo' => 'Descrizione', 'testo' => ''],
        ['type' => 'elenco', 'titolo' => 'Caratteristiche', 'voci' => ['']],
    ],
];
foreach ($items as $i => $p) {
    if (($p['id'] ?? '') === $id && $id !== '') {
        $index = $i;
        $product = $p;
        break;
    }
}
if ($id !== '' && $index === null) {
    http_response_code(404);
    exit('Prodotto non trovato');
}

function admin_store_pdf(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 12_000_000) {
        return null;
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if ($finfo->file($file['tmp_name']) !== 'application/pdf') {
        return null;
    }
    $dir = dirname(__DIR__) . '/assets/pdf';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $name = bin2hex(random_bytes(8)) . '.pdf';
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
        return null;
    }
    return '/assets/pdf/' . $name;
}

function admin_store_upload(array $file): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || ($file['size'] ?? 0) > 4_000_000) {
        return null;
    }
    $info = @getimagesize($file['tmp_name']);
    $map = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($map[$info[2]])) {
        return null;
    }
    $dir = dirname(__DIR__) . '/assets/img/upload';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    $name = bin2hex(random_bytes(8)) . '.' . $map[$info[2]];
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $name)) {
        return null;
    }
    return '/assets/img/upload/' . $name;
}

function admin_block_label(string $type): string
{
    return match ($type) {
        'testo' => 'Testo',
        'elenco' => 'Elenco',
        'specifiche' => 'Specifiche',
        'immagine' => 'Immagine',
        default => $type,
    };
}

$errore = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    admin_csrf_check();
    if (isset($_POST['delete']) && $index !== null) {
        array_splice($items, $index, 1);
        prodotti_save($items);
        header('Location: /admin/index.php');
        exit;
    }
    $brand = trim((string) ($_POST['brand'] ?? ''));
    $model = trim((string) ($_POST['model'] ?? ''));
    $category = (string) ($_POST['category'] ?? 'registratori');
    if (!isset(categorie()[$category])) {
        $category = 'registratori';
    }
    $audience = array_values(array_intersect((array) ($_POST['audience'] ?? []), ['negozi', 'ristorazione']));
    $summary = trim((string) ($_POST['summary'] ?? ''));
    $alt = trim((string) ($_POST['alt'] ?? ''));
    $slug = trim((string) ($_POST['slug'] ?? ''));
    $slug = strtolower((string) preg_replace('/[^a-z0-9-]+/', '-', $slug));
    $slug = trim($slug, '-');
    if ($brand === '' || $model === '' || $summary === '' || $slug === '') {
        $errore = 'Marca, modello, indirizzo della scheda e descrizione breve sono obbligatori.';
    }
    foreach ($items as $i => $p) {
        if (($p['id'] ?? '') === $slug && $i !== $index) {
            $errore = 'Esiste già un prodotto con questo indirizzo.';
        }
    }
    $image = (string) ($product['image'] ?? '');
    if (!empty($_FILES['cover']['tmp_name']) && is_uploaded_file($_FILES['cover']['tmp_name'])) {
        $saved = admin_store_upload($_FILES['cover']);
        if ($saved === null) {
            $errore = 'Immagine di copertina non valida. Usa JPG, PNG o WebP entro 4 MB.';
        } else {
            $image = $saved;
        }
    }

    $blocks = [];
    $types = (array) ($_POST['block_type'] ?? []);
    $titles = (array) ($_POST['block_titolo'] ?? []);
    $texts = (array) ($_POST['block_testo'] ?? []);
    $lists = (array) ($_POST['block_voci'] ?? []);
    $alts = (array) ($_POST['block_alt'] ?? []);
    $caps = (array) ($_POST['block_didascalia'] ?? []);
    $srcs = (array) ($_POST['block_src'] ?? []);
    foreach ($types as $n => $type) {
        if (!in_array($type, ['testo', 'elenco', 'specifiche', 'immagine'], true)) {
            continue;
        }
        $block = ['type' => $type, 'titolo' => trim((string) ($titles[$n] ?? ''))];
        if ($type === 'testo') {
            $block['testo'] = html_sanitize_rich(trim((string) ($texts[$n] ?? '')));
        } elseif ($type === 'elenco' || $type === 'specifiche') {
            $voci = preg_split("/\r\n|\n|\r/", (string) ($lists[$n] ?? '')) ?: [];
            $block['voci'] = array_values(array_filter(array_map('trim', $voci), static fn ($v) => $v !== ''));
        } else {
            $block['src'] = (string) ($srcs[$n] ?? '');
            $block['alt'] = trim((string) ($alts[$n] ?? ''));
            $block['didascalia'] = trim((string) ($caps[$n] ?? ''));
            $fileKey = 'block_file_' . $n;
            if (!empty($_FILES[$fileKey]['tmp_name']) && is_uploaded_file($_FILES[$fileKey]['tmp_name'])) {
                $saved = admin_store_upload($_FILES[$fileKey]);
                if ($saved === null) {
                    $errore = 'Una immagine di blocco non è valida.';
                } else {
                    $block['src'] = $saved;
                }
            }
        }
        $blocks[] = $block;
    }

    $pdf = (string) ($product['pdf'] ?? '');
    if (isset($_POST['remove_pdf'])) {
        $pdf = '';
    }
    if (!empty($_FILES['scheda']['tmp_name']) && is_uploaded_file($_FILES['scheda']['tmp_name'])) {
        $savedPdf = admin_store_pdf($_FILES['scheda']);
        if ($savedPdf === null) {
            $errore = 'Il file della scheda deve essere un PDF entro 12 MB.';
        } else {
            $pdf = $savedPdf;
        }
    }

    $gallery = [];
    $existingSrc = (array) ($_POST['gallery_src'] ?? []);
    $existingAlt = (array) ($_POST['gallery_alt'] ?? []);
    foreach ($existingSrc as $gi => $src) {
        $src = trim((string) $src);
        if ($src === '') {
            continue;
        }
        $gallery[] = [
            'src' => $src,
            'alt' => trim((string) ($existingAlt[$gi] ?? '')),
        ];
    }
    if (!empty($_FILES['gallery_files']['name']) && is_array($_FILES['gallery_files']['name'])) {
        $count = count($_FILES['gallery_files']['name']);
        for ($gi = 0; $gi < $count; $gi++) {
            if (($_FILES['gallery_files']['error'][$gi] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $one = [
                'name' => (string) $_FILES['gallery_files']['name'][$gi],
                'type' => (string) ($_FILES['gallery_files']['type'][$gi] ?? ''),
                'tmp_name' => (string) $_FILES['gallery_files']['tmp_name'][$gi],
                'error' => (int) $_FILES['gallery_files']['error'][$gi],
                'size' => (int) $_FILES['gallery_files']['size'][$gi],
            ];
            $saved = admin_store_upload($one);
            if ($saved === null) {
                $errore = 'Una foto della gallery non è valida. Usa JPG, PNG o WebP entro 4 MB.';
            } else {
                $gallery[] = [
                    'src' => $saved,
                    'alt' => $brand . ' ' . $model,
                ];
            }
        }
    }

    if ($errore === '') {
        $product = [
            'id' => $slug,
            'brand' => $brand,
            'model' => $model,
            'category' => $category,
            'audience' => $audience,
            'image' => $image,
            'alt' => $alt !== '' ? $alt : $brand . ' ' . $model,
            'summary' => $summary,
            'meta_title' => trim((string) ($_POST['meta_title'] ?? '')),
            'meta_description' => trim((string) ($_POST['meta_description'] ?? '')),
            'pdf' => $pdf,
            'gallery' => $gallery,
            'updated' => date('c'),
            'published' => isset($_POST['published']),
            'blocks' => $blocks,
        ];
        if ($index === null) {
            $items[] = $product;
        } else {
            $items[$index] = $product;
        }
        prodotti_save($items);
        if (empty($product['published'])) {
            evidenza_save(evidenza_ids());
        }
        header('Location: /admin/edit.php?id=' . rawurlencode($slug) . '&ok=1');
        exit;
    }
    $product = [
        'id' => $slug,
        'brand' => $brand,
        'model' => $model,
        'category' => $category,
        'audience' => $audience,
        'image' => $image,
        'alt' => $alt,
        'summary' => $summary,
        'meta_title' => trim((string) ($_POST['meta_title'] ?? '')),
        'meta_description' => trim((string) ($_POST['meta_description'] ?? '')),
        'pdf' => $pdf,
        'gallery' => $gallery,
        'published' => isset($_POST['published']),
        'blocks' => $blocks,
    ];
}

$cats = categorie();
$csrf = admin_csrf();
$isNew = $index === null;
$titleName = $isNew ? 'Nuovo prodotto' : prodotto_label($product);
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($titleName) ?> | Backoffice iCash</title>
  <?php admin_favicons(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/admin.css?v=20261105a">
</head>
<body class="adm-shell">
  <header class="bar">
    <a class="brand" href="/admin/index.php" title="Catalogo iCash">
      <img src="/assets/img/brand/logo-white.png?v=20260328" alt="iCash" width="120" height="60">
    </a>
    <nav>
      <a href="/admin/index.php">Elenco prodotti</a>
      <?php if (!$isNew): ?>
        <a href="/prodotto/<?= e((string) $product['id']) ?>" target="_blank" rel="noopener">Anteprima pubblica</a>
      <?php endif; ?>
      <a href="/landing/registratori-telematici" target="_blank" rel="noopener">Landing registratori</a>
      <a href="/landing/bar-ristoranti" target="_blank" rel="noopener">Landing bar/ristoranti</a>
      <a href="/admin/logout.php">Esci</a>
    </nav>
  </header>

  <main class="adm-main">
    <div class="page-head">
      <div>
        <h1><?= e($titleName) ?></h1>
        <p class="sub"><?= $isNew ? 'Crea una nuova scheda catalogo' : 'Modifica scheda e contenuti a blocchi' ?></p>
      </div>
    </div>

    <?php if (isset($_GET['ok'])): ?><p class="ok">Scheda salvata correttamente.</p><?php endif; ?>
    <?php if ($errore !== ''): ?><p class="err"><?= e($errore) ?></p><?php endif; ?>

    <form method="post" enctype="multipart/form-data" id="product-form" class="editor-layout">
      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

      <div class="editor-main">
        <section class="panel">
          <h2>Anagrafica</h2>
          <div class="grid two">
            <label>Marca <input name="brand" value="<?= e((string) $product['brand']) ?>" required></label>
            <label>Modello <input name="model" value="<?= e((string) $product['model']) ?>" required></label>
            <label>Indirizzo scheda (URL)
              <input name="slug" value="<?= e((string) $product['id']) ?>" required pattern="[a-z0-9-]+" title="Solo lettere minuscole, numeri e trattini">
            </label>
            <label>Categoria
              <select name="category">
                <?php foreach ($cats as $key => $label): ?>
                  <option value="<?= e($key) ?>" <?= ($product['category'] ?? '') === $key ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <p class="hint" style="margin-top:0.2rem">Visibilità e canali</p>
          <div class="checks">
            <label class="inline"><input type="checkbox" name="audience[]" value="negozi" <?= in_array('negozi', $product['audience'] ?? [], true) ? 'checked' : '' ?>> Negozi</label>
            <label class="inline"><input type="checkbox" name="audience[]" value="ristorazione" <?= in_array('ristorazione', $product['audience'] ?? [], true) ? 'checked' : '' ?>> Bar e ristoranti</label>
            <label class="inline"><input type="checkbox" name="published" <?= !empty($product['published']) ? 'checked' : '' ?>> Visibile sul sito</label>
          </div>
          <p class="hint">Se togli la spunta e salvi, il prodotto esce da catalogo, caroselli, home e sitemap. L’indirizzo pubblico risponde 404.</p>
          <label>Descrizione breve (card catalogo)
            <textarea name="summary" rows="3" required><?= e((string) $product['summary']) ?></textarea>
          </label>
        </section>

        <section class="panel">
          <h2>SEO</h2>
          <label>Meta title <span class="opt">se vuoto: marca e modello</span>
            <input name="meta_title" maxlength="70" value="<?= e((string) ($product['meta_title'] ?? '')) ?>">
          </label>
          <label>Meta description <span class="opt">se vuota: descrizione breve</span>
            <textarea name="meta_description" rows="2" maxlength="170"><?= e((string) ($product['meta_description'] ?? '')) ?></textarea>
          </label>
        </section>

        <section class="panel">
          <h2>Media</h2>
          <label>Testo alternativo copertina <input name="alt" value="<?= e((string) ($product['alt'] ?? '')) ?>"></label>
          <label>Nuova copertina <input type="file" name="cover" accept="image/jpeg,image/png,image/webp"></label>
          <?php if (!empty($product['image'])): ?>
            <div class="cover-preview"><img class="thumb" src="<?= e((string) $product['image']) ?>" alt=""></div>
          <?php endif; ?>

          <h2 style="margin-top:1.2rem">Gallery</h2>
          <p class="hint">Foto aggiuntive in scheda pubblica. Riordina o rimuovi senza ricaricare; le nuove foto si caricano al salvataggio.</p>
          <div class="gallery-list" id="gallery-list">
            <?php foreach ($product['gallery'] ?? [] as $gi => $gItem): ?>
              <article class="gallery-edit" data-index="<?= (int) $gi ?>">
                <img class="thumb" src="<?= e((string) ($gItem['src'] ?? '')) ?>" alt="">
                <div>
                  <input type="hidden" name="gallery_src[]" value="<?= e((string) ($gItem['src'] ?? '')) ?>">
                  <label>Testo alternativo <input name="gallery_alt[]" value="<?= e((string) ($gItem['alt'] ?? '')) ?>"></label>
                </div>
                <div class="gallery-tools">
                  <button type="button" class="tiny ghost" data-gallery-action="up">Su</button>
                  <button type="button" class="tiny ghost" data-gallery-action="down">Giù</button>
                  <button type="button" class="tiny danger" data-gallery-action="remove">Rimuovi</button>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
          <label>Aggiungi foto
            <input id="gallery-files" type="file" name="gallery_files[]" accept="image/jpeg,image/png,image/webp" multiple>
          </label>

          <h2 style="margin-top:1.2rem">PDF scheda tecnica</h2>
          <p class="hint">File scaricabile dalla scheda pubblica. I contenuti testuali vanno nei blocchi (es. Specifiche).</p>
          <label>Carica o sostituisci PDF <input type="file" name="scheda" accept="application/pdf"></label>
          <?php if (!empty($product['pdf'])): ?>
            <p>
              <a href="<?= e((string) $product['pdf']) ?>" target="_blank" rel="noopener">Apri PDF attuale</a>
              · <label class="inline"><input type="checkbox" name="remove_pdf" value="1"> Rimuovi PDF</label>
            </p>
          <?php endif; ?>
        </section>

        <section class="panel">
          <h2>Contenuti a blocchi</h2>
          <p class="hint">Descrizione, elenchi, specifiche e immagini della scheda. Aggiungi e riordina senza ricaricare la pagina.</p>
          <div id="blocks-empty" class="empty-blocks" <?= !empty($product['blocks']) ? 'hidden' : '' ?>>Nessun blocco. Aggiungine uno qui sotto.</div>
          <div class="blocks-list" id="blocks-list">
            <?php foreach ($product['blocks'] ?? [] as $n => $block):
                $type = (string) ($block['type'] ?? 'testo');
                $vociText = ($type === 'elenco' || $type === 'specifiche') ? implode("\n", $block['voci'] ?? []) : '';
                ?>
              <article class="block-edit" data-type="<?= e($type) ?>" data-index="<?= (int) $n ?>">
                <header>
                  <span class="block-type" data-type-label><?= e(admin_block_label($type)) ?></span>
                  <div class="block-tools">
                    <button type="button" class="tiny ghost" data-block-action="up">Su</button>
                    <button type="button" class="tiny ghost" data-block-action="down">Giù</button>
                    <button type="button" class="tiny danger" data-block-action="remove">Rimuovi</button>
                  </div>
                </header>
                <input type="hidden" name="block_type[]" value="<?= e($type) ?>">
                <label>Titolo <input name="block_titolo[]" value="<?= e((string) ($block['titolo'] ?? '')) ?>"></label>

                <div data-for="testo" class="rich-wrap" <?= $type === 'testo' ? '' : 'hidden' ?>>
                  <label>Testo</label>
                  <div class="rich-toolbar" role="toolbar" aria-label="Formattazione testo">
                    <button type="button" class="tiny ghost" data-rich-cmd="bold" title="Grassetto"><strong>G</strong></button>
                    <button type="button" class="tiny ghost" data-rich-cmd="italic" title="Corsivo"><em>C</em></button>
                    <button type="button" class="tiny ghost" data-rich-cmd="insertUnorderedList" title="Elenco puntato">• Lista</button>
                    <button type="button" class="tiny ghost" data-rich-cmd="insertOrderedList" title="Elenco numerato">1. Lista</button>
                    <button type="button" class="tiny ghost" data-rich-cmd="createLink" title="Inserisci link">Link</button>
                    <button type="button" class="tiny ghost" data-rich-cmd="unlink" title="Togli link">Togli link</button>
                    <button type="button" class="tiny ghost" data-rich-cmd="removeFormat" title="Pulisci formato">Pulisci</button>
                  </div>
                  <div class="rich-area" contenteditable="true" data-rich-area <?= $type === 'testo' ? '' : 'hidden' ?>></div>
                </div>
                <textarea data-field="testo" name="block_testo[]" rows="5" hidden><?= e($type === 'testo' ? (string) ($block['testo'] ?? '') : '') ?></textarea>

                <div data-for="elenco" <?= $type === 'elenco' ? '' : 'hidden' ?>>
                  <label>Voci (una per riga)</label>
                </div>
                <div data-for="specifiche" <?= $type === 'specifiche' ? '' : 'hidden' ?>>
                  <label>Specifiche (Etichetta: valore)</label>
                </div>
                <textarea data-field="voci" name="block_voci[]" rows="8" placeholder="<?= $type === 'specifiche' ? 'Una riga per dato, formato Etichetta: valore' : 'Una voce per riga' ?>" <?= ($type === 'elenco' || $type === 'specifiche') ? '' : 'hidden' ?>><?= e($vociText) ?></textarea>

                <div data-for="immagine" <?= $type === 'immagine' ? '' : 'hidden' ?>>
                  <?php if (!empty($block['src']) && $type === 'immagine'): ?>
                    <img class="thumb" data-img-preview src="<?= e((string) $block['src']) ?>" alt="">
                  <?php else: ?>
                    <img class="thumb" data-img-preview src="" alt="" hidden>
                  <?php endif; ?>
                  <label>Immagine <input type="file" data-block-file name="block_file_<?= (int) $n ?>" accept="image/jpeg,image/png,image/webp" <?= $type === 'immagine' ? '' : 'disabled' ?>></label>
                </div>
                <input type="hidden" data-field="src" name="block_src[]" value="<?= e($type === 'immagine' ? (string) ($block['src'] ?? '') : '') ?>">
                <label data-for-field="alt" <?= $type === 'immagine' ? '' : 'hidden' ?>>Testo alternativo
                  <input data-field="alt" name="block_alt[]" value="<?= e($type === 'immagine' ? (string) ($block['alt'] ?? '') : '') ?>">
                </label>
                <label data-for-field="didascalia" <?= $type === 'immagine' ? '' : 'hidden' ?>>Didascalia
                  <input data-field="didascalia" name="block_didascalia[]" value="<?= e($type === 'immagine' ? (string) ($block['didascalia'] ?? '') : '') ?>">
                </label>
              </article>
            <?php endforeach; ?>
          </div>

          <div class="add-row">
            <span class="hint" style="margin:0 0.4rem 0 0">Aggiungi blocco:</span>
            <button type="button" class="ghost" data-add-block="testo">Testo</button>
            <button type="button" class="ghost" data-add-block="elenco">Elenco</button>
            <button type="button" class="ghost" data-add-block="specifiche">Specifiche</button>
            <button type="button" class="ghost" data-add-block="immagine">Immagine</button>
          </div>
        </section>

        <div class="save-dock">
          <p class="save-row">
            <button type="submit" class="primary" name="save" value="1">Salva scheda</button>
            <?php if (!$isNew): ?>
              <button type="submit" name="delete" value="1" class="danger" onclick="return confirm('Eliminare questo prodotto dal catalogo?')">Elimina</button>
              <a class="btn ghost" href="/prodotto/<?= e((string) $product['id']) ?>" target="_blank" rel="noopener">Apri scheda pubblica</a>
            <?php endif; ?>
            <a class="btn ghost" href="/admin/index.php">Torna all’elenco</a>
          </p>
        </div>
      </div>

      <aside class="editor-side">
        <section class="panel side-card">
          <h2>Stato</h2>
          <p class="status-line"><?= !empty($product['published']) ? 'Visibile sul sito' : 'Nascosto: pagina 404, fuori dalla sitemap' ?></p>
          <p><?= e($cats[$product['category'] ?? ''] ?? '') ?></p>
          <?php if (!empty($product['updated'])): ?>
            <p>Aggiornato: <?= e(date('d/m/Y H:i', strtotime((string) $product['updated']))) ?></p>
          <?php endif; ?>
          <p>I contenuti della scheda pubblica arrivano dai blocchi. Il PDF resta un allegato scaricabile.</p>
        </section>
      </aside>
    </form>
  </main>

  <template id="tpl-block">
    <article class="block-edit" data-type="" data-index="0">
      <header>
        <span class="block-type" data-type-label></span>
        <div class="block-tools">
          <button type="button" class="tiny ghost" data-block-action="up">Su</button>
          <button type="button" class="tiny ghost" data-block-action="down">Giù</button>
          <button type="button" class="tiny danger" data-block-action="remove">Rimuovi</button>
        </div>
      </header>
      <input type="hidden" name="block_type[]" value="">
      <label>Titolo <input name="block_titolo[]" value=""></label>
      <div data-for="testo" class="rich-wrap" hidden>
        <label>Testo</label>
        <div class="rich-toolbar" role="toolbar" aria-label="Formattazione testo">
          <button type="button" class="tiny ghost" data-rich-cmd="bold" title="Grassetto"><strong>G</strong></button>
          <button type="button" class="tiny ghost" data-rich-cmd="italic" title="Corsivo"><em>C</em></button>
          <button type="button" class="tiny ghost" data-rich-cmd="insertUnorderedList" title="Elenco puntato">• Lista</button>
          <button type="button" class="tiny ghost" data-rich-cmd="insertOrderedList" title="Elenco numerato">1. Lista</button>
          <button type="button" class="tiny ghost" data-rich-cmd="createLink" title="Inserisci link">Link</button>
          <button type="button" class="tiny ghost" data-rich-cmd="unlink" title="Togli link">Togli link</button>
          <button type="button" class="tiny ghost" data-rich-cmd="removeFormat" title="Pulisci formato">Pulisci</button>
        </div>
        <div class="rich-area" contenteditable="true" data-rich-area hidden></div>
      </div>
      <textarea data-field="testo" name="block_testo[]" rows="5" hidden></textarea>
      <div data-for="elenco" hidden><label>Voci (una per riga)</label></div>
      <div data-for="specifiche" hidden><label>Specifiche (Etichetta: valore)</label></div>
      <textarea data-field="voci" name="block_voci[]" rows="8" hidden></textarea>
      <div data-for="immagine" hidden>
        <img class="thumb" data-img-preview src="" alt="" hidden>
        <label>Immagine <input type="file" data-block-file name="block_file_0" accept="image/jpeg,image/png,image/webp" disabled></label>
      </div>
      <input type="hidden" data-field="src" name="block_src[]" value="">
      <label hidden data-for-field="alt">Testo alternativo
        <input data-field="alt" name="block_alt[]" value="">
      </label>
      <label hidden data-for-field="didascalia">Didascalia
        <input data-field="didascalia" name="block_didascalia[]" value="">
      </label>
    </article>
  </template>

  <script src="/assets/js/admin-edit.js?v=20260327s"></script>
</body>
</html>
