<?php
declare(strict_types=1);

function prodotti_file(): string
{
    return dirname(__DIR__) . '/data/prodotti.json';
}

function evidenza_file(): string
{
    return dirname(__DIR__) . '/data/evidenza.json';
}

/** @return list<string> */
function evidenza_ids(): array
{
    $path = evidenza_file();
    if (!is_file($path)) {
        return ['rch-ape3', 'custom-fusion', 'cashmatic-860', 'epson-fp81'];
    }
    $raw = file_get_contents($path);
    $data = json_decode($raw === false ? '[]' : $raw, true);
    if (!is_array($data)) {
        return [];
    }
    $ids = [];
    foreach ($data as $id) {
        $id = trim((string) $id);
        if ($id !== '' && !in_array($id, $ids, true)) {
            $ids[] = $id;
        }
        if (count($ids) >= 4) {
            break;
        }
    }
    return $ids;
}

/** @param list<string> $ids */
function evidenza_save(array $ids): void
{
    $clean = [];
    $published = [];
    foreach (prodotti_all() as $p) {
        $published[(string) ($p['id'] ?? '')] = !empty($p['published']);
    }
    foreach ($ids as $id) {
        $id = trim((string) $id);
        if ($id === '' || empty($published[$id]) || in_array($id, $clean, true)) {
            continue;
        }
        $clean[] = $id;
        if (count($clean) >= 4) {
            break;
        }
    }
    $json = json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        throw new RuntimeException('Salvataggio evidenza non riuscito');
    }
    $path = evidenza_file();
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
        throw new RuntimeException('Salvataggio evidenza non riuscito');
    }
    if (!rename($tmp, $path)) {
        throw new RuntimeException('Salvataggio evidenza non riuscito');
    }
}

/** @return list<array<string,mixed>> */
function prodotti_all(): array
{
    $raw = file_get_contents(prodotti_file());
    $data = json_decode($raw === false ? '[]' : $raw, true);
    return is_array($data) ? $data : [];
}

/** @param list<array<string,mixed>> $items */
function prodotti_save(array $items): void
{
    $json = json_encode(array_values($items), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        throw new RuntimeException('Salvataggio non riuscito');
    }
    $path = prodotti_file();
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json . "\n", LOCK_EX) === false) {
        throw new RuntimeException('Salvataggio non riuscito');
    }
    if (!rename($tmp, $path)) {
        throw new RuntimeException('Salvataggio non riuscito');
    }
    require_once __DIR__ . '/sitemap.php';
    sitemap_write(public_base());
}

/** Riordina il catalogo. L’elenco deve contenere tutti gli id esistenti, una sola volta. */
function prodotti_reorder(array $ids): void
{
    $items = prodotti_all();
    $byId = [];
    foreach ($items as $p) {
        $id = (string) ($p['id'] ?? '');
        if ($id === '') {
            continue;
        }
        $byId[$id] = $p;
    }
    $order = [];
    foreach ($ids as $id) {
        $id = trim((string) $id);
        if ($id === '' || isset($order[$id]) || !isset($byId[$id])) {
            continue;
        }
        $order[$id] = $byId[$id];
    }
    if (count($order) !== count($byId)) {
        throw new RuntimeException('Ordine non valido');
    }
    prodotti_save(array_values($order));
}

/** Oscura o ripubblica una scheda. Se oscurata, esce anche dall’evidenza. La sitemap si rigenera. */
function prodotto_set_published(string $id, bool $published): void
{
    $id = trim($id);
    $items = prodotti_all();
    $found = false;
    foreach ($items as $i => $p) {
        if (($p['id'] ?? '') !== $id) {
            continue;
        }
        $items[$i]['published'] = $published;
        $items[$i]['updated'] = date('c');
        $found = true;
        break;
    }
    if (!$found) {
        throw new RuntimeException('Prodotto non trovato');
    }
    prodotti_save($items);
    if (!$published) {
        evidenza_save(evidenza_ids());
    }
}

/** @return list<array<string,mixed>> */
function prodotti(): array
{
    $out = [];
    foreach (prodotti_all() as $p) {
        if (!empty($p['published'])) {
            $out[] = $p;
        }
    }
    return $out;
}

function prodotto_by_id(string $id, bool $onlyPublished = true): ?array
{
    foreach (prodotti_all() as $p) {
        if (($p['id'] ?? '') !== $id) {
            continue;
        }
        if ($onlyPublished && empty($p['published'])) {
            return null;
        }
        return $p;
    }
    return null;
}

function prodotto_label(array $p): string
{
    return trim((string) ($p['brand'] ?? '') . ' ' . (string) ($p['model'] ?? ''));
}

function categorie(): array
{
    return [
        'registratori' => 'Registratori di cassa',
        'touch' => 'Sistemi touch e POS',
        'contante' => 'Casse automatiche',
        'periferiche' => 'Periferiche',
    ];
}

