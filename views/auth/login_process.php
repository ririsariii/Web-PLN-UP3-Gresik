<?php
session_start();
require '../../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Cek apakah username ada di database
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            // Login berhasil, buat session
            $_SESSION["admin_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            
            // Redirect ke halaman dashboard
            header("Location: ../../views/dashboard/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location.href='../../views/auth/login.php';</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!'); window.location.href='../../views/auth/login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
