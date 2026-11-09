<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
$page = 'home';
$title = 'Registratori di cassa e POS a Ciampino | iCash';
$description = 'iCash è il partner di fiducia per il registratore di cassa: vendita, noleggio e assistenza per negozi, farmacie, supermercati, bar e ristoranti.';
$canonicalPath = '/';
require __DIR__ . '/inc/header.php';
$byId = [];
foreach (prodotti() as $p) {
    $byId[$p['id']] = $p;
}
$inEvidenza = array_values(array_filter(
    evidenza_ids(),
    static fn (string $id): bool => isset($byId[$id])
));
?>
<section class="hero" id="hero" aria-roledescription="carousel" aria-label="Presentazione iCash">
  <div class="hero-slider">
    <article class="hero-slide is-on" data-hero-slide>
      <div class="hero-grid">
        <div class="hero-copy">
          <p class="hero-kicker">Punto cassa, semplice e veloce</p>
          <h1>Il tuo partner di fiducia per il registratore di cassa</h1>
          <p class="lead">Vendita, noleggio e assistenza. Ti affianchiamo nella scelta, nell’installazione e dopo, quando il banco è in funzione.</p>
          <div class="hero-actions">
            <a class="btn" href="/contatti" title="Contatti e preventivo">Richiedi un preventivo</a>
            <a class="btn btn-line" data-track="whatsapp" href="<?= e(wa_link('Buongiorno, vorrei un preventivo per un sistema di cassa.')) ?>" target="_blank" rel="noopener">WhatsApp</a>
            <a class="btn btn-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
          </div>
        </div>
        <div class="hero-collage">
          <img class="shot shot-a" src="/assets/img/prodotti/ape3.webp" alt="Registratore touch RCH APE 3 RT" title="Registratore touch RCH APE 3 RT" width="1200" height="800">
          <img class="shot shot-b" src="/assets/img/prodotti/onda.webp" alt="Registratore RCH Onda RT" title="Registratore RCH Onda RT" width="1200" height="800">
          <img class="shot shot-c" src="/assets/img/prodotti/cashmatic-860.webp" alt="Cassa automatica Cashmatic 860" title="Cassa automatica Cashmatic 860" width="800" height="800">
        </div>
      </div>
    </article>
    <article class="hero-slide" data-hero-slide hidden>
      <div class="hero-grid">
        <div class="hero-copy">
          <p class="hero-kicker">Negozi, farmacie, supermercati</p>
          <p class="hero-title">Registratori, PC POS e casse automatiche</p>
          <p class="lead">Telematico, cassetto e contante automatico, messi insieme sul banco del punto vendita.</p>
          <div class="hero-actions">
            <a class="btn" href="/soluzioni#negozi" title="Soluzioni per negozi">Soluzioni per negozi</a>
            <a class="btn btn-line" data-track="whatsapp" href="<?= e(wa_link('Buongiorno, vorrei un preventivo per un negozio.')) ?>" target="_blank" rel="noopener">WhatsApp</a>
            <a class="btn btn-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
          </div>
        </div>
        <div class="hero-collage">
          <img class="shot shot-a" src="/assets/img/prodotti/print30.webp" alt="Stampante fiscale RCH Print 3.0" title="Stampante fiscale RCH Print 3.0" width="1200" height="800">
          <img class="shot shot-b" src="/assets/img/prodotti/fusion.webp" alt="Terminale touch Custom Fusion" title="Terminale touch Custom Fusion" width="1200" height="800">
          <img class="shot shot-c" src="/assets/img/prodotti/cashmatic-inpay.webp" alt="Modulo Cashmatic InPay integrato nel banco" title="Modulo Cashmatic InPay" width="1024" height="1024">
        </div>
      </div>
    </article>
    <article class="hero-slide" data-hero-slide hidden>
      <div class="hero-grid">
        <div class="hero-copy">
          <p class="hero-kicker">Bar e ristoranti</p>
          <p class="hero-title">Gestionali per ogni formula di ristorazione</p>
          <p class="lead">Classica, all you can eat, sushi, poké e le altre. Banco, comande e cassa automatica.</p>
          <div class="hero-actions">
            <a class="btn" href="/soluzioni#ristorazione" title="Soluzioni per bar e ristoranti">Soluzioni per la ristorazione</a>
            <a class="btn btn-line" data-track="whatsapp" href="<?= e(wa_link('Buongiorno, vorrei un preventivo per un bar o un ristorante.')) ?>" target="_blank" rel="noopener">WhatsApp</a>
            <a class="btn btn-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
          </div>
        </div>
        <div class="hero-collage">
          <img class="shot shot-a" src="/assets/img/prodotti/at15.webp" alt="Terminale touch RCH AT15" title="Terminale touch RCH AT15" width="1200" height="800">
          <img class="shot shot-b" src="/assets/img/prodotti/fp81.webp" alt="Registratore Epson FP-81" title="Registratore Epson FP-81" width="1200" height="800">
          <img class="shot shot-c" src="/assets/img/prodotti/cashmatic-1060.webp" alt="Cassa automatica Cashmatic 1060" title="Cassa automatica Cashmatic 1060" width="800" height="800">
        </div>
      </div>
    </article>
  </div>
  <div class="hero-dots" role="tablist" aria-label="Slide della presentazione">
    <button type="button" class="is-on" role="tab" aria-selected="true" aria-label="Slide 1 di 3"></button>
    <button type="button" role="tab" aria-selected="false" aria-label="Slide 2 di 3"></button>
    <button type="button" role="tab" aria-selected="false" aria-label="Slide 3 di 3"></button>
  </div>