/** Pulisce testo scheda da glifi Symbol/PUA dei PDF e allinea a sinistra. */
function scheda_testo_clean(string $text): string
{
    if ($text === '') {
        return '';
    }
    $out = '';
    $len = mb_strlen($text, 'UTF-8');
    for ($i = 0; $i < $len; $i++) {
        $ch = mb_substr($text, $i, 1, 'UTF-8');
        $o = mb_ord($ch, 'UTF-8');
        if (($o >= 0xE000 && $o <= 0xF8FF) || ($o >= 0xF0000 && $o <= 0xFFFFD)) {
            $out .= '•';
            continue;
        }
        if (str_contains('□■▪▫◆◇►▶➢➤✓✔', $ch)) {
            $out .= '•';
            continue;
        }
        if (str_contains("\u{FFFD}✕✖★☆", $ch)) {
            continue;
        }
        $out .= $ch;
    }
    $out = preg_replace('/•\s*•+/u', '•', $out) ?? $out;
    $lines = preg_split("/\r\n|\n|\r/", $out) ?: [];
    $clean = [];
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '') {
            $clean[] = '';
            continue;
        }
        // colonne PDF con grandi spazi → elenco a sinistra
        if (preg_match('/\S {2,}\S/u', $line)) {
            $parts = preg_split('/ {2,}/u', $line) ?: [];
            $parts = array_values(array_filter(array_map('trim', $parts), static fn ($p) => $p !== ''));
            $line = implode(' · ', $parts);
        }
        $line = preg_replace('/^(.+?)\s*•\s*$/u', '• $1', $line) ?? $line;
        // piedi pagina produttore (rumore da estrazione PDF)
        if (preg_match('/^Rch Spa\b/iu', $line) || preg_match('/^rev\.\d+/iu', $line)) {
            continue;
        }
        $clean[] = $line;
    }
    $text = implode("\n", $clean);
    $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;
    return trim($text);
}

/** HTML semplificato per blocchi testo: p, br, strong, em, ul, ol, li, a[href]. */
function html_sanitize_rich(string $html): string
{
    $html = trim($html);
    if ($html === '') {
        return '';
    }
    $allowed = '<p><br><strong><b><em><i><ul><ol><li><a>';
    $html = strip_tags($html, $allowed);
    $html = preg_replace('/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu', '', $html) ?? $html;
    $html = preg_replace_callback(
        '/<a\b([^>]*)>/iu',
        static function (array $m): string {
            $attrs = $m[1];
            $href = '';
            if (preg_match('/href\s*=\s*"([^"]*)"/iu', $attrs, $hm) || preg_match("/href\s*=\s*'([^']*)'/iu", $attrs, $hm)) {
                $href = trim(html_entity_decode($hm[1], ENT_QUOTES, 'UTF-8'));
            }
            if ($href === '' || preg_match('/^\s*javascript:/iu', $href) || preg_match('/^\s*data:/iu', $href)) {
                return '<a>';
            }
            if (!preg_match('~^(https?://|/|#|mailto:)~iu', $href)) {
                return '<a>';
            }
            $rel = str_starts_with(strtolower($href), 'http') ? ' rel="noopener noreferrer"' : '';
            $target = str_starts_with(strtolower($href), 'http') ? ' target="_blank"' : '';
            return '<a href="' . e($href) . '"' . $target . $rel . '>';
        },
        $html
    ) ?? $html;
    $html = str_ireplace(['<b>', '</b>'], ['<strong>', '</strong>'], $html);
    $html = str_ireplace(['<i>', '</i>'], ['<em>', '</em>'], $html);
    return trim($html);
}

/** Converte testo piano o HTML semplificato in HTML sicuro per la scheda pubblica. */
function rich_text_html(string $raw): string
{
    $raw = trim($raw);
    if ($raw === '') {
        return '';
    }
    if (preg_match('/<(p|ul|ol|li|strong|em|b|i|a|br)\b/i', $raw)) {
        return html_sanitize_rich($raw);
    }
    $paras = preg_split("/\n{2,}/", $raw) ?: [];
    $html = '';
    foreach ($paras as $para) {
        $para = trim($para);
        if ($para === '') {
            continue;
        }
        $html .= '<p>' . nl2br(e($para)) . '</p>';
    }
    return $html;
}

function render_blocchi(array $product): void
{
    foreach ($product['blocks'] ?? [] as $block) {
        $type = (string) ($block['type'] ?? '');
        $titolo = trim((string) ($block['titolo'] ?? ''));
        echo '<section class="block block-' . e($type) . '">';
        if ($titolo !== '') {
            echo '<h2 class="block-title">' . e($titolo) . '</h2>';
        }
        echo '<div class="block-panel">';
        if ($type === 'testo') {
            echo rich_text_html((string) ($block['testo'] ?? ''));
        } elseif ($type === 'specifiche') {
            echo '<dl class="specs">';
            foreach ($block['voci'] ?? [] as $voce) {
                $voce = trim((string) $voce);
                if ($voce === '') {
                    continue;
                }
                $parts = explode(':', $voce, 2);
                $k = trim($parts[0]);
                $v = trim($parts[1] ?? '');
                echo '<div><dt>' . e($k) . '</dt><dd>' . e($v !== '' ? $v : $k) . '</dd></div>';
            }
            echo '</dl>';
        } elseif ($type === 'elenco') {
            echo '<ul>';
            foreach ($block['voci'] ?? [] as $voce) {
                $voce = trim((string) $voce);
                if ($voce !== '') {
                    echo '<li>' . e($voce) . '</li>';
                }
            }
            echo '</ul>';
        } elseif ($type === 'immagine') {
            $src = (string) ($block['src'] ?? '');
            if ($src !== '') {
                $alt = (string) ($block['alt'] ?? '');
                echo '<figure><img src="' . e($src) . '" alt="' . e($alt) . '" title="' . e($alt !== '' ? $alt : (string) ($block['didascalia'] ?? '')) . '">';
                $cap = trim((string) ($block['didascalia'] ?? ''));
                if ($cap !== '') {
                    echo '<figcaption>' . e($cap) . '</figcaption>';
                }
                echo '</figure>';
            }
        }
        echo '</div></section>';
    }
}
