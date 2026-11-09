<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function aggiornamento_is_fisso(array $row): bool
{
    return !empty($row['fisso']);
}

/** @return list<array<string,mixed>> */
function aggiornamenti_all(bool $onlyPublished = false): array
{
    $sql = 'SELECT * FROM aggiornamenti';
    if ($onlyPublished) {
        $sql .= ' WHERE pubblicato = 1';
    }
    $sql .= ' ORDER BY fisso ASC, data_pub DESC, id DESC';
    return db()->query($sql)->fetchAll();
}

/** Articoli gestibili (non i 4 blocchi fissi). */
/** @return list<array<string,mixed>> */
function aggiornamenti_dinamici(bool $onlyPublished = true): array
{
    $sql = 'SELECT * FROM aggiornamenti WHERE COALESCE(fisso, 0) = 0';
    if ($onlyPublished) {
        $sql .= ' AND pubblicato = 1';
    }
    $sql .= ' ORDER BY data_pub DESC, id DESC';
    return db()->query($sql)->fetchAll();
}

/** I 4 blocchi originari, sempre in fondo. */
/** @return list<array<string,mixed>> */
function aggiornamenti_fissi(): array
{
    return db()->query(
        'SELECT * FROM aggiornamenti WHERE fisso = 1 AND pubblicato = 1 ORDER BY id ASC'
    )->fetchAll();
}

/** @return list<array<string,mixed>> */
function aggiornamenti_pubblicati(int $limit = 0): array
{
    $sql = 'SELECT * FROM aggiornamenti WHERE pubblicato = 1 ORDER BY fisso ASC, data_pub DESC, id DESC';
    if ($limit > 0) {
        $sql .= ' LIMIT ' . (int) $limit;
    }
    return db()->query($sql)->fetchAll();
}

function aggiornamento_by_slug(string $slug, bool $onlyPublished = true): ?array
{
    $slug = trim($slug);
    if ($slug === '') {
        return null;
    }
    $sql = 'SELECT * FROM aggiornamenti WHERE slug = :slug';
    if ($onlyPublished) {
        $sql .= ' AND pubblicato = 1';
    }
    $st = db()->prepare($sql);
    $st->execute([':slug' => $slug]);
    $row = $st->fetch();
    return $row ?: null;
}

function aggiornamento_by_id(int $id): ?array
{
    $st = db()->prepare('SELECT * FROM aggiornamenti WHERE id = :id');
    $st->execute([':id' => $id]);
    $row = $st->fetch();
    return $row ?: null;
}

/** @return list<array<string,mixed>> */
function aggiornamenti_altri(string $excludeSlug, int $limit = 5): array
{
    $st = db()->prepare(
        'SELECT * FROM aggiornamenti
         WHERE pubblicato = 1 AND COALESCE(fisso, 0) = 0 AND slug != :slug
         ORDER BY data_pub DESC, id DESC
         LIMIT ' . (int) $limit
    );
    $st->execute([':slug' => $excludeSlug]);
    return $st->fetchAll();
}

function aggiornamento_slugify(string $titolo, ?int $ignoreId = null): string
{
    $base = strtolower(trim($titolo));
    $base = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $base) ?: $base;
    $base = preg_replace('/[^a-z0-9]+/', '-', $base) ?? '';
    $base = trim($base, '-');
    if ($base === '') {
        $base = 'aggiornamento';
    }
    $slug = $base;
    $n = 2;
    while (aggiornamento_slug_taken($slug, $ignoreId)) {
        $slug = $base . '-' . $n;
        $n++;
    }
    return $slug;
}

function aggiornamento_slug_taken(string $slug, ?int $ignoreId = null): bool
{
    $sql = 'SELECT id FROM aggiornamenti WHERE slug = :slug';
    $params = [':slug' => $slug];
    if ($ignoreId !== null) {
        $sql .= ' AND id != :id';
        $params[':id'] = $ignoreId;
    }
    $st = db()->prepare($sql);
    $st->execute($params);
    return (bool) $st->fetchColumn();
}

/**
 * @param array{titolo:string,riassunto?:string,testo:string,data_pub:string,immagine:?string,pubblicato:bool,slug?:string} $data
 */
