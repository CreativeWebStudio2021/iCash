<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/prodotti.php';

admin_require();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /admin/index.php');
    exit;
}

admin_csrf_check();

$id = trim((string) ($_POST['id'] ?? ''));
$published = (string) ($_POST['published'] ?? '') === '1';

try {
    prodotto_set_published($id, $published);
} catch (Throwable $e) {
    http_response_code(400);
    exit('Prodotto non trovato');
}

header('Location: /admin/index.php');
exit;
