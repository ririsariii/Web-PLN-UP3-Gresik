<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Pastikan PHPMailer sudah di-install via Composer

function sendMail($to, $subject, $body) {
    $mail = new PHPMailer(true);
    
    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('EMAIL_USER'); // Ambil dari variabel lingkungan (.env)
        $mail->Password   = getenv('EMAIL_PASS'); // Ambil dari variabel lingkungan (.env)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Pengaturan email
        $mail->setFrom(getenv('EMAIL_USER'), 'Admin');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return "Email berhasil dikirim!";
    } catch (Exception $e) {
        return "Email gagal dikirim: {$mail->ErrorInfo}";
    }
}
?>
