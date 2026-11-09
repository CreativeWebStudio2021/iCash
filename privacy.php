<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$page = 'privacy';
$title = 'Informativa privacy | iCash';
$description = 'Informativa privacy di iCash S.r.l.s.: dati del modulo preventivo, email di conferma, telefono, WhatsApp, cookie e diritti dell’interessato.';
$canonicalPath = '/privacy';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Informativa privacy', 'Come iCash tratta i dati di chi visita il sito o chiede un preventivo.', [['Home', '/'], ['Privacy', null]], 'info'); ?>
<section class="section">
  <div class="wrap prose">
    <p>Questa informativa descrive il trattamento dei dati personali di chi consulta <?= e(SITE_NAME) ?> o invia una richiesta. Il sito presenta registratori di cassa, sistemi POS, software gestionali e soluzioni per il contante, e raccoglie richieste di preventivo. Non c’è un acquisto online e non c’è un’area riservata.</p>
    <p>Ultimo aggiornamento: 29 ottobre 2026.</p>

    <h2>Titolare</h2>
    <p>Titolare del trattamento è <?= e(SITE_LEGAL) ?>, <?= e(ADDR_STREET) ?>, <?= e(ADDR_CAP) ?> <?= e(ADDR_CITY) ?> (<?= e(ADDR_PROV) ?>), P. IVA <?= e(PIVA) ?>.</p>
    <p>Per esercitare i diritti o chiedere informazioni sul trattamento: <a href="mailto:<?= e(MAIL_PUBLIC) ?>"><?= e(MAIL_PUBLIC) ?></a>, telefono <a href="<?= e(tel_link(PHONE_PRIMARY)) ?>"><?= e(PHONE_PRIMARY_LABEL) ?></a>.</p>

    <h2>Dati che ci invii</h2>
    <p>Il modulo di contatto chiede nome e cognome, telefono, email, tipo di attività e messaggio. Se arrivi da una scheda prodotto, nel messaggio può comparire marca e modello. Registriamo anche la pagina da cui parte la richiesta.</p>
    <p>L’email è obbligatoria perché serve a inviarti la conferma di ricezione e a risponderti. Nome, telefono e messaggio sono obbligatori per gestire la richiesta. Il tipo di attività è facoltativo.</p>
    <p>La casella in fondo al modulo è il consenso a essere ricontattato per quella richiesta. Senza la casella il modulo non parte.</p>
    <p>Se chiami i numeri pubblicati o scrivi su WhatsApp, trattiamo il numero e il contenuto della conversazione per risponderti. La chat WhatsApp avviene sull’applicazione di Meta: a quel trattamento si applica anche l’informativa di Meta.</p>

    <h2>Dati raccolti in automatico</h2>
    <p>All’invio del modulo salviamo, insieme alla richiesta, data e ora e l’indirizzo IP di provenienza. Servono a limitare invii automatici e abusi. Il modulo include anche un campo nascosto anti-spam, che una persona non compila.</p>
    <p>Sul dominio icashregister.it, all’invio, il sito usa reCAPTCHA v3 di Google per riconoscere lo spam. Google riceve un segnale tecnico collegato a quell’invio. Sulle anteprime che non usano quel dominio il controllo non viene caricato.</p>
    <p>La navigazione può comportare il trattamento di dati tecnici da parte di servizi incorporati nel sito: carattere da Google Fonts, mappa della home (Leaflet e tessere OpenStreetMap), banner di consenso iubenda e, se accetti la misurazione, Google Tag Manager. Il dettaglio dei cookie è nella <a href="/cookie" title="Cookie policy">cookie policy</a>.</p>

    <h2>Perché li trattiamo</h2>
    <ul>
      <li>Rispondere alla richiesta di preventivo, contatto o assistenza, e inviarti la email di conferma. Base: misure precontrattuali che ci hai chiesto e consenso espresso con la casella del modulo.</li>
      <li>Se dalla richiesta nasce un contratto di vendita, noleggio o assistenza, i dati servono a eseguirlo e agli obblighi contabili e fiscali collegati.</li>
      <li>Proteggere il modulo da invii automatici e conservare traccia tecnica dell’invio. Base: interesse legittimo alla sicurezza del sito.</li>
      <li>Misurare visite e clic su telefono, WhatsApp e invio modulo, solo per le finalità accettate nel banner. Base: consenso.</li>
    </ul>
    <p>Non usiamo i dati per inviare newsletter o promozioni non richieste. Non prendiamo decisioni automatizzate che producano effetti giuridici sulla persona: reCAPTCHA serve solo a filtrare lo spam.</p>

    <h2>A chi li comunichiamo</h2>
    <p>I messaggi del modulo arrivano a <?= e(MAIL_PUBLIC) ?> e restano in un archivio sul server che ospita il sito. La conferma al mittente parte dallo stesso indirizzo, tramite il server di posta aziendale.</p>
    <p>Possono trattare dati, per nostro conto o in proprio, il fornitore di hosting, il gestore della posta, iubenda per il banner di consenso, Google per font, Tag Manager e reCAPTCHA, OpenStreetMap e la rete di distribuzione che carica la mappa, e Meta se scegli WhatsApp.</p>
    <p>Non vendiamo i dati e non li cediamo a elenchi commerciali.</p>

    <h2>Per quanto li teniamo</h2>
    <p>Le richieste a cui non segue un contratto si conservano per 24 mesi dall’ultimo contatto, poi si cancellano o si rendono anonime. Se nasce un rapporto di vendita, noleggio o assistenza, i dati del rapporto restano per la durata del contratto e per i tempi previsti dalla legge civile e fiscale.</p>
    <p>I tempi dei cookie sono descritti nella <a href="/cookie" title="Cookie policy">cookie policy</a>.</p>

    <h2>Trasferimenti fuori dallo Spazio economico europeo</h2>
    <p>Hosting e posta aziendale sono usati per erogare il sito in Italia. Google, la rete che distribuisce la mappa e, se apri WhatsApp, Meta possono trattare dati anche fuori dallo Spazio economico europeo. In quel caso il trasferimento si appoggia alle clausole contrattuali tipo o alle altre garanzie previste dal Regolamento (UE) 2016/679.</p>

    <h2>I tuoi diritti</h2>
    <p>Puoi chiedere accesso, rettifica, cancellazione, limitazione, opposizione e, quando il trattamento si basa sul consenso o su un contratto ed è svolto con mezzi automatizzati, la portabilità. Puoi revocare il consenso in qualsiasi momento: la revoca non tocca i trattamenti già svolti.</p>
    <p>La richiesta si invia a <?= e(MAIL_PUBLIC) ?>. Hai anche il diritto di proporre reclamo al Garante per la protezione dei dati personali (<a href="https://www.garanteprivacy.it" rel="noopener">garanteprivacy.it</a>).</p>

    <h2>Cookie</h2>
    <p>Il sito non ha un’area riservata e non imposta cookie di sessione propri. Il banner, Google Tag Manager e reCAPTCHA sono descritti nella <a href="/cookie" title="Cookie policy">cookie policy</a>.</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';
