<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$place_name = $_POST['place_name'];
$category = $_POST['category'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$description = $_POST['description'];
$id_user = $_SESSION['user_id'];

$sql = "INSERT INTO markers (place_name, category, latitude, longitude, description, id_user)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssddsi", $place_name, $category, $latitude, $longitude, $description, $id_user);

if ($stmt->execute()) {
    header("Location: ../dashboard/dashboard.php");
    exit;
} else {
    echo "Gagal menyimpan marker: " . $stmt->error;
}
