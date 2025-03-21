<?php
session_start(); 
require_once "../config/db.php"; // Koneksi ke database

$action = $_GET['action'] ?? '';

if ($action == "upload" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $IDPEL = $_POST['IDPEL'] ?? '';

    // ✅ Validasi ID pelanggan
    if (!$IDPEL || !is_numeric($IDPEL)) {
        $_SESSION['error'] = "ID pelanggan tidak valid.";
        header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
        exit();
    }

    // ✅ Folder upload per pelanggan
    $uploadDir = realpath("../uploads") . "/" . $IDPEL . "/"; 
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true) && !is_dir($uploadDir)) {
            $_SESSION['error'] = "Gagal membuat folder penyimpanan.";
            header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
            exit();
        }
    }

    // ✅ Validasi file
    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        $_SESSION['error'] = "Tidak ada file yang dipilih.";
        header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
        exit();
    }

    $files = $_FILES['files'];
    $allowedTypes = ["jpg", "jpeg", "png", "pdf", "docx"];
    $maxSize = 2 * 1024 * 1024; // 2MB
    $uploadedFiles = [];

    for ($i = 0; $i < count($files['name']); $i++) {
        $fileName = basename($files['name'][$i]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $fileSize = $files['size'][$i];

        // ✅ Validasi tipe file
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = "Tipe file tidak didukung: $fileName";
            header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
            exit();
        }

        // ✅ Validasi ukuran file
        if ($fileSize > $maxSize) {
            $_SESSION['error'] = "Ukuran file terlalu besar (max 2MB): $fileName";
            header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
            exit();
        }

        // ✅ Hindari nama file duplikat (pakai timestamp + hash)
        $newFileName = time() . "_" . md5($fileName) . "." . $fileType;
        $targetFilePath = $uploadDir . $newFileName;

        // ✅ Upload file
        if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
            $uploadedFiles[] = $newFileName;
        } else {
            $_SESSION['error'] = "Gagal mengupload file: $fileName";
            header("Location: ../controllers/dataPelanggan.php?action=upload&IDPEL=" . urlencode($IDPEL));
            exit();
        }
    }

    // ✅ Sukses
    $_SESSION['success'] = "Invoice berhasil diupload!";
    header("Location: ../controllers/dataPelanggan.php?action=list");
    exit();
}
?>
