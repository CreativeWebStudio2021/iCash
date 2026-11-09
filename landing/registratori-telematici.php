<?php
declare(strict_types=1);
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/prodotti.php';
require_once __DIR__ . '/../inc/form.php';
$page = 'landing';
$title = 'Registratori di cassa telematici, vendita e noleggio | iCash';
$description = 'Vendita e noleggio di registratori di cassa telematici RCH, Custom ed Epson. Installazione, configurazione e preventivo iCash, senza acquisto online.';
$canonicalPath = '/landing/registratori-telematici';
require __DIR__ . '/../inc/header.php';
$ids = ['rch-ape3', 'rch-onda', 'custom-bigplus', 'epson-fp81'];
$wa = wa_link('Buongiorno, vorrei un preventivo per un registratore di cassa telematico, vendita o noleggio.');
?>
<section class="page-hero tone-landing">
  <div class="wrap">
    <nav class="crumbs" aria-label="Percorso di navigazione"><a href="/" title="Home">Home</a> <span aria-hidden="true">/</span> <span aria-current="page">Registratori telematici</span></nav>
    <p class="eyebrow">Vendita e noleggio</p>
    <h1>Registratore di cassa telematico, pronto all’uso.</h1>
    <p class="lead">Per negozi, bar e attività che devono emettere il documento commerciale. Marchi RCH, Custom ed Epson, con installazione e configurazione. Nessun prezzo in pagina e nessun acquisto online.</p>
    <div class="hero-actions">
      <a class="btn" href="#modulo">Richiedi un preventivo</a>
      <a class="btn" data-track="whatsapp" href="<?= e($wa) ?>" target="_blank" rel="noopener">Scrivi su WhatsApp</a>
      <a class="btn btn-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
    </div>
  </div>
</section>

<section class="section section-blush">
  <div class="wrap landing-split" data-motion="pair">
    <figure class="landing-cash enter enter-left">
      <img src="/assets/img/prodotti/ape3.webp" alt="Registratore touch RCH APE 3 RT" title="Registratore touch RCH APE 3 RT" width="1200" height="800">
    </figure>
    <div class="landing-stack enter enter-right">
      <article class="solution-card">
        <h2>Documento commerciale</h2>
        <p>Registratori telematici per l’emissione dello scontrino elettronico e l’invio dei corrispettivi, dei marchi RCH, Custom ed Epson.</p>
      </article>
      <article class="solution-card">
        <h2>Vendita o noleggio</h2>
        <p>Entrambe le formule sono previste. Durata, canone e condizioni si definiscono nel preventivo, non in questa pagina.</p>
      </article>
      <article class="solution-card">
        <h2>Installazione inclusa</h2>
        <p>Posizionamento, collegamento di cassetto e periferiche, configurazione e prova con chi userà la cassa tutti i giorni.</p>
      </article>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap">
    <div class="landing-pair" data-motion="stagger">
    <article class="solution-card enter enter-up">
      <p class="eyebrow">A chi si rivolge</p>
      <h2>Negozi, bar e professionisti</h2>
      <p>La proposta parte dal tipo di attività: apertura nuova o sostituzione di un registratore già in uso.</p>
      <ul class="pair-list">
        <li><strong>Negozio</strong> — cassa da banco, lettore e, se serve, più punti cassa.</li>
        <li><strong>Bar e ristorante</strong> — registratore al banco; le comande si possono aggiungere dopo.</li>
        <li><strong>Sostituzione</strong> — passaggio da un apparecchio non più adeguato, con configurazione nella proposta.</li>
      </ul>
    </article>
    <article class="solution-card enter enter-up">
      <p class="eyebrow">Il percorso</p>
      <h2>Dalla richiesta all’installazione</h2>
      <p>Quattro passaggi, gli stessi per vendita e noleggio. Le condizioni economiche restano nel preventivo.</p>
      <ol class="pair-list">
        <li><strong>Richiesta</strong> — tipo di attività, formula scelta, numero di casse.</li>
        <li><strong>Proposta</strong> — modello RCH, Custom o Epson e solo le periferiche necessarie.</li>
        <li><strong>Installazione</strong> — posizionamento, impostazione fiscale e prova operativa.</li>
        <li><strong>Assistenza</strong> — intervento successivo, con condizioni concordate in proposta.</li>
      </ol>
    </article>
    </div>
  </div>
</section>

<section class="section catalog">
  <div class="wrap">
    <div class="section-head">
      <div>
        <p class="eyebrow">Modelli</p>
        <h2 class="section-title-sm">Alcuni registratori del catalogo</h2>
      </div>
      <a class="text-link" href="/prodotti?categoria=registratori" title="Catalogo registratori di cassa">Tutto il catalogo</a>
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
    <p class="eyebrow">Fornitori ufficiali</p>
    <h2 class="section-title-sm">RCH, Custom, Epson</h2>
    <p class="narrow">Registratori dei marchi indicati in presentazione. I marchi restano dei rispettivi titolari.</p>
    <ul class="brands brands-open" data-motion="stagger">
      <li class="enter enter-up"><img src="/assets/img/marchi/rch.png" alt="Logo RCH" title="RCH" width="220" height="80"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/custom.png" alt="Logo Custom" title="Custom" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/epson.png" alt="Logo Epson" title="Epson" width="220" height="48"></li>
    </ul>
  </div>
</section>

<section class="section contact-page" id="modulo">
  <div class="wrap contact-grid" data-motion="pair">
    <div class="enter enter-left">
      <h2 class="section-title-sm">Richiedi il preventivo</h2>
      <p>Indica attività, telefono e se ti serve la vendita o il noleggio. Ti ricontattiamo noi. La richiesta non è un ordine.</p>
      <?php render_form('landing-registratori'); ?>
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
          <p><a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a></p>
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
      <h2>Ti serve un preventivo sul registratore?</h2>
      <p>Vendita o noleggio, con installazione. Nessun acquisto online.</p>
    </div>
    <a class="btn btn-light" href="#modulo">Richiedi un preventivo</a>
  </div>
</section>
<?php require __DIR__ . '/../inc/footer.php';