function aggiornamento_save(?int $id, array $data): int
{
    require_once __DIR__ . '/prodotti.php';
    $titolo = trim($data['titolo']);
    $riassunto = trim((string) ($data['riassunto'] ?? ''));
    if (mb_strlen($riassunto) > 320) {
        $riassunto = rtrim(mb_substr($riassunto, 0, 319)) . '…';
    }
    $testo = html_sanitize_rich(trim($data['testo']));
    $dataPub = trim($data['data_pub']);
    if ($titolo === '') {
        throw new InvalidArgumentException('Il titolo è obbligatorio.');
    }
    if (trim(strip_tags($testo)) === '') {
        throw new InvalidArgumentException('Il testo è obbligatorio.');
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataPub)) {
        throw new InvalidArgumentException('Data non valida.');
    }
    $slug = trim((string) ($data['slug'] ?? ''));
    if ($slug === '') {
        $slug = aggiornamento_slugify($titolo, $id);
    } else {
        $slug = aggiornamento_slugify($slug, $id);
    }
    $immagine = $data['immagine'] ?? null;
    if ($immagine === '') {
        $immagine = null;
    }
    $pub = !empty($data['pubblicato']) ? 1 : 0;
    $now = gmdate('c');

    if ($id !== null) {
        $existing = aggiornamento_by_id($id);
        if ($existing !== null && aggiornamento_is_fisso($existing)) {
            throw new InvalidArgumentException('Questo blocco è fisso e non può essere modificato.');
        }
    }

    if ($id === null) {
        $st = db()->prepare(
            'INSERT INTO aggiornamenti (slug, titolo, riassunto, testo, data_pub, immagine, pubblicato, fisso, created_at, updated_at)
             VALUES (:slug, :titolo, :riassunto, :testo, :data_pub, :immagine, :pubblicato, 0, :created_at, :updated_at)'
        );
        $st->execute([
            ':slug' => $slug,
            ':titolo' => $titolo,
            ':riassunto' => $riassunto,
            ':testo' => $testo,
            ':data_pub' => $dataPub,
            ':immagine' => $immagine,
            ':pubblicato' => $pub,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
        $newId = (int) db()->lastInsertId();
    } else {
        $st = db()->prepare(
            'UPDATE aggiornamenti SET
                slug = :slug, titolo = :titolo, riassunto = :riassunto, testo = :testo, data_pub = :data_pub,
                immagine = :immagine, pubblicato = :pubblicato, updated_at = :updated_at
             WHERE id = :id AND COALESCE(fisso, 0) = 0'
        );
        $st->execute([
            ':slug' => $slug,
            ':titolo' => $titolo,
            ':riassunto' => $riassunto,
            ':testo' => $testo,
            ':data_pub' => $dataPub,
            ':immagine' => $immagine,
            ':pubblicato' => $pub,
            ':updated_at' => $now,
            ':id' => $id,
        ]);
        $newId = $id;
    }
    require_once __DIR__ . '/sitemap.php';
    sitemap_write(public_base());
    return $newId;
}

function aggiornamento_set_published(int $id, bool $published): void
{
    $row = aggiornamento_by_id($id);
    if ($row === null) {
        throw new InvalidArgumentException('Aggiornamento non trovato.');
    }
    if (aggiornamento_is_fisso($row)) {
        throw new InvalidArgumentException('Questo blocco è fisso e non può essere oscurato.');
    }
    $st = db()->prepare(
        'UPDATE aggiornamenti SET pubblicato = :pub, updated_at = :updated_at
         WHERE id = :id AND COALESCE(fisso, 0) = 0'
    );
    $st->execute([
        ':pub' => $published ? 1 : 0,
        ':updated_at' => gmdate('c'),
        ':id' => $id,
    ]);
    require_once __DIR__ . '/sitemap.php';
    sitemap_write(public_base());
}

function aggiornamento_delete(int $id): void
{
    $row = aggiornamento_by_id($id);
    if ($row === null) {
        return;
    }
    if (aggiornamento_is_fisso($row)) {
        throw new InvalidArgumentException('Questo blocco è fisso e non può essere eliminato.');
    }
    $img = trim((string) ($row['immagine'] ?? ''));
    $st = db()->prepare('DELETE FROM aggiornamenti WHERE id = :id AND COALESCE(fisso, 0) = 0');
    $st->execute([':id' => $id]);
    if ($img !== '' && str_starts_with($img, '/assets/img/upload/aggiornamenti/')) {
        $path = dirname(__DIR__) . $img;
        if (is_file($path)) {
            @unlink($path);
        }
    }
    require_once __DIR__ . '/sitemap.php';
    sitemap_write(public_base());
}

function aggiornamento_excerpt(string $testo, int $max = 160): string
{
    $plain = html_entity_decode(strip_tags($testo), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $plain = trim(preg_replace('/\s+/u', ' ', $plain) ?? $plain);
    if (mb_strlen($plain) <= $max) {
        return $plain;
    }
    return rtrim(mb_substr($plain, 0, $max - 1)) . '…';
}

/** Testo in card: riassunto se presente, altrimenti testo tagliato. */
function aggiornamento_card_text(array $item, int $max = 160): string
{
    $riassunto = trim((string) ($item['riassunto'] ?? ''));
    if ($riassunto !== '') {
        return $riassunto;
    }
    return aggiornamento_excerpt((string) ($item['testo'] ?? ''), $max);
}

function aggiornamento_data_label(string $ymd): string
{
    if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $ymd, $m)) {
        return $ymd;
    }
    $mesi = [1 => 'gennaio', 'febbraio', 'marzo', 'aprile', 'maggio', 'giugno', 'luglio', 'agosto', 'settembre', 'ottobre', 'novembre', 'dicembre'];
    $mese = $mesi[(int) $m[2]] ?? $m[2];
    return ((int) $m[3]) . ' ' . $mese . ' ' . $m[1];
}

/** @return array{ok:bool,path?:string,error?:string} */
function aggiornamento_upload_image(array $file): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return ['ok' => true];
    }
    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return ['ok' => false, 'error' => 'Upload non riuscito.'];
    }
    if (($file['size'] ?? 0) > 3 * 1024 * 1024) {
        return ['ok' => false, 'error' => 'Immagine troppo grande (max 3 MB).'];
    }
    $tmp = (string) ($file['tmp_name'] ?? '');
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp) ?: '';
    $map = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    if (!isset($map[$mime])) {
        return ['ok' => false, 'error' => 'Formato non supportato (JPG, PNG, WEBP, GIF).'];
    }
    $dir = dirname(__DIR__) . '/assets/img/upload/aggiornamenti';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        return ['ok' => false, 'error' => 'Cartella upload non disponibile.'];
    }
    $name = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $map[$mime];
    $dest = $dir . '/' . $name;
    if (!move_uploaded_file($tmp, $dest)) {
        return ['ok' => false, 'error' => 'Salvataggio file non riuscito.'];
    }
    return ['ok' => true, 'path' => '/assets/img/upload/aggiornamenti/' . $name];
}
