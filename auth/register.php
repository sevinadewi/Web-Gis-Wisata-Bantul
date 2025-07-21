<?php
include_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($name) || empty($email) || empty($password)) {
        echo "Semua field harus diisi.";
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $cek = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $cek->bind_param("s", $email);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        echo "Email sudah digunakan.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (name , email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $hashedPassword);

    if ($stmt->execute()) {
        // Redirect ke login.php
        header("Location: ../login.php");
        exit;
    } else {
        echo "Registrasi gagal: " . $stmt->error;
    }

    $stmt->close();
    $cek->close();
    $conn->close();
}
?>
