<?php
session_start();
require_once '../koneksi.php';
require_once '../query.php';

$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = trim($_POST['judul_buku']);
    $pengarang = trim($_POST['pengarang_buku']);
    $penerbit = trim($_POST['penerbit_buku']);
    $tahun = trim($_POST['tahun']);

    // Menampung banyak error
    $errors = [];

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

    // Tangkap data file gambar
    $img_name = $_FILES['gambar']['name'];
    $img_tmp = $_FILES['gambar']['tmp_name'];
    $img_size = $_FILES['gambar']['size'];
    $img_error = $_FILES['gambar']['error'];

    // Validasi 3: Apakah user beneran upload file?
    if ($img_error === 4) {
        $errors[] = 'Wajib mengunggah gambar cover buku!';
    } else {
        // Validasi Gambar Lanjutan jika file diupload
        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

        // Cek Ekstensi
        if (!in_array($ext, $ekstensiValid)) {
            $errors[] = 'Format file gambar salah! Hanya menerima JPG, JPEG, PNG, dan WEBP.';
        } else {
            // Cek MIME Type asli (Hanya jika ekstensi valid untuk menghindari error fungsi)
            $cekMime = getimagesize($img_tmp);
            if ($cekMime === false) {
                $errors[] = 'File terdeteksi rusak atau bukan gambar asli!';
            }
        }

        // Cek Ukuran File
        if ($img_size > 2000000) {
            $errors[] = 'Ukuran gambar terlalu besar! Maksimal adalah 2MB.';
        }
    }

    // JIKA ADA ERROR
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: index.php'); // Sesuai rute halaman tambahmu
        exit();
    }

    // JIKA LOLOS VALIDASI
    $nama_baru_gambar = uniqid() . '.' . $ext;
    $simpan = $db->createBuku($judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp);

    if ($simpan) {
        $_SESSION['berhasil'] = 'Buku baru sukses ditambahkan!';
    } else {
        $_SESSION['errors'] = ['Gagal menambahkan data buku ke database!'];
    }
}

header('Location: index.php');
exit();
?>
