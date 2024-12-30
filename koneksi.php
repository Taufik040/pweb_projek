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


$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$base_url = "http://localhost/PHP/PROJEK_home/";
