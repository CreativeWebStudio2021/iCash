<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/form.php';
require_once __DIR__ . '/inc/social.php';
$page = 'contatti';
$title = 'Contatti e preventivo | iCash Ciampino';
$description = 'Richiedi un preventivo a iCash: sede in Via Giacomo Brodolini 33 a Ciampino, telefono, WhatsApp e modulo contatti.';
$canonicalPath = '/contatti';
$prodotto = isset($_GET['prodotto']) ? trim((string) $_GET['prodotto']) : '';
$prodotto = mb_substr($prodotto, 0, 160);
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Chiedi un preventivo.', 'Chiama, scrivi su WhatsApp o compila il modulo. La sede è in Via Giacomo Brodolini 33 a Ciampino.', [['Home', '/'], ['Contatti', null]], 'contatti'); ?>
<section class="section contact-page">
  <div class="wrap contact-grid" data-motion="pair">
    <div class="enter enter-left">
      <h2 class="section-title-sm">Richiesta informazioni</h2>
      <?php render_form('contatti', $prodotto); ?>
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
          <h2>Sede</h2>
          <p><?= e(SITE_LEGAL) ?><br><?= e(ADDR_STREET) ?><br><?= e(ADDR_CAP) ?> <?= e(ADDR_CITY) ?> (<?= e(ADDR_PROV) ?>)<br>P. IVA <?= e(PIVA) ?></p>
        </div>
        <div>
          <h2>Telefono</h2>
          <p><a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a><br>
          <a data-track="phone" href="<?= e(tel_link(PHONE_SECONDARY)) ?>"><?= e(PHONE_SECONDARY_LABEL) ?></a></p>
        </div>
        <div>
          <h2>WhatsApp</h2>
          <p>Cellulare <?= e(WHATSAPP_LABEL) ?>.</p>
          <p><a class="btn btn-small" data-track="whatsapp" href="<?= e(wa_link($prodotto !== '' ? "Buongiorno, vorrei ricevere un preventivo per il prodotto {$prodotto}, che ho visto sul vostro sito." : 'Buongiorno, vorrei informazioni.')) ?>" target="_blank" rel="noopener">Apri WhatsApp</a></p>
        </div>
        <div>
          <h2>Email</h2>
          <p><a href="mailto:<?= e(MAIL_PUBLIC) ?>"><?= e(MAIL_PUBLIC) ?></a></p>
          <h2>Social</h2>
          <?php render_social('light'); ?>
        </div>
      </div>
    </aside>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
