<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION["id_admin"])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu untuk mengakses halaman ini!');
        window.location.href = 'login.php';
    </script>";
    exit();
}

$query_jenis_surat = "SELECT id_jenis_surat, nama_jenis_surat FROM jenis_surat";
$result_jenis_surat = $koneksi->query($query_jenis_surat);

$query_pengguna = "SELECT id_pengguna, nama_pengguna FROM pengguna";
$result_pengguna = $koneksi->query($query_pengguna);

$status_surat = "belum diproses";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jenis_surat = htmlspecialchars($_POST['id_jenis_surat']);
    $id_pengguna = htmlspecialchars($_POST['id_pengguna']);
    $tanggal_surat = htmlspecialchars($_POST['tanggal_surat']);
    $file_surat = $_FILES['file_surat'];
    $allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
    $extension = pathinfo($file_surat['name'], PATHINFO_EXTENSION);
    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9]/', '_', basename($file_surat['name'], '.' . $extension)) . '.' . $extension;
    $target_dir = "C:/laragon/www/PHP/PROJEK_home/uploads/" . $filename;

    if (!in_array(mime_content_type($file_surat['tmp_name']), $allowed_types)) {
        $_SESSION['message'] = 'File harus berupa gambar (JPG/PNG) atau PDF.';
        $_SESSION['message_type'] = 'danger';
    } elseif (!move_uploaded_file($file_surat['tmp_name'], $target_dir)) {
        $_SESSION['message'] = 'Gagal mengunggah file.';
        $_SESSION['message_type'] = 'danger';
    } else {
        $query = $koneksi->prepare("INSERT INTO surat (id_jenis_surat, id_pengguna, tanggal_surat, file_surat, status_surat) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("iisss", $id_jenis_surat, $id_pengguna, $tanggal_surat, $filename, $status_surat);
        if ($query->execute()) {
            $_SESSION['message'] = 'Pengajuan surat berhasil dilakukan!';
            $_SESSION['message_type'] = 'success';
            header('Location: surat.php');
            exit();
        } else {
            $_SESSION['message'] = 'Gagal mengajukan surat: ' . $query->error;
            $_SESSION['message_type'] = 'danger';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SILADesa Admin - Dashboard</title>

    <!-- CSS -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div>
                    <img src="http://localhost/PHP/PROJEK_home/image/software-engineer.png" alt="Logo" class="logo"
                        style="max-width:70%">
                </div>
                <div class="sidebar-brand-text mx-3">SILADesa</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a class="nav-link" href="user-login.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>User Data</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_data.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Admin Data</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="jenis_surat.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Jenis Surat</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="info.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Informasi</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="surat.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pengajuan Surat</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nama_admin"]; ?></span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil-admin.php">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container fluid">
                    <h1 class="h3 mb-2 text-gray-800">Manajemen Surat</h1>
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?= $_SESSION['message_type']; ?>">
                            <?= $_SESSION['message'];
                            unset($_SESSION['message'], $_SESSION['message_type']); ?>
                        </div>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Surat</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="id_jenis_surat">Jenis Surat</label>
                                    <select name="id_jenis_surat" id="id_jenis_surat" class="form-control" required>
                                        <option value="" disabled selected>Pilih Jenis Surat</option>
                                        <?php while ($row = $result_jenis_surat->fetch_assoc()): ?>
                                            <option value="<?= $row['id_jenis_surat']; ?>">
                                                <?= htmlspecialchars($row['nama_jenis_surat']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_pengguna">Nama Pengguna</label>
                                    <select name="id_pengguna" id="id_pengguna" class="form-control" required>
                                        <option value="" disabled selected>Pilih Pengguna</option>
                                        <?php while ($row = $result_pengguna->fetch_assoc()): ?>
                                            <option value="<?= htmlspecialchars($row['id_pengguna']); ?>">
                                                <?= htmlspecialchars($row['nama_pengguna']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_surat">Tanggal Surat</label>
                                    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="file_surat" class="form-label">Unggah File (JPG, PNG, PDF)</label>
                                    <input type="file" class="form-control-file" id="file_surat" name="file_surat"
                                        accept=".jpg, .jpeg, .png, .pdf" required>
                                </div>
                                <button class="btn btn-secondary" type="button"
                                    onclick="window.history.back()">Kembali</button>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Surat</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID Surat</th>
                                            <th>Jenis Surat</th>
                                            <th>Nama Pengguna</th>
                                            <th>Tanggal Surat</th>
                                            <th>Status Surat</th>
                                            <th>File Surat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = "
                        SELECT s.id_surat, js.nama_jenis_surat, p.nama_pengguna, s.tanggal_surat, s.status_surat, s.file_surat
                        FROM surat s
                        JOIN jenis_surat js ON s.id_jenis_surat = js.id_jenis_surat
                        JOIN pengguna p ON s.id_pengguna = p.id_pengguna
                    ";
                                        $result = $koneksi->query($q);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                        ?>
                                                <tr>
                                                    <td><?= $row['id_surat'] ?></td>
                                                    <td><?= $row['nama_jenis_surat'] ?></td>
                                                    <td><?= $row['nama_pengguna'] ?></td>
                                                    <td><?= $row['tanggal_surat'] ?></td>
                                                    <td>
                                                        <?php
                                                        $status = $row['status_surat'];
                                                        if ($status === 'belum diproses') {
                                                            echo '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Belum Diproses</span>';
                                                        } elseif ($status === 'sedang diproses') {
                                                            echo '<span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Sedang Diproses</span>';
                                                        } elseif ($status === 'selesai diproses') {
                                                            echo '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai Diproses</span>';
                                                        } else {
                                                            echo '<span class="badge badge-secondary">Tidak Dikenal</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($row['file_surat'] !== null): ?>
                                                            <a href="/uploads/<?= htmlspecialchars($row['file_surat']); ?>"
                                                                target="_blank" data-toggle="tooltip"
                                                                title="Klik untuk melihat file">
                                                                <i class="fas fa-file"></i> Lihat File
                                                            </a>
                                                        <?php else: ?>
                                                            <span class="text-danger">Tidak Tersedia</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <a href="edit-surat.php?id=<?= $row['id_surat'] ?>"
                                                            class="btn btn-warning btn-sm">Update</a>
                                                        <a href="delete-surat.php?id=<?= $row['id_surat'] ?>"
                                                            class="btn btn-danger btn-sm">Delete</a>
                                                    </td>
                                                </tr>
                                        <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <h6>Copyright &copy; 2024</h6>
                        <h6>@taufiknurardiansyah</h6>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
    <script src="vendor/chart.js/Chart.min.js"></script>
    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 10, // Jumlah default entri per halaman
                "lengthMenu": [5, 10, 25, 50, 100], // Opsi jumlah entri
                "searching": true, // Aktifkan pencarian
                "ordering": true, // Aktifkan pengurutan kolom
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/Indonesian.json" // Terjemahan ke Bahasa Indonesia
                }
            });
        });
    </script>
</body>

</html>