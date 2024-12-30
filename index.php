<?php
session_start();
if (isset($_SESSION["nama_pengguna"])) {
    $nama_pengguna = htmlspecialchars($_SESSION["nama_pengguna"], ENT_QUOTES, 'UTF-8');
} else {
    $nama_pengguna = "Guest";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" type="text/css" href="style-jenis.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .welcome-section {
            max-width: 980px;
            margin: 30px auto;
            text-align: center;
            padding: 20px;
        }

        .welcome-section h2 {
            font-size: 2rem;
            color: orange;
        }

        .welcome-section p {
            font-size: 1rem;
            line-height: 1.8;
            color: white;
        }

        .slider {
            max-width: 100%;
            margin: 20px auto;
            position: relative;
        }

        .slider input[type="radio"] {
            display: none;
        }

        .slides {
            display: flex;
            overflow: hidden;
        }

        .slide {
            min-width: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .slide img {
            width: 100%;
            display: block;
        }

        .nav {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }

        .nav-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 5px;
            background-color: #ccc;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .nav-dot:hover {
            background-color: #2c3e50;
        }

        input#slide1:checked~.slides {
            transform: translateX(0);
        }

        input#slide2:checked~.slides {
            transform: translateX(-100%);
        }

        input#slide3:checked~.slides {
            transform: translateX(-200%);
        }

        input#slide1:checked~.nav label[for="slide1"],
        input#slide2:checked~.nav label[for="slide2"],
        input#slide3:checked~.nav label[for="slide3"] {
            background-color: #2c3e50;
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
        <div class="welcome-section">
            <h2>Selamat Datang, <?= $nama_pengguna ?></h2>
            <h2>di Sistem Informasi dan Layanan Administrasi Desa RT 02</h2>
            <p>Kami hadir untuk mempermudah penyebaran informasi dan pengajuan surat di lingkungan Desa Karanggeneng RT
                02 RW 01, Kelurahan Tlogorejo, Temanggung. Sistem ini dirancang sebagai solusi digital untuk memberikan
                layanan yang cepat dan efisien
                bagi
                seluruh warga.</p>
        </div>
        <div class="slider" style="max-width: 720px">
            <input type="radio" name="slider" id="slide1" checked>
            <input type="radio" name="slider" id="slide2">
            <input type="radio" name="slider" id="slide3">
            <div class="slides">
                <div class="slide" id="img1">
                    <img src="image/p.jpg" alt="Slide 1">
                </div>
            </div>
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
    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
    </script>
</body>

</html>