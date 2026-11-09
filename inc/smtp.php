<?php
declare(strict_types=1);

require_once __DIR__ . '/smtp.secret.php';

function smtp_expect($fp, array $codes): string
{
    $data = '';
    do {
        $line = fgets($fp, 2048);
        if ($line === false) {
            throw new RuntimeException('Connessione SMTP chiusa');
        }
        $data .= $line;
        $more = isset($line[3]) && $line[3] === '-';
    } while ($more);
    $code = (int) substr($data, 0, 3);
    if (!in_array($code, $codes, true)) {
        throw new RuntimeException('SMTP ' . trim($data));
    }
    return $data;
}

function smtp_cmd($fp, string $cmd, array $codes): string
{
    fwrite($fp, $cmd . "\r\n");
    return smtp_expect($fp, $codes);
}

/**
 * @param string $body Corpo plain text, oppure HTML se $htmlBody è null e $body contiene markup
 * @param string|null $htmlBody Se valorizzato, invia multipart/alternative (plain + html)
 */
function smtp_send(string $subject, string $body, string $replyTo = '', ?string $to = null, ?string $htmlBody = null): void
{
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'SNI_enabled' => true,
            'peer_name' => SMTP_TLS_NAME,
        ],
    ]);
    $fp = stream_socket_client('tcp://' . SMTP_HOST . ':' . SMTP_PORT, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
    if (!$fp) {
        throw new RuntimeException('SMTP non raggiungibile');
    }
    stream_set_timeout($fp, 15);
    try {
        smtp_expect($fp, [220]);
        smtp_cmd($fp, 'EHLO icashregister.it', [250]);
        smtp_cmd($fp, 'STARTTLS', [220]);
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            throw new RuntimeException('STARTTLS non riuscito');
        }
        smtp_cmd($fp, 'EHLO icashregister.it', [250]);
        smtp_cmd($fp, 'AUTH LOGIN', [334]);
        smtp_cmd($fp, base64_encode(SMTP_USER), [334]);
        smtp_cmd($fp, base64_encode(SMTP_PASS), [235]);
        $recipient = $to !== null && $to !== '' ? $to : MAIL_TO;
        smtp_cmd($fp, 'MAIL FROM:<' . MAIL_FROM . '>', [250]);
        smtp_cmd($fp, 'RCPT TO:<' . $recipient . '>', [250, 251]);
        smtp_cmd($fp, 'DATA', [354]);
        $headers = [
            'From: iCash <' . MAIL_FROM . '>',
            'To: <' . $recipient . '>',
            'Subject: ' . encode_subject($subject),
            'Date: ' . date(DATE_RFC2822),
            'MIME-Version: 1.0',
        ];
        if ($replyTo !== '' && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: <' . $replyTo . '>';
        }
        if ($htmlBody !== null && $htmlBody !== '') {
            $boundary = 'b_' . bin2hex(random_bytes(8));
            $headers[] = 'Content-Type: multipart/alternative; boundary="' . $boundary . '"';
            $parts = [
                '--' . $boundary,
                'Content-Type: text/plain; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                '',
                $body,
                '--' . $boundary,
                'Content-Type: text/html; charset=UTF-8',
                'Content-Transfer-Encoding: 8bit',
                '',
                $htmlBody,
                '--' . $boundary . '--',
            ];
            $content = implode("\r\n", $parts);
        } else {
            $headers[] = 'Content-Type: text/plain; charset=UTF-8';
            $headers[] = 'Content-Transfer-Encoding: 8bit';
            $content = $body;
        }
        $payload = implode("\r\n", $headers) . "\r\n\r\n" . dot_stuff($content) . "\r\n.";
        fwrite($fp, $payload . "\r\n");
        smtp_expect($fp, [250]);
        smtp_cmd($fp, 'QUIT', [221]);
    } finally {
        fclose($fp);
    }
}

function encode_subject(string $subject): string
{
    return '=?UTF-8?B?' . base64_encode($subject) . '?=';
}

function dot_stuff(string $body): string
{
    $body = str_replace(["\r\n", "\r"], "\n", $body);
    $lines = explode("\n", $body);
    foreach ($lines as &$line) {
        if (str_starts_with($line, '.')) {
            $line = '.' . $line;
        }
    }
    return implode("\r\n", $lines);
}
