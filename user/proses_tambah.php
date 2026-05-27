<?php
session_start();
require_once '../koneksi.php';
require_once '../query.php';
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // trim() digunakan untuk menghapus spasi kosong yang tidak sengaja/sengaja diinput user
    $nama = trim($_POST['nama_user']);
    $user = trim($_POST['username']);
    $pass = $_POST['password']; // Password tidak perlu di-trim karena spasi bisa jadi bagian dari password

    // Bikin array kosong buat nampung semua error
    $errors = [];

    // Validasi 1: Cek form kosong
    if (empty($nama)) {
        $errors[] = 'Nama user tidak boleh kosong!';
    }
    if (empty($user)) {
        $errors[] = 'Username tidak boleh kosong!';
    }
    if (empty($pass)) {
        $errors[] = 'Password tidak boleh kosong!';
    }

    // Validasi 2: Panjang karakter (Hanya dicek kalau inputan gak kosong)
    if (!empty($user) && strlen($user) < 5) {
        $errors[] = 'Username terlalu pendek! Minimal 5 karakter.';
    }
    if (!empty($pass) && strlen($pass) < 8) {
        $errors[] = 'Password terlalu lemah! Minimal 8 karakter.';
    }

    // JIKA ADA ERROR (Array $errors tidak kosong)
    if (!empty($errors)) {
        // Masukkan seluruh array error ke dalam session
        $_SESSION['errors'] = $errors;
        header('Location: index.php');
        exit();
    }

    // Eksekusi simpan ke database (password di-hash otomatis di dalam class Query)
    $simpan = $db->createUser($nama, $user, $pass);

    if ($simpan) {
        $_SESSION['berhasil'] = 'User baru sukses ditambahkan!';
    } else {
        // Karena kita sudah sepakat pakai array list ol, eror database ini kita masukkan ke array errors juga biar seragam
        $_SESSION['errors'] = ['Gagal menambah data, username mungkin sudah digunakan!'];
    }

    // Kembalikan ke halaman utama setelah proses selesai
    header('Location: index.php');
    exit();
}
