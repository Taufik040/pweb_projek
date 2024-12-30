<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "pweb_projek";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (empty($_POST["nama_pengguna"]) || empty($_POST["password_pengguna"])) {
    die("Nama pengguna dan password tidak boleh kosong.");
}

$nama_pengguna = trim($_POST["nama_pengguna"]);
$password = trim($_POST["password_pengguna"]);

$sql_check = "SELECT * FROM pengguna WHERE nama_pengguna = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $nama_pengguna);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user["password_pengguna"])) {
        session_start();
        $_SESSION["nama_pengguna"] = $nama_pengguna;
        $_SESSION["id_pengguna"] = $user["id_pengguna"];
        header("Location: index.php");
    } else {
        header("Location: login.php?login_gagal=1");
    }
} else {
    header("Location: login.php?login_gagal=1");
}

// Menutup koneksi
$stmt_check->close();
$conn->close();
?>