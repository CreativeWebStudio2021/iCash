<?php
declare(strict_types=1);
require __DIR__ . '/auth.php';
require_once dirname(__DIR__) . '/inc/prodotti.php';

header('Content-Type: application/json; charset=utf-8');

if (!admin_ok()) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'error' => 'Non autenticato'], JSON_UNESCAPED_UNICODE);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Metodo non consentito'], JSON_UNESCAPED_UNICODE);
    exit;
}

$raw = file_get_contents('php://input');
$payload = json_decode($raw === false ? '' : $raw, true);
if (!is_array($payload)) {
    $payload = $_POST;
}

$csrf = (string) ($payload['csrf'] ?? '');
if ($csrf === '' || !hash_equals(admin_csrf(), $csrf)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Richiesta non valida'], JSON_UNESCAPED_UNICODE);
    exit;
}

$ids = $payload['ids'] ?? [];
if (!is_array($ids)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Dati non validi'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    prodotti_reorder(array_map('strval', $ids));
    echo json_encode(['ok' => true, 'message' => 'Ordine del catalogo aggiornato'], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Salvataggio non riuscito'], JSON_UNESCAPED_UNICODE);
}
