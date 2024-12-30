<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['id'];
$query = $koneksi->prepare("SELECT * FROM admin_data WHERE id_admin = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_admin = htmlspecialchars($_POST['nama_admin']);

    $update_query = $koneksi->prepare("UPDATE admin_data SET nama_admin = ? WHERE id_admin = ?");
    $update_query->bind_param("si", $nama_admin, $id);
    if ($update_query->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); location.href='admin_data.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data: {$update_query->error}');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit admin</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Edit Admin</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama admin</label>
                <input type="text" name="nama_admin" value="<?php echo htmlspecialchars($row['nama_admin']); ?>"
                    class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="admin_data.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>