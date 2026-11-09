<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/prodotti.php';
require_once __DIR__ . '/../inc/form.php';
$page = 'landing';
$title = 'Cassa e gestionale per bar e ristoranti | iCash';
$description = 'Sistemi di cassa e gestionali per bar e ristoranti: banco telematico, comande, stampanti e ordine in autonomia. Preventivo iCash, senza acquisto online.';
$canonicalPath = '/landing/bar-ristoranti';
require __DIR__ . '/../inc/header.php';
$ids = ['rch-at15-print', 'custom-fusion', 'kiosk', 'cashmatic-860'];
$wa = wa_link('Buongiorno, vorrei un preventivo per cassa e gestionale per bar o ristorante.');
?>
<section class="page-hero tone-landing">
  <div class="wrap">
    <nav class="crumbs" aria-label="Percorso di navigazione"><a href="/" title="Home">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Bar e ristoranti</span></nav>
    <p class="eyebrow">Food e ristorazione</p>
    <h1>Cassa e gestionale per bar e ristoranti.</h1>
    <p class="lead">Software gestionali per ristorazione classica, all you can eat, sushi, poké e altre formule. Banco telematico, comande e stampanti. Preventivo su misura, senza acquisto online.</p>
    <div class="hero-actions">
      <a class="btn" href="#modulo">Richiedi un preventivo</a>
      <a class="btn" data-track="whatsapp" href="<?= e($wa) ?>" target="_blank" rel="noopener">Scrivi su WhatsApp</a>
      <a class="btn btn-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
    </div>
  </div>
</section>

<section class="section section-blush">
  <div class="wrap solution-grid" data-motion="stagger">
    <article class="solution-card enter enter-up">
      <h2>Banco</h2>
      <p>Registratore touch o terminale con stampante fiscale, pronto per il servizio continuo e per il documento commerciale.</p>
    </article>
    <article class="solution-card enter enter-up">
      <h2>Sala</h2>
      <p>Terminalini per le comande e stampanti di servizio, tra gli strumenti indicati per la ristorazione.</p>
    </article>
    <article class="solution-card enter enter-up">
      <h2>Autonomia</h2>
      <p>Chiosco per l’ordine in sala e, se il contante pesa sul turno, modulo Cashmatic. I prezzi restano nel preventivo.</p>
    </article>
  </div>
</section>

<section class="section">
  <div class="wrap split-media split-media-reverse" data-motion="pair">
    <figure class="enter enter-right">
      <img src="/assets/img/stock/locale.jpg?v=20260328f" alt="Sala di un ristorante con tavoli apparecchiati e banco bar" title="Bar e ristoranti" width="1600" height="1066">
    </figure>
    <div class="enter enter-left">
      <p class="eyebrow">Per bar e ristoranti</p>
      <h2 class="section-title-sm">Cassa al banco, comande in sala</h2>
      <p>Il gestionale food segue il servizio del locale: ordine, stampa e documento commerciale. Chiosco e cassa automatica si aggiungono solo se servono.</p>
      <ul class="pair-list">
        <li><strong>Gestionale food</strong> — per ristorazione classica, all you can eat, sushi, poké e altre formule.</li>
        <li><strong>Comande</strong> — terminalini e stampanti collegati alla cassa.</li>
        <li><strong>Pagamenti</strong> — contante, anche automatico, e POS per carte e bancomat.</li>
        <li><strong>Avvio</strong> — installazione, configurazione e prova con il personale di sala.</li>
      </ul>
    </div>
  </div>
</section>

