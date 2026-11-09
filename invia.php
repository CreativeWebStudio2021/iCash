<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /contatti');
    exit;
}

$honeypot = trim((string) ($_POST['sito_web'] ?? ''));
$ts = (int) ($_POST['ts'] ?? 0);
$tooFast = $ts > 0 && (time() - $ts) < 3;
if ($honeypot !== '' || $tooFast) {
    header('Location: /contatti?inviato=1');
    exit;
}

$nome = trim((string) ($_POST['nome'] ?? ''));
$telefono = trim((string) ($_POST['telefono'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$attivita = trim((string) ($_POST['attivita'] ?? ''));
$messaggio = trim((string) ($_POST['messaggio'] ?? ''));
$privacy = (string) ($_POST['privacy'] ?? '');
$contesto = trim((string) ($_POST['contesto'] ?? 'contatti'));
$prodotto = trim((string) ($_POST['prodotto'] ?? ''));

require_once __DIR__ . '/inc/lead.php';
$route = lead_route($contesto);
$back = $route['back'];

if ($nome === '' || $telefono === '' || $messaggio === '' || $privacy !== '1') {
    header('Location: ' . $back . '?errore=campi');
    exit;
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ' . $back . '?errore=campi');
    exit;
}

require_once __DIR__ . '/inc/config.php';
if (is_production_host()) {
    require_once __DIR__ . '/inc/recaptcha.php';
    $token = trim((string) ($_POST['recaptcha'] ?? ''));
    if (!recaptcha_ok($token, $_SERVER['REMOTE_ADDR'] ?? '')) {
        header('Location: ' . $back . '?errore=captcha');
        exit;
    }
}

$dir = dirname(__DIR__) === '/var/www' ? __DIR__ . '/data' : __DIR__ . '/data';
if (!is_dir($dir)) {
    mkdir($dir, 0750, true);
}
$record = [
    'at' => date('c'),
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    'contesto' => mb_substr($contesto, 0, 80),
    'prodotto' => mb_substr($prodotto, 0, 160),
    'nome' => mb_substr($nome, 0, 120),
    'telefono' => mb_substr($telefono, 0, 40),
    'email' => mb_substr($email, 0, 160),
    'attivita' => mb_substr($attivita, 0, 40),
    'messaggio' => mb_substr($messaggio, 0, 2000),
];
$ok = file_put_contents($dir . '/richieste.jsonl', json_encode($record, JSON_UNESCAPED_UNICODE) . "\n", FILE_APPEND | LOCK_EX);
if ($ok === false) {
    header('Location: ' . $back . '?errore=invio');
    exit;
}
require_once __DIR__ . '/inc/smtp.php';
require_once __DIR__ . '/inc/mail-template.php';
$subject = 'Richiesta dal sito' . ($prodotto !== '' ? ' — ' . $prodotto : '');
$mail = mail_lead_template($record);
try {
    smtp_send(mb_substr($subject, 0, 180), $mail['plain'], $record['email'], null, $mail['html']);
} catch (Throwable $e) {
    header('Location: ' . $back . '?errore=invio');
    exit;
}
$receipt = mail_receipt_template($record);
try {
    smtp_send('Abbiamo ricevuto la tua richiesta', $receipt['plain'], MAIL_PUBLIC, $record['email'], $receipt['html']);
} catch (Throwable $e) {
    // La richiesta è già arrivata in azienda: la conferma al cliente non blocca la thank-you page.
}
header('Location: ' . lead_thanks_url($route['da']));
