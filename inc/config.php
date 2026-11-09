<?php
declare(strict_types=1);

const SITE_NAME = 'iCash';
const SITE_LEGAL = 'iCash S.r.l.s.';
const SITE_DOMAIN = 'https://icashregister.it';
/** Host che oggi pubblica il sito, finché icashregister.it non punta qui. */
const SITE_PUBLIC = 'https://icash.cwstudio.it';
const SITE_TAGLINE = 'Registratori di cassa, POS e gestione del contante';

const ADDR_STREET = 'Via Giacomo Brodolini 33';
const ADDR_CITY = 'Ciampino';
const ADDR_CAP = '00043';
const ADDR_PROV = 'RM';
const PIVA = '17771921008';
const MAIL_PUBLIC = 'info@icashregister.it';
require_once __DIR__ . '/lead.secret.php';
const RECAPTCHA_SITE = '6LetqwEuAAAAAHT31FZEHc36hxyR7IPK5jAq8GEx';

function public_base(): string
{
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    $host = (string) preg_replace('/:\d+$/', '', $host);
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    if ($host === 'www.icashregister.it' || $host === 'icashregister.it') {
        return 'https://icashregister.it';
    }
    if ($host === 'icash.cwstudio.it') {
        return ($https ? 'https' : 'http') . '://icash.cwstudio.it';
    }
    return SITE_PUBLIC;
}

function is_production_host(): bool
{
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    $host = (string) preg_replace('/:\d+$/', '', $host);
    return $host === 'icashregister.it' || $host === 'www.icashregister.it';
}

/** Su staging (es. icash.cwstudio.it) niente indicizzazione. */
function seo_allow_index(): bool
{
    return is_production_host();
}

const PHONE_PRIMARY = '0671058165';
const PHONE_PRIMARY_LABEL = '06 7105 8165';
/** Numero cellulare indicato in presentazione. Da confermare come WhatsApp Business. */
const WHATSAPP_E164 = '393792475200';
const WHATSAPP_LABEL = '379 247 5200';

function wa_link(string $message): string
{
    return 'https://wa.me/' . WHATSAPP_E164 . '?text=' . rawurlencode($message);
}

function tel_link(string $digits): string
{
    return 'tel:+39' . $digits;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
