<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Input Marker</title>
</head>
<body>
    <h2>Tambah Marker Baru</h2>
    <form action="save_marker.php" method="POST">
        <label>Nama Tempat:</label><br>
        <input type="text" name="place_name" required><br>

        <label>Kategori:</label><br>
        <input type="text" name="category"><br>

        <label>Latitude:</label><br>
        <input type="text" name="latitude" required><br>

        <label>Longitude:</label><br>
        <input type="text" name="longitude" required><br>

        <label>Deskripsi:</label><br>
        <textarea name="description"></textarea><br>

        <button type="submit">Simpan</button>
    </form>
</body>
</html>
