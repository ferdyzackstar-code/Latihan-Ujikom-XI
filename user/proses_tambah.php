<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_user'];
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (!empty($nama) && !empty($user) && !empty($pass)) {
        $simpan = $db->createUser($nama, $user, $pass);
        if ($simpan) {
            $_SESSION['berhasil'] = "User baru sukses ditambahkan!";
        } else {
            $_SESSION['error'] = "Gagal menambah data, username mungkin sudah dipakai!";
        }
    } else {
        $_SESSION['error'] = "Form tidak boleh ada yang kosong!";
    }
}
header("Location: index.php");
exit();