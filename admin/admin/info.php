<?php
session_start();
include "koneksi.php";

// Cek apakah pengguna sudah login
if (!isset($_SESSION["id_admin"])) {
    echo "<script>
        alert('Anda harus login terlebih dahulu untuk mengakses halaman ini!');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Proses form ketika metode pengiriman adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitasi dan ambil input dari pengguna
    $nama_info = htmlspecialchars($_POST['nama_info']);
    $deskripsi_info = htmlspecialchars($_POST['deskripsi_info']);
    $lokasi_info = htmlspecialchars($_POST['lokasi_info']);

    // Proses upload gambar
    $gambar_info = $_FILES['gambar_info']['name'];
    $lokasi = $_FILES['gambar_info']['tmp_name'];
    $gambar_baru = time() . "_" . basename($gambar_info);
    $target_dir = "img/" . $gambar_baru;
    $tanggal_info = !empty($_POST['tanggal_info']) ? $_POST['tanggal_info'] : date('Y-m-d');

    // Daftar tipe file yang diperbolehkan
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

    // Validasi file gambar
    if (!empty($gambar_info) && in_array($_FILES['gambar_info']['type'], $allowed_types)) {
        if (move_uploaded_file($lokasi, $target_dir)) {
            // Query untuk menambahkan data ke database
            $query = $koneksi->prepare("INSERT INTO informasi (nama_info, deskripsi_info, gambar_info, lokasi_info, tanggal_info) VALUES (?, ?, ?, ?, ?)");
            $query->bind_param("sssss", $nama_info, $deskripsi_info, $gambar_baru, $lokasi_info, $tanggal_info);

            if ($query->execute()) {
                echo "<script>alert('Berhasil tambah informasi');</script>";
                echo "<script>location.href = 'info.php?berhasil';</script>";
            } else {
                echo "<script>alert('Gagal tambah informasi');</script>";
                echo "<script>location.href = 'info.php?gagal';</script>";
            }
        } else {
            echo "<script>alert('Gagal mengupload gambar. Periksa izin folder');</script>";
        }
    } else {
        echo "<script>alert('Tipe file tidak valid atau kosong. Hanya JPG, JPEG, dan PNG diperbolehkan');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SILADesa Admin - Dashboard</title>

    <!-- CSS -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">

    <!-- Stylesheets -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <!-- Custom styles -->
    <style>
        .scroll-to-top {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            display: none;
            width: 2.75rem;
            height: 2.75rem;
            text-align: center;
            padding-top: 13;
            color: #fff;
            background: rgba(90, 92, 105, .5);
            line-height: 46px;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
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
            <li class="nav-item active">
                <a class="nav-link" href="info.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Informasi</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="surat.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Pengajuan Surat</span></a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>

        <!-- Main Content -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- User Dropdown -->
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION["nama_admin"]; ?>
                                </span>
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

                <!-- Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Manajemen Informasi</h1>

                    <!-- Form Tambah Informasi -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Informasi</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="nama_info">Nama Informasi</label>
                                        <input type="text" name="nama_info" class="form-control" id="nama_info"
                                            required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="gambar_info">Foto</label>
                                        <input type="file" name="gambar_info" class="form-control" id="gambar_info"
                                            accept="image/*">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi_info">Deskripsi Informasi</label>
                                    <textarea name="deskripsi_info" id="deskripsi_info" class="form-control"
                                        rows="4"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="lokasi_info">Lokasi Informasi</label>
                                    <textarea name="lokasi_info" id="lokasi_info" class="form-control"
                                        rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_info">Tanggal Informasi</label>
                                    <input type="date" id="tanggal_info" name="tanggal_info" class="form-control">
                                </div>
                                <button class="btn btn-secondary" type="button"
                                    onclick="window.history.back()">Kembali</button>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Data Informasi -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Informasi</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Info</th>
                                            <th>Foto</th>
                                            <th>Deskripsi</th>
                                            <th>Lokasi</th>
                                            <th>Tanggal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = "SELECT * FROM informasi";
                                        $result = $koneksi->query($q);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                                    <td>{$row['id_info']}</td>
                                                    <td>{$row['nama_info']}</td>
                                                    <td><img src='img/{$row['gambar_info']}' alt='' width='100'></td>
                                                    <td>{$row['deskripsi_info']}</td>
                                                    <td>{$row['lokasi_info']}</td>
                                                    <td>{$row['tanggal_info']}</td>
                                                    <td>
                                                        <a href='edit-info.php?id={$row['id_info']}' class='btn btn-warning btn-sm'>Update</a>
                                                        <a href='delete-info.php?id={$row['id_info']}' class='btn btn-danger btn-sm'>Delete</a>
                                                    </td>
                                                </tr>";
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
        </div>
    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

    <!-- DataTables Initialization -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50, 100],
                "searching": true,
                "ordering": true,
                "columnDefs": [
                    { "orderable": false, "targets": 6 } // Nonaktifkan pengurutan pada kolom 'Aksi'
                ],
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/Indonesian.json"
                }
            });
        });
    </script>
</body>

</html>