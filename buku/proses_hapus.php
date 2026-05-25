<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";

$db = new Query($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Opsional: hapus file gambar fisik di folder agar tidak menumpuk sampah storage
    $buku = $db->getIdBuku($id);
    if ($buku && file_exists("../gambar/" . $buku->gambar)) {
        unlink("../gambar/" . $buku->gambar);
    }

    $hapus = $db->deleteBuku($id);

    if ($hapus) {
        $_SESSION['berhasil'] = "Buku sukses dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus buku!";
    }
}

header("Location: index.php");
exit();
?>