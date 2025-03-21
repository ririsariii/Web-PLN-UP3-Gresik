<?php 
include "../config/db.php"; 
require_once "../models/dataPelangganModel.php";

// Pencarian berdasarkan Nama atau IDPEL
$keyword = $_GET['keyword'] ?? '';
$data = (!empty($keyword)) ? searchDataPelanggan($keyword) : getAllDataPelanggan();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PLN UP3 Gresik - Data Pelanggan</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom fonts and styles -->
    <link href="../views/dashboard/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../views/dashboard/template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../views/dashboard/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../views/dashboard/dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">PLN UP3 Gresik</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="../views/dashboard/dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="../controllers/dataPelanggan.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Pelanggan</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- table -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                <h1 class="h3 mb-2 text-gray-800">Data Pelanggan PLN UP3 Gresik</h1>
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <div class="container-fluind">
                <div class="row">
                <div class="col-lg-11 mx-auto"> 
                <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pelanggan UP3 Gresik</h6>
                        </div>

                        <div class="d-flex justify-content mt-3 px-3">
                            <a href="dataPelanggan.php?action=add" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Tambah Data
                            </a>
                        </div>

                        <div class="card-body">                    
                        <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                                <tr>
                                    <th>ID Pelanggan</th>
                                    <th>Nama Pelanggan</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th>Aksi</th>
                                </tr>
                        </thead>
                    <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $row) : ?>
                <tr>
                    <td class="text-center"><?= htmlspecialchars($row['IDPEL']); ?></td>
                    <td><?= htmlspecialchars($row['Nama_Pelanggan']); ?></td>
                    <td>
                        <?php 
                        if (!empty($row['Email'])) {
                            $emails = explode(',', $row['Email']); 
                            echo "<ul class='m-0 p-0' style='list-style-position: inside;'>"; 
                            foreach ($emails as $email) {
                                echo "<li>" . htmlspecialchars(trim($email)) . "</li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<span class='text-muted'>Tidak ada email</span>";
                        }
                        ?>
                    </td>
                    <td class="text-center"><?= htmlspecialchars($row['No_Tlf']); ?></td>
                    <td>
                        <div class="d-flex flex-column gap-2">
                            <!-- ✅ Grup Tombol Edit & Hapus -->
                            <div class="btn-group mb-2">
                                <a href="dataPelanggan.php?action=edit&IDPEL=<?= $row['IDPEL']; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-idpel="<?= $row['IDPEL']; ?>">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>

                            </div>

                            <!-- ✅ Form Upload Invoice -->
                            <form action="uploadController.php?action=upload" method="POST" enctype="multipart/form-data" class="mt-2">
                                <input type="hidden" name="IDPEL" value="<?= $row['IDPEL']; ?>">
                                <input type="file" name="files[]" class="form-control form-control-sm mb-2" multiple required>
                                <button type="submit" class="btn btn-info btn-sm w-100 mb-2">
                                    <i class="fas fa-upload"></i> Upload Invoice
                                </button>
                            </form>

                            <!-- ✅ Tombol Kirim Email -->
                            <a href="send_email.php?email=<?= isset($row['Email']) ? urlencode($row['Email']) : ''; ?>&idpel=<?= isset($row['IDPEL']) ? urlencode($row['IDPEL']) : ''; ?>" class="btn btn-success btn-sm"> ✉ Kirim Email

                            </a>
                        
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
</div>
</div>
</div>
<!-- Footer -->
<footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PLN UP3 Gresik 2025</span>
                    </div>
                </div>
            </footer>

<!-- ✅ Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data pelanggan ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">OK</a>
            </div>
        </div>
    </div>
</div>

<!-- ✅ JavaScript untuk Menangani Hapus -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var confirmDeleteModal = document.getElementById("confirmDeleteModal");
        var confirmDeleteBtn = document.getElementById("confirmDeleteBtn");

        confirmDeleteModal.addEventListener("show.bs.modal", function (event) {
            var button = event.relatedTarget; // Tombol yang diklik
            var idPel = button.getAttribute("data-idpel"); // Ambil IDPEL dari tombol

            // Set href tombol OK agar mengarah ke penghapusan
            confirmDeleteBtn.href = "dataPelanggan.php?action=delete&IDPEL=" + idPel;
        });
    });
</script>


<!-- ✅ Tambahkan FontAwesome untuk ikon -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
            
            <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="../views/dashboard/template/vendor/jquery/jquery.min.js"></script>
    <script src="../views/dashboard/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../views/dashboard/template/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../views/dashboard/template/js/sb-admin-2.min.js"></script>
    <script src="../views/dashboard/template/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../views/dashboard/template/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="../views/dashboard/template/js/demo/datatables-demo.js"></script>
</body>

</html>
