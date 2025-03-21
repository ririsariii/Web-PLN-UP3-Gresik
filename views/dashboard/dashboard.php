<?php
session_start();
if (!isset($_SESSION["admin_id"])) {
    header("Location: ../views/auth/login.php");
    exit();
}

require '../../config/db.php';

// Query jumlah pelanggan
$query = "SELECT COUNT(*) as total FROM data_pelanggan";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$totalPelanggan = $row['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard PLN UP3 Gresik">
    <meta name="author" content="PLN UP3 Gresik">

    <title>PLN UP3 Gresik - Dashboard</title>

    <!-- Custom fonts & styles -->
    <link href="../../views/dashboard/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../../views/dashboard/template/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../../views/dashboard/dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="sidebar-brand-text mx-3">PLN UP3 Gresik</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item ">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <li class="nav-item active">
                <a class="nav-link" href="../../controllers/dataPelanggan.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Data Pelanggan</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin PLN</span>
                                <img class="img-profile rounded-circle" src="../../views/dashboard/template/img/undraw_profile_2.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../../views/auth/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard PLN UP3 Gresik</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Pelanggan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?= $totalPelanggan; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Container Fluid -->
            </div>
            <!-- End Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; PLN UP3 Gresik 2025</span>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->
        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <script src="../../views/dashboard/template/vendor/jquery/jquery.min.js"></script>
    <script src="../../views/dashboard/template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../views/dashboard/template/vendor/jquery-easing/jquery.easing.min.js"></script>
</body>

</html>
