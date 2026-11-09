(function () {
  var form = document.getElementById('aggiornamento-form');
  if (!form) return;

  var source = form.querySelector('#testo');
  var area = form.querySelector('[data-rich-area]');
  var toolbar = form.querySelector('.rich-toolbar');
  if (!source || !area) return;

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

  function sync() {
    var html = area.innerHTML.trim();
    if (html === '<br>' || html === '<p><br></p>' || html === '<div><br></div>') html = '';
    source.value = html;
  }

  area.innerHTML = plainToHtml(source.value);
  area.addEventListener('input', sync);
  area.addEventListener('blur', sync);

  if (toolbar) {
    toolbar.addEventListener('click', function (ev) {
      var btn = ev.target.closest('[data-rich-cmd]');
      if (!btn) return;
      ev.preventDefault();
      area.focus();
      var cmd = btn.getAttribute('data-rich-cmd');
      if (cmd === 'createLink') {
        var url = window.prompt('Indirizzo del link (https://… o /percorso)', 'https://');
        if (!url) return;
        document.execCommand('createLink', false, url);
      } else {
        document.execCommand(cmd, false, null);
      }
      sync();
    });
  }

  form.addEventListener('submit', function (ev) {
    sync();
    if (!source.value.replace(/<[^>]+>/g, '').trim()) {
      ev.preventDefault();
      alert('Inserisci il testo dell’aggiornamento.');
      area.focus();
    }
  });
})();
