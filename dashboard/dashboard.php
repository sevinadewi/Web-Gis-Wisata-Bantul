<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <title>DASHBOARD</title>

  <!-- Bootstrap CSS -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css"
  />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link
  rel="stylesheet"
  href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css"
/>

  <!-- Leaflet & GeoJSON dependencies -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="../js/leaflet.ajax.js"></script>


  <style>
    #mapid {
    width: 100%;
    height: 500px; /* Atau ubah sesuai kebutuhan */
    border-radius: 10px;
    }

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
      width: 8px;
      height: 14px;
      float: left;
      margin-right: 8px;
      opacity: 0.7;
    }

  </style>
</head>
<body>
    <!-- <h2>Welcome Admin!</h2>
<p>This is the dashboard.</p>
<a href="../auth/logout.php">Logout</a> -->


<div class="container mt-4">
  <a href="../auth/logout.php" class="logout-link">
    <i class="fas fa-user-circle"></i> Logout
</a>

  <h3 class="alert alert-info text-center">Data Location</h3>
  <div class="row">
    <!-- Form input -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          Masukkan data lokasi Anda di sini.
          <br><br>
          <form method="post" action="../auth/save_marker.php">
            <label>Nama Tempat</label>
            <input type="text" name="place_name" class="form-control mt-1" required placeholder="Nama Lokasi Anda">

            <label class="mt-4">Kategori</label>
            <input type="text" name="category" class="form-control mt-1" required placeholder="Kategori Lokasi">

            <label class="mt-4">Latitude</label>
            <input type="text" name="latitude" class="form-control mt-1" required id="latitude">

            <label class="mt-4">Longitude</label>
            <input type="text" name="longitude" class="form-control mt-1" required id="longitude">

            <label class="mt-4">Deskripsi</label>
            <input type="text" name="description" class="form-control mt-1" required placeholder="Deskripsi Lokasi">
            

            <button class="btn btn-primary mt-4" type="submit">Submit</button>
       

            </form>
        </div>
      </div>
    </div>
    <!-- Data table and map -->
    <div class="col-lg-8">
      <table class="table table-striped">
        <tr>
          <th>No</th>
          <th>Name</th>
          <th>Kategori</th>
          <th>Lat</th>
          <th>Long</th>
          <th>Deskripsi</th>
          <th>Action</th>
        </tr>
        <?php
            $num = 1;
            require '../auth/db.php';
            $select = $conn->query("SELECT * FROM markers");
            while ($data = $select->fetch_assoc()) {
            ?>
            <tr>
            <td><?= $num++ ?></td>
            <td><?= htmlspecialchars($data['place_name']) ?></td>
             <td><?= htmlspecialchars($data['category']) ?></td>
            <td><?= $data['latitude'] ?></td>
            <td><?= $data['longitude'] ?></td>
            <td><?= htmlspecialchars($data['description']) ?></td>
            <td>-</td> <!-- Ganti jika ingin tambahkan waktu -->
            <td>
                <button
                onclick='showMap(<?= $data["latitude"] ?>, <?= $data["longitude"] ?>, <?= json_encode($data["place_name"]) ?>, <?= json_encode($data["description"]) ?>)'
                class="btn btn-primary btn-sm">
                Location
                </button>
                <a href="../auth/delete_marker.php?id=<?= $data['id_marker'] ?>"
                class="btn btn-danger btn-sm"
                onclick="return confirm('Yakin hapus?');">
                Delete
                </a>
            </td>
            </tr>
        <?php } ?>
      </table>

      <div class="card">
        <div class="card-body">
          <h5 class="alert alert-info text-center">
            Maps
          </h5>
          <div id="mapid"></div>
        </div>
      </div>
    </div>
  </div>


  
</div>
<script src="../js/map.js"></script>

<!-- <script>
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
        console.log("Jenis wisata:", feature.properties.jenis);
        

        const jenis = feature.properties.jenis.toLowerCase();
        let color = "#3388ff";

        if (jenis.includes("pantai")) color = "#0088cc";
        else if (jenis.includes("hutan")) color = "#228B22";
        else if (jenis.includes("budaya")) color = "#A52A2A";
        else if (jenis.includes("kerajinan")) color = "#ab7a11ff";
        console.log("Jenis wisata:", jenis);
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
    fetch('../geojson/wisata-bantul.geojson?' + new Date().getTime())

    .then(res => res.json())
    .then(data => {
        wisataAreaLayer.addData(data);
        Object.values(wisataLayerGroup).forEach(l => l.addData(data));
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
    "Budaya": L.geoJSON(null, {
        filter: f => f.properties.jenis.toLowerCase().includes("budaya"),
        style: { color: "#A52A2A", weight: 2, fillOpacity: 0.4 },
        onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
    }),
    "Kerajinan": L.geoJSON(null, {
        filter: f => f.properties.jenis.toLowerCase().includes("kerajinan"),
        style: { color: "#ab7a11ff", weight: 2, fillOpacity: 0.4 },
        onEachFeature: (f, l) => l.bindPopup(`<b>${f.properties.nama}</b><br>Jenis: ${f.properties.jenis}`)
    })
    };

    // Load GeoJSON ke semua layer wisata filter
    fetch('../geojson/wisata-bantul.geojson')
    .then(res => res.json())
    .then(data => {
        Object.values(wisataLayerGroup).forEach(l => l.addData(data));
    });

    fetch("../auth/get_markers.php")
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
    "Budaya": wisataLayerGroup["Budaya"],
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
    div.innerHTML += '<i style="background:#A52A2A"></i> Budaya<br>';
    div.innerHTML += '<i style="background:#A52A2A"></i> Kerajinan<br>';
    return div;
    };
    legend.addTo(map);
    </script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script
    src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.min.js">
    </script>

    <script>
    function getLocation() {
        if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
        } else {
        alert('Your device does not support geolocation.');
        }
    }

    function showPosition(data) {
        document.getElementById('latitude').value = data.coords.latitude;
        document.getElementById('longitude').value = data.coords.longitude;
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

    let detailMarker;  // untuk menyimpan marker detail yang ditampilkan

    // Inisialisasi peta sekali saja (bisa diletakkan setelah window.onload atau di bagian awal script)
    map = L.map("mapid").setView([-7.9, 110.3], 10); // titik tengah awal Bantul

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Fungsi untuk menampilkan detail lokasi dan memindahkan view
    function showMap(latitude, longitude, name, address) {
        // Hapus marker sebelumnya jika ada
        if (detailMarker) {
            map.removeLayer(detailMarker);
        }

        // Pindah view ke lokasi baru
        map.setView([latitude, longitude], 15);

        // Tambahkan marker baru
        detailMarker = L.marker([latitude, longitude]).addTo(map)
            .bindPopup(`<b>${name}</b><br>${address}`)
            .openPopup();
    }


    getLocation();
</script> -->
</body>
</html>
