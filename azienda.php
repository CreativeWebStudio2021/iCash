<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
$page = 'azienda';
$title = 'Chi siamo | iCash, registratori e POS';
$description = 'iCash affianca negozi, bar e ristoranti con registratori telematici, POS, gestionali e casse automatiche. Oltre 30 anni di esperienza e più di 4.000 punti cassa installati.';
$canonicalPath = '/azienda';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle(
    'Il punto cassa, senza complicazioni.',
    'Soluzioni per registratori telematici, POS e gestione del punto vendita food e retail, con assistenza dedicata.',
    [['Home', '/'], ['Azienda', null]],
    'azienda'
); ?>

<section class="section">
  <div class="wrap split-media" data-motion="pair">
    <figure class="azienda-photo enter enter-left">
      <img src="/assets/img/stock/azienda-punto-vendita.jpg" alt="Interno di un punto vendita retail" title="Soluzioni per il punto vendita" width="1600" height="1068">
    </figure>
    <div class="enter enter-right">
      <p class="eyebrow">Chi siamo</p>
      <h2 class="section-title-sm">Un’azienda orientata al punto vendita</h2>
      <p>iCash è un’azienda dinamica e orientata all’innovazione. Nasce per offrire soluzioni di qualità su registratori di cassa telematici, sistemi POS e strumenti completi per la gestione del punto vendita food e retail.</p>
      <p>Affianchiamo attività commerciali, ristoranti, bar, negozi e professionisti con strumenti affidabili, conformi alle normative vigenti e pensati per semplificare il lavoro quotidiano.</p>
      <p>Con un team di professionisti qualificati e una visione orientata al futuro, accompagniamo ogni cliente verso soluzioni personalizzabili sulle sue esigenze.</p>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="wrap">
    <p class="eyebrow">La nostra missione</p>
    <h2 class="section-title-sm">Creare valore sul banco, ogni giorno</h2>
    <p class="narrow">Crediamo che un sistema di cassa efficace sia il cuore di ogni attività commerciale. Lavoriamo su qualità del servizio, innovazione continua, affidabilità, trasparenza, disponibilità e assistenza dedicata.</p>
    <div class="stat-row" data-motion="stagger">
      <div class="stat enter enter-up">
        <strong>30+</strong>
        <span>anni di esperienza nel settore</span>
      </div>
      <div class="stat enter enter-up">
        <strong>4.000+</strong>
        <span>punti cassa installati</span>
      </div>
      <div class="stat enter enter-up">
        <strong>Italia</strong>
        <span>presenza su tutto il territorio nazionale</span>
      </div>
    </div>
  </div>
</section>

<section class="section section-blush">
  <div class="wrap">
    <p class="eyebrow">Cosa facciamo</p>
    <h2 class="section-title-sm">Specializzati nel punto cassa</h2>
    <ol class="service-index" data-motion="stagger">
      <li class="enter enter-up"><strong>Vendita e noleggio</strong> di registratori di cassa telematici.</li>
      <li class="enter enter-up"><strong>Software gestionali</strong> per attività di ogni tipo, secondo le esigenze del cliente.</li>
      <li class="enter enter-up"><strong>Sistemi POS</strong> per retail e ristorazione.</li>
      <li class="enter enter-up"><strong>Periferiche di servizio</strong>: lettori barcode, terminalini e stampanti per comande.</li>
      <li class="enter enter-up"><strong>Assistenza tecnica</strong> rapida e qualificata.</li>
      <li class="enter enter-up"><strong>Installazione e configurazione</strong> complete.</li>
      <li class="enter enter-up"><strong>Cassetti automatici Cashmatic</strong> per la gestione del contante.</li>
      <li class="enter enter-up"><strong>POS per pagamenti elettronici</strong> (carte e bancomat) e tanto altro.</li>
    </ol>
  </div>
</section>

<section class="section section-soft" id="marchi">
  <div class="wrap">
    <p class="eyebrow">Fornitori ufficiali</p>
    <h2 class="section-title-sm">Marchi che trattiamo</h2>
    <p class="narrow">Siamo fornitori ufficiali di RCH, Custom, Epson e delle casse automatiche Cashmatic. I marchi restano dei rispettivi titolari.</p>
    <ul class="brands brands-open" data-motion="stagger">
      <li class="enter enter-up"><img src="/assets/img/marchi/rch.png" alt="Logo RCH" title="RCH" width="220" height="80"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/custom.png" alt="Logo Custom" title="Custom" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/epson.png" alt="Logo Epson" title="Epson" width="220" height="48"></li>
      <li class="enter enter-up"><img src="/assets/img/marchi/cashmatic.png" alt="Logo Cashmatic" title="Cashmatic" width="220" height="48"></li>
    </ul>
  </div>
</section>

<section class="section section-blush" id="catalogo">
  <div class="wrap section-head">
    <h2>Dal catalogo</h2>
    <a class="text-link" href="/prodotti" title="Catalogo prodotti">Tutto il catalogo</a>
  </div>
  <div class="prod-carousel" data-carousel>
    <button type="button" class="prod-carousel-btn" data-carousel-prev aria-label="Prodotti precedenti">‹</button>
    <div class="prod-carousel-view">
      <div class="prod-carousel-track">
        <?php foreach (prodotti() as $p): $label = prodotto_label($p); ?>
          <a class="prod-slide" href="/prodotto/<?= e((string) $p['id']) ?>" title="Scheda <?= e($label) ?>">
            <img src="<?= e((string) $p['image']) ?>" alt="<?= e((string) ($p['alt'] ?? $label)) ?>" width="320" height="240">
            <span><?= e((string) $p['brand']) ?></span>
            <strong><?= e((string) $p['model']) ?></strong>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <button type="button" class="prod-carousel-btn" data-carousel-next aria-label="Prodotti successivi">›</button>
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
