<?php
session_start(); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Fungsi Kirim Email
function sendMail($toEmails, $subject, $message, $attachmentPath = null) {
    $mail = new PHPMailer(true);

    try {
        $emailUser = getenv('EMAIL_USER') ?: 'liaadejule@gmail.com'; 
        $emailPass = getenv('EMAIL_PASS') ?: 'ixsl nbif ftwq wkec';

        if (!$emailUser || !$emailPass) {
            return ["status" => "error", "message" => "Konfigurasi SMTP tidak ditemukan."];
        }

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $emailUser;
        $mail->Password   = $emailPass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($emailUser, 'Admin PLN UP3 Gresik');
        $mail->addReplyTo($emailUser, 'Admin PLN UP3 Gresik');

        $emailList = explode(",", $toEmails);
        $validEmails = [];

        foreach ($emailList as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mail->addAddress($email);
                $validEmails[] = $email;
            }
        }

        if (empty($validEmails)) {
            return ["status" => "error", "message" => "Tidak ada alamat email yang valid."];
        }

        if ($attachmentPath && file_exists($attachmentPath)) {
            $mail->addAttachment($attachmentPath);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = nl2br($message);
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return ["status" => "success", "message" => "Email berhasil dikirim ke " . implode(", ", $validEmails)];

    } catch (Exception $e) {
        return ["status" => "error", "message" => "Gagal mengirim email: " . $mail->ErrorInfo];
    }
}

// ✅ Pastikan tidak menampilkan JSON mentah
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['email']) && isset($_GET['idpel'])) {
    $to = $_GET['email'];
    $idpel = $_GET['idpel'];

    $uploadDir = "../uploads/" . $idpel . "/";

    if (!is_dir($uploadDir)) {
        $_SESSION['error'] = "❌ Folder tidak ditemukan untuk ID pelanggan $idpel";
        header("Location: dataPelanggan.php?action=list");
        exit();
    }

    $files = glob($uploadDir . "*.{pdf,jpg,png,docx}", GLOB_BRACE);

    if (empty($files)) {
        $_SESSION['error'] = "❌ Tidak ada file yang ditemukan untuk ID pelanggan $idpel";
        header("Location: dataPelanggan.php?action=list");
        exit();
    }

    usort($files, function ($a, $b) {
        return filemtime($b) - filemtime($a);
    });

    $attachmentPath = $files[0];

    $subject = "Tagihan Listrik PLN - ID: $idpel";
    $message = "
    <h2>Yth. Pelanggan PLN</h2>
    <p>Kami informasikan bahwa file terkait tagihan listrik Anda telah tersedia.</p>
    <p><strong>ID Pelanggan:</strong> $idpel</p>
    <p><strong>Tanggal:</strong> " . date("d F Y") . "</p>
    <p>Silakan unduh file terlampir untuk informasi lebih lanjut.</p>
    <p>Jika ada pertanyaan, hubungi layanan pelanggan PLN 123.</p>
    <br>
    <p><strong>PLN UP3 Gresik</strong></p>
    ";

    $response = sendMail($to, $subject, $message, $attachmentPath);
    
    if ($response["status"] === "success") {
        $_SESSION['success'] = $response["message"];
    } else {
        $_SESSION['error'] = $response["message"];
    }

    // ✅ Redirect ke halaman utama
    header("Location: dataPelanggan.php?action=list");
    exit();
}
?>
