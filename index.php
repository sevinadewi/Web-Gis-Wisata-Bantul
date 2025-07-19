<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>WebGIS Bantul</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="js/leaflet.ajax.js"></script>
  <style>
    html, body { height: 100%; margin: 0; }
    #map { width: 100%; height: 100vh; }
  </style>
</head>
<body>
<div id="map"></div>

<script>
const map = L.map('map').setView([-7.9, 110.4], 10);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap',
  maxZoom: 18
}).addTo(map);

// ✅ Highlight khusus Kabupaten Bantul
const bantulLayer = new L.GeoJSON.AJAX(["geojson/id-yo.geojson"], {
  style: function(feature) {
    if (feature.properties && feature.properties.name === "Kabupaten Bantul") {
      return { color: "#EBC005", weight: 2, fillOpacity: 0.4 };
    } else {
      return { color: "#ccc", weight: 0, fillOpacity: 0 };
    }
  },
  filter: function(feature) {
    return feature.properties && feature.properties.name === "Kabupaten Bantul";
  },
  onEachFeature: function(feature, layer) {
    layer.bindPopup("Wilayah: " + feature.properties.name);
  }
}).addTo(map);

// ✅ Tambahkan layer area wisata di Bantul (bukan marker titik)
const wisataAreaLayer = L.geoJSON(null, {
  style: function(feature) {
    // Gaya berdasarkan jenis wisata
    const jenis = feature.properties.jenis.toLowerCase();
    let color = "#3388ff"; // default

    if (jenis.includes("pantai")) color = "#0088cc";
    else if (jenis.includes("hutan")) color = "#228B22";
    else if (jenis.includes("budaya")) color = "#A52A2A";

    return {
      color: color,
      weight: 2,
      fillOpacity: 0.4
    };
  },
  onEachFeature: function(feature, layer) {
    const nama = feature.properties.nama;
    const jenis = feature.properties.jenis;
    layer.bindPopup(`<b>${nama}</b><br>Jenis: ${jenis}`);
  }
});

map.addLayer(wisataAreaLayer);

// ✅ Load file geojson area wisata
fetch('geojson/wisata-bantul.geojson')
  .then(res => res.json())
  .then(data => wisataAreaLayer.addData(data));

</script>
</body>
</html>
