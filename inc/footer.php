<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/social.php';
$waGeneric = wa_link('Buongiorno, vorrei informazioni sui vostri registratori di cassa e sistemi POS.');
?>
</main>
<footer class="site-footer">
  <div class="wrap footer-grid">
    <div>
      <p class="logo logo-on-dark">
        <img src="/assets/img/brand/logo-white.png?v=20260328" alt="iCash" width="160" height="80">
      </p>
      <p><?= e(SITE_LEGAL) ?><br><?= e(ADDR_STREET) ?><br><?= e(ADDR_CAP) ?> <?= e(ADDR_CITY) ?> (<?= e(ADDR_PROV) ?>)</p>
      <p>P. IVA <?= e(PIVA) ?></p>
    </div>
    <div>
      <h2>Contatti</h2>
      <p><a data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a><br>
      <a data-track="phone" href="<?= e(tel_link(PHONE_SECONDARY)) ?>"><?= e(PHONE_SECONDARY_LABEL) ?></a></p>
      <p><a href="mailto:<?= e(MAIL_PUBLIC) ?>"><?= e(MAIL_PUBLIC) ?></a></p>
      <p><a data-track="whatsapp" href="<?= e($waGeneric) ?>" target="_blank" rel="noopener">WhatsApp <?= e(WHATSAPP_LABEL) ?></a></p>
    </div>
    <div>
      <h2>Pagine</h2>
      <p><a href="/soluzioni" title="Soluzioni e servizi">Soluzioni</a><br><a href="/prodotti" title="Catalogo prodotti">Prodotti</a><br><a href="/assistenza" title="Assistenza tecnica">Assistenza</a><br><a href="/aggiornamenti" title="Aggiornamenti su registratori e adempimenti">Aggiornamenti</a><br><a href="/mappa-del-sito" title="Mappa del sito">Mappa del sito</a></p>
    </div>
    <div>
      <h2>Social</h2>
      <?php render_social('dark'); ?>
    </div>
  </div>
  <div class="wrap footer-note">
    <p class="footer-legal">
      <a href="/privacy" title="Informativa privacy">Privacy</a>
      <span aria-hidden="true">·</span>
      <a href="/cookie" title="Cookie policy">Cookie</a>
      <span aria-hidden="true">·</span>
      <a href="/fonti-immagini" title="Fonti delle immagini">Fonti delle immagini</a>
    </p>
    <p class="footer-credits">
      <span>Realizzato da</span>
      <a class="credits-logo" href="https://www.creativewebstudio.it" target="_blank" rel="noopener" title="web agency a Civitavecchia, Roma">
        <img src="/assets/img/brand/logo-creativewebstudio.png" alt="web agency a Civitavecchia, Roma" width="500" height="119" loading="lazy">
      </a>
      <span aria-hidden="true">·</span>
      <a class="credits-link" href="https://www.creativewebstudio.it/siti-internet-negozi-commercio" target="_blank" rel="noopener" title="Siti internet per negozi e commercio — Creative Web Studio">Siti internet per negozi e commercio</a>
    </p>
  </div>
</footer>
<div class="dock" role="region" aria-label="Contatti rapidi">
  <a class="dock-phone" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>">Chiama</a>
  <a class="dock-wa" data-track="whatsapp" href="<?= e($waGeneric) ?>" target="_blank" rel="noopener">WhatsApp</a>
  <a class="dock-quote" href="/contatti" title="Richiedi un preventivo">Preventivo</a>
</div>
<?php if (is_production_host()): ?>
<script src="https://www.google.com/recaptcha/api.js?render=<?= e(RECAPTCHA_SITE) ?>"></script>
<script>window.RECAPTCHA_KEY = <?= json_encode(RECAPTCHA_SITE) ?>;</script>
<?php endif; ?>
<script src="/assets/js/site.js?v=20261104g"></script>
</body>
</html>
