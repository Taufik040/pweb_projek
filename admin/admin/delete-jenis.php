<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $delete_query = $koneksi->prepare("DELETE FROM jenis_surat WHERE id_jenis_surat = ?");
    $delete_query->bind_param("i", $id);

    if ($delete_query->execute()) {
        echo "<script>alert('Data berhasil dihapus');</script>";
        echo "<script>location.href='jenis_surat.php';</script>";
    } else {
        echo "Gagal menghapus data: " . $delete_query->error;
    }
} else {
    echo "ID tidak ditemukan.";
    exit;
}
?>