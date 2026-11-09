(function () {
  var tbody = document.querySelector('.crm-table-wrap tbody');
  if (!tbody) return;
  var csrf = (window.ORDINE && window.ORDINE.csrf) || '';
  var msgEl = document.getElementById('ordine-msg');
  var saving = false;
  var again = false;
  var dragRow = null;

  function rows() {
    return Array.prototype.slice.call(tbody.querySelectorAll('tr[data-product-id]'));
  }

  function setMsg(text, kind) {
    if (!msgEl) return;
    msgEl.textContent = text;
    msgEl.className = 'hint' + (kind ? ' is-' + kind : '');
  }

  function renumber() {
    var list = rows();
    list.forEach(function (row, i) {
      var input = row.querySelector('.order-pos');
      if (input) {
        input.value = String(i + 1);
        input.max = String(list.length);
      }
      var up = row.querySelector('[data-move="up"]');
      var down = row.querySelector('[data-move="down"]');
      var first = row.querySelector('[data-move="first"]');
      var last = row.querySelector('[data-move="last"]');
      if (up) up.disabled = i === 0;
      if (first) first.disabled = i === 0;
      if (down) down.disabled = i === list.length - 1;
      if (last) last.disabled = i === list.length - 1;
    });
  }

  function save() {
    if (saving) {
      again = true;
      return;
    }
    saving = true;
    again = false;
    var sent = rows().map(function (row) { return row.getAttribute('data-product-id'); });
    setMsg('Salvataggio ordine…', 'pending');
    fetch('/admin/ordine-save.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      credentials: 'same-origin',
      body: JSON.stringify({ csrf: csrf, ids: sent })
    })
      .then(function (r) { return r.json().then(function (data) { return { ok: r.ok, data: data }; }); })
      .then(function (res) {
        if (!res.ok || !res.data || !res.data.ok) {
          throw new Error((res.data && res.data.error) || 'Salvataggio non riuscito');
        }
        setMsg(res.data.message || 'Ordine del catalogo aggiornato', 'ok');
      })
      .catch(function (err) {
        setMsg(err.message || 'Salvataggio non riuscito', 'err');
      })
      .finally(function () {
        saving = false;
        if (again) save();
      });
  }

  function moveRow(row, to) {
    var list = rows();
    var from = list.indexOf(row);
    if (from < 0) return;
    to = Math.max(0, Math.min(list.length - 1, to));
    if (from === to) {
      renumber();
      return;
    }
    list.splice(from, 1);
    list.splice(to, 0, row);
    list.forEach(function (item) { tbody.appendChild(item); });
    renumber();
    save();
  }

  function clearDrop() {
    rows().forEach(function (row) {
      row.classList.remove('is-drop-before', 'is-drop-after', 'is-dragging');
    });
  }

  tbody.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-move]');
    if (!btn || btn.disabled) return;
    var row = btn.closest('tr');
    if (!row) return;
    var list = rows();
    var i = list.indexOf(row);
    var move = btn.getAttribute('data-move');
    if (move === 'up') moveRow(row, i - 1);
    else if (move === 'down') moveRow(row, i + 1);
    else if (move === 'first') moveRow(row, 0);
    else if (move === 'last') moveRow(row, list.length - 1);
  });

  tbody.addEventListener('change', function (e) {
    var input = e.target.closest('.order-pos');
    if (!input) return;
    var row = input.closest('tr');
    var n = parseInt(input.value, 10);
    var total = rows().length;
    if (!row || !n || n < 1) {
      renumber();
      return;
    }
    moveRow(row, Math.min(total, n) - 1);
  });

  tbody.addEventListener('keydown', function (e) {
    if (e.key !== 'Enter' || !e.target.classList.contains('order-pos')) return;
    e.preventDefault();
    e.target.blur();
  });

  tbody.addEventListener('dragstart', function (e) {
    var handle = e.target.closest('.drag-handle');
    var row = e.target.closest('tr');
    if (!handle || !row) {
      e.preventDefault();
      return;
    }
    dragRow = row;
    row.classList.add('is-dragging');
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/plain', row.getAttribute('data-product-id') || '');
  });

  tbody.addEventListener('dragover', function (e) {
    if (!dragRow) return;
    var row = e.target.closest('tr');
    if (!row || row === dragRow) return;
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    var rect = row.getBoundingClientRect();
    var after = e.clientY > rect.top + rect.height / 2;
    rows().forEach(function (item) { item.classList.remove('is-drop-before', 'is-drop-after'); });
    row.classList.add(after ? 'is-drop-after' : 'is-drop-before');
  });

  tbody.addEventListener('drop', function (e) {
    if (!dragRow) return;
    e.preventDefault();
    var row = e.target.closest('tr');
    var moving = dragRow;
    var placeAfter = false;
    if (row && row !== moving) {
      var rect = row.getBoundingClientRect();
      placeAfter = e.clientY > rect.top + rect.height / 2;
    }
    clearDrop();
    dragRow = null;
    if (!row || row === moving) return;
    var list = rows();
    var to = list.indexOf(row);
    var from = list.indexOf(moving);
    if (placeAfter) to += 1;
    if (from < to) to -= 1;
    moveRow(moving, to);
  });

  tbody.addEventListener('dragend', function () {
    clearDrop();
    dragRow = null;
  });

  renumber();
})();
