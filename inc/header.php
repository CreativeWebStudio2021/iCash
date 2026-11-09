<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/pagetitle.php';
/** @var string $page */
/** @var string $title */
/** @var string $description */
$page = $page ?? 'home';
$title = $title ?? SITE_NAME;
$description = $description ?? '';
$canonical = public_base() . ($canonicalPath ?? '/');
if (!isset($robots)) {
    $robots = seo_allow_index() ? 'index,follow' : 'noindex,nofollow';
} elseif (!seo_allow_index()) {
    $robots = 'noindex,nofollow';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<?php if (seo_allow_index()): ?>
  <script type="text/javascript" src="https://embeds.iubenda.com/widgets/c8d77009-8b0f-4c52-a9e5-37e3ed49e9a6.js"></script>
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PDNMZNTM');</script>
<?php endif; ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
  <meta name="robots" content="<?= e($robots) ?>">
  <meta name="googlebot" content="<?= e($robots) ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">
  <meta name="theme-color" content="#1c1220">
  <link rel="icon" href="/assets/favicon.ico?v=20260328" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32.png?v=20260328">
  <link rel="apple-touch-icon" href="/assets/apple-touch-icon.png?v=20260328">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;0,900;1,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css?v=20261105h">
  <script>if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) document.documentElement.classList.add('motion');</script>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "<?= e(SITE_LEGAL) ?>",
    "url": "<?= e(public_base()) ?>",
    "telephone": "+39<?= e(PHONE_PRIMARY) ?>",
    "email": "<?= e(MAIL_PUBLIC) ?>",
    "vatID": "<?= e(PIVA) ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "<?= e(ADDR_STREET) ?>",
      "postalCode": "<?= e(ADDR_CAP) ?>",
      "addressLocality": "<?= e(ADDR_CITY) ?>",
      "addressRegion": "<?= e(ADDR_PROV) ?>",
      "addressCountry": "IT"
    }
  }
  </script>
</head>
<body>
<?php if (seo_allow_index()): ?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PDNMZNTM" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?php endif; ?>
<a class="skip" href="#contenuto">Vai al contenuto</a>
<header class="site-header">
  <div class="wrap header-bar">
    <a class="logo" href="/" title="iCash, home" aria-label="iCash, torna alla home">
      <img src="/assets/img/brand/logo-white.png?v=20260328" alt="iCash" width="160" height="80">
    </a>
    <button class="nav-toggle" type="button" aria-expanded="false" aria-controls="menu">Menu</button>
    <nav id="menu" class="nav">
      <a href="/" title="Home iCash" <?= $page === 'home' ? 'aria-current="page"' : '' ?>>Home</a>
      <a href="/azienda" title="L'azienda iCash" <?= $page === 'azienda' ? 'aria-current="page"' : '' ?>>Azienda</a>
      <a href="/soluzioni" title="Soluzioni per negozi e ristorazione" <?= $page === 'soluzioni' ? 'aria-current="page"' : '' ?>>Soluzioni</a>
      <a href="/prodotti" title="Catalogo prodotti" <?= $page === 'prodotti' ? 'aria-current="page"' : '' ?>>Prodotti</a>
      <a href="/assistenza" title="Assistenza tecnica" <?= $page === 'assistenza' ? 'aria-current="page"' : '' ?>>Assistenza</a>
      <a href="/aggiornamenti" title="Aggiornamenti su registratori e adempimenti" <?= $page === 'aggiornamenti' ? 'aria-current="page"' : '' ?>>Aggiornamenti</a>
      <a href="/contatti" title="Contatti e preventivo" <?= $page === 'contatti' ? 'aria-current="page"' : '' ?>>Contatti</a>
    </nav>
    <a class="btn btn-small header-cta" href="/contatti" title="Richiedi un preventivo">Richiedi un preventivo</a>
  </div>
</header>
<main id="contenuto">
