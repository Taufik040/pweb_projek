<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "pweb_projek";

$conn = new mysqli($host, $user, $pass, $db_name);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
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
$stmt_check = $conn->prepare($sql_check);
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
$conn->close();
?>