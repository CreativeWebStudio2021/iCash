<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/** @return list<array{loc:string,title:string,group:string,images:list<array{loc:string,title:string}>}> */
function sitemap_entries(): array
{
    $pages = [
        ['path' => '/', 'title' => 'Home', 'group' => 'Pagine', 'images' => [
            ['loc' => '/assets/img/prodotti/ape3.webp', 'title' => 'Registratore touch RCH APE 3 RT'],
            ['loc' => '/assets/img/prodotti/onda.webp', 'title' => 'Registratore RCH Onda RT'],
            ['loc' => '/assets/img/prodotti/cashmatic-860.webp', 'title' => 'Cassa automatica Cashmatic 860'],
            ['loc' => '/assets/img/marchi/rch.png', 'title' => 'Logo RCH'],
            ['loc' => '/assets/img/marchi/custom.png', 'title' => 'Logo Custom'],
            ['loc' => '/assets/img/marchi/epson.png', 'title' => 'Logo Epson'],
            ['loc' => '/assets/img/marchi/cashmatic.png', 'title' => 'Logo Cashmatic'],
        ]],
        ['path' => '/azienda', 'title' => 'Azienda', 'group' => 'Pagine', 'images' => [
            ['loc' => '/assets/img/prodotti/at15-print.webp', 'title' => 'Punto cassa touch con stampante fiscale'],
        ]],
        ['path' => '/soluzioni', 'title' => 'Soluzioni e servizi', 'group' => 'Pagine', 'images' => [
            ['loc' => '/assets/img/prodotti/onda.webp', 'title' => 'Registratore telematico RCH Onda RT'],
            ['loc' => '/assets/img/prodotti/daytona.jpeg', 'title' => 'Terminale touch Custom'],
            ['loc' => '/assets/img/prodotti/cashmatic-860.webp', 'title' => 'Cassa automatica Cashmatic 860'],
        ]],
        ['path' => '/prodotti', 'title' => 'Catalogo prodotti', 'group' => 'Pagine', 'images' => []],
        ['path' => '/assistenza', 'title' => 'Assistenza tecnica', 'group' => 'Pagine', 'images' => []],
        ['path' => '/aggiornamenti', 'title' => 'Aggiornamenti su registratori e adempimenti', 'group' => 'Pagine', 'images' => []],
        ['path' => '/contatti', 'title' => 'Contatti', 'group' => 'Pagine', 'images' => []],
        ['path' => '/landing/registratori-telematici', 'title' => 'Registratori di cassa telematici', 'group' => 'Landing', 'images' => []],
        ['path' => '/landing/bar-ristoranti', 'title' => 'Cassa e gestionale per bar e ristoranti', 'group' => 'Landing', 'images' => []],
        ['path' => '/privacy', 'title' => 'Informativa privacy', 'group' => 'Informazioni', 'images' => []],
        ['path' => '/cookie', 'title' => 'Cookie policy', 'group' => 'Informazioni', 'images' => []],
        ['path' => '/mappa-del-sito', 'title' => 'Mappa del sito', 'group' => 'Informazioni', 'images' => []],
        ['path' => '/fonti-immagini', 'title' => 'Fonti delle immagini', 'group' => 'Informazioni', 'images' => []],
    ];
    foreach (prodotti() as $p) {
        $images = [];
        if (!empty($p['image'])) {
            $images[] = ['loc' => (string) $p['image'], 'title' => (string) ($p['alt'] ?? prodotto_label($p))];
        }
        foreach ($p['blocks'] ?? [] as $block) {
            if (($block['type'] ?? '') !== 'immagine' || empty($block['src'])) {
                continue;
            }
            $images[] = [
                'loc' => (string) $block['src'],
                'title' => (string) (($block['alt'] ?? '') !== '' ? $block['alt'] : ($block['didascalia'] ?? prodotto_label($p))),
            ];
        }
        $pages[] = [
            'path' => '/prodotto/' . $p['id'],
            'title' => prodotto_label($p),
            'group' => 'Prodotti',
            'images' => $images,
            'lastmod' => (string) ($p['updated'] ?? ''),
        ];
    }
    if (is_file(__DIR__ . '/aggiornamenti.php')) {
        require_once __DIR__ . '/aggiornamenti.php';
        foreach (aggiornamenti_pubblicati() as $a) {
            $images = [];
            if (!empty($a['immagine'])) {
                $images[] = ['loc' => (string) $a['immagine'], 'title' => (string) $a['titolo']];
            }
            $pages[] = [
                'path' => '/aggiornamento/' . $a['slug'],
                'title' => (string) $a['titolo'],
                'group' => 'Aggiornamenti',
                'images' => $images,
                'lastmod' => (string) ($a['data_pub'] ?? ''),
            ];
        }
    }
    return $pages;
}

function sitemap_abs(string $base, string $path): string
{
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return rtrim($base, '/') . ($path === '' ? '/' : $path);
}

function sitemap_write(string $base): void
{
    if (!function_exists('prodotti')) {
        require_once __DIR__ . '/prodotti.php';
    }
    $root = dirname(__DIR__);
    $entries = sitemap_entries();
    $pageXml = [];
    $imageXml = [];
    foreach ($entries as $entry) {
        $loc = sitemap_abs($base, $entry['path']);
        $last = $entry['lastmod'] ?? '';
        $pageXml[] = '  <url><loc>' . sitemap_x($loc) . '</loc>' . ($last !== '' ? '<lastmod>' . sitemap_x(substr($last, 0, 10)) . '</lastmod>' : '') . '</url>';
        if ($entry['images'] === []) {
            continue;
        }
        $imgs = '';
        foreach ($entry['images'] as $image) {
            $imgs .= '<image:image><image:loc>' . sitemap_x(sitemap_abs($base, $image['loc'])) . '</image:loc>'
                . '<image:title>' . sitemap_x($image['title']) . '</image:title></image:image>';
        }
        $imageXml[] = '  <url><loc>' . sitemap_x($loc) . '</loc>' . $imgs . '</url>';
    }
    $pages = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
        . implode("\n", $pageXml) . "\n</urlset>\n";
    $images = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n"
        . implode("\n", $imageXml) . "\n</urlset>\n";
    $index = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
        . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
        . '  <sitemap><loc>' . sitemap_x(sitemap_abs($base, '/sitemap-pagine.xml')) . '</loc></sitemap>' . "\n"
        . '  <sitemap><loc>' . sitemap_x(sitemap_abs($base, '/sitemap-immagini.xml')) . '</loc></sitemap>' . "\n"
        . '</sitemapindex>' . "\n";
    if (seo_allow_index()) {
        $robots = "User-agent: DomainCrawler\nDisallow: /\n\n"
            . "User-agent: SemrushBot\nDisallow: /\n\n"
            . "User-agent: AhrefsBot\nDisallow: /\n\n"
            . "User-agent: *\n"
            . "Disallow: /*.jpg\n"
            . "Disallow: /*.JPG\n"
            . "Disallow: /*.png\n"
            . "Disallow: /*.PDF\n"
            . "Disallow: /*.pdf\n"
            . "Disallow: /*.mp3\n"
            . "Disallow: /*.MOV\n"
            . "Disallow: /*.mov\n"
            . "Disallow: /*.AVI\n"
            . "Disallow: /*.avi\n"
            . "Disallow: /*.csv\n"
            . "Disallow: /*.data\n"
            . "Disallow: /data/\n"
            . "Disallow: /inc/\n"
            . "Disallow: /materiale/\n"
            . "Disallow: /invia.php\n"
            . "Disallow: /admin/\n"
            . "Crawl-delay: 2\n\n"
            . "Sitemap: " . sitemap_abs($base, '/sitemap.xml') . "\n";
    } else {
        // Staging: blocca crawling e non pubblicare URL in sitemap
        $empty = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
            . '</urlset>' . "\n";
        $pages = $empty;
        $images = $empty;
        $index = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n"
            . '</sitemapindex>' . "\n";
        $robots = "# Staging: non indicizzare\n"
            . "User-agent: *\nDisallow: /\n\n"
            . "User-agent: Googlebot\nDisallow: /\n\n"
            . "User-agent: Googlebot-Image\nDisallow: /\n\n"
            . "User-agent: Bingbot\nDisallow: /\n";
    }
    $writes = [
        $root . '/sitemap-pagine.xml' => $pages,
        $root . '/sitemap-immagini.xml' => $images,
        $root . '/sitemap.xml' => $index,
        $root . '/robots.txt' => $robots,
    ];
    foreach ($writes as $file => $body) {
        if (file_put_contents($file, $body) === false) {
            throw new RuntimeException('Sitemap non aggiornata');
        }
    }
}

function sitemap_x(string $value): string
{
    return htmlspecialchars($value, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}
