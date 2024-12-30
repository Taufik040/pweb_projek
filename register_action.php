<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "pweb_projek";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (empty($_POST["nama_pengguna"]) || empty($_POST["password_pengguna"]) || empty($_POST["email_pengguna"])) {
    die("Harap isi semua data.");
}

$nama_pengguna = htmlspecialchars(trim($_POST["nama_pengguna"]), ENT_QUOTES, 'UTF-8');
$password = trim($_POST["password_pengguna"]);
$email_pengguna = filter_var(trim($_POST["email_pengguna"]), FILTER_SANITIZE_EMAIL);

if (!filter_var($email_pengguna, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Registrasi gagal. Format email tidak valid.'); window.location.href = 'register.php';</script>";
}

if (strlen($password) < 8) {
    echo "<script>alert('Registrasi gagal. Password harus lebih dari 8 karakter.'); window.location.href = 'register.php';</script>";
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql_check = "SELECT * FROM pengguna WHERE nama_pengguna = ? OR email_pengguna = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $nama_pengguna, $email_pengguna);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('Nama Pengguna atau Email sudah digunakan. Silakan pilih yang lain.'); window.location.href = 'register.php';</script>";
} else {
    $sql_insert = "INSERT INTO pengguna (nama_pengguna, password_pengguna, email_pengguna) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sss", $nama_pengguna, $hashed_password, $email_pengguna);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'login.php';</script>";
    } else {
        echo "<script>alert('Registrasi gagal. Silakan coba lagi.'); window.location.href = 'register.php';</script>";
    }
}

$stmt_check->close();
$stmt_insert->close();
$conn->close();
?>