<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/lead.php';

$da = (string) ($_GET['da'] ?? '');
$t = (string) ($_GET['t'] ?? '');
$sig = (string) ($_GET['s'] ?? '');
$fresh = lead_thanks_fresh($da, $t, $sig);
if (!in_array($da, ['registratori', 'ristorazione', 'contatti'], true)) {
    $da = 'contatti';
}
$back = match ($da) {
    'registratori' => '/landing/registratori-telematici',
    'ristorazione' => '/landing/bar-ristoranti',
    default => '/contatti',
};

$page = 'grazie';
$title = 'Richiesta ricevuta | iCash';
$description = 'La richiesta di contatto a iCash è stata registrata.';
$canonicalPath = '/grazie';
$robots = 'noindex, nofollow';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Richiesta ricevuta.', 'Ti abbiamo scritto una email di conferma. Ti ricontattiamo al telefono o all’indirizzo indicato.', [['Home', '/'], ['Richiesta ricevuta', null]], 'contatti'); ?>
<section class="section">
  <div class="wrap prose">
    <p>La richiesta non è un ordine e non c’è nessun pagamento da fare online. Se non vedi la conferma, controlla anche la posta indesiderata.</p>
    <p>
      <a class="btn" data-track="phone" href="<?= e(tel_link(PHONE_PRIMARY)) ?>">Chiama <?= e(PHONE_PRIMARY_LABEL) ?></a>
      <a class="btn btn-ghost" href="<?= e($back) ?>">Torna alla pagina precedente</a>
    </p>
  </div>
</section>
<?php if ($fresh): ?>
<script>
window.dataLayer = window.dataLayer || [];
(function () {
  var key = <?= json_encode('lead_ok:' . $sig, JSON_UNESCAPED_UNICODE) ?>;
  try {
    if (window.sessionStorage.getItem(key)) return;
    window.sessionStorage.setItem(key, '1');
  } catch (e) {}
  window.dataLayer.push({
    event: 'lead_ok',
    lead_context: <?= json_encode($da, JSON_UNESCAPED_UNICODE) ?>,
    lead_page: '/grazie'
  });
})();
</script>
<?php endif; ?>
<?php require __DIR__ . '/inc/footer.php';