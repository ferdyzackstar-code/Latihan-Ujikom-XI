<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";

$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul     = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang_buku'];
    $penerbit  = $_POST['penerbit_buku'];
    $tahun     = $_POST['tahun'];
    
    // Tangkap data file gambar
    $img_name  = $_FILES['gambar']['name'];
    $img_tmp   = $_FILES['gambar']['tmp_name'];

    $simpan = $db->createBuku($judul, $pengarang, $penerbit, $tahun, $img_name, $img_tmp);

    if ($simpan) {
        $_SESSION['berhasil'] = "Buku baru sukses ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan data buku!";
    }
}

header("Location: index.php");
exit();
?>