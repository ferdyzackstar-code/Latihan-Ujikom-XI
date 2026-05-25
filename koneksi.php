<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan_farelFerdyawan";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal cuy: " . $conn->connect_error);
}
?>