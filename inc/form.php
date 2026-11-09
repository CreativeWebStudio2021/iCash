<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

function render_form(string $context, string $product = ''): void
{
    $err = $_GET['errore'] ?? '';
    $ok = isset($_GET['inviato']);
    ?>
    <?php if ($ok): ?>
      <p class="form-ok" role="status">Richiesta registrata. Ti ricontattiamo al recapito indicato.</p>
    <?php endif; ?>
    <?php if ($err === 'campi'): ?>
      <p class="form-err" role="alert">Compila nome, telefono, email e messaggio, e accetta l’informativa.</p>
    <?php elseif ($err === 'invio'): ?>
      <p class="form-err" role="alert">Invio non riuscito. Chiama il <?= e(PHONE_PRIMARY_LABEL) ?> oppure scrivi su WhatsApp.</p>
    <?php elseif ($err === 'captcha'): ?>
      <p class="form-err" role="alert">Controllo antispam non superato. Riprova tra poco oppure chiama il <?= e(PHONE_PRIMARY_LABEL) ?>.</p>
    <?php endif; ?>
    <form class="lead-form" method="post" action="/invia.php">
      <input type="hidden" name="contesto" value="<?= e($context) ?>">
      <input type="hidden" name="prodotto" value="<?= e($product) ?>">
      <input type="hidden" name="ts" value="<?= time() ?>">
      <div class="hp" aria-hidden="true">
        <label>Sito web<input type="text" name="sito_web" tabindex="-1" autocomplete="off"></label>
      </div>
      <label>Nome e cognome
        <input name="nome" required maxlength="120" autocomplete="name">
      </label>
      <label>Telefono
        <input name="telefono" required maxlength="30" autocomplete="tel" inputmode="tel">
      </label>
      <label>Email
        <input name="email" type="email" required maxlength="160" autocomplete="email">
      </label>
      <label>Attività
        <select name="attivita">
          <option value="">Seleziona</option>
          <option>Negozio</option>
          <option>Bar o ristorante</option>
          <option>Altro</option>
        </select>
      </label>
      <label>Messaggio
        <textarea name="messaggio" required maxlength="2000" rows="5"><?php
          if ($product !== '') {
              echo e("Buongiorno, vorrei ricevere un preventivo per il prodotto {$product}, che ho visto sul vostro sito.");
          }
        ?></textarea>
      </label>
      <label class="check">
        <input type="checkbox" name="privacy" value="1" required>
        <span>Ho letto l’<a href="/privacy" title="Informativa privacy">informativa privacy</a> e acconsento al contatto per questa richiesta.</span>
      </label>
      <button type="submit">Invia richiesta</button>
      <p class="form-note">Ti arriva una email di conferma all’indirizzo indicato. Nessun acquisto online. La richiesta non è un ordine.<?php if (is_production_host()): ?> Questo modulo è protetto da reCAPTCHA: si applicano l’<a href="https://policies.google.com/privacy">informativa privacy</a> e i <a href="https://policies.google.com/terms">termini</a> di Google.<?php else: ?> In questa anteprima il controllo reCAPTCHA è disattivato.<?php endif; ?></p>
    </form>
    <?php
}
