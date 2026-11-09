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
