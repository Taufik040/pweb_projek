<?php
session_start();
include "koneksi.php";
if (!isset($_SESSION["nama_pengguna"])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu untuk mengakses halaman ini!');
        window.location.href = 'login.php';
    </script>";
    exit();
}
$query_jenis_surat = "SELECT id_jenis_surat, nama_jenis_surat FROM jenis_surat";
$result_jenis_surat = $koneksi->query($query_jenis_surat);
$status_surat = "belum diproses";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jenis_surat = htmlspecialchars($_POST['id_jenis_surat']);
    $tanggal_surat = htmlspecialchars($_POST['tanggal_surat']);
    $file_surat = $_FILES['file_surat'];
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $extension = pathinfo($file_surat['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', basename($file_surat['name'], '.' . $extension)) . '.' . $extension;
    $target_dir = "uploads/" . $filename;
    if (!in_array($file_surat['type'], $allowed_types)) {
        $_SESSION['message'] = 'File harus berupa gambar (JPG/PNG) atau PDF.';
        $_SESSION['message_type'] = 'danger';
    } elseif (!move_uploaded_file($file_surat['tmp_name'], $target_dir)) {
        $_SESSION['message'] = 'Gagal mengunggah file.';
        $_SESSION['message_type'] = 'danger';
    } else {
        $query = $koneksi->prepare("INSERT INTO surat (id_jenis_surat, id_pengguna, tanggal_surat, file_surat, status_surat) VALUES (?, ?, ?, ?, ?)");
        $id_pengguna = $_SESSION['id_pengguna'];
        $query->bind_param("iisss", $id_jenis_surat, $id_pengguna, $tanggal_surat, $filename, $status_surat);
        if ($query->execute()) {
            $_SESSION['message'] = 'Pengajuan surat berhasil dilakukan!';
            $_SESSION['message_type'] = 'success';
            header('Location: pengajuan.php');
            exit();
        } else {
            $_SESSION['message'] = 'Gagal mengajukan surat: ' . $query->error;
            $_SESSION['message_type'] = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #f99d00;
            --secondary-color: #1b163d;
            --background-color: #f8f9fa;
            --text-color: #212529;
            --border-radius: 8px;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Roboto', sans-serif;
            color: var(--text-color);
        }

        .card {
            border-radius: var(--border-radius);
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            font-size: 1.5rem;
            text-align: center;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .form-label {
            font-weight: bold;
            color: var(--secondary-color);
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transition: 0.3s;
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        footer {
            margin-top: 20px;
            background-color: var(--secondary-color);
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        /* Hover Animation */
        .form-control:focus {
            box-shadow: 0 0 5px rgba(249, 157, 0, 0.5);
            border-color: var(--primary-color);
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                Form Pengajuan Surat
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-<?= $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['message']; ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="id_jenis_surat" class="form-label">Jenis Surat</label>
                        <select name="id_jenis_surat" id="id_jenis_surat" class="form-control" required>
                            <option value="" disabled selected>Pilih Jenis Surat</option>
                            <?php while ($row = $result_jenis_surat->fetch_assoc()): ?>
                                <option value="<?= $row['id_jenis_surat']; ?>" <?= ($_GET['jenis'] == $row['nama_jenis_surat']) ? "selected" : "" ?>>
                                    <?= htmlspecialchars($row['nama_jenis_surat']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                    </div>
                    <div class="form-group">
                        <label for="file_surat" class="form-label">Unggah File (JPG, PNG, PDF)</label>
                        <input type="file" class="form-control-file" id="file_surat" name="file_surat"
                            accept=".jpg, .jpeg, .png, .pdf" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Ajukan Surat</button>
                    <a href="surat.php" class="btn btn-secondary btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>