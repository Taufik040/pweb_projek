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

if (empty($_POST["nama_admin"]) || empty($_POST["password_admin"])) {
    die("Nama admin dan password tidak boleh kosong.");
}

$nama_admin = trim($_POST["nama_admin"]);
$password = trim($_POST["password_admin"]);

// echo $nama_admin;
// echo $password;
// die;

$sql_check = "SELECT * FROM admin_data WHERE nama_admin = ?";
$stmt_check = $koneksi->prepare($sql_check);
$stmt_check->bind_param("s", $nama_admin);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    // echo "true";
    // die;
    $user = $result->fetch_assoc();
    if (password_verify($password, $user["password_admin"])) {
        session_start();
        $_SESSION["nama_admin"] = $nama_admin;
        $_SESSION["id_admin"] = $user["id_admin"];
        header("Location: index.php");
    } else {
        header("Location: login.php?login_gagal=1");
    }
} else {
    header("Location: login.php?login_gagal=1");
}

$stmt_check->close();
$koneksi->close();
