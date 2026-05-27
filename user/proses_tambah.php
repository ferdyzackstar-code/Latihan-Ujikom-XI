<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // trim() digunakan untuk menghapus spasi kosong yang tidak sengaja/sengaja diinput user
    $nama = trim($_POST['nama_user']);
    $user = trim($_POST['username']);
    $pass = $_POST['password']; // Password tidak perlu di-trim karena spasi bisa jadi bagian dari password

    // Cek apakah setelah di-trim, form-nya beneran diisi teks valid
    if (!empty($nama) && !empty($user) && !empty($pass)) {
        
        // Eksekusi simpan ke database (password di-hash otomatis di dalam class Query)
        $simpan = $db->createUser($nama, $user, $pass);
        
        if ($simpan) {
            $_SESSION['berhasil'] = "User baru sukses ditambahkan!";
            header("Location: index.php"); // Sukses -> Kembali ke halaman utama user
            exit();
        } else {
            $_SESSION['error'] = "Gagal menambah data, username mungkin sudah digunakan!";
        }
    } else {
        $_SESSION['error'] = "Semua form wajib diisi dengan benar (tidak boleh hanya spasi)!";
    }
    
    // Jika gagal, kembalikan ke form tambah user (sesuaikan nama filenya, misal: tambah.php atau index.php?page=tambah)
    header("Location: index.php"); 
    exit();
}