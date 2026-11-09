<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/aggiornamenti.php';

admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/aggiornamenti.php');
    exit;
}

admin_csrf_check();

$id = (int) ($_POST['id'] ?? 0);
$published = (string) ($_POST['published'] ?? '') === '1';

try {
    aggiornamento_set_published($id, $published);
    header('Location: /admin/aggiornamenti.php?ok=vis');
    exit;
} catch (Throwable $e) {
    header('Location: /admin/aggiornamenti.php?err=' . rawurlencode($e->getMessage()));
    exit;
}
