<?php
session_start();

// Pastikan pengguna telah login
if (!isset($_SESSION["id_pengguna"])) {
    echo "
    <script>
        alert('Anda harus login terlebih dahulu untuk mengakses halaman ini!');
        window.location.href = 'login.php'; // Redirect ke halaman login
    </script>";
    exit();
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "pweb_projek"); // Ganti "nama_database" sesuai database Anda
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$id_pengguna = $_SESSION["id_pengguna"];

// Ambil data pengguna saat ini
$sql = "SELECT nama_pengguna, email_pengguna FROM pengguna WHERE id_pengguna = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Proses update profil jika form disubmit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama_pengguna = $_POST["nama_pengguna"];
    $email_pengguna = $_POST["email_pengguna"];
    $password_pengguna = $_POST["password_pengguna"];

    if (!empty($password_pengguna)) {
        $password_hash = password_hash($password_pengguna, PASSWORD_DEFAULT);
        $sql = "UPDATE pengguna SET nama_pengguna = ?, email_pengguna = ?, password_pengguna = ? WHERE id_pengguna = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama_pengguna, $email_pengguna, $password_hash, $id_pengguna);
    } else {
        $sql = "UPDATE pengguna SET nama_pengguna = ?, email_pengguna = ? WHERE id_pengguna = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_pengguna, $email_pengguna, $id_pengguna);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href = 'edit-profile.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan. Profil gagal diperbarui.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" type="text/css" href="style-jenis.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
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

        .form-container {
            max-width: 400px;
            width: 100%;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin: 20px auto;
            font-family: 'Poppins', sans-serif;
        }

        .form-container h2 {
            text-align: center;
            font-size: 26px;
            color: #444;
            margin-bottom: 25px;
        }

        .form-container label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
            margin-bottom: 10px;
            display: block;
        }

        .form-container input {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 14px;
            color: #333;
            background: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        .form-container input:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.5);
            outline: none;
        }

        .form-container .password-group {
            position: relative;
        }

        .form-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 35%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #888;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            color: #fff;
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .form-container button:hover {
            background: linear-gradient(to right, #5a0dbc, #1d68f0);
            transform: translateY(-3px);
        }

        .form-container button:active {
            background: linear-gradient(to right, #4909a0, #175bc5);
        }

        @media (max-width: 768px) {
            .form-container {
                padding: 20px;
            }
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
        <div class="form-container">
            <h2>Edit Profil</h2>
            <form action="" method="POST">
                <label for="nama_pengguna">Nama Pengguna</label>
                <input type="text" id="nama_pengguna" name="nama_pengguna"
                    value="<?php echo htmlspecialchars($user['nama_pengguna']); ?>" required>

                <label for="email_pengguna">Email</label>
                <input type="email" id="email_pengguna" name="email_pengguna"
                    value="<?php echo htmlspecialchars($user['email_pengguna']); ?>" required>

                <label for="password_pengguna">Password Baru (Opsional)</label>
                <div class="password-group">
                    <input type="password" id="password_pengguna" name="password_pengguna"
                        placeholder="Masukkan password baru jika ingin mengubah">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i> <!-- Use FontAwesome icon -->
                </div>

                <button type="submit">Simpan Perubahan</button>
            </form>
        </div>
    </main>
    <script>
        let subMenu = document.getElementById("subMenu");
        function toggleMenu() {
            subMenu.classList.toggle("open-menu");
        }
        const togglePassword = () => {
            const passwordField = document.getElementById('password_pengguna');
            const icon = document.querySelector('.toggle-password');
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        };
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