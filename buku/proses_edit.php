<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";

$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id        = $_POST['id_buku'];
    $judul     = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang_buku'];
    $penerbit  = $_POST['penerbit_buku'];
    $tahun     = $_POST['tahun'];
    
    $img_name  = $_FILES['gambar']['name'];
    $img_tmp   = $_FILES['gambar']['tmp_name'];

    $update = $db->updateBuku($id, $judul, $pengarang, $penerbit, $tahun, $img_name, $img_tmp);

    if ($update) {
        $_SESSION['berhasil'] = "Data buku sukses diubah!";
    } else {
        $_SESSION['error'] = "Gagal mengubah data buku!";
    }
}

header("Location: index.php");
exit();
?>