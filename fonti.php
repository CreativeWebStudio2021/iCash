<?php
declare(strict_types=1);
require_once __DIR__ . '/inc/config.php';
$page = 'fonti';
$title = 'Fonti delle immagini | iCash';
$description = 'Crediti delle fotografie stock usate sul sito iCash, con autore e licenza.';
$canonicalPath = '/fonti-immagini';
require __DIR__ . '/inc/header.php';
?>
<?php render_pagetitle('Fonti delle immagini', 'Fotografie stock usate nelle pagine, distinte dalle foto prodotto fornite dal cliente.', [['Home', '/'], ['Fonti delle immagini', null]], 'info'); ?>
<section class="section">
  <div class="wrap prose">
    <p>Le foto dei prodotti e i loghi dei marchi non sono in questo elenco: arrivano dal materiale consegnato o dai siti dei produttori. Qui ci sono solo le immagini stock.</p>
    <h2>Pagine Azienda e Soluzioni</h2>
    <ul>
      <li>Negozi, interno di boutique senza insegne. Foto di Ksenia Chernaya su Pexels. <a href="https://www.pexels.com/photo/clothes-hanged-on-clothes-rack-3965545/" title="Foto originale su Pexels" target="_blank" rel="noopener">Scheda originale</a>. Licenza Pexels.</li>
      <li>Bar e ristoranti, sala con tavoli apparecchiati e banco. Foto di Zak Chapman su Pexels. <a href="https://www.pexels.com/photo/gray-padded-chair-2290753/" title="Foto originale su Pexels" target="_blank" rel="noopener">Scheda originale</a>. Licenza Pexels.</li>
      <li>Assistenza, terminale portatile al tavolo. Foto di Yan Krukau su Pexels. <a href="https://www.pexels.com/photo/man-holding-a-credit-card-terminal-6816413/" title="Foto originale su Pexels" target="_blank" rel="noopener">Scheda originale</a>. Licenza Pexels.</li>
    </ul>
    <p>La licenza Pexels consente l’uso gratuito, anche commerciale, senza obbligo di attribuzione. I crediti restano indicati qui.</p>
  </div>
</section>
<?php require __DIR__ . '/inc/footer.php';