<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/inc/config.php';
require_once dirname(__DIR__) . '/inc/admin.secret.php';

session_name('icash_admin');
session_start();

function admin_ok(): bool
{
    return !empty($_SESSION['admin']);
}

function admin_favicons(): void
{
    echo '<link rel="icon" href="/assets/favicon.ico?v=20260328" sizes="any">' . "\n";
    echo '  <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32.png?v=20260328">' . "\n";
    echo '  <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png?v=20260328">' . "\n";
    echo '  <meta name="robots" content="noindex, nofollow">' . "\n";
}

function admin_require(): void
{
    if (!admin_ok()) {
        header('Location: /admin/login.php');
        exit;
    }
}

function admin_csrf(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return $_SESSION['csrf'];
}

function admin_csrf_check(): void
{
    $sent = (string) ($_POST['csrf'] ?? '');
    if ($sent === '' || !hash_equals(admin_csrf(), $sent)) {
        http_response_code(400);
        exit('Richiesta non valida');
    }
}

function admin_layout_start(string $title, string $section = 'prodotti'): void
{
    $sec = $section;
    ?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?> | Backoffice iCash</title>
  <?php admin_favicons(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/admin.css?v=20261109f">
</head>
<body class="adm-shell">
  <header class="bar">
    <a class="brand" href="/admin/index.php" title="Backoffice iCash">
      <img src="/assets/img/brand/logo-white.png?v=20260328" alt="iCash" width="120" height="60">
      <span class="brand-tag">Backoffice</span>
    </a>
    <nav class="bar-nav" aria-label="Sezioni admin">
      <a class="nav-pill<?= $sec === 'prodotti' ? ' is-on' : '' ?>" href="/admin/index.php">Prodotti</a>
      <a class="nav-pill<?= $sec === 'aggiornamenti' ? ' is-on' : '' ?>" href="/admin/aggiornamenti.php">Aggiornamenti</a>
      <a href="/" target="_blank" rel="noopener">Sito</a>
      <a href="/admin/logout.php">Esci</a>
    </nav>
  </header>
  <main class="adm-main">
<?php
}

function admin_layout_end(string $extraScripts = ''): void
{
    ?>
  </main>
  <?= $extraScripts ?>
</body>
</html>
<?php
}
