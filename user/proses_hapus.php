<?php
session_start();
require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $hapus = $db->deleteUser($id);
    
    if ($hapus) {
        $_SESSION['berhasil'] = "Data user berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data user!";
    }
}
header("Location: index.php");
exit();