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
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
    $dbHost = $env["DB_HOST"];
    $dbUsername = $env["DB_USERNAME"];
    $dbPassword = $env["DB_PASSWORD"];
    $dbName = $env["DB_NAME"];
} else {
    $dbHost = getenv("DB_HOST");
    $dbUsername = getenv("DB_USERNAME");
    $dbPassword = getenv("DB_PASSWORD");
    $dbName = getenv("DB_NAME");
}


$koneksi = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk menghitung jumlah pengguna
$result = $koneksi->query("SELECT COUNT(*) AS jumlah_pengguna FROM pengguna");
$row = $result->fetch_assoc();
$jumlah_pengguna = $row['jumlah_pengguna'];

// Query untuk menghitung jumlah informasi
$result2 = $koneksi->query("SELECT COUNT(*) AS jumlah_informasi FROM informasi");
$row = $result2->fetch_assoc();
$jumlah_informasi = $row['jumlah_informasi'];

// Query untuk menghitung jumlah pengajuan
$result3 = $koneksi->query("SELECT COUNT(*) AS jumlah_pengajuan FROM surat");
$row = $result3->fetch_assoc();
$jumlah_pengajuan = $row['jumlah_pengajuan'];

// Query untuk menghitung jumlah jenis surat
$result4 = $koneksi->query("SELECT COUNT(*) AS jumlah_jenis FROM jenis_surat");
$row = $result4->fetch_assoc();
$jumlah_jenis = $row['jumlah_jenis'];

// Query untuk menghitung jumlah status_surat
$status_belum_diproses = $koneksi->query("SELECT COUNT(*) AS jumlah FROM surat WHERE status_surat = 'Belum Diproses'")->fetch_assoc()['jumlah'];
$status_sedang_diproses = $koneksi->query("SELECT COUNT(*) AS jumlah FROM surat WHERE status_surat = 'Sedang Diproses'")->fetch_assoc()['jumlah'];
$status_selesai_diproses = $koneksi->query("SELECT COUNT(*) AS jumlah FROM surat WHERE status_surat = 'Selesai Diproses'")->fetch_assoc()['jumlah'];
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
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
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
            <li class="nav-item active">
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
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
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
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Jumlah Pengguna</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $jumlah_pengguna; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Jumlah Informasi</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $jumlah_informasi; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah
                                                Pengajuan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $jumlah_pengajuan; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Jenis
                                                Surat</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo $jumlah_jenis; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-envelope fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Row -->
                    <div class="row justify-content-center">
                        <!-- Tambahkan justify-content-end -->
                        <!-- Pie Chart -->
                        <div class="col-xl-20 col-lg-10">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Status Surat</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary"></i> Belum Diproses
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success"></i> Selesai Diproses
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info"></i> Sedang Diproses
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->
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
    <script>
        // Pie Chart
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Belum Diproses", "Sedang Diproses", "Selesai Diproses"],
                datasets: [{
                    data: [
                        <?php echo $status_belum_diproses; ?>,
                        <?php echo $status_sedang_diproses; ?>,
                        <?php echo $status_selesai_diproses; ?>
                    ],
                    backgroundColor: ['#4e73df', '#36b9cc', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#2c9faf', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
    </script>
</body>

</html>