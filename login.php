<?php
session_start();
require_once "koneksi.php";

if (isset($_SESSION['username'])) {
    header("Location: dashboard/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $query = "SELECT * FROM tbl_user WHERE username = '$username'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_object();
            if (password_verify($password, $user->password)) {
                $_SESSION['id_user'] = $user->id_user;
                $_SESSION['nama_user'] = $user->nama_user;
                $_SESSION['username'] = $user->username;
                header("Location: dashboard/index.php");
                exit();
            } else { $error = "Password salah cuy!"; }
        } else { $error = "Username tidak ditemukan!"; }
    } else { $error = "Form tidak boleh kosong!"; }
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Masuk User</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #eef5f9; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 35px 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); width: 340px; text-align: center; }
        .logo { font-size: 40px; color: #155724; margin-bottom: 10px; }
        h2 { margin: 5px 0; color: #000; font-size: 26px; font-weight: bold; }
        p.sub { color: #333; font-size: 14px; margin-bottom: 20px; }
        .group { text-align: left; margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; font-size: 14px; color: #333; }
        input { width: 100%; padding: 10px 12px; border: 2px solid #ccc; border-radius: 8px; box-sizing: border-box; font-size: 14px; color: #666; }
        input:focus { border-color: #007bff; outline: none; }
        .btn-blue { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn-green { display: inline-block; padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: bold; text-decoration: none; margin-top: 10px; }
        .alert { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; border: 1px solid #c3e6cb; }
        p.footer-text { font-size: 13px; color: #666; margin-top: 20px; margin-bottom: 5px; }
    </style>
</head>
<body>

<div class="box">
    <div class="logo">🌿</div>
    <h2>Masuk User</h2>
    <p class="sub">Masukkan Username dan Password dengan benar!</p>

    <?php if (isset($_SESSION['berhasil'])): ?>
        <div class="success"><?= $_SESSION['berhasil']; unset($_SESSION['berhasil']); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="alert"><?= $error; ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="group">
            <label>Username:</label>
            <input type="text" name="username" placeholder="Masukan Username" required>
        </div>
        <div class="group">
            <label>Password:</label>
            <input type="password" name="password" placeholder="Masukan Password" required>
        </div>
        <button type="submit" class="btn-blue">Masuk</button>
    </form>

    <p class="footer-text">Belum Memiliki akun?</p>
    <a href="register.php" class="btn-green">Daftar Akun</a>
</div>

</body>
</html>