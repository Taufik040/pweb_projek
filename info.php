<?php
session_start();

include "koneksi.php";
$sql = "SELECT * FROM informasi";
$result = $koneksi->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi</title>
    <link rel="stylesheet" href="style-jenis.css">
</head>

<body>
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

    <div class="hero">
        <h1>Selamat Datang di SILADesa RT 02</h1>
        <p>Informasi terbaru dan pengumuman penting untuk warga desa.</p>
        <a href="#info-baru" class="cta-btn">Lihat Informasi</a>
    </div>
    <main id="info-baru">
        <section class="info-section" id="info-section" style="
    padding-top: 10px; 
    padding: 50px 20px;
    background-color: rgba(128, 128, 128, 0.5);
    background-size: cover;
    margin: 0;
    margin-top: auto;
    min-height: 100vh;">
            <h1 class="section-title">Informasi Terbaru</h1>
            <div class="info-list">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()):
                        ?>
                        <div class="info-item">
                            <img src="admin/admin/img/<?php echo $row['gambar_info']; ?>"
                                alt="<?php echo $row['nama_info']; ?>">
                            <div class="info-content">
                                <h3><?php echo htmlspecialchars($row['nama_info']); ?></h3>
                                <p><?php echo htmlspecialchars($row['deskripsi_info']); ?></p>
                                <h4><?php echo htmlspecialchars($row['lokasi_info']); ?></h4>
                                <h4><?php
                                if (isset($row['tanggal_info'])) {
                                    echo htmlspecialchars($row['tanggal_info']);
                                }
                                ?></h4>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Tidak ada informasi yang tersedia.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

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

    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
</body>

</html>