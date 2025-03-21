<?php
require_once "../config/db.php";

// READ - Mengambil semua data pelanggan

function getAllDataPelanggan() {
    global $conn;
    $query = "SELECT dp.IDPEL, dp.Nama_Pelanggan, dp.No_Tlf, 
                     GROUP_CONCAT(ep.Email SEPARATOR ', ') AS Email
              FROM data_pelanggan dp
              LEFT JOIN emails_pelanggan ep ON dp.IDPEL = ep.IDPEL
              GROUP BY dp.IDPEL";
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// CREATE - Menambahkan data pelanggan dengan validasi
function createDataPelanggan($IDPEL, $Nama_Pelanggan, $No_Tlf, $emails) {
    global $conn;
    try {
        $conn->begin_transaction(); // Mulai transaksi

        // Simpan data pelanggan ke tabel utama
        $query = "INSERT INTO data_pelanggan (IDPEL, Nama_Pelanggan, No_Tlf) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $IDPEL, $Nama_Pelanggan, $No_Tlf);

        if (!$stmt->execute()) {
            throw new Exception("Gagal menyimpan data pelanggan.");
        }

        // Simpan semua email pelanggan
        foreach ($emails as $email) {
            $queryEmail = "INSERT INTO emails_pelanggan (IDPEL, Email) VALUES (?, ?)";
            $stmtEmail = $conn->prepare($queryEmail);
            $stmtEmail->bind_param("ss", $IDPEL, $email);

            if (!$stmtEmail->execute()) {
                throw new Exception("Gagal menyimpan email pelanggan.");
            }
        }

        $conn->commit(); // Commit transaksi jika semua berhasil
        return true;
    } catch (Exception $e) {
        $conn->rollback(); // Batalkan transaksi jika ada error
        return $e->getMessage(); // Kembalikan pesan error
    }
}

function getPelangganByID($idpel) {
    global $conn;
    $query = "SELECT dp.IDPEL, dp.Nama_Pelanggan, 
                     GROUP_CONCAT(ep.Email SEPARATOR ', ') AS Email, 
                     dp.No_Tlf 
              FROM data_pelanggan dp
              LEFT JOIN emails_pelanggan ep ON dp.IDPEL = ep.IDPEL
              WHERE dp.IDPEL = ?
              GROUP BY dp.IDPEL, dp.Nama_Pelanggan, dp.No_Tlf";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $idpel);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// READ - Mengambil semua data pelanggan
function getDataPelangganById($IDPEL) {
    global $conn;

    // Ambil data pelanggan
    $stmt = $conn->prepare("SELECT * FROM data_pelanggan WHERE IDPEL = ?");
    $stmt->bind_param("s", $IDPEL);
    $stmt->execute();
    $pelanggan = $stmt->get_result()->fetch_assoc();

    if (!$pelanggan) {
        return null; // Jika pelanggan tidak ditemukan, kembalikan null
    }

    // Ambil email pelanggan
    $pelanggan['emails'] = getEmailsByIDPEL($IDPEL);
    
    return $pelanggan;
}

// Search Data Pelanggan dengan Email
function searchDataPelanggan($keyword) {
    global $conn;
    $query = "SELECT dp.IDPEL, dp.Nama_Pelanggan, 
                     GROUP_CONCAT(ep.Email SEPARATOR ', ') AS Email, 
                     dp.No_Tlf 
              FROM data_pelanggan dp
              LEFT JOIN emails_pelanggan ep ON dp.IDPEL = ep.IDPEL
              WHERE dp.IDPEL LIKE ? OR dp.Nama_Pelanggan LIKE ?
              GROUP BY dp.IDPEL, dp.Nama_Pelanggan, dp.No_Tlf";

    $stmt = $conn->prepare($query);
    $searchKey = "%$keyword%";
    $stmt->bind_param("ss", $searchKey, $searchKey);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


//mendapatkan data email pelanggan
function getEmailsByIDPEL($IDPEL) {
    global $conn;
    $emails = [];

    $query = "SELECT Email FROM emails_pelanggan WHERE IDPEL = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $IDPEL);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['Email'];
    }
    return $emails;
}


// UPDATE - Mengupdate data pelanggan berdasarkan IDPEL
function updateDataPelanggan($IDPEL, $Nama_Pelanggan, $No_Tlf, $emails) {
    global $conn;

    try {
        $conn->begin_transaction(); // Mulai transaksi

        // Update data pelanggan
        $query = "UPDATE data_pelanggan SET Nama_Pelanggan = ?, No_Tlf = ? WHERE IDPEL = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $Nama_Pelanggan, $No_Tlf, $IDPEL);

        if (!$stmt->execute()) {
            throw new Exception("Gagal memperbarui data pelanggan.");
        }

        // Hapus email lama
        $queryDeleteEmails = "DELETE FROM emails_pelanggan WHERE IDPEL = ?";
        $stmtDelete = $conn->prepare($queryDeleteEmails);
        $stmtDelete->bind_param("s", $IDPEL);

        if (!$stmtDelete->execute()) {
            throw new Exception("Gagal menghapus email lama.");
        }

        // Tambahkan email baru
        foreach ($emails as $email) {
            $queryEmail = "INSERT INTO emails_pelanggan (IDPEL, Email) VALUES (?, ?)";
            $stmtEmail = $conn->prepare($queryEmail);
            $stmtEmail->bind_param("ss", $IDPEL, $email);

            if (!$stmtEmail->execute()) {
                throw new Exception("Gagal menyimpan email baru.");
            }
        }

        $conn->commit(); // Commit transaksi jika semua berhasil
        return true;
    } catch (Exception $e) {
        $conn->rollback(); // Batalkan transaksi jika ada error
        return $e->getMessage();
    }
}



// DELETE - Menghapus data pelanggan berdasarkan IDPEL
function deleteDataPelanggan($IDPEL) {
    global $conn;

    try {
        $conn->begin_transaction(); // Mulai transaksi

        // Hapus email pelanggan terlebih dahulu
        $stmtEmails = $conn->prepare("DELETE FROM emails_pelanggan WHERE IDPEL = ?");
        $stmtEmails->bind_param("s", $IDPEL);
        if (!$stmtEmails->execute()) {
            throw new Exception("Gagal menghapus email pelanggan.");
        }

        // Hapus pelanggan
        $stmtPelanggan = $conn->prepare("DELETE FROM data_pelanggan WHERE IDPEL = ?");
        $stmtPelanggan->bind_param("s", $IDPEL);
        if (!$stmtPelanggan->execute()) {
            throw new Exception("Gagal menghapus data pelanggan.");
        }

        $conn->commit(); // Commit transaksi jika semua berhasil
        return true;
    } catch (Exception $e) {
        $conn->rollback(); // Batalkan transaksi jika ada error
        return $e->getMessage();
    }
}

