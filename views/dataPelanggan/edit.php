<?php
require_once '../config/db.php'; // Pastikan koneksi database sudah di-load

// Ambil IDPEL dari parameter URL
$IDPEL = $_GET['IDPEL'] ?? '';

if (!$IDPEL) {
    die("ID pelanggan tidak ditemukan!");
}

// Ambil data pelanggan utama
$queryPelanggan = "SELECT * FROM data_pelanggan WHERE IDPEL = ?";
$stmtPelanggan = $conn->prepare($queryPelanggan);
$stmtPelanggan->bind_param("s", $IDPEL);
$stmtPelanggan->execute();
$resultPelanggan = $stmtPelanggan->get_result();
$data = $resultPelanggan->fetch_assoc();

if (!$data) {
    die("Data pelanggan tidak ditemukan!");
}

// Ambil semua email pelanggan berdasarkan IDPEL
$queryEmails = "SELECT Email FROM emails_pelanggan WHERE IDPEL = ?";
$stmtEmails = $conn->prepare($queryEmails);
$stmtEmails->bind_param("s", $IDPEL);
$stmtEmails->execute();
$resultEmails = $stmtEmails->get_result();

$emails = [];
while ($row = $resultEmails->fetch_assoc()) {
    $emails[] = $row['Email'];
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Pelanggan</title>
    <!-- Custom fonts and styles -->
    <link href="../views/dashboard/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../views/dashboard/template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../views/dashboard/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Edit Data Pelanggan</h3>
            </div>
            <div class="card-body">
                <form action="../controllers/dataPelanggan.php?action=update" method="POST">
                    <input type="hidden" name="IDPEL" value="<?= htmlspecialchars($data['IDPEL']) ?>">
                    
                    <div class="mb-3">
                        <label for="Nama_Pelanggan" class="form-label">Nama Pelanggan:</label>
                        <input type="text" id="Nama_Pelanggan" name="Nama_Pelanggan" class="form-control" 
                            value="<?= htmlspecialchars($data['Nama_Pelanggan']) ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <div id="email-container">
                            <?php if (empty($emails)) $emails = ['']; ?>
                            <?php foreach ($emails as $index => $email) { ?>
                                <div class="input-group mb-2">
                                    <input type="email" name="emails[]" class="form-control" value="<?= htmlspecialchars(trim($email)) ?>" required>
                                    <button type="button" class="btn btn-<?= $index == 0 ? 'success add-email' : 'danger remove-email' ?>">
                                        <?= $index == 0 ? '+' : '-' ?>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="No_Tlf" class="form-label">No Telepon:</label>
                        <input type="tel" id="No_Tlf" name="No_Tlf" class="form-control" 
                            value="<?= htmlspecialchars($data['No_Tlf']) ?>" required>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="dataPelanggan.php?action=list" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#email-container").on("click", ".add-email", function () {
                let newInput = `
                    <div class="input-group mb-2">
                        <input type="email" name="emails[]" class="form-control" placeholder="Masukkan email baru" required>
                        <button type="button" class="btn btn-danger remove-email">-</button>
                    </div>
                `;
                $("#email-container").append(newInput);
            });
            
            $("#email-container").on("click", ".remove-email", function () {
                $(this).parent().remove();
            });
        });
    </script>
</body>
</html>
