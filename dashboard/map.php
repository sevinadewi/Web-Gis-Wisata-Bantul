<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>WebGIS Bantul</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="../js/leaflet.ajax.js"></script>

  <style>
    html, body { height: 100%; margin: 0; }
    #mapid { width: 100%; height: 100vh; }
    .legend {
      position: absolute;
      bottom: 20px;
      left: 10px;
      background: white;
      padding: 10px;
      border-radius: 5px;
      line-height: 1.5;
      font-size: 14px;
      box-shadow: 0 0 5px rgba(0,0,0,0.3);
    }
    .legend i {
      width: 14px;
      height: 14px;
      float: left;
      margin-right: 8px;
      opacity: 0.7;
    }
  </style>
</head>
<body>

<div id="mapid"></div>
<script src="../js/map.js"></script>

<!-- <script>
// Inisialisasi map
const map = L.map('map').setView([-7.9, 110.4], 10);

// Basemap Layer
const baseLayers = {
  "OpenStreetMap": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap'
  }),
  "Satellite": L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
    attribution: 'Tiles © Esri'
  }),
  "Topographic": L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenTopoMap'
  })
};
baseLayers["OpenStreetMap"].addTo(map); // default

// Layer Wilayah
const bantulLayer = new L.GeoJSON.AJAX(["geojson/id-yo.geojson"], {
  style: function(feature) {
    if (feature.properties && feature.properties.name === "Kabupaten Bantul") {
      return { color: "#EBC005", weight: 2, fillOpacity: 0.4 };
    } else if (feature.properties && feature.properties.name.includes("Sleman")) {
      return { color: "#999", weight: 1, fillOpacity: 0.2 };
    } else {
      return { color: "#ccc", weight: 0.5, fillOpacity: 0.1 };
    }
  },
  onEachFeature: function(feature, layer) {
    layer.bindPopup("Wilayah: " + feature.properties.name);
  }
});
bantulLayer.addTo(map);

// Layer Area Wisata
const wisataAreaLayer = L.geoJSON(null, {
  style: function(feature) {
    const jenis = feature.properties.jenis.toLowerCase();
    let color = "#3388ff";

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
fetch('geojson/wisata-bantul.geojson')
  .then(res => res.json())
  .then(data => wisataAreaLayer.addData(data));

// Layer Group untuk Filter
const wisataLayerGroup = {
  "Pantai": L.geoJSON(null, {
    filter: f => f.properties.jenis.toLowerCase().includes("pantai"),
    style: { color: "#0088cc", weight: 2, fillOpacity: 0.4 },
    onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
  }),
  "Hutan": L.geoJSON(null, {
    filter: f => f.properties.jenis.toLowerCase().includes("hutan"),
    style: { color: "#228B22", weight: 2, fillOpacity: 0.4 },
    onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
  }),
  "Budaya": L.geoJSON(null, {
    filter: f => f.properties.jenis.toLowerCase().includes("budaya"),
    style: { color: "#A52A2A", weight: 2, fillOpacity: 0.4 },
    onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
  })
};

// Load GeoJSON ke semua layer wisata filter
fetch('geojson/wisata-bantul.geojson')
  .then(res => res.json())
  .then(data => {
    Object.values(wisataLayerGroup).forEach(l => l.addData(data));
  });

fetch("auth/get_markers.php")
  .then(res => res.json())
  .then(data => {
    data.forEach(marker => {
      L.marker([marker.latitude, marker.longitude])
        .addTo(map)
        .bindPopup(`<b>${marker.place_name}</b><br>${marker.description}`);
    });
  });


// Layer Control
const overlayLayers = {
  "Kabupaten Bantul": bantulLayer,
  "Semua Area Wisata": wisataAreaLayer,
  "Pantai": wisataLayerGroup["Pantai"],
  "Hutan": wisataLayerGroup["Hutan"],
  "Budaya": wisataLayerGroup["Budaya"]
};

L.control.layers(baseLayers, overlayLayers).addTo(map);

// Legend
const legend = L.control({ position: "bottomleft" });
legend.onAdd = function () {
  const div = L.DomUtil.create("div", "legend");
  div.innerHTML += "<b>Legenda Wisata</b><br>";
  div.innerHTML += '<i style="background:#0088cc"></i> Pantai<br>';
  div.innerHTML += '<i style="background:#228B22"></i> Hutan<br>';
  div.innerHTML += '<i style="background:#A52A2A"></i> Budaya<br>';
  return div;
};
legend.addTo(map);
</script> -->

</body>
</html>
