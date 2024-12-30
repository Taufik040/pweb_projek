<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['id'];
$query = $koneksi->prepare("SELECT * FROM informasi WHERE id_info = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_info = htmlspecialchars($_POST['nama_info']);
    $deskripsi_info = htmlspecialchars($_POST['deskripsi_info']);
    $lokasi_info = htmlspecialchars($_POST['lokasi_info']);
    $tanggal_info = htmlspecialchars($_POST['tanggal_info']);


    $foto = $_FILES['gambar_info'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $filename = $row['gambar_info'];

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

    $update_query = $koneksi->prepare("UPDATE informasi SET nama_info = ?, deskripsi_info = ?, tanggal_info = ?, lokasi_info = ?, gambar_info = ? WHERE id_info = ?");
    $update_query->bind_param("ssssss", $nama_info, $deskripsi_info, $tanggal_info, $lokasi_info, $filename, $id);
    if ($update_query->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); location.href='info.php';</script>";
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
        <h2>Edit Informasi</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Informasi</label>
                <input type="text" name="nama_info" value="<?php echo htmlspecialchars($row['nama_info']); ?>"
                    class="form-control" required>
            </div>
            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="gambar_info" class="form-control">
                <?php if (!empty($row['gambar_info'])): ?>
                    <img src="img/<?php echo htmlspecialchars($row['gambar_info']); ?>" width="200">
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label>Deskripsi Info</label>
                <textarea name="deskripsi_info" class="form-control"
                    required><?php echo htmlspecialchars($row['deskripsi_info']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="">Tanggal Info</label>
                <input type="date" name="tanggal_info" id="" class="form-control">
            </div>
            <div class="form-group">
                <label>Lokasi Info</label>
                <textarea name="lokasi_info" class="form-control"
                    required><?php echo htmlspecialchars($row['lokasi_info']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="info.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>