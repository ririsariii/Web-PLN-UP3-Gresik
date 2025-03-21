<?php if (!isset($data)) { die("Pelanggan tidak ditemukan!"); } ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Upload File untuk <?= htmlspecialchars($data['Nama_Pelanggan']); ?></h3>
            </div>
            <div class="card-body">
                <form action="dataPelanggan.php?action=process_upload" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="file">Pilih File:</label>
                        <input type="file" name="file" id="file" class="form-control-file" required>
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                    <a href="dataPelanggan.php?action=list" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