<section class="section catalog">
  <div class="wrap">
    <div class="section-head">
      <div>
        <p class="eyebrow">Dal catalogo</p>
        <h2 class="section-title-sm">Strumenti utili in un locale</h2>
      </div>
      <a class="text-link" href="/prodotti?audience=ristorazione" title="Prodotti per bar e ristoranti">Vedi la selezione food</a>
    </div>
    <div class="cards cards-soft product-grid" data-motion="stagger">
      <?php foreach (prodotti() as $p):
          if (!in_array($p['id'], $ids, true)) {
              continue;
          }
          $label = prodotto_label($p);
          ?>
        <article class="card soft product-card enter enter-up">
          <a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>"><img src="<?= e((string) $p['image']) ?>" alt="<?= e((string) ($p['alt'] ?? $label)) ?>" title="<?= e((string) ($p['alt'] ?? $label)) ?>" width="640" height="480"></a>
          <p class="brand"><?= e((string) $p['brand']) ?></p>
          <h2><a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>"><?= e((string) $p['model']) ?></a></h2>
          <p><?= e(mb_strimwidth((string) $p['summary'], 0, 110, '…')) ?></p>
          <div class="product-actions">
            <a class="btn btn-small" href="/prodotto/<?= e((string) $p['id']) ?>" title="Apri la scheda di <?= e($label) ?>">Apri scheda prodotto</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-soft">
  <div class="wrap">
    <p class="eyebrow">Marchi</p>
    <h2 class="section-title-sm">Hardware per il servizio</h2>
    <p class="narrow">RCH, Custom, Epson e casse automatiche Cashmatic. I marchi restano dei rispettivi titolari.</p>
    <ul class="brands brands-open" data-motion="stagger">
      <li class="enter enter-up"><img src="/assets/img/marchi/rch.png" alt="Logo RCH" title="RCH" width="220" height="80"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/custom.png" alt="Logo Custom" title="Custom" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/epson.png" alt="Logo Epson" title="Epson" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/cashmatic.png" alt="Logo Cashmatic" title="Cashmatic" width="220" height="48"></li>
    </ul>
  </div>
</section>

<section class="section contact-page" id="modulo">
  <div class="wrap contact-grid" data-motion="pair">
    <div class="enter enter-left">
      <h2 class="section-title-sm">Preventivo per il locale</h2>
      <p>Scrivi quante casse ti servono, se lavori con le comande e se ti interessa l’ordine in autonomia. Ti ricontattiamo noi.</p>
      <?php render_form('landing-bar-ristoranti'); ?>
    </div>
    <aside class="contact-side enter enter-right">
      <div class="contact-map">
        <h2 class="section-title-sm">Dove siamo</h2>
        <div class="map-embed map-embed-side">
          <iframe title="Mappa della sede iCash a Ciampino" src="https://www.openstreetmap.org/export/embed.html?bbox=12.5889%2C41.8087%2C12.5969%2C41.8137&amp;layer=mapnik&amp;marker=41.81118%2C12.59292" loading="lazy"></iframe>
        </div>
        <p class="map-link"><a href="https://www.openstreetmap.org/?mlat=41.81118&amp;mlon=12.59292#map=17/41.81118/12.59292" target="_blank" rel="noopener">Apri la mappa</a></p>
      </div>
      <div class="contact-facts">
        <div>
          <h2>Telefono</h2>
          <p><a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a><br>
          <a data-track="phone" href="<?= e(tel_link(PHONE_SECONDARY)) ?>"><?= e(PHONE_SECONDARY_LABEL) ?></a></p>
        </div>
        <div>
          <h2>WhatsApp</h2>
          <p><a class="btn btn-small" data-track="whatsapp" href="<?= e($wa) ?>" target="_blank" rel="noopener">Scrivi su WhatsApp</a></p>
        </div>
        <div>
          <h2>Sede</h2>
          <p><?= e(SITE_LEGAL) ?><br><?= e(ADDR_STREET) ?><br><?= e(ADDR_CAP) ?> <?= e(ADDR_CITY) ?> (<?= e(ADDR_PROV) ?>)</p>
        </div>
      </div>
    </aside>
  </div>
</section>

<section class="band">
  <div class="wrap band-inner">
    <div>
      <h2>Ti serve un preventivo per il locale?</h2>
      <p>Cassa, gestionale e installazione. Nessun acquisto online.</p>
    </div>
    <a class="btn btn-light" href="#modulo">Richiedi un preventivo</a>
  </div>
</section>
<?php require __DIR__ . '/../inc/footer.php';
