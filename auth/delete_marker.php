<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id_marker = intval($_GET['id']);
$id_user = $_SESSION['user_id'];

// Cek apakah marker ini milik user yang sedang login
$check = $conn->prepare("SELECT id_marker FROM markers WHERE id_marker = ? AND id_user = ?");
$check->bind_param("ii", $id_marker, $id_user);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    die("Akses ditolak atau marker tidak ditemukan.");
}

// Hapus marker
$stmt = $conn->prepare("DELETE FROM markers WHERE id_marker = ? AND id_user = ?");
$stmt->bind_param("ii", $id_marker, $id_user);

if ($stmt->execute()) {
    header("Location: ../dashboard/dashboard.php");
    exit;
} else {
    echo "Gagal menghapus marker: " . $stmt->error;
}
