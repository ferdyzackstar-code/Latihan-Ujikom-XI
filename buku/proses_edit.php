<?php
session_start();
require_once '../koneksi.php';
require_once '../query.php';

$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id_buku']; // Amankan ID dengan type casting integer
    $judul = trim($_POST['judul_buku']);
    $pengarang = trim($_POST['pengarang_buku']);
    $penerbit = trim($_POST['penerbit_buku']);
    $tahun = trim($_POST['tahun']);

    if ($id <= 0 || empty($judul) || empty($pengarang) || empty($penerbit) || empty($tahun)) {
        $_SESSION['error'] = 'Form edit tidak valid atau ada data yang kosong!';
        header('Location: index.php');
        exit();
    }

    $img_name = $_FILES['gambar']['name'];
    $img_tmp = $_FILES['gambar']['tmp_name'];
    $img_size = $_FILES['gambar']['size'];

    $nama_baru_gambar = ''; // Default kosong jika tidak ganti gambar

    // Jika user mengunggah gambar baru
    if (!empty($img_name)) {
        // Validasi Ekstensi
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $ekstensiValid)) {
            $_SESSION['error'] = 'Format gambar salah! Hanya menerima JPG, JPEG, PNG, dan WEBP.';
            header('Location: index.php');
            exit();
        }

        // Validasi MIME Type asli gambar
        $cekMime = getimagesize($img_tmp);
        if ($cekMime === false) {
            $_SESSION['error'] = 'File terdeteksi bukan gambar asli!';
            header('Location: index.php');
            exit();
        }

        // Validasi Ukuran (Maksimal 2MB)
        if ($img_size > 2000000) {
            $_SESSION['error'] = 'Ukuran gambar maksimal 2MB!';
            header('Location: index.php');
            exit();
        }

        // Buat nama baru untuk gambar baru
        $nama_baru_gambar = uniqid() . '.' . $ext;
    }

    // Panggil updateBuku (Logika hapus gambar fisik lama sudah di-handle otomatis di dalam class Query)
    $update = $db->updateBuku($id, $judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp);

    if ($update) {
        $_SESSION['berhasil'] = 'Data buku sukses diubah!';
    } else {
        $_SESSION['error'] = 'Gagal mengubah data buku!';
    }
}

header('Location: index.php');
exit();
?>
