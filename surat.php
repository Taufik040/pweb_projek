<?php
session_start();
if (!isset($_SESSION["nama_pengguna"])) {
    echo "
    <script>
        alert('Anda harus login terlebih dahulu untuk mengakses halaman ini!');
        window.location.href = 'login.php'; // Redirect ke halaman login
    </script>";
    exit();
}

include "koneksi.php";
$sql = "SELECT * FROM jenis_surat";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Surat</title>
    <link rel="stylesheet" type="text/css" href="style-jenis.css">

</head>

<body style="background: url('image/foto1.jpg') no-repeat center center;">
    <header>
        <div class="container">
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="menu-icon">
                <span></span>
            </label>
            <a href="index.php">
                <img src="image/logotmg.png" alt="Logo" class="logo">
            </a>
            <a class="site-title" href="index.php"
                style="font-size: 1.3rem; text-decoration: none; color: white;">SILADesa RT 02</a>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="info.php">Informasi</a></li>
                    <li><a href="surat.php">Pengajuan Surat</a></li>
                </ul>
            </nav>
            <div class="user-menu">
                <?php if (isset($_SESSION["nama_pengguna"])): ?>
                    <img src="image/user.png" class="user-pic" onclick="toggleMenu()">
                    <div class="sub-menu-wrap" id="subMenu">
                        <div class="sub-menu">
                            <div class="user-info">
                                <img src="image/user.png">
                                <h2><?php echo $_SESSION["nama_pengguna"]; ?></h2>
                            </div>
                            <hr>
                            <a href="edit-profile.php" class="sub-menu-link">
                                <img src="image/profile.png">
                                <p>Edit Profile</p>
                            </a>
                            <a href="log-out.php" class="sub-menu-link">
                                <img src="image/logout.png">
                                <p>Logout</p>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="login-btn">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <section class="info-section">
        <h2 class="section-title">Daftar Jenis Surat</h2>
        <div class="info-list">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="info-item">
                        <img src="admin/admin/img/<?php echo $row['gambar_jenis_surat']; ?>"
                            alt="<?php echo $row['nama_jenis_surat']; ?>">
                        <div class="info-content">
                            <h3><?php echo htmlspecialchars($row['nama_jenis_surat']); ?></h3>
                            <p><?php echo htmlspecialchars($row['deskripsi_jenis_surat']); ?></p>
                            <h4><?php echo htmlspecialchars($row['syarat_jenis_surat']); ?></h4>
                            <a href="pengajuan.php?jenis=<?php echo urlencode($row['nama_jenis_surat']); ?>"
                                class="cta-btn">Ajukan Surat</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada jenis surat tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
</body>
<footer>
    <p>© 2024 SILADesa RT 02. All Rights Reserved.</p>
    <ul>
        <li><a href="home.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="info.php">Informasi</a></li>
        <li><a href="surat.php">Pengajuan Surat</a></li>
        <li><a href="edit-profile.php">Edit Profil</a></li>
    </ul>
</footer>

</html>