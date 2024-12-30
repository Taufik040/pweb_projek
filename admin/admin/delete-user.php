<?php
include "koneksi.php";

$id = $_GET['id'];
$query = $koneksi->prepare("DELETE FROM pengguna WHERE id_pengguna = ?");
$query->bind_param("i", $id);

if ($query->execute()) {
    echo "<script>alert('Berhasil hapus pengguna');</script>";
    echo "<script>location.href='user-login.php';</script>";
} else {
    echo "<script>alert('Gagal hapus pengguna');</script>";
    echo "<script>location.href='user-login.php';</script>";
}
?>
