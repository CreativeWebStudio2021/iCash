<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/prodotti.php';
$page = 'aggiornamenti';
$title = 'Aggiornamenti su registratori e adempimenti | iCash';
$description = 'Orientamento iCash su collegamento registratore-POS, leggi di bilancio, corrispettivi telematici e fatturazione elettronica. Senza scadenze inventate.';
$canonicalPath = '/aggiornamenti';
$waAssistenza = wa_link('Buongiorno, ho bisogno di assistenza tecnica su un sistema di cassa.');
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Aggiornamenti per chi ha il punto cassa', 'Gli argomenti su cui confrontarci prima di scegliere o aggiornare il registratore. Le scadenze si verificano sul caso concreto.', [['Home', '/'], ['Aggiornamenti', null]], 'info'); ?>
<section class="section">
  <div class="wrap assist-layout">
    <div>
      <div class="topic-index" data-motion="stagger">
        <article class="enter enter-up">
          <h2 id="pagamenti">Registratore e pagamenti elettronici</h2>
          <p>Il collegamento tra registratore telematico e strumento di pagamento elettronico è un adempimento in aggiornamento. Le scadenze sono cambiate nel tempo. Prima di un acquisto o di un aggiornamento guardiamo il banco che hai già, non una regola generica.</p>
        </article>
        <article class="enter enter-up">
          <h2 id="bilancio">Leggi di bilancio</h2>
          <p>Le leggi di bilancio intervengono spesso su corrispettivi, scontrino e fattura. Quando una novità cambia il lavoro in cassa, va letta insieme al modello di registratore e al gestionale, non solo come titolo di giornale.</p>
        </article>
        <article class="enter enter-up">
          <h2 id="corrispettivi">Adempimenti del registratore</h2>
          <p>Invio telematico dei corrispettivi e documento commerciale sono il lavoro quotidiano del registratore. Installazione e assistenza servono a tenerlo allineato, non solo a consegnare l’apparecchio.</p>
        </article>
        <article class="enter enter-up">
          <h2 id="fattura">Fatturazione elettronica</h2>
          <p>Chi emette anche fatture ha un secondo canale, oltre allo scontrino. Il gestionale e il registratore si scelgono di conseguenza, in particolare se l’attività fa sia vendita al banco sia documenti verso altre imprese.</p>
        </article>
      </div>
    </div>
    <aside class="assist-side" data-motion="stagger">
      <div class="enter enter-up">
        <h2>Come richiedere supporto</h2>
        <ul class="support-lines">
          <li>
            <span>Telefono</span>
            <a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>
            <a data-track="phone" href="<?= e(tel_link(PHONE_SECONDARY)) ?>"><?= e(PHONE_SECONDARY_LABEL) ?></a>
          </li>
          <li>
            <span>WhatsApp</span>
            <a data-track="whatsapp" href="<?= e($waAssistenza) ?>" target="_blank" rel="noopener"><?= e(WHATSAPP_LABEL) ?></a>
          </li>
          <li>
            <span>Modulo</span>
            <a href="/contatti" title="Modulo contatti">Scrivi dal modulo contatti</a>
          </li>
        </ul>
        <p class="hero-actions">
          <a class="btn" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>">Chiama</a>
          <a class="btn" data-track="whatsapp" href="<?= e($waAssistenza) ?>" target="_blank" rel="noopener">WhatsApp</a>
          <a class="btn btn-line" href="/assistenza" title="Assistenza tecnica">Pagina assistenza</a>
        </p>
      </div>
    </aside>
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
<?php require __DIR__ . '/inc/footer.php';