</section>

<section class="section section-magenta" id="settori">
  <div class="wrap">
    <h2>Attività che seguiamo</h2>
    <div class="sector-grid" data-motion="late">
      <article class="sector-card enter enter-up">
        <h3>Farmacie</h3>
        <p>Banco e cassa automatica per l’incasso in farmacia.</p>
      </article>
      <article class="sector-card enter enter-up">
        <h3>Supermercati e minimarket</h3>
        <p>Più punti cassa, lettori e gestione del contante.</p>
      </article>
      <article class="sector-card enter enter-up">
        <h3>Abbigliamento e retail</h3>
        <p>Registratore o PC POS, cassetto e gestionale di negozio.</p>
      </article>
      <article class="sector-card enter enter-up">
        <h3>Ristorazione</h3>
        <p>Classica, all you can eat, sushi, poké e altre formule.</p>
      </article>
    </div>
  </div>
</section>

<section class="section section-blush" id="attivita">
  <div class="wrap split-media" data-motion="pair">
    <figure class="enter enter-left">
      <img src="/assets/img/stock/negozio.jpg" alt="Interno di un negozio" title="Negozi" width="1600" height="1068">
    </figure>
    <div class="enter enter-right">
      <h2>Negozi</h2>
      <p>Registratori di cassa telematici, PC POS, cassetti portamonete tradizionali e casse automatiche.</p>
      <p><a class="text-link" href="/soluzioni#negozi" title="Soluzioni per negozi">Soluzioni per negozi</a></p>
    </div>
  </div>
</section>

<section class="section">
  <div class="wrap split-media split-media-reverse" data-motion="pair">
    <figure class="enter enter-right">
      <img src="/assets/img/stock/locale.jpg?v=20260328f" alt="Sala di un ristorante con tavoli apparecchiati e banco bar" title="Bar e ristoranti" width="1600" height="1066">
    </figure>
    <div class="enter enter-left">
      <h2>Bar e ristoranti</h2>
      <p>Software gestionali per tutti i tipi di ristorazione: classica, all you can eat, sushi, poké e altre formule.</p>
      <p><a class="text-link" href="/soluzioni#ristorazione" title="Soluzioni per bar e ristoranti">Soluzioni per la ristorazione</a></p>
    </div>
  </div>
</section>

<section class="section section-ink" id="cashmatic">
  <div class="wrap">
    <h2>Casse automatiche Cashmatic</h2>
    <p class="narrow">Incasso e resto del contante, in formati da banco e con modulo integrato nel piano. Le foto sono quelle della gamma 460, 860, 1060 e InPay.</p>
    <div class="cash-grid" data-motion="stagger">
      <a class="cash-card enter enter-up" href="/prodotto/cashmatic-460" title="Scheda Cashmatic 460">
        <img src="/assets/img/prodotti/cashmatic-460.webp" alt="Cassa automatica Cashmatic 460" width="800" height="800">
        <h3>460</h3>
        <p>Il formato più compatto.</p>
      </a>
      <a class="cash-card enter enter-up" href="/prodotto/cashmatic-860" title="Scheda Cashmatic 860">
        <img src="/assets/img/prodotti/cashmatic-860.webp" alt="Cassa automatica Cashmatic 860" width="800" height="800">
        <h3>860</h3>
        <p>Il formato da banco intermedio.</p>
      </a>
      <a class="cash-card enter enter-up" href="/prodotto/cashmatic-1060" title="Scheda Cashmatic 1060">
        <img src="/assets/img/prodotti/cashmatic-1060.webp" alt="Cassa automatica Cashmatic 1060" width="800" height="800">
        <h3>1060</h3>
        <p>Il formato più ampio da banco.</p>
      </a>
      <a class="cash-card enter enter-up" href="/prodotto/cashmatic-inpay" title="Scheda Cashmatic InPay">
        <img src="/assets/img/prodotti/cashmatic-inpay.webp" alt="Modulo Cashmatic InPay integrato nel banco" width="1024" height="1024">
        <h3>InPay</h3>
        <p>Modulo integrato nel banco.</p>
      </a>
    </div>
  </div>
</section>

<section class="section section-blush">
  <div class="wrap">
    <h2>Cosa facciamo</h2>
    <ol class="service-index" data-motion="stagger">
      <li class="enter enter-up"><strong>Vendita e noleggio</strong> di registratori di cassa telematici.</li>
      <li class="enter enter-up"><strong>Software gestionali</strong> scelti in base al tipo di attività.</li>
      <li class="enter enter-up"><strong>Sistemi POS</strong> per negozi e ristorazione.</li>
      <li class="enter enter-up"><strong>Lettori, terminalini e stampanti</strong> di servizio.</li>
      <li class="enter enter-up"><strong>Casse automatiche</strong> per la gestione del contante.</li>
      <li class="enter enter-up"><strong>Installazione e assistenza</strong> tecnica.</li>
    </ol>
  </div>
