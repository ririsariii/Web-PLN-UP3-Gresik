<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Pelanggan</title>
    <!-- Custom fonts and styles -->
    <link href="../views/dashboard/template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="../views/dashboard/template/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../views/dashboard/template/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h4 class="mb-0">Tambah Data Pelanggan</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="dataPelanggan.php?action=store" method="POST">
                            <div class="mb-3">
                                <label for="IDPEL" class="form-label">ID Pelanggan</label>
                                <input type="text" id="IDPEL" name="IDPEL" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="Nama_Pelanggan" class="form-label">Nama Pelanggan</label>
                                <input type="text" id="Nama_Pelanggan" name="Nama_Pelanggan" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email Pelanggan</label>
                                <div id="email-container">
                                    <div class="input-group mb-2">
                                        <input type="email" name="emails[]" class="form-control" placeholder="Masukkan email" required>
                                        <button type="button" class="btn btn-success add-email">+</button>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="No_Tlf" class="form-label">No Telepon</label>
                                <input type="tel" id="No_Tlf" name="No_Tlf" class="form-control" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="dataPelanggan.php?action=list" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
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
                $(this).closest(".input-group").remove();
            });
        });
    </script>
</body>
</html>