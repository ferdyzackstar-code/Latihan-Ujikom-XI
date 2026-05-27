<?php
session_start();
require_once '../koneksi.php';
require_once '../query.php';
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Amankan ID dengan memaksa tipenya menjadi Integer angka
    $id = (int) $_POST['id_user'];
    $nama = trim($_POST['nama_user']);
    $user = trim($_POST['username']);
    $pass = $_POST['password']; // Bisa kosong jika tidak diganti

    // Bikin array kosong buat nampung semua error
    $errors = [];

    // Validasi 1: Cek ID validasi dasar
    if ($id <= 0) {
        $errors[] = 'ID User tidak valid!';
    }

    // Validasi 2: Cek form wajib yang kosong
    if (empty($nama)) {
        $errors[] = 'Nama user tidak boleh kosong!';
    }
    if (empty($user)) {
        $errors[] = 'Username tidak boleh kosong!';
    }

    // Validasi 3: Panjang karakter Username
    if (!empty($user) && strlen($user) < 5) {
        $errors[] = 'Username terlalu pendek! Minimal 5 karakter.';
    }

    // Validasi 4: Panjang karakter Password (HANYA dicek jika password diisi/ingin diganti)
    if (!empty($pass) && strlen($pass) < 8) {
        $errors[] = 'Password baru terlalu lemah! Minimal 8 karakter.';
    }

    // JIKA ADA ERROR (Array $errors tidak kosong)
    if (!empty($errors)) {
        // Masukkan seluruh array error ke dalam session
        $_SESSION['errors'] = $errors;
        // Kembalikan ke halaman form edit berdasarkan ID user tersebut
        header('Location: index.php?id=' . $id);
        exit();
    }

    // JIKA LOLOS VALIDASI: Eksekusi update ke database
    $update = $db->updateUser($id, $nama, $user, $pass);

    if ($update) {
        $_SESSION['berhasil'] = 'Data user sukses diperbarui!';
        header('Location: index.php'); // Sukses -> Kembali ke halaman utama user
        exit();
    } else {
        // Jika gagal di tingkat database (misal username kembar)
        $_SESSION['errors'] = ['Gagal memperbarui data user! Username mungkin sudah dipakai.'];
    }

    // Jika gagal di database, kembalikan ke halaman form edit lagi
    header('Location: index.php?id=' . $id);
    exit();
}
