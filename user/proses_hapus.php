<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if (isset($_GET['id'])) {
    // CRITICAL: Paksa ID dari URL menjadi Integer angka untuk mencegah manipulasi query URL string
    $id = (int)$_GET['id'];
    
    // Pastikan ID-nya valid (bukan 0 atau minus)
    if ($id > 0) {
        $hapus = $db->deleteUser($id);
        
        if ($hapus) {
            $_SESSION['berhasil'] = "Data user berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data user! ID tidak ditemukan.";
        }
    } else {
        $_SESSION['error'] = "ID user tidak valid!";
    }
} else {
    $_SESSION['error'] = "Tidak ada ID yang dipilih untuk dihapus!";
}

header("Location: index.php");
exit();