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

$id_admin = $_SESSION["id_admin"];

$sql = "SELECT nama_admin FROM admin_data WHERE id_admin = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_admin = trim($_POST["nama_admin"]);
    $password_admin = trim($_POST["password_admin"]);

    $success = true;

    if (!empty($nama_admin)) {
        $query = "UPDATE admin_data SET nama_admin = ? WHERE id_admin = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("si", $nama_admin, $id_admin);
        if (!$stmt->execute()) {
            $success = false;
        }
    }

    if (!empty($password_admin)) {
        $hashed_password = password_hash($password_admin, PASSWORD_BCRYPT);
        $query = "UPDATE admin_data SET password_admin = ? WHERE id_admin = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("si", $hashed_password, $id_admin);
        if (!$stmt->execute()) {
            $success = false;
        }
    }

    if ($success) {
        echo "<script>alert('Perubahan berhasil disimpan!'); window.location.href = 'profil-admin.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan perubahan. Silakan coba lagi.');</script>";
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        <!-- End Sidebar -->

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
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo htmlspecialchars($user['nama_admin']); ?>
                                </span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="profil-admin.php"><i
                                        class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php"><i
                                        class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Main Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Edit Profil</h1>
                    <div class="card shadow-lg p-4">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="nama_admin" class="form-label">Nama Admin</label>
                                <input type="text" id="nama_admin" name="nama_admin" class="form-control"
                                    value="<?php echo htmlspecialchars($user['nama_admin']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="password_admin" class="form-label">Password Baru (Opsional)</label>
                                <div class="input-group">
                                    <input type="password" id="password_admin" name="password_admin"
                                        class="form-control" placeholder="Masukkan password baru jika ingin mengubah">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye toggle-password"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <script>
                    function togglePassword() {
                        const passwordField = document.getElementById('password_admin');
                        const icon = document.querySelector('.toggle-password');
                        const type = passwordField.type === 'password' ? 'text' : 'password';
                        passwordField.type = type;
                        icon.classList.toggle('fa-eye-slash');
                        icon.classList.toggle('fa-eye');
                    }
                </script>
            </div>

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
</body>

</html>