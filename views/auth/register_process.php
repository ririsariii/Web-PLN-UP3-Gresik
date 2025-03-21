<?php
require '../../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Cek apakah password dan confirm password sesuai
    if ($password !== $confirm_password) {
        echo "<script>alert('Password dan konfirmasi password tidak cocok!'); window.location.href='../../views/auth/register.php';</script>";
        exit();
    }

    // Hash password sebelum disimpan ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Cek apakah username sudah ada
    $query_check = "SELECT * FROM admin WHERE username = ?";
    $stmt_check = $conn->prepare($query_check);
    $stmt_check->bind_param("s", $username);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Username sudah terdaftar!'); window.location.href='../../views/auth/register.php';</script>";
        exit();
    }

    // Tambahkan user baru sebagai admin
    $query_insert = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($query_insert);
    $stmt_insert->bind_param("ss", $username, $hashed_password);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../../views/auth/login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat registrasi!'); window.location.href='../../views/auth/register.php';</script>";
    }

    $stmt_insert->close();
    $conn->close();
}
?>
