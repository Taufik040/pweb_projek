<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['id'];
$query = $koneksi->prepare("SELECT * FROM jenis_surat WHERE id_jenis_surat = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_jenis_surat = htmlspecialchars($_POST['nama_jenis_surat']);
    $deskripsi_jenis_surat = htmlspecialchars($_POST['deskripsi_jenis_surat']);
    $syarat_jenis_surat = htmlspecialchars($_POST['syarat_jenis_surat']);


    $foto = $_FILES['gambar_jenis_surat'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $filename = $row['gambar_jenis_surat'];

    if (!empty($foto['name'])) {
        $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', basename($foto['name'], '.' . $extension)) . '.' . $extension;
        $target_dir = "img/" . $filename;

        if (in_array($foto['type'], $allowed_types)) {
            if (!move_uploaded_file($foto['tmp_name'], $target_dir)) {
                die("<script>alert('Gagal mengunggah file.'); history.back();</script>");
            }
        } else {
            die("<script>alert('Tipe file tidak valid.'); history.back();</script>");
        }
    }

    $update_query = $koneksi->prepare("UPDATE jenis_surat SET nama_jenis_surat = ?, deskripsi_jenis_surat = ?, syarat_jenis_surat = ?, gambar_jenis_surat = ? WHERE id_jenis_surat = ?");
    $update_query->bind_param("ssssi", $nama_jenis_surat, $deskripsi_jenis_surat, $syarat_jenis_surat, $filename, $id);
    if ($update_query->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); location.href='jenis_surat.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: {$update_query->error}');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Edit Jenis Surat</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2>Edit Jenis Surat</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Jenis Surat</label>
                <input type="text" name="nama_jenis_surat"
                    value="<?php echo htmlspecialchars($row['nama_jenis_surat']); ?>" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="gambar_jenis_surat" class="form-control">
                <?php if (!empty($row['gambar_jenis_surat'])): ?>
                    <img src="img/<?php echo htmlspecialchars($row['gambar_jenis_surat']); ?>" width="200">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Deskripsi Surat</label>
                <textarea name="deskripsi_jenis_surat" class="form-control"
                    required><?php echo htmlspecialchars($row['deskripsi_jenis_surat']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Syarat Surat</label>
                <textarea name="syarat_jenis_surat" class="form-control"
                    required><?php echo htmlspecialchars($row['syarat_jenis_surat']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="jenis_surat.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>