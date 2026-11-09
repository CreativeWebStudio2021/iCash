(function () {
  var cfg = window.EVIDENZA || {};
  var max = cfg.max || 4;
  var csrf = cfg.csrf || '';
  var checks = Array.prototype.slice.call(document.querySelectorAll('.evidenza-check'));
  var countEl = document.getElementById('evidenza-count');
  var msgEl = document.getElementById('evidenza-msg');
  var saving = false;
  var timer = null;

  function selectedIds() {
    return checks.filter(function (c) { return c.checked && !c.disabled; }).map(function (c) { return c.value; });
  }

  function updateLabels() {
    var ids = selectedIds();
    if (countEl) countEl.textContent = ids.length + ' / ' + max;
    checks.forEach(function (c) {
      var span = c.parentElement && c.parentElement.querySelector('span');
      if (span) span.textContent = c.checked ? 'Sì' : '—';
      if (!c.disabled && !c.checked && ids.length >= max) {
        c.disabled = true;
        c.dataset.blocked = '1';
      } else if (c.dataset.blocked === '1' && ids.length < max) {
        c.disabled = false;
        delete c.dataset.blocked;
      }
    });
  }

  function setMsg(text, kind) {
    if (!msgEl) return;
    msgEl.textContent = text || '';
    msgEl.className = 'evidenza-msg' + (kind ? ' is-' + kind : '');
  }

  function save() {
    if (saving) return;
    saving = true;
    setMsg('Salvataggio…', 'pending');
    var ids = selectedIds().slice(0, max);
    fetch('/admin/evidenza-save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ csrf: csrf, ids: ids })
    })
      .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
      .then(function (res) {
        if (!res.ok || !res.data || !res.data.ok) {
          throw new Error((res.data && res.data.error) || 'Errore di salvataggio');
        }
        var saved = res.data.ids || ids;
        checks.forEach(function (c) {
          if (c.disabled && c.dataset.blocked !== '1') return;
          c.checked = saved.indexOf(c.value) !== -1;
        });
        updateLabels();
        setMsg(res.data.message || 'Salvato', 'ok');
      })
      .catch(function (err) {
        setMsg(err.message || 'Salvataggio non riuscito', 'err');
      })
      .finally(function () {
        saving = false;
      });
  }

  function queueSave() {
    clearTimeout(timer);
    timer = setTimeout(save, 180);
  }

  checks.forEach(function (c) {
    c.addEventListener('change', function () {
      var ids = selectedIds();
      if (c.checked && ids.length > max) {
        c.checked = false;
        setMsg('Puoi selezionare al massimo ' + max + ' prodotti.', 'err');
        updateLabels();
        return;
      }
      updateLabels();
      queueSave();
    });
  });

  updateLabels();
})();
