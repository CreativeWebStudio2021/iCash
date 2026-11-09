<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/** @return array{back:string,da:string} */
function lead_route(string $contesto): array
{
    return match ($contesto) {
        'landing-registratori' => ['back' => '/landing/registratori-telematici', 'da' => 'registratori'],
        'landing-bar-ristoranti' => ['back' => '/landing/bar-ristoranti', 'da' => 'ristorazione'],
        default => ['back' => '/contatti', 'da' => 'contatti'],
    };
}

function lead_thanks_url(string $da): string
{
    $allowed = ['registratori', 'ristorazione', 'contatti'];
    if (!in_array($da, $allowed, true)) {
        $da = 'contatti';
    }
    $t = (string) time();
    $sig = hash_hmac('sha256', $da . '|' . $t, LEAD_SIGN_KEY);
    return '/grazie?da=' . rawurlencode($da) . '&t=' . rawurlencode($t) . '&s=' . rawurlencode($sig);
}

/** True solo subito dopo un invio reale, entro due ore. */
function lead_thanks_fresh(string $da, string $t, string $sig): bool
{
    $allowed = ['registratori', 'ristorazione', 'contatti'];
    if (!in_array($da, $allowed, true)) {
        return false;
    }
    if (!preg_match('/^\d{10}$/', $t) || !preg_match('/^[a-f0-9]{64}$/', $sig)) {
        return false;
    }
    $age = time() - (int) $t;
    if ($age < 0 || $age > 7200) {
        return false;
    }
    $expected = hash_hmac('sha256', $da . '|' . $t, LEAD_SIGN_KEY);
    return hash_equals($expected, $sig);
}
