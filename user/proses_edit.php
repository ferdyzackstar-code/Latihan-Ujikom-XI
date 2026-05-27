<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Amankan ID dengan memaksa tipenya menjadi Integer angka
    $id   = (int)$_POST['id_user'];
    $nama = trim($_POST['nama_user']);
    $user = trim($_POST['username']);
    $pass = $_POST['password']; // Bisa kosong jika tidak diganti

    if ($id > 0 && !empty($nama) && !empty($user)) {
        $update = $db->updateUser($id, $nama, $user, $pass);
        
        if ($update) {
            $_SESSION['berhasil'] = "Data user sukses diperbarui!";
            header("Location: index.php"); // Sukses -> Kembali ke halaman utama user
            exit();
        } else {
            $_SESSION['error'] = "Gagal memperbarui data user! Username mungkin sudah dipakai.";
        }
    } else {
        $_SESSION['error'] = "Form nama dan username wajib diisi dengan benar!";
    }
    
    // Jika gagal, kembalikan ke halaman form edit berdasarkan ID user tersebut
    header("Location: index.php?id=" . $id); // Sesuaikan dengan parameter halaman edit kamu
    exit();
}