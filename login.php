<?php
session_start();
if (isset($_SESSION["nama_pengguna"])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Login</title>
</head>
<body>
<div class="container">
    <?php if (isset($_GET["login_gagal"])): ?>
        <div class="notifikasi">
            Login gagal! <br> Username atau Password salah
        </div>
    <?php endif; ?>
    <form method="post" action="validasi.php">
        <div class="input">
            <input type="text" class="element-input" name="nama_pengguna" placeholder="Username" required>
        </div>
        <div class="input">
            <input type="password" class="element-input" name="password_pengguna" placeholder="Password" required>
        </div>
        <div class="input">
            <button type="submit" name="login" class="button-login">Login</button>
        </div>
    </form>
    <div class="input">
        <p style="color: white; margin-top: 10px;">
            Belum punya akun? <a href="register.php" style="color: orange;">Daftar di sini</a>
        </p>
    </div>
</div>
</body>
</html>