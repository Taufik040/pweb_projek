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
    $nama_pengguna = htmlspecialchars($_POST['nama_pengguna']);
    $email_pengguna = htmlspecialchars($_POST['email_pengguna']);
    $password_pengguna = htmlspecialchars($_POST['password_pengguna']);

    // Cek apakah nama_pengguna sudah ada
    $check_query = $koneksi->prepare("SELECT id_pengguna FROM pengguna WHERE nama_pengguna = ?");
    $check_query->bind_param("s", $nama_pengguna);
    $check_query->execute();
    $check_result = $check_query->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Nama pengguna sudah terdaftar. Gunakan nama lain.');</script>";
        echo "<script>location.href='user-login.php?gagal';</script>";
    } else {
        // Hash password
        $hashed_password = password_hash($password_pengguna, PASSWORD_DEFAULT);

        // Insert data ke database
        $query = $koneksi->prepare("INSERT INTO pengguna (nama_pengguna, email_pengguna, password_pengguna) VALUES (?, ?, ?)");
        $query->bind_param("sss", $nama_pengguna, $email_pengguna, $hashed_password);

        if ($query->execute()) {
            echo "<script>alert('Berhasil tambah pengguna');</script>";
            echo "<script>location.href='user-login.php?berhasil';</script>";
        } else {
            echo "<script>alert('Gagal tambah pengguna');</script>";
            echo "<script>location.href='user-login.php?gagal';</script>";
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
    <title>SILADesa Admin - User Management</title>

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
            <li class="nav-item active">
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

                <!-- End of Topbar -->

                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Manajemen Pengguna</h1>

                    <!-- Form Section -->
                    <div class="card shadow-lg mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Pengguna</h6>
                        </div>
                        <div class="card-body p-md-5">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="nama_pengguna">Nama Pengguna</label>
                                    <input type="text" name="nama_pengguna" class="form-control" id="nama_pengguna"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="email_pengguna">Email Pengguna</label>
                                    <input type="email" name="email_pengguna" class="form-control" id="email_pengguna"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="password_pengguna">Password Pengguna</label>
                                    <input type="password" name="password_pengguna" class="form-control"
                                        id="password_pengguna" required>
                                </div>
                                <button class="btn btn-sm btn-secondary" type="button"
                                    onclick="window.history.back()">Kembali</button>
                                <button class="btn btn-sm btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="card shadow-lg">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Pengguna</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama Pengguna</th>
                                            <th>Email</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $q = "SELECT * FROM pengguna";
                                        $result = $koneksi->query($q);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>
                                <td>{$row['id_pengguna']}</td>
                                <td>{$row['nama_pengguna']}</td>
                                <td>{$row['email_pengguna']}</td>
                                <td>
                                    <a href='edit-user.php?id={$row['id_pengguna']}' class='btn btn-warning btn-sm'>Update</a>
                                    <a href='delete-user.php?id={$row['id_pengguna']}' class='btn btn-danger btn-sm'>Delete</a>
                                </td>
                            </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
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
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": 10, // Menentukan jumlah default entri per halaman
                "lengthMenu": [5, 10, 25, 50, 100], // Opsi jumlah entri per halaman
                "searching": true, // Aktifkan fitur pencarian
                "ordering": true, // Aktifkan fitur pengurutan kolom
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.13.5/i18n/Indonesian.json" // Terjemahan ke Bahasa Indonesia
                }
            });
        });
    </script>
</body>

</html>