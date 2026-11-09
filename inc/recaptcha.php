<?php
declare(strict_types=1);

require_once __DIR__ . '/recaptcha.secret.php';

function recaptcha_ok(string $token, string $remoteIp = ''): bool
{
    if ($token === '') {
        return false;
    }
    $payload = [
        'secret' => RECAPTCHA_SECRET,
        'response' => $token,
    ];
    if ($remoteIp !== '') {
        $payload['remoteip'] = $remoteIp;
    }
    $ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
    if ($ch === false) {
        return false;
    }
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($payload),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 8,
    ]);
    $raw = curl_exec($ch);
    curl_close($ch);
    $data = json_decode((string) $raw, true);
    if (!is_array($data) || empty($data['success'])) {
        return false;
    }
    if (($data['action'] ?? '') !== 'lead') {
        return false;
    }
    return (float) ($data['score'] ?? 0) >= 0.5;
}