</section>

<section class="section section-soft">
  <div class="wrap">
    <div class="section-head">
      <h2>Prodotti in evidenza</h2>
      <a class="text-link" href="/prodotti" title="Catalogo prodotti">Tutto il catalogo</a>
    </div>
    <div class="cards cards-soft" data-motion="stagger">
      <?php foreach ($inEvidenza as $id): $p = $byId[$id]; ?>
        <article class="card soft enter enter-right">
          <a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e(prodotto_label($p)) ?>"><img src="<?= e($p['image']) ?>" alt="<?= e($p['alt']) ?>" title="<?= e($p['alt']) ?>" width="640" height="480"></a>
          <p class="brand"><?= e($p['brand']) ?></p>
          <h3><a href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e(prodotto_label($p)) ?>"><?= e($p['model']) ?></a></h3>
          <p><?= e(mb_strimwidth((string) $p['summary'], 0, 110, '…')) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-alt" id="marchi">
  <div class="wrap">
    <h2>Marchi trattati</h2>
    <p class="narrow">Siamo fornitori ufficiali di RCH, Custom, Epson e delle casse automatiche Cashmatic. I marchi restano dei rispettivi titolari.</p>
    <ul class="brands brands-open" data-motion="stagger">
      <li class="enter enter-up"><img src="/assets/img/marchi/rch.png" alt="Logo RCH" title="RCH" width="220" height="80"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/custom.png" alt="Logo Custom" title="Custom" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/epson.png" alt="Logo Epson" title="Epson" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/cashmatic.png" alt="Logo Cashmatic" title="Cashmatic" width="220" height="48"></li>
    </ul>
  </div>
</section>

<section class="section" id="territorio">
  <div class="wrap territory" data-motion="pair">
    <div class="enter enter-up">
      <h2>Presenza sul territorio</h2>
      <p>Siamo sul mercato da oltre 30 anni e presenti su tutto il territorio nazionale, con oltre 4.000 punti cassa installati. La sede è a Ciampino, in provincia di Roma, in Via Giacomo Brodolini 33.</p>
      <p>Da qui seguiamo negozi, farmacie, supermercati, bar e ristoranti in Italia: registratori telematici, PC POS, software gestionali e casse automatiche, in vendita o a noleggio. Installazione, configurazione e assistenza restano a carico nostro anche quando il banco è lontano dalla sede.</p>
      <p>Sulla mappa i punti mostrano questa diffusione. Il pallino più evidente è la sede di Ciampino.</p>
    </div>
    <div class="map-embed enter enter-right">
      <div id="italy-presence" class="italy-map" role="img" aria-label="Italia: punti cassa iCash sul territorio nazionale e sede a Ciampino"></div>
    </div>
  </div>
</section>

<section class="section section-blush" id="aggiornamenti">
  <div class="wrap">
    <div class="section-head">
      <h2>Aggiornamenti per chi ha il punto cassa</h2>
      <a class="text-link" href="/aggiornamenti" title="Aggiornamenti su registratori e adempimenti">Vedi gli argomenti</a>
    </div>
    <div class="topic-index" data-motion="stagger">
      <article class="enter enter-up">
        <h3><a href="/aggiornamenti#pagamenti">Registratore e pagamenti elettronici</a></h3>
        <p>Il collegamento tra registratore telematico e POS è un adempimento che va verificato sul banco, non dato per scontato.</p>
      </article>
      <article class="enter enter-up">
        <h3><a href="/aggiornamenti#bilancio">Leggi di bilancio</a></h3>
        <p>Corrispettivi, scontrino e fattura cambiano spesso. Quando una norma tocca la cassa, la spieghiamo qui.</p>
      </article>
      <article class="enter enter-up">
        <h3><a href="/aggiornamenti#corrispettivi">Adempimenti del registratore</a></h3>
        <p>Invio dei corrispettivi e documento commerciale: il registratore deve restare allineato.</p>
      </article>
      <article class="enter enter-up">
        <h3><a href="/aggiornamenti#fattura">Fatturazione elettronica</a></h3>
        <p>Chi emette anche fatture ha un secondo canale, oltre allo scontrino. Gestionale e registratore si scelgono di conseguenza.</p>
      </article>
    </div>
  </div>
</section>

<section class="band">
  <div class="wrap band-inner">
    <div>
      <h2>Ti serve un preventivo sul punto cassa?</h2>
      <p>Vendita o noleggio, con installazione e assistenza. Nessun acquisto online.</p>
    </div>
    <a class="btn btn-light" href="/contatti" title="Contatti e preventivo">Richiedi un preventivo</a>
  </div>
</section>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="/assets/js/italy-map.js?v=20260329a"></script>
<?php require __DIR__ . '/inc/footer.php';
