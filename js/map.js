// map.js

// Inisialisasi map
const map = L.map('mapid').setView([-7.9, 110.4], 10);

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
const bantulLayer = new L.GeoJSON.AJAX(["../geojson/id-yo.geojson"], {
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
    else if (jenis.includes("pemandangan")) color = "#A52A2A";
    else if (jenis.includes("kerajinan")) color = "#ab7a11ff";

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
  "Pemandangan": L.geoJSON(null, {
    filter: f => f.properties.jenis.toLowerCase().includes("pemandangan"),
    style: { color: "#A52A2A", weight: 2, fillOpacity: 0.4 },
    onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
  }),
  "Kerajinan": L.geoJSON(null, {
    filter: f => f.properties.jenis.toLowerCase().includes("kerajinan"),
    style: { color: "#ab7a11ff", weight: 2, fillOpacity: 0.4 },
    onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
  })
};

// Load GeoJSON ke semua layer wisata
fetch('../geojson/wisata-bantul.geojson?' + new Date().getTime())
  .then(res => res.json())
  .then(data => {
    wisataAreaLayer.addData(data);
    Object.values(wisataLayerGroup).forEach(l => l.addData(data));
  });

// Load marker dari PHP
fetch("../auth/get_markers.php")
  .then(res => res.json())
  .then(data => {
    data.forEach(marker => {
      // L.marker([marker.latitude, marker.longitude])
      //   .addTo(map)
      //   .bindPopup(`<b>${marker.place_name}</b><br>${marker.description}`);
      const name = marker.place_name || 'Nama tidak tersedia';
  const description = marker.description || 'Tidak ada deskripsi';
  const category = marker.category || 'Kategori tidak tersedia';

  const popupContent = `
    <b>${name}</b><br>
    <i>${category}</i><br>
    ${description}
  `;

  L.marker([marker.latitude, marker.longitude])
    .addTo(map)
    .bindPopup(popupContent);
    });
  });

// Layer Control
const overlayLayers = {
  "Kabupaten Bantul": bantulLayer,
  "Semua Area Wisata": wisataAreaLayer,
  "Pantai": wisataLayerGroup["Pantai"],
  "Hutan": wisataLayerGroup["Hutan"],
  "Pemandangan": wisataLayerGroup["Pemandangan"],
  "Kerajinan": wisataLayerGroup["Kerajinan"]
};
L.control.layers(baseLayers, overlayLayers).addTo(map);

// Legend
const legend = L.control({ position: "bottomleft" });
legend.onAdd = function () {
  const div = L.DomUtil.create("div", "legend");
  div.innerHTML += "<b>Legenda Wisata</b><br>";
  div.innerHTML += '<i style="background:#0088cc"></i> Pantai<br>';
  div.innerHTML += '<i style="background:#228B22"></i> Hutan<br>';
  div.innerHTML += '<i style="background:#A52A2A"></i> Pemandangan<br>';
  div.innerHTML += '<i style="background:#ab7a11ff"></i> Kerajinan<br>';
  return div;
};
legend.addTo(map);

// Fungsi Geolokasi
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else {
    alert('Your device does not support geolocation.');
  }
}

function showPosition(data) {
  const latInput = document.getElementById('latitude');
  const lonInput = document.getElementById('longitude');
  if (latInput && lonInput) {
    latInput.value = data.coords.latitude;
    lonInput.value = data.coords.longitude;
  }
}

function showError(error) {
  let error_message = '';
  switch (error.code) {
    case error.PERMISSION_DENIED:
      error_message = "User denied the request for Geolocation.";
      break;
    case error.POSITION_UNAVAILABLE:
      error_message = "Location information is unavailable.";
      break;
    case error.TIMEOUT:
      error_message = "The request to get user location timed out.";
      break;
    case error.UNKNOWN_ERROR:
      error_message = "An unknown error occurred.";
      break;
  }
  alert(error_message);
}

let detailMarker;
function showMap(latitude, longitude, name, address) {
  if (detailMarker) {
    map.removeLayer(detailMarker);
  }
  map.setView([latitude, longitude], 15);
  detailMarker = L.marker([latitude, longitude]).addTo(map)
    .bindPopup(`<b>${name}</b><br>${address}`)
    .openPopup();
}

// Panggil geolocation otomatis
getLocation();