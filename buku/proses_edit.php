<?php
session_start();
require_once '../koneksi.php';
require_once '../query.php';

$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id_buku']; // Amankan ID
    $judul = trim($_POST['judul_buku']);
    $pengarang = trim($_POST['pengarang_buku']);
    $penerbit = trim($_POST['penerbit_buku']);
    $tahun = trim($_POST['tahun']);

    $errors = [];

    // Validasi ID dasar
    if ($id <= 0) {
        $errors[] = 'ID Buku tidak valid!';
    }

    // Validasi 1: Cek Input Teks Kosong
    if (empty($judul)) {
        $errors[] = 'Judul buku tidak boleh kosong!';
    }
    if (empty($pengarang)) {
        $errors[] = 'Nama pengarang tidak boleh kosong!';
    }
    if (empty($penerbit)) {
        $errors[] = 'Nama penerbit tidak boleh kosong!';
    }
    if (empty($tahun)) {
        $errors[] = 'Tahun terbit tidak boleh kosong!';
    }

    // Validasi 2: Minimal Karakter (Hanya dicek jika tidak kosong)
    if (!empty($judul) && strlen($judul) < 5) {
        $errors[] = 'Judul terlalu pendek! Minimal harus 5 karakter.';
    }
    if (!empty($pengarang) && strlen($pengarang) < 5) {
        $errors[] = 'Nama pengarang terlalu pendek! Minimal harus 5 karakter.';
    }
    if (!empty($penerbit) && strlen($penerbit) < 5) {
        $errors[] = 'Nama penerbit terlalu pendek! Minimal harus 5 karakter.';
    }
    if (!empty($tahun) && strlen($tahun) < 4) {
        $errors[] = 'Tahun terbit tidak valid! Minimal harus 4 karakter.';
    }

    $img_name = $_FILES['gambar']['name'];
    $img_tmp = $_FILES['gambar']['tmp_name'];
    $img_size = $_FILES['gambar']['size'];

    $nama_baru_gambar = ''; // Default kosong jika tidak ganti gambar

    // Validasi 3: Jika user memilih/mengunggah gambar baru
    if (!empty($img_name)) {
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $ekstensiValid)) {
            $errors[] = 'Format gambar salah! Hanya menerima JPG, JPEG, PNG, dan WEBP.';
        } else {
            $cekMime = getimagesize($img_tmp);
            if ($cekMime === false) {
                $errors[] = 'File terdeteksi bukan gambar asli!';
            }
        }

        if ($img_size > 2000000) {
            $errors[] = 'Ukuran gambar baru maksimal 2MB!';
        }

        // Set nama baru jika sementara tidak ada error terkait gambar
        if (empty($errors)) {
            $nama_baru_gambar = uniqid() . '.' . $ext;
        }
    }

    // JIKA ADA ERROR
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        // Kembalikan ke halaman form edit berdasarkan ID buku tersebut agar inputan tidak hilang
        header('Location: index.php?id=' . $id);
        exit();
    }

    // JIKA LOLOS VALIDASI: Eksekusi update
    $update = $db->updateBuku($id, $judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp);

    if ($update) {
        $_SESSION['berhasil'] = 'Data buku sukses diubah!';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['errors'] = ['Gagal mengubah data buku di database!'];
    }

    header('Location: index.php?id=' . $id);
    exit();
}

header('Location: index.php');
exit();
?>
