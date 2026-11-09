<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/prodotti.php';
admin_require();
$cats = categorie();
$all = prodotti_all();
$pub = count(array_filter($all, static fn ($p) => !empty($p['published'])));
$draft = count($all) - $pub;
$evidenza = evidenza_ids();
$csrf = admin_csrf();
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Catalogo | Backoffice iCash</title>
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
      <a class="btn-bar" href="/admin/edit.php">Nuovo prodotto</a>
      <a href="/" target="_blank" rel="noopener">Sito</a>
      <a href="/landing/registratori-telematici" target="_blank" rel="noopener">Landing registratori</a>
      <a href="/landing/bar-ristoranti" target="_blank" rel="noopener">Landing bar/ristoranti</a>
      <a href="/admin/logout.php">Esci</a>
    </nav>
  </header>
  <main class="adm-main">
    <div class="page-head">
      <div>
        <h1>Prodotti</h1>
        <p class="sub"><?= (int) count($all) ?> schede · <?= (int) $pub ?> visibili · <?= (int) $draft ?> nascoste</p>
      </div>
      <a class="btn primary" href="/admin/edit.php">Nuovo prodotto</a>
    </div>

    <section class="panel evidenza-bar" id="evidenza-bar">
      <div>
        <h2>In evidenza in home</h2>
        <p class="hint" style="margin:0">Seleziona fino a 4 prodotti pubblicati. Il salvataggio è immediato, senza ricaricare la pagina.</p>
      </div>
      <div class="evidenza-status">
        <span class="badge badge-on" id="evidenza-count"><?= (int) count($evidenza) ?> / 4</span>
        <span class="evidenza-msg" id="evidenza-msg" aria-live="polite"></span>
      </div>
    </section>

    <section class="panel">
      <h2>Landing page</h2>
      <p class="hint">Pagine dedicate per Ads e campagne. Si aprono in una nuova scheda.</p>
      <p class="save-row" style="margin:0">
        <a class="btn ghost" href="/landing/registratori-telematici" target="_blank" rel="noopener">Registratori telematici</a>
        <a class="btn ghost" href="/landing/bar-ristoranti" target="_blank" rel="noopener">Bar e ristoranti</a>
      </p>
    </section>

    <p class="hint" id="ordine-msg">L’ordine di questa tabella è quello del catalogo sul sito. Trascina la riga, usa le frecce oppure scrivi la posizione.</p>
    <div class="crm-table-wrap">
      <table>
        <thead>
          <tr>
            <th>Ordine</th>
            <th></th>
            <th>Home</th>
            <th>Prodotto</th>
            <th>Categoria</th>
            <th>Stato</th>
            <th>Azioni</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($all as $i => $p):
            $pid = (string) ($p['id'] ?? '');
            $isPub = !empty($p['published']);
            $isOn = in_array($pid, $evidenza, true);
            ?>
          <tr data-product-id="<?= e($pid) ?>">
            <td class="order-cell">
              <div class="order-tools">
                <span class="icon-btn drag-handle" draggable="true" title="Trascina" aria-label="Trascina <?= e(prodotto_label($p)) ?>" role="img">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M9 6a1.5 1.5 0 1 1-1.5-1.5A1.5 1.5 0 0 1 9 6zm7.5-1.5A1.5 1.5 0 1 0 18 6a1.5 1.5 0 0 0-1.5-1.5zM9 12a1.5 1.5 0 1 1-1.5-1.5A1.5 1.5 0 0 1 9 12zm7.5-1.5A1.5 1.5 0 1 0 18 12a1.5 1.5 0 0 0-1.5-1.5zM9 18a1.5 1.5 0 1 1-1.5-1.5A1.5 1.5 0 0 1 9 18zm7.5-1.5A1.5 1.5 0 1 0 18 18a1.5 1.5 0 0 0-1.5-1.5z"/></svg>
                </span>
                <label class="order-pos-label">
                  <span class="sr">Posizione</span>
                  <input class="order-pos" type="number" min="1" max="<?= (int) count($all) ?>" value="<?= (int) $i + 1 ?>" inputmode="numeric" aria-label="Posizione di <?= e(prodotto_label($p)) ?>">
                </label>
                <div class="order-nav">
                  <button type="button" class="icon-btn" data-move="first" title="Primo" aria-label="Porta <?= e(prodotto_label($p)) ?> in prima posizione">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6 5h12v2H6zm6 3 6 6h-4v5h-4v-5H6z"/></svg>
                  </button>
                  <button type="button" class="icon-btn" data-move="up" title="Su" aria-label="Sposta <?= e(prodotto_label($p)) ?> in su">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 6l7 7h-4v5h-6v-5H5z"/></svg>
                  </button>
                  <button type="button" class="icon-btn" data-move="down" title="Giù" aria-label="Sposta <?= e(prodotto_label($p)) ?> in giù">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 18l-7-7h4V6h6v5h4z"/></svg>
                  </button>
                  <button type="button" class="icon-btn" data-move="last" title="Ultimo" aria-label="Porta <?= e(prodotto_label($p)) ?> in ultima posizione">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6 17h12v2H6zm6-1 6-6h-4v-5h-4v5H6z"/></svg>
                  </button>
                </div>
              </div>
            </td>
            <td>
              <?php if (!empty($p['image'])): ?>
                <img class="thumb-sm" src="<?= e((string) $p['image']) ?>" alt="">
              <?php endif; ?>
            </td>
            <td>
              <label class="evidenza-toggle" title="<?= $isPub ? 'Mostra in home' : 'Pubblica il prodotto per metterlo in evidenza' ?>">
                <input
                  type="checkbox"
                  class="evidenza-check"
                  value="<?= e($pid) ?>"
                  <?= $isOn ? 'checked' : '' ?>
                  <?= $isPub ? '' : 'disabled' ?>
                >
                <span><?= $isOn ? 'Sì' : '—' ?></span>
              </label>
            </td>
            <td>
              <strong><?= e(prodotto_label($p)) ?></strong><br>
              <span style="color:#5b6577;font-size:0.85rem">/prodotto/<?= e($pid) ?></span>
            </td>
            <td><?= e($cats[$p['category']] ?? (string) $p['category']) ?></td>
            <td>
              <?php if ($isPub): ?>
                <span class="badge badge-on">Visibile</span>
              <?php else: ?>
                <span class="badge badge-off">Nascosto</span>
              <?php endif; ?>
              <form method="post" action="/admin/visibilita-save.php" class="vis-form">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <input type="hidden" name="id" value="<?= e($pid) ?>">
                <input type="hidden" name="published" value="<?= $isPub ? '0' : '1' ?>">
                <button type="submit" class="ghost tiny vis-btn"<?= $isPub ? ' onclick="return confirm(\'Oscurare questo prodotto? Esce da catalogo e sitemap, e la scheda pubblica risponde 404.\')"' : '' ?>><?= $isPub ? 'Oscura' : 'Ripubblica' ?></button>
              </form>
            </td>
            <td>
              <div class="row-actions">
                <a class="icon-btn" href="/admin/edit.php?id=<?= e(rawurlencode($pid)) ?>" title="Modifica" aria-label="Modifica <?= e(prodotto_label($p)) ?>">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M4 16.8V20h3.2L17.4 9.8l-3.2-3.2zM19.7 7.5a1 1 0 0 0 0-1.4l-1.8-1.8a1 1 0 0 0-1.4 0l-1.3 1.3 3.2 3.2z"/></svg>
                </a>
                <a class="icon-btn" href="/prodotto/<?= e($pid) ?>" target="_blank" rel="noopener" title="Vedi" aria-label="Vedi <?= e(prodotto_label($p)) ?>">
                  <svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M12 6.5C7.5 6.5 3.7 9.2 2 12c1.7 2.8 5.5 5.5 10 5.5s8.3-2.7 10-5.5c-1.7-2.8-5.5-5.5-10-5.5zm0 9a3.5 3.5 0 1 1 3.5-3.5A3.5 3.5 0 0 1 12 15.5zm0-5.5a2 2 0 1 0 2 2 2 2 0 0 0-2-2z"/></svg>
                </a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
  <script>
    window.EVIDENZA = {
      csrf: <?= json_encode($csrf, JSON_UNESCAPED_UNICODE) ?>,
      max: 4,
      ids: <?= json_encode($evidenza, JSON_UNESCAPED_UNICODE) ?>
    };
  </script>
  <script>
    window.ORDINE = { csrf: <?= json_encode($csrf, JSON_UNESCAPED_UNICODE) ?> };
  </script>
  <script src="/assets/js/admin-evidenza.js?v=20260327x"></script>
  <script src="/assets/js/admin-ordine.js?v=20260328h"></script>
</body>
</html>
