<?php
session_start(); // Pastikan session dimulai hanya sekali di awal
require_once "../models/dataPelangganModel.php";

// Ambil aksi dari parameter URL (default: list)
$action = $_GET['action'] ?? 'list';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PLN UP3 Gresik - Data Pelanggan</title>
    <!-- Custom fonts and styles -->
    <link href="../dashboard/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../dashboard/template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../dashboard/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<!-- Notifikasi -->
<?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Sukses!",
            text: "<?= $_SESSION['success']; ?>",
            confirmButtonText: "OK"
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "<?= $_SESSION['error']; ?>",
            confirmButtonText: "Tutup"
        });
    </script>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>


<?php
// Handle berbagai aksi berdasarkan parameter URL
if ($action == "list") {
    $keyword = $_GET['keyword'] ?? '';
    include "../views/dataPelanggan/index.php";

} elseif ($action == "add") {  
    include "../views/dataPelanggan/add.php";

} elseif ($action == "store" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $IDPEL = $_POST["IDPEL"] ?? '';
    $Nama_Pelanggan = $_POST["Nama_Pelanggan"] ?? '';
    $No_Tlf = $_POST["No_Tlf"] ?? '';
    $emails = $_POST["emails"] ?? []; // Ambil multiple email

    if (empty($IDPEL) || empty($Nama_Pelanggan) || empty($No_Tlf) || empty($emails)) {
        $_SESSION['error'] = "❌ Semua field harus diisi!";
        header("Location: dataPelanggan.php?action=add");
        exit();
    }

    $result = createDataPelanggan($IDPEL, $Nama_Pelanggan, $No_Tlf, $emails);
    
    $_SESSION[$result === true ? 'success' : 'error'] = $result === true ? "✅ Data pelanggan berhasil ditambahkan!" : "❌ Terjadi kesalahan: $result";
    header("Location: dataPelanggan.php?action=list");
    exit();

} elseif ($action == "edit") {
    $data = getDataPelangganById($_GET["IDPEL"]);
    $emails = getEmailsByIDPEL($_GET["IDPEL"]);
    include "../views/dataPelanggan/edit.php";

} elseif ($action == "update" && $_SERVER["REQUEST_METHOD"] == "POST") {
    $IDPEL = $_POST["IDPEL"];
    $Nama_Pelanggan = $_POST["Nama_Pelanggan"];
    $No_Tlf = $_POST["No_Tlf"];
    $emails = $_POST["emails"] ?? [];

    updateDataPelanggan($IDPEL, $Nama_Pelanggan, $No_Tlf, $emails);
    $_SESSION['success'] = "✅ Data pelanggan berhasil diperbarui!";
    
    header("Location: dataPelanggan.php?action=list");
    exit();

} elseif ($action == "delete") {
    deleteDataPelanggan($_GET["IDPEL"]);
    $_SESSION['success'] = "✅ Data pelanggan berhasil dihapus!";
    
    header("Location: dataPelanggan.php?action=list");
    exit();

} elseif ($action == "upload") {  
    $data = getDataPelangganById($_GET["IDPEL"]);
    include "../views/dataPelanggan/upload.php";

} elseif ($action == "process_upload" && $_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_FILES["file"]["name"])) {
        $uploadDir = "../uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $uploadDir . $fileName;

        $_SESSION[move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath) ? 'success' : 'error'] = move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath) ? "✅ File berhasil diupload." : "❌ Gagal mengupload file.";
    } else {
        $_SESSION['error'] = "❌ Tidak ada file yang dipilih.";
    }

    header("Location: dataPelanggan.php?action=list");
    exit();

} elseif ($action == "send_email" && isset($_GET['IDPEL'])) {
    require_once "../controllers/send_email.php";

    $IDPEL = $_GET['IDPEL'];
    $emails = getEmailsByIDPEL($IDPEL);
    $subject = "Notifikasi Pelanggan";
    $message = "<h3>Halo, ini email otomatis dari sistem.</h3><p>Terima kasih telah menggunakan layanan kami.</p>";

    if (!empty($emails)) {
        $allSuccess = array_reduce($emails, function($carry, $email) use ($subject, $message) {
            return sendMail($email, $subject, $message)["status"] === "success" && $carry;
        }, true);

        $_SESSION[$allSuccess ? 'success' : 'error'] = $allSuccess ? "✅ Email berhasil dikirim ke semua pelanggan!" : "❌ Gagal mengirim beberapa email.";
    } else {
        $_SESSION['error'] = "❌ Tidak ada email yang tersedia untuk pelanggan ini.";
    }

    header("Location: dataPelanggan.php?action=list");
    exit();
}
?>

<!-- Tambahkan SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {
    $(".btn-send-email").click(function () {
        var email = $(this).data("email");
        var idpel = $(this).data("idpel");

        if (!email) {
            Swal.fire({
                icon: "warning",
                title: "Email Tidak Ditemukan",
                text: "Tidak ada email yang tersedia untuk pelanggan ini."
            });
            return;
        }

        Swal.fire({
            title: "Konfirmasi",
            text: "Apakah Anda yakin ingin mengirim email?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ya, Kirim!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "controllers/send_email.php",
                    type: "POST",  // Gunakan POST
                    data: { email: email, idpel: idpel },
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            icon: response.status === "success" ? "success" : "error",
                            title: response.status === "success" ? "Sukses!" : "Gagal!",
                            text: response.message
                        });
                    },
                    error: function () {
                        Swal.fire({
                            icon: "error",
                            title: "Gagal!",
                            text: "Tidak dapat terhubung ke server."
                        });
                    }
                });
            }
        });
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-send-email").forEach(button => {
        button.addEventListener("click", function () {
            let email = this.getAttribute("data-email");
            let idpel = this.getAttribute("data-idpel");

            if (!email) {
                alert("Email tidak ditemukan!");
                return;
            }

            fetch("send_email.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: email=${encodeURIComponent(email)}&idpel=${encodeURIComponent(idpel)}
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("✅ Email berhasil dikirim!");
                } else {
                    alert("❌ Gagal mengirim email: " + data.message);
                }
            })
            .catch(error => {
                alert("⚠ Terjadi kesalahan saat menghubungi server.");
                console.error("Error:", error);
            });
        });
    });
});
</script>

</body>

</html>