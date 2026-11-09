<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$page = 'assistenza';
$title = 'Assistenza registratori di cassa | iCash';
$description = 'Assistenza tecnica iCash su registratori telematici, POS, stampanti e casse automatiche. Come chiedere supporto da Ciampino.';
$canonicalPath = '/assistenza';
$waAssistenza = wa_link('Buongiorno, ho bisogno di assistenza tecnica su un sistema di cassa.');
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Quando il punto cassa si ferma.', 'Assistenza tecnica, installazione e configurazione su apparecchiature trattate da iCash. Orari e condizioni di contratto si concordano in proposta.', [['Home', '/'], ['Assistenza', null]], 'assistenza'); ?>

<section class="section">
  <div class="wrap assist-layout">
    <div>
      <h2 class="section-title-sm">Cosa copre il servizio</h2>
      <p>Registratori telematici, sistemi POS, stampanti, palmari comande, cassetti e casse automatiche installati da iCash. Il perimetro di ogni contratto si definisce in fase di proposta.</p>
      <ol class="service-index service-photo" data-motion="stagger">
        <li class="enter enter-up"><a href="/prodotti?categoria=registratori" title="Registratori di cassa"><strong>Registratori telematici</strong><img src="/assets/img/prodotti/onda.webp" alt="Registratore telematico RCH Onda RT" width="800" height="600"></a></li>
        <li class="enter enter-up"><a href="/prodotti?categoria=touch" title="Sistemi touch e POS"><strong>Sistemi POS</strong><img src="/assets/img/prodotti/at15.webp" alt="Terminale touch RCH AT15" width="800" height="600"></a></li>
        <li class="enter enter-up"><a href="/prodotti?categoria=periferiche" title="Stampanti e periferiche"><strong>Stampanti</strong><img src="/assets/img/prodotti/print30.webp" alt="Stampante fiscale RCH Print 3.0 RT" width="800" height="600"></a></li>
        <li class="enter enter-up"><a href="/prodotti?audience=ristorazione" title="Prodotti per la ristorazione"><strong>Palmari comande</strong><img src="/assets/img/stock/palmare-comande.jpg" alt="Terminale portatile usato al tavolo" width="540" height="432"></a></li>
        <li class="enter enter-up"><a href="/prodotto/cassetto" title="Scheda cassetto portavalori"><strong>Cassetti</strong><img src="/assets/img/prodotti/cassetto.jpg" alt="Cassetto portavalori aperto" width="800" height="600"></a></li>
        <li class="enter enter-up"><a href="/prodotti?categoria=contante" title="Casse automatiche"><strong>Casse automatiche</strong><img src="/assets/img/prodotti/cashmatic-860.webp" alt="Cassa automatica Cashmatic 860" width="800" height="800"></a></li>
      </ol>
    </div>
    <aside class="assist-side" data-motion="stagger">
      <div class="enter enter-up">
        <h2>Come richiedere supporto</h2>
        <ul class="support-lines">
          <li>
            <span>Telefono</span>
            <a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
          </li>
          <li>
            <span>WhatsApp</span>
            <a data-track="whatsapp" href="<?= e($waAssistenza) ?>" target="_blank" rel="noopener"><?= e(WHATSAPP_LABEL) ?></a>
          </li>
        </ul>
        <p class="hero-actions">
          <a class="btn" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>">Chiama l’assistenza</a>
          <a class="btn" data-track="whatsapp" href="<?= e($waAssistenza) ?>" target="_blank" rel="noopener">WhatsApp assistenza</a>
          <a class="btn btn-line" href="/contatti" title="Modulo contatti">Scrivi dal modulo</a>
        </p>
      </div>
      <div class="enter enter-up support-follow">
        <h2>Cosa preparare</h2>
        <ul class="support-lines">
          <li>Marca e modello</li>
          <li>Cosa compare a video o sullo scontrino</li>
          <li>Se il blocco riguarda la cassa, la stampante o il gestionale</li>
        </ul>
      </div>
    </aside>
  </div>
</section>

<section class="section section-alt" id="marchi">
  <div class="wrap">
    <h2 class="section-title-sm">Marchi su cui interveniamo</h2>
    <p class="narrow">iCash tratta RCH, Custom, Epson e soluzioni Cashmatic. I marchi restano dei rispettivi titolari.</p>
    <ul class="brands brands-open" data-motion="stagger">
      <li class="enter enter-up"><img src="/assets/img/marchi/rch.png" alt="Logo RCH" title="RCH" width="220" height="80"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/custom.png" alt="Logo Custom" title="Custom" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/epson.png" alt="Logo Epson" title="Epson" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/cashmatic.png" alt="Logo Cashmatic" title="Cashmatic" width="220" height="48"></li>
    </ul>
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
<?php require __DIR__ . '/inc/footer.php';
