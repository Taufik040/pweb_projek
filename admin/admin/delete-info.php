<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_query = $koneksi->prepare("DELETE FROM informasi WHERE id_info = ?");
    $delete_query->bind_param("i", $id);

    if ($delete_query->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>location.href='info.php';</script>";
    } else {
        echo "Gagal menghapus data: " . $delete_query->error;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}
?>