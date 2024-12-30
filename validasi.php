<?php
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

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (empty($_POST["nama_pengguna"]) || empty($_POST["password_pengguna"])) {
    die("Nama pengguna dan password tidak boleh kosong.");
}

$nama_pengguna = trim($_POST["nama_pengguna"]);
$password = trim($_POST["password_pengguna"]);

$sql_check = "SELECT * FROM pengguna WHERE nama_pengguna = ?";
$stmt_check = $koneksi->prepare($sql_check);
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
$koneksi->close();
