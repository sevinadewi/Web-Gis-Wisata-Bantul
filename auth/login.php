<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan field diisi
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($email) || empty($password)) {
        echo "Semua field harus diisi.";
        exit;
    }

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'admin') {
                header("Location: ../dashboard/dashboard.php");
                exit;
            } else {
                echo "Login berhasil. Selamat datang, user!";
                // Atau redirect ke halaman khusus user
                // header("Location: user_home.php");
            }
        } else {
            echo "Password salah.";
        }
    } else {
        echo "No user found.";
    }

    $stmt->close();
    $conn->close();
}
?>
