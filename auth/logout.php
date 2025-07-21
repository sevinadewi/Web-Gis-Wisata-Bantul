<?php
session_start();
session_destroy();

echo "Redirecting..."; // debug
header("Location: ../index.php");
exit();
