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

    // 1. Validasi Input Teks Kosong
    if (empty($judul) || empty($pengarang) || empty($penerbit) || empty($tahun)) {
        $_SESSION['error'] = 'Semua form teks wajib diisi!';
        header('Location: index.php');
        exit();
    }

    // Tangkap data file gambar
    $img_name = $_FILES['gambar']['name'];
    $img_tmp = $_FILES['gambar']['tmp_name'];
    $img_size = $_FILES['gambar']['size'];
    $img_error = $_FILES['gambar']['error'];

    // 2. Validasi: Apakah user beneran upload file?
    if ($img_error === 4) {
        $_SESSION['error'] = 'Wajib mengunggah gambar cover buku!';
        header('Location: index.php');
        exit();
    }

    // 3. Validasi Ekstensi File
    $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ext = strtolower(pathinfo($img_name, PATHINFO_EXTENSION));

    if (!in_array($ext, $ekstensiValid)) {
        $_SESSION['error'] = 'Format file salah! Hanya menerima JPG, JPEG, PNG, dan WEBP.';
        header('Location: index.php');
        exit();
    }

    // 4. Validasi MIME Type (Memastikan file di dalamnya beneran struktur gambar, bukan script php yang diganti nama)
    $cekMime = getimagesize($img_tmp);
    if ($cekMime === false) {
        $_SESSION['error'] = 'File terdeteksi corrupt atau bukan gambar asli!';
        header('Location: index.php');
        exit();
    }

    // 5. Validasi Ukuran File (Maksimal 2MB = 2.000.000 bytes)
    if ($img_size > 2000000) {
        $_SESSION['error'] = 'Ukuran gambar terlalu besar! Maksimal adalah 2MB.';
        header('Location: index.php');
        exit();
    }

    // 6. Generate nama baru unik di sini, lalu oper ke class Query
    $nama_baru_gambar = uniqid() . '.' . $ext;

    // Panggil fungsi createBuku dengan nama baru gambar yang sudah aman
    $simpan = $db->createBuku($judul, $pengarang, $penerbit, $tahun, $nama_baru_gambar, $img_tmp);

    if ($simpan) {
        $_SESSION['berhasil'] = 'Buku baru sukses ditambahkan!';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan data buku ke database!';
    }
}

header('Location: index.php');
exit();
?>
