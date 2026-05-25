<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id   = $_POST['id_user'];
    $nama = $_POST['nama_user'];
    $user = $_POST['username'];
    $pass = $_POST['password']; // Bisa kosong jika tidak diganti

    if (!empty($id) && !empty($nama) && !empty($user)) {
        $update = $db->updateUser($id, $nama, $user, $pass);
        if ($update) {
            $_SESSION['berhasil'] = "Data user sukses diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui data user!";
        }
    } else {
        $_SESSION['error'] = "Form nama dan username wajib diisi!";
    }
}
header("Location: index.php");
exit();