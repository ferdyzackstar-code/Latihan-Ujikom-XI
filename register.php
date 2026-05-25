<?php
session_start();
require_once "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = $_POST['nama_user'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($nama) && !empty($username) && !empty($password)) {
        $password_aman = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO tbl_user (nama_user, username, password) VALUES ('$nama', '$username', '$password_aman')";
        $simpan = $conn->query($query);

        if ($simpan) {
            $_SESSION['berhasil'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit();
        } else { $error = "Gagal mendaftar, username mungkin sudah ada!"; }
    } else { $error = "Semua form wajib diisi!"; }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Pendaftaran User</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #eef5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 35px 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 340px; text-align: center; }
        .logo { font-size: 40px; color: #155724; margin-bottom: 10px; }
        h2 { margin: 5px 0; color: #000; font-size: 26px; font-weight: bold; }
        p.sub { color: #666; font-size: 14px; margin-bottom: 20px; }
        .group { text-align: left; margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 14px; color: #333; }
        input { width: 100%; padding: 10px 12px; border: 2px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 14px; color: #666; }
        input:focus { border-color: #007bff; outline: none; }
        .btn-blue { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-green { display: inline-block; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: bold; text-decoration: none; margin-top: 10px; }
        .alert { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #f5c6cb; }
        p.footer-text { font-size: 13px; color: #666; margin-top: 20px; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="box">
    <div class="logo">🌿</div>
    <h2>Pendaftaran User</h2>
    <p class="sub">Silahkan di isi dengan benar!</p>

    <?php if (isset($error)): ?>
        <div class="alert"><?= $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="group">
            <label>Nama lengkap:</label>
            <input type="text" name="nama_user" placeholder="Masukkan Nama lengkap" required>
        </div>
        <div class="group">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Masukkan Username" required>
        </div>
        <div class="group">
            <label>Password:</label>
            <input type="password" name="password" placeholder="Masukkan Password" required>
        </div>
        <button type="submit" class="btn-blue">Daftar</button>
    </form>

    <p class="footer-text">Sudah memiliki akun?</p>
    <a href="login.php" class="btn-green">Masuk</a>
</div>

</body>
</html>