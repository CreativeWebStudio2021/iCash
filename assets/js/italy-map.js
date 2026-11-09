(function () {
  var el = document.getElementById('italy-presence');
  if (!el || typeof L === 'undefined') return;

  var map = L.map(el, { scrollWheelZoom: false });
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 12,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  var fill = '#d01278';
  var stroke = '#9d0d5a';
  var points = [
    [45.07, 7.69], [45.46, 9.19], [45.70, 9.67], [45.81, 9.08], [45.54, 10.22],
    [45.44, 10.99], [45.44, 12.33], [46.07, 11.12], [46.50, 11.35], [45.65, 13.78],
    [44.41, 8.93], [44.49, 11.34], [44.80, 10.33], [44.65, 10.93], [44.05, 12.57],
    [43.72, 10.40], [43.77, 11.26], [43.32, 11.33], [43.62, 13.52], [43.11, 12.39],
    [42.56, 12.65], [42.46, 14.21], [42.35, 13.40], [41.90, 12.49], [41.47, 12.90],
    [41.46, 15.55], [41.12, 16.87], [40.85, 14.27], [40.68, 14.77], [40.64, 15.80],
    [40.35, 18.17], [40.63, 16.60], [39.30, 16.25], [38.91, 16.59], [38.19, 15.55],
    [38.12, 13.36], [37.51, 15.09], [37.07, 15.29], [40.56, 8.32], [40.92, 9.50],
    [39.22, 9.12], [44.31, 9.32], [45.16, 10.79], [41.55, 14.66], [42.95, 12.70]
  ];

  points.forEach(function (point) {
    L.circleMarker(point, {
      radius: 5,
      color: stroke,
      weight: 1,
      fillColor: fill,
      fillOpacity: 0.92
    }).addTo(map);
  });

  L.circleMarker([41.81118, 12.59292], {
    radius: 8,
    color: '#fff',
    weight: 2,
    fillColor: fill,
    fillOpacity: 1
  }).addTo(map).bindTooltip('Sede iCash, Ciampino', { direction: 'top' });

  map.fitBounds([[36.4, 6.6], [47.15, 18.6]], { padding: [12, 12] });
})();
