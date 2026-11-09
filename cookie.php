<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$page = 'cookie';
$title = 'Cookie policy | iCash';
$description = 'Cookie policy iCash: banner iubenda, Google Tag Manager, reCAPTCHA e come viene ricordata la scelta di consenso.';
$canonicalPath = '/cookie';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Cookie policy', 'Come il sito ricorda il consenso e quali strumenti di misurazione usa.', [['Home', '/'], ['Cookie', null]], 'info'); ?>
<section class="section">
  <div class="wrap prose">
    <p>Questa pagina è l’indirizzo indicato in iubenda come cookie policy (<code>https://www.icashregister.it/cookie.php</code>). Il banner di consenso è il widget iubenda incorporato nel sito. Il testo va letto insieme all’<a href="/privacy" title="Informativa privacy">informativa privacy</a>.</p>
    <h2>Titolare</h2>
    <p><?= e(SITE_LEGAL) ?>, <?= e(ADDR_STREET) ?>, <?= e(ADDR_CAP) ?> <?= e(ADDR_CITY) ?> (<?= e(ADDR_PROV) ?>), P. IVA <?= e(PIVA) ?>. Email <?= e(MAIL_PUBLIC) ?>. Telefono <?= e(PHONE_PRIMARY_LABEL) ?>.</p>
    <h2>Preferenza sul banner</h2>
    <p>La scelta (accetta, rifiuta o personalizza) la registra iubenda con un proprio cookie tecnico. Dura finché non si cancellano i dati del sito dal browser oppure non si aggiorna la policy e iubenda richiede di nuovo il consenso.</p>
    <h2>Misurazione</h2>
    <p>È presente Google Tag Manager, contenitore GTM-PDNMZNTM, con Google Consent Mode collegato a iubenda. I cookie di statistica dipendono dai tag attivi in quel contenitore e partono solo per le finalità accettate nel banner.</p>
    <p>Gli eventi inviati al contenitore, dopo il consenso, sono <code>phone</code> (clic sul telefono), <code>whatsapp</code> (clic su WhatsApp), <code>form_submit</code> (invio del modulo) e <code>lead_ok</code> (apertura della pagina di conferma <code>/grazie</code> dopo un invio riuscito).</p>
    <h2>reCAPTCHA</h2>
    <p>Su icashregister.it, al momento dell’invio di un modulo, il sito carica reCAPTCHA v3 di Google. Google può impostare cookie tecnici legati a quel controllo antispam, tra cui <code>_GRECAPTCHA</code>. Non è collegato al banner di misurazione: parte solo per proteggere l’invio. Sulle anteprime che non usano il dominio icashregister.it reCAPTCHA non viene caricato.</p>
    <h2>Cosa non usiamo</h2>
    <p>Il sito non ha un’area riservata e non imposta cookie di sessione propri. Non ci sono cookie di profilazione pubblicitaria installati direttamente dalle pagine, al di fuori di quanto eventualmente attivato dentro Google Tag Manager dopo il consenso.</p>
    <h2>Come togliere la scelta</h2>
    <p>Dalle impostazioni del browser si cancellano cookie e dati dei siti per questo dominio. Alla visita successiva il banner si ripresenta.</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';