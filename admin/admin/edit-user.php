<?php
include "koneksi.php";

if (!isset($_GET['id'])) {
    die("ID tidak ditemukan.");
}

$id = $_GET['id'];
$query = $koneksi->prepare("SELECT * FROM pengguna WHERE id_pengguna = ?");
$query->bind_param("i", $id);
$query->execute();
$result = $query->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pengguna = htmlspecialchars($_POST['nama_pengguna']);
    $email_pengguna = htmlspecialchars($_POST['email_pengguna']);

    $update_query = $koneksi->prepare("UPDATE pengguna SET nama_pengguna = ?, email_pengguna = ? WHERE id_pengguna = ?");
    $update_query->bind_param("ssi", $nama_pengguna, $email_pengguna, $id);
    if ($update_query->execute()) {
        echo "<script>alert('Data berhasil diperbarui'); location.href='user-login.php';</script>";
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
    <title>Edit Pengguna</title>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Edit Pengguna</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Pengguna</label>
                <input type="text" name="nama_pengguna" value="<?php echo htmlspecialchars($row['nama_pengguna']); ?>"
                    class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email Pengguna</label>
                <input type="email" name="email_pengguna"
                    value="<?php echo htmlspecialchars($row['email_pengguna']); ?>" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="admin_data.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>