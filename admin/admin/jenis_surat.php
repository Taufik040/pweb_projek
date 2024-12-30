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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_jenis_surat = htmlspecialchars($_POST['nama_jenis_surat']);
    $deskripsi_jenis_surat = htmlspecialchars($_POST['deskripsi_jenis_surat']);
    $syarat_jenis_surat = htmlspecialchars($_POST['syarat_jenis_surat']);

    $gambar_jenis_surat = $_FILES['gambar_jenis_surat']['name'];
    $lokasi = $_FILES['gambar_jenis_surat']['tmp_name'];
    $gambar_baru = time() . "_" . basename($gambar_jenis_surat);
    $target_dir = "img/" . $gambar_baru;
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

    if (!empty($gambar_jenis_surat) && in_array($_FILES['gambar_jenis_surat']['type'], $allowed_types)) {
        if (move_uploaded_file($lokasi, $target_dir)) {
            $query = $koneksi->prepare("INSERT INTO jenis_surat (nama_jenis_surat, deskripsi_jenis_surat, gambar_jenis_surat, syarat_jenis_surat) VALUES (?, ?, ?, ?)");
            $query->bind_param("ssss", $nama_jenis_surat, $deskripsi_jenis_surat, $gambar_baru, $syarat_jenis_surat);

            if ($query->execute()) {
                echo "<script>alert('Berhasil tambah jenis surat');</script>";
                echo "<script>location.href='jenis_surat.php?berhasil';</script>";
            } else {
                echo "<script>alert('Gagal tambah jenis surat');</script>";
                echo "<script>location.href='jenis_surat.php?gagal';</script>";
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
            <li class="nav-item active">
                <a class="nav-link" href="jenis_surat.php">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Jenis Surat</span></a>
            </li>
            <li class="nav-item">
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
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
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

                <!-- Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Jenis Surat</h1>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Jenis Surat</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Nama Jenis Surat</label>
                                    <input type="text" name="nama_jenis_surat" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Foto</label>
                                    <input type="file" name="gambar_jenis_surat" class="form-control" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi Surat</label>
                                    <textarea name="deskripsi_jenis_surat" cols="30" rows="5"
                                        class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Syarat Surat</label>
                                    <textarea name="syarat_jenis_surat" cols="30" rows="5"
                                        class="form-control"></textarea>
                                </div>
                                <button type="button" onclick="window.history.back()"
                                    class="btn btn-secondary btn-sm">Kembali</button>
                                <button type="submit" name="submit" class="btn btn-info btn-sm">Submit</button>
                            </form>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Jenis Surat</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Foto</th>
                                            <th>Deskripsi</th>
                                            <th>Syarat</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = "SELECT * FROM jenis_surat";
                                        $result = $koneksi->query($q);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['id_jenis_surat'] ?></td>
                                                    <td><?php echo $row['nama_jenis_surat'] ?></td>
                                                    <td>
                                                        <?php if (!empty($row['gambar_jenis_surat']) && file_exists('img/' . $row['gambar_jenis_surat'])): ?>
                                                            <img src="img/<?php echo htmlspecialchars($row['gambar_jenis_surat']); ?>"
                                                                width="100">
                                                        <?php else: ?>
                                                            <p>Image not available</p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo $row['deskripsi_jenis_surat'] ?></td>
                                                    <td><?php echo $row['syarat_jenis_surat'] ?></td>
                                                    <td>
                                                        <a href="edit-jenis.php?id=<?php echo $row['id_jenis_surat'] ?>"
                                                            class="btn btn-sm btn-warning">Update</a>
                                                        <a href="delete-jenis.php?id=<?php echo $row['id_jenis_surat'] ?>"
                                                            class="btn btn-sm btn-danger">Delete</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center'>0 results</td></tr>";
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
    </div>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>
    <link href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": 10, // Menentukan jumlah default entri per halaman
                "lengthMenu": [5, 10, 25, 50, 100], // Opsi entri per halaman
                "searching": true, // Aktifkan fitur pencarian
                "ordering": true, // Aktifkan pengurutan kolom
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/Indonesian.json" // Terjemahan ke Bahasa Indonesia
                }
            });
        });
    </script>
</body>

</html>