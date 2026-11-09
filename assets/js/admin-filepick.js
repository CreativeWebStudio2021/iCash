(function () {
  document.querySelectorAll('[data-file-pick]').forEach(function (input) {
    var wrap = input.closest('.file-pick');
    var name = wrap && wrap.querySelector('[data-file-name]');
    if (!name) return;
    input.addEventListener('change', function () {
      var file = input.files && input.files[0];
      name.textContent = file ? file.name : 'Nessun file selezionato';
    });
  });
})();
