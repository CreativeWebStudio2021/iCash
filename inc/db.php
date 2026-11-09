<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $dir = dirname(__DIR__) . '/data';
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Cartella data non disponibile');
    }
    $path = $dir . '/icash.sqlite';
    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    db_migrate($pdo);
    return $pdo;
}

function db_migrate(PDO $pdo): void
{
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS aggiornamenti (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            slug TEXT NOT NULL UNIQUE,
            titolo TEXT NOT NULL,
            riassunto TEXT NOT NULL DEFAULT \'\',
            testo TEXT NOT NULL DEFAULT \'\',
            data_pub TEXT NOT NULL,
            immagine TEXT NULL,
            pubblicato INTEGER NOT NULL DEFAULT 1,
            fisso INTEGER NOT NULL DEFAULT 0,
            created_at TEXT NOT NULL,
            updated_at TEXT NOT NULL
        )'
    );
    $cols = $pdo->query('PRAGMA table_info(aggiornamenti)')->fetchAll();
    $names = array_map(static fn ($c) => (string) ($c['name'] ?? ''), $cols);
    if (!in_array('fisso', $names, true)) {
        $pdo->exec('ALTER TABLE aggiornamenti ADD COLUMN fisso INTEGER NOT NULL DEFAULT 0');
    }
    if (!in_array('riassunto', $names, true)) {
        $pdo->exec('ALTER TABLE aggiornamenti ADD COLUMN riassunto TEXT NOT NULL DEFAULT \'\'');
    }
    $pdo->exec('CREATE INDEX IF NOT EXISTS idx_aggiornamenti_data ON aggiornamenti (data_pub DESC, id DESC)');

    $fixedSlugs = [
        'registratore-e-pagamenti-elettronici',
        'leggi-di-bilancio',
        'adempimenti-del-registratore',
        'fatturazione-elettronica',
    ];
    $stFix = $pdo->prepare('UPDATE aggiornamenti SET fisso = 1 WHERE slug = :slug');
    foreach ($fixedSlugs as $slug) {
        $stFix->execute([':slug' => $slug]);
    }

    $n = (int) $pdo->query('SELECT COUNT(*) FROM aggiornamenti')->fetchColumn();
    if ($n === 0) {
        db_seed_aggiornamenti($pdo);
    }
}

function db_seed_aggiornamenti(PDO $pdo): void
{
    $now = gmdate('c');
    $seed = [
        [
            'slug' => 'registratore-e-pagamenti-elettronici',
            'titolo' => 'Registratore e pagamenti elettronici',
            'testo' => "Il collegamento tra registratore telematico e strumento di pagamento elettronico è un adempimento in aggiornamento. Le scadenze sono cambiate nel tempo.\n\nPrima di un acquisto o di un aggiornamento guardiamo il banco che hai già, non una regola generica.",
            'data_pub' => '2026-10-01',
        ],
        [
            'slug' => 'leggi-di-bilancio',
            'titolo' => 'Leggi di bilancio',
            'testo' => "Le leggi di bilancio intervengono spesso su corrispettivi, scontrino e fattura.\n\nQuando una novità cambia il lavoro in cassa, va letta insieme al modello di registratore e al gestionale, non solo come titolo di giornale.",
            'data_pub' => '2026-09-15',
        ],
        [
            'slug' => 'adempimenti-del-registratore',
            'titolo' => 'Adempimenti del registratore',
            'testo' => "Invio telematico dei corrispettivi e documento commerciale sono il lavoro quotidiano del registratore.\n\nInstallazione e assistenza servono a tenerlo allineato, non solo a consegnare l’apparecchio.",
            'data_pub' => '2026-08-20',
        ],
        [
            'slug' => 'fatturazione-elettronica',
            'titolo' => 'Fatturazione elettronica',
            'testo' => "Chi emette anche fatture ha un secondo canale, oltre allo scontrino.\n\nIl gestionale e il registratore si scelgono di conseguenza, in particolare se l’attività fa sia vendita al banco sia documenti verso altre imprese.",
            'data_pub' => '2026-07-10',
        ],
    ];
    $st = $pdo->prepare(
        'INSERT INTO aggiornamenti (slug, titolo, riassunto, testo, data_pub, immagine, pubblicato, fisso, created_at, updated_at)
         VALUES (:slug, :titolo, \'\', :testo, :data_pub, NULL, 1, 1, :created_at, :updated_at)'
    );
    foreach ($seed as $row) {
        $st->execute([
            ':slug' => $row['slug'],
            ':titolo' => $row['titolo'],
            ':testo' => $row['testo'],
            ':data_pub' => $row['data_pub'],
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);
    }
}
