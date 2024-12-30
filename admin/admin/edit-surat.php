<?php
include "koneksi.php";

// Validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = (int) $_GET['id'];

// Ambil data surat berdasarkan ID
$query = $koneksi->prepare("SELECT * FROM surat WHERE id_surat = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

// Proses update status surat
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status_surat = trim($_POST['status_surat']);

    // Validasi nilai ENUM
    $valid_statuses = ["belum diproses", "sedang diproses", "selesai diproses"];
    if (!in_array($status_surat, $valid_statuses, true)) {
        echo "<script>alert('Status surat tidak valid.');</script>";
    } else {
        $update_query = $koneksi->prepare("UPDATE surat SET status_surat = ? WHERE id_surat = ?");
        $update_query->bind_param("si", $status_surat, $id);

        if ($update_query->execute()) {
            echo "<script>alert('Data berhasil diperbarui'); location.href='surat.php';</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data: {$update_query->error}');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Status Surat</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Edit Status Surat</h2>
        <form method="POST">
            <div class="form-group">
                <label for="status_surat">Status Surat</label>
                <select id="status_surat" name="status_surat" class="form-control" required>
                    <option value="" disabled>Pilih Status</option>
                    <option value="belum diproses" <?= $row['status_surat'] === "belum diproses" ? "selected" : "" ?>>Belum
                        Diproses</option>
                    <option value="sedang diproses" <?= $row['status_surat'] === "sedang diproses" ? "selected" : "" ?>>
                        Sedang Diproses</option>
                    <option value="selesai diproses" <?= $row['status_surat'] === "selesai diproses" ? "selected" : "" ?>>
                        Selesai Diproses</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="surat.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>