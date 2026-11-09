<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$page = 'soluzioni';
$title = 'Soluzioni per negozi, bar e ristoranti | iCash';
$description = 'Vendita e noleggio di registratori telematici, sistemi POS, gestionali e casse automatiche. Installazione e configurazione per il punto cassa.';
$canonicalPath = '/soluzioni';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Il sistema giusto per il tipo di banco.', 'Vendita o noleggio, poi installazione e configurazione. Il preventivo si chiede in pagina contatti o su WhatsApp.', [['Home', '/'], ['Soluzioni', null]], 'soluzioni'); ?>
<section class="section section-blush">
  <div class="wrap">
    <div class="solution-grid" data-motion="stagger">
      <a class="solution-card enter enter-up" href="/prodotti?categoria=registratori" title="Registratori di cassa">
        <img src="/assets/img/prodotti/onda.webp" alt="Registratore telematico RCH Onda RT" title="Registratore telematico RCH Onda RT" width="800" height="600">
        <h2>Registratori telematici</h2>
        <p>Vendita e noleggio di registratori per l’invio dei corrispettivi. Modelli da banco RCH, Custom ed Epson, compatti o con display touch.</p>
      </a>
      <a class="solution-card enter enter-up" href="/prodotti?categoria=touch" title="Sistemi touch e POS">
        <img src="/assets/img/prodotti/daytona.jpeg" alt="Terminale touch Custom" title="Terminale touch Custom" width="800" height="600">
        <h2>POS e gestionali</h2>
        <p>Sistemi touch collegati a stampante fiscale, lettore barcode o palmare. Il gestionale cambia se l’attività è un negozio oppure un locale.</p>
      </a>
      <a class="solution-card enter enter-up" href="/prodotti?categoria=contante" title="Casse automatiche">
        <img src="/assets/img/prodotti/cashmatic-860.webp" alt="Cassa automatica Cashmatic 860" title="Cassa automatica Cashmatic 860" width="800" height="800">
        <h2>Contante automatico</h2>
        <p>Casse Cashmatic 460, 860 e 1060, e modulo InPay integrato nel banco, per incasso e resto del contante.</p>
      </a>
    </div>
  </div>
</section>
<section class="section section-alt" id="negozi">
  <div class="wrap split-media" data-motion="pair">
    <figure class="enter enter-left">
      <img src="/assets/img/stock/negozio.jpg" alt="Interno di un negozio di abbigliamento, senza insegne" title="Interno di un negozio" width="1600" height="1068">
    </figure>
    <div class="enter enter-right">
      <h2>Negozi</h2>
      <p>Registratori di cassa telematici, PC POS, cassetti portamonete tradizionali e casse automatiche. La stessa base serve farmacie, supermercati, minimarket, abbigliamento e gli altri negozi.</p>
      <p>La composizione, compresi lettori e gestionale, si definisce in preventivo.</p>
      <p class="hero-actions">
        <a class="btn" href="/prodotti?categoria=registratori" title="Registratori di cassa">Vedi i registratori</a>
        <a class="btn btn-line" href="/prodotti?categoria=periferiche" title="Periferiche">Periferiche</a>
        <a class="btn btn-line" href="/assistenza" title="Assistenza tecnica">Assistenza</a>
      </p>
    </div>
  </div>
</section>
<section class="section" id="ristorazione">
  <div class="wrap split-media split-media-reverse" data-motion="pair">
    <figure class="enter enter-right">
      <img src="/assets/img/stock/locale.jpg?v=20260328f" alt="Sala di un ristorante con tavoli apparecchiati e banco bar" title="Sala di un ristorante" width="1600" height="1066">
    </figure>
    <div class="enter enter-left">
      <h2>Bar e ristoranti</h2>
      <p>Software gestionali per tutti i tipi di ristorazione: classica, all you can eat, sushi, poké e altre formule. In sala si aggiungono comande, stampanti e, quando serve, il chiosco.</p>
      <p>Colleghiamo lettori, terminalini e stampanti di comanda, come indicato tra le specializzazioni in presentazione.</p>
      <p class="hero-actions">
        <a class="btn" href="/prodotti?audience=ristorazione" title="Prodotti per bar e ristoranti">Prodotti per la ristorazione</a>
        <a class="btn btn-line" href="/aggiornamenti" title="Aggiornamenti sul punto cassa">Aggiornamenti</a>
        <a class="btn btn-line" href="/contatti" title="Contatti e preventivo">Chiedi un preventivo</a>
      </p>
    </div>
  </div>
</section>
<section class="section section-alt">
  <div class="wrap prose" data-motion="stagger">
    <div class="enter enter-up">
      <h2 class="section-title-sm">Installazione e configurazione</h2>
      <p>Posizionamento, collegamento delle periferiche, impostazione fiscale e prova con chi userà la cassa tutti i giorni. L’assistenza successiva è descritta nella pagina dedicata, senza condizioni economiche non approvate.</p>
      <p class="hero-actions">
        <a class="btn" href="/contatti" title="Contatti e preventivo">Richiedi un preventivo</a>
        <a class="btn btn-line" href="/assistenza" title="Assistenza tecnica">Come funziona l’assistenza</a>
        <a class="btn btn-line" href="/azienda" title="Chi siamo">L’azienda</a>
      </p>
    </div>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';