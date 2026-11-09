<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
if (admin_ok()) {
    header('Location: /admin/index.php');
    exit;
}
$errore = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim((string) ($_POST['user'] ?? ''));
    $pass = (string) ($_POST['pass'] ?? '');
    $hash = ADMIN_USERS[$user] ?? '';
    if ($hash !== '' && password_verify($pass, $hash)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        header('Location: /admin/index.php');
        exit;
    }
    $errore = true;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Accesso catalogo | iCash</title>
  <?php admin_favicons(); ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/admin.css?v=20261109f">
</head>
<body class="admin-login">
  <form method="post" class="panel">
    <img class="login-logo" src="/assets/img/brand/logo.png?v=20260328" alt="iCash" width="160" height="80">
    <h1>Backoffice iCash</h1>
    <?php if ($errore): ?><p class="err">Utente o password non corretti.</p><?php endif; ?>
    <label>Utente <input name="user" autocomplete="username" required></label>
    <label>Password <input name="pass" type="password" autocomplete="current-password" required></label>
    <button type="submit">Entra</button>
  </form>
</body>
</html>
