<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" type="text/css" href="style-jenis.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        main {
            flex: 1;
            padding: 20px;
        }

        .content-section {
            max-width: 900px;
            margin: 20px auto;
        }

        .content-section h2,
        h3 {
            font-size: 2rem;
            margin-bottom: 10px;
            color: orange;
        }

        .content-section p,
        .content-section ul {
            line-height: 1.6;
            font-size: 1rem;
            color: white;
        }

        .photo img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .map iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 8px;
        }
    </style>
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

    <main>
        <div class="content-section">
            <h2>Universitas Teknologi Yogyakarta</h2>
            <p>Universitas Teknologi Yogyakarta atau biasa disingkat UTY adalah salah satu perguruan tinggi swasta
                terbaik di Provinsi Daerah Istimewa Yogyakarta (DIY). Universitas ini diselenggarakan oleh Yayasan
                "Dharma Bhakti IPTEK".</p>
            <ul>
                <li>Sekolah Tinggi Ilmu Ekonomi Yogyakarta (STIEYO)</li>
                <li>Akademi Bahasa Asing Yogyakarta (ABAYO)</li>
                <li>Sekolah Tinggi Manajemen Informatika dan Komputer (STMIK) Dharma Bangsa</li>
            </ul>
        </div>

        <div class="content-section photo">
            <h3>Struktur Organisasi RT 02</h3>
            <img src="image/struk.jpg" alt="Foto Struktur">
        </div>

        <div class="content-section map">
            <h3>Lokasi</h3>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d247.34275924369362!2d110.1551215!3d-7.2993751!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e70781120cfb599%3A0xa1725fe3d501d904!2sP524%2B739%2C%20Karanggeneng%2C%20Tlogorejo%2C%20Temanggung%2C%20Temanggung%20Regency%2C%20Central%20Java%2056229!5e0!3m2!1sen!2sid!4v1735395760577!5m2!1sen!2sid"></iframe>
        </div>

        <div class="content-section photo">
            <h3>Foto Dokumentasi</h3>
            <img src="image/doku.jpg" alt="Foto Struktur">
        </div>
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
</body>

</html>