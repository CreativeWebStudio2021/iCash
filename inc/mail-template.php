<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Costruisce corpo plain + HTML per le email del sito.
 *
 * @param array{
 *   title?: string,
 *   intro?: string,
 *   rows?: list<array{0:string,1:string}>,
 *   message?: string,
 *   footer?: string
 * } $data
 * @return array{plain:string,html:string}
 */
function mail_template(array $data): array
{
    $title = trim((string) ($data['title'] ?? 'Messaggio dal sito iCash'));
    $intro = trim((string) ($data['intro'] ?? ''));
    $rows = $data['rows'] ?? [];
    $message = trim((string) ($data['message'] ?? ''));
    $footer = trim((string) ($data['footer'] ?? (SITE_LEGAL . ' · ' . ADDR_STREET . ', ' . ADDR_CAP . ' ' . ADDR_CITY . ' (' . ADDR_PROV . ')')));
    $site = public_base();

    $plainLines = [$title, ''];
    if ($intro !== '') {
        $plainLines[] = $intro;
        $plainLines[] = '';
    }
    foreach ($rows as $row) {
        $label = trim((string) ($row[0] ?? ''));
        $value = trim((string) ($row[1] ?? ''));
        if ($label === '' && $value === '') {
            continue;
        }
        $plainLines[] = $label . ': ' . $value;
    }
    if ($message !== '') {
        $plainLines[] = '';
        $plainLines[] = 'Messaggio:';
        $plainLines[] = $message;
    }
    $plainLines[] = '';
    $plainLines[] = $footer;
    $plainLines[] = $site;
    $plain = implode("\n", $plainLines);

    $rowsHtml = '';
    foreach ($rows as $row) {
        $label = trim((string) ($row[0] ?? ''));
        $value = trim((string) ($row[1] ?? ''));
        if ($label === '' && $value === '') {
            continue;
        }
        $rowsHtml .= '<tr>'
            . '<td style="padding:10px 12px;border-bottom:1px solid #eadfd6;color:#5c5160;font-size:13px;width:34%;vertical-align:top;">' . e($label) . '</td>'
            . '<td style="padding:10px 12px;border-bottom:1px solid #eadfd6;color:#1c1220;font-size:15px;vertical-align:top;">' . nl2br(e($value)) . '</td>'
            . '</tr>';
    }

    $introHtml = $intro !== ''
        ? '<p style="margin:0 0 18px;color:#5c5160;font-size:15px;line-height:1.55;">' . e($intro) . '</p>'
        : '';
    $messageHtml = $message !== ''
        ? '<div style="margin:18px 0 0;padding:14px 16px;background:#f7f3ee;border-radius:8px;border:1px solid #eadfd6;">'
            . '<p style="margin:0 0 8px;color:#5c5160;font-size:12px;letter-spacing:0.06em;text-transform:uppercase;font-weight:700;">Messaggio</p>'
            . '<p style="margin:0;color:#1c1220;font-size:15px;line-height:1.6;white-space:pre-wrap;">' . e($message) . '</p>'
            . '</div>'
        : '';

    $html = '<!DOCTYPE html><html lang="it"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<title>' . e($title) . '</title></head>'
        . '<body style="margin:0;padding:0;background:#f3eee8;font-family:Lato,Helvetica,Arial,sans-serif;">'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3eee8;padding:24px 12px;">'
        . '<tr><td align="center">'
        . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #e4d9d0;">'
        . '<tr><td style="background:#1c1220;padding:18px 22px;">'
        . '<p style="margin:0;color:#ffffff;font-size:18px;font-weight:700;letter-spacing:0.02em;">iCash</p>'
        . '<p style="margin:4px 0 0;color:#c8b8c0;font-size:12px;">' . e(SITE_TAGLINE) . '</p>'
        . '</td></tr>'
        . '<tr><td style="padding:22px;">'
        . '<h1 style="margin:0 0 12px;color:#1c1220;font-size:22px;line-height:1.25;font-weight:700;">' . e($title) . '</h1>'
        . $introHtml
        . ($rowsHtml !== '' ? '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid #eadfd6;border-radius:8px;overflow:hidden;">' . $rowsHtml . '</table>' : '')
        . $messageHtml
        . '</td></tr>'
        . '<tr><td style="padding:14px 22px 20px;background:#faf7f4;border-top:1px solid #eadfd6;">'
        . '<p style="margin:0;color:#5c5160;font-size:12px;line-height:1.5;">' . e($footer) . '<br>'
        . '<a href="' . e($site) . '" style="color:#9d0d5a;text-decoration:none;">' . e($site) . '</a></p>'
        . '</td></tr>'
        . '</table>'
        . '</td></tr></table>'
        . '</body></html>';

    return ['plain' => $plain, 'html' => $html];
}

/** @param array<string,mixed> $record */
function mail_lead_template(array $record): array
{
    $prodotto = trim((string) ($record['prodotto'] ?? ''));
    $contesto = trim((string) ($record['contesto'] ?? 'contatti'));
    $rows = [
        ['Pagina', $contesto],
        ['Nome', (string) ($record['nome'] ?? '')],
        ['Telefono', (string) ($record['telefono'] ?? '')],
        ['Email', (string) ($record['email'] ?? '') !== '' ? (string) $record['email'] : '—'],
        ['Attività', (string) ($record['attivita'] ?? '') !== '' ? (string) $record['attivita'] : '—'],
    ];
    if ($prodotto !== '') {
        array_unshift($rows, ['Prodotto', $prodotto]);
    }
    return mail_template([
        'title' => 'Nuova richiesta dal sito',
        'intro' => 'È arrivata una richiesta di contatto dal modulo del sito iCash.',
        'rows' => $rows,
        'message' => (string) ($record['messaggio'] ?? ''),
    ]);
}

/** Conferma di ricezione inviata a chi ha compilato il modulo. */
function mail_receipt_template(array $record): array
{
    $nome = trim((string) ($record['nome'] ?? ''));
    $prodotto = trim((string) ($record['prodotto'] ?? ''));
    $intro = 'Ciao' . ($nome !== '' ? ' ' . $nome : '') . ', abbiamo ricevuto la tua richiesta. Ti ricontattiamo al telefono o a questa email.';
    $rows = [
        ['Telefono indicato', (string) ($record['telefono'] ?? '')],
    ];
    if ($prodotto !== '') {
        $rows[] = ['Prodotto', $prodotto];
    }
    return mail_template([
        'title' => 'Richiesta ricevuta',
        'intro' => $intro,
        'rows' => $rows,
        'message' => (string) ($record['messaggio'] ?? ''),
        'footer' => SITE_LEGAL . ' · ' . PHONE_PRIMARY_LABEL . ' · ' . MAIL_PUBLIC,
    ]);
}
