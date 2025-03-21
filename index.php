<?php
session_start();
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: views/dashboard/dashboard.php"); // Jika sudah login, arahkan ke dashboard
    exit();
} else {
    header("Location: views/auth/login.php"); // Jika belum login, arahkan ke halaman login
    exit();
}
?>
