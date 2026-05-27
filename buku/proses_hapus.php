<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";

$db = new Query($conn);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Amankan ID dari URL string parameter
    
    if ($id > 0) {
        // Method deleteBuku($id) di class Query yang baru sudah otomatis menghapus file fisik di foldernya
        $hapus = $db->deleteBuku($id);

        if ($hapus) {
            $_SESSION['berhasil'] = "Buku sukses dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus buku atau ID tidak ditemukan!";
        }
    } else {
        $_SESSION['error'] = "ID buku tidak valid!";
    }
} else {
    $_SESSION['error'] = "Tidak ada buku yang dipilih untuk dihapus!";
}

header("Location: index.php");
exit();
?>