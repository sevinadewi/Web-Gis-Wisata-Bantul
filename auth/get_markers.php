<?php
require 'db.php';

$sql = "SELECT id_marker, place_name, category, latitude, longitude, description FROM markers";
$result = $conn->query($sql);

$markers = [];

while ($row = $result->fetch_assoc()) {
    $markers[] = $row;
}

header('Content-Type: application/json');
echo json_encode($markers);
