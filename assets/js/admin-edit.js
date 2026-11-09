(function () {
  var form = document.getElementById('product-form');
  if (!form) return;

  var blocksList = document.getElementById('blocks-list');
  var galleryList = document.getElementById('gallery-list');
  var blockTpl = document.getElementById('tpl-block');

  function typeLabel(type) {
    return ({ testo: 'Testo', elenco: 'Elenco', specifiche: 'Specifiche', immagine: 'Immagine' })[type] || type;
  }

  function escapeHtml(text) {
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function plainToHtml(text) {
    text = String(text || '').trim();
    if (!text) return '<p><br></p>';
    if (/<(p|ul|ol|li|strong|em|b|i|a|br)\b/i.test(text)) return text;
    return text.split(/\n{2,}/).map(function (para) {
      para = para.trim();
      if (!para) return '';
      return '<p>' + escapeHtml(para).replace(/\n/g, '<br>') + '</p>';
    }).filter(Boolean).join('') || '<p><br></p>';
  }

  function syncRichToTextarea(card) {
    var area = card.querySelector('[data-rich-area]');
    var source = card.querySelector('[data-field="testo"]');
    if (!area || !source) return;
    var html = area.innerHTML.trim();
    if (html === '<br>' || html === '<p><br></p>') html = '';
    source.value = html;
  }

  function mountRichEditor(card) {
    var area = card.querySelector('[data-rich-area]');
    var source = card.querySelector('[data-field="testo"]');
    var wrap = card.querySelector('.rich-wrap');
    if (!area || !source || !wrap) return;
    if (card.dataset.type !== 'testo') {
      area.hidden = true;
      return;
    }
    area.hidden = false;
    wrap.hidden = false;
    if (!area.getAttribute('data-rich-ready')) {
      area.innerHTML = plainToHtml(source.value);
      area.setAttribute('data-rich-ready', '1');
      area.addEventListener('input', function () {
        syncRichToTextarea(card);
      });
      area.addEventListener('blur', function () {
        syncRichToTextarea(card);
      });
    }
  }

  function reindexBlocks() {
    if (!blocksList) return;
    blocksList.querySelectorAll('.block-edit').forEach(function (card, i) {
      card.dataset.index = String(i);
      var file = card.querySelector('[data-block-file]');
      if (file) file.setAttribute('name', 'block_file_' + i);
    });
    var empty = document.getElementById('blocks-empty');
    if (empty) empty.hidden = blocksList.children.length > 0;
  }

  function reindexGallery() {
    if (!galleryList) return;
    galleryList.querySelectorAll('.gallery-edit').forEach(function (card, i) {
      card.dataset.index = String(i);
    });
  }

  function moveEl(el, dir) {
    if (!el || !el.parentElement) return;
    if (dir < 0 && el.previousElementSibling) {
      el.parentElement.insertBefore(el, el.previousElementSibling);
    } else if (dir > 0 && el.nextElementSibling) {
      el.parentElement.insertBefore(el.nextElementSibling, el);
    }
  }

  function buildBlock(type, data) {
    data = data || {};
    if (!blockTpl) return null;
    var node = blockTpl.content.firstElementChild.cloneNode(true);
    node.dataset.type = type;
    node.querySelector('[data-type-label]').textContent = typeLabel(type);
    node.querySelector('[name="block_type[]"]').value = type;
    node.querySelector('[name="block_titolo[]"]').value = data.titolo || (type === 'specifiche' ? 'Scheda tecnica' : '');

    node.querySelectorAll('[data-for]').forEach(function (slot) {
      slot.hidden = slot.getAttribute('data-for') !== type;
    });

    var testo = node.querySelector('[data-field="testo"]');
    var voci = node.querySelector('[data-field="voci"]');
    var src = node.querySelector('[data-field="src"]');
    var alt = node.querySelector('[data-field="alt"]');
    var cap = node.querySelector('[data-field="didascalia"]');
    var file = node.querySelector('[data-block-file]');
    var richArea = node.querySelector('[data-rich-area]');

    testo.value = type === 'testo' ? (data.testo || '') : '';
    if (richArea) richArea.hidden = type !== 'testo';
    voci.value = (type === 'elenco' || type === 'specifiche') ? (data.voci || []).join('\n') : '';
    voci.hidden = !(type === 'elenco' || type === 'specifiche');
    voci.placeholder = type === 'specifiche' ? 'Una riga per dato, formato Etichetta: valore' : 'Una voce per riga';
    src.value = type === 'immagine' ? (data.src || '') : '';
    alt.value = type === 'immagine' ? (data.alt || '') : '';
    cap.value = type === 'immagine' ? (data.didascalia || '') : '';
    var altWrap = node.querySelector('[data-for-field="alt"]');
    var capWrap = node.querySelector('[data-for-field="didascalia"]');
    if (altWrap) altWrap.hidden = type !== 'immagine';
    if (capWrap) capWrap.hidden = type !== 'immagine';
    if (file) file.disabled = type !== 'immagine';

    var preview = node.querySelector('[data-img-preview]');
    if (preview) {
      if (type === 'immagine' && data.src) {
        preview.src = data.src;
        preview.hidden = false;
      } else {
        preview.removeAttribute('src');
        preview.hidden = true;
      }
    }
    return node;
  }

  form.querySelectorAll('[data-add-block]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var type = btn.getAttribute('data-add-block');
      var node = buildBlock(type);
      if (!node || !blocksList) return;
      blocksList.appendChild(node);
      reindexBlocks();
      if (type === 'testo') mountRichEditor(node);
      node.scrollIntoView({ behavior: 'smooth', block: 'center' });
      var title = node.querySelector('[name="block_titolo[]"]');
      if (title) title.focus();
    });
  });

  if (blocksList) {
    blocksList.addEventListener('click', function (ev) {
      var richBtn = ev.target.closest('[data-rich-cmd]');
      if (richBtn) {
        ev.preventDefault();
        var card = richBtn.closest('.block-edit');
        var area = card && card.querySelector('[data-rich-area]');
        if (!area) return;
        area.focus();
        var cmd = richBtn.getAttribute('data-rich-cmd');
        if (cmd === 'createLink') {
          var url = window.prompt('Indirizzo del link (https://… o /percorso)', 'https://');
          if (!url) return;
          document.execCommand('createLink', false, url);
        } else {
          document.execCommand(cmd, false, null);
        }
        syncRichToTextarea(card);
        return;
      }

      var btn = ev.target.closest('[data-block-action]');
      if (!btn) return;
      var blockCard = btn.closest('.block-edit');
      if (!blockCard) return;
      var action = btn.getAttribute('data-block-action');
      if (action === 'up') moveEl(blockCard, -1);
      if (action === 'down') moveEl(blockCard, 1);
      if (action === 'remove') {
        if (confirm('Rimuovere questo blocco?')) blockCard.remove();
      }
      reindexBlocks();
    });
  }

  if (galleryList) {
    galleryList.addEventListener('click', function (ev) {
      var btn = ev.target.closest('[data-gallery-action]');
      if (!btn) return;
      var card = btn.closest('.gallery-edit');
      if (!card) return;
      var action = btn.getAttribute('data-gallery-action');
      if (action === 'up') moveEl(card, -1);
      if (action === 'down') moveEl(card, 1);
      if (action === 'remove') card.remove();
      reindexGallery();
    });
  }

  form.addEventListener('submit', function () {
    reindexBlocks();
    form.querySelectorAll('.block-edit').forEach(function (card) {
      var type = card.querySelector('[name="block_type[]"]').value;
      if (type === 'testo') syncRichToTextarea(card);
      var file = card.querySelector('[data-block-file]');
      if (file) file.disabled = type !== 'immagine';
    });
  });

  // Init existing text blocks
  if (blocksList) {
    blocksList.querySelectorAll('.block-edit').forEach(function (card) {
      if (card.dataset.type === 'testo' || (card.querySelector('[name="block_type[]"]') || {}).value === 'testo') {
        card.dataset.type = 'testo';
        mountRichEditor(card);
      }
    });
  }

  reindexBlocks();
  reindexGallery();
})();
