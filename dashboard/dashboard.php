<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
?>

<h2>Welcome Admin!</h2>
<p>This is the dashboard.</p>
<a href="../auth/logout.php">Logout</a>
