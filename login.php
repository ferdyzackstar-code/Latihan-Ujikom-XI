<?php
session_start();
require_once "koneksi.php";

if (isset($_SESSION['username'])) {
    header("Location: dashboard/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // ANTISIPASI SQL INJECTION: Menggunakan Prepared Statement (?)
        $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_object();
            // Verifikasi password hash aman
            if (password_verify($password, $user->password)) {
                $_SESSION['id_user'] = $user->id_user;
                $_SESSION['nama_user'] = $user->nama_user;
                $_SESSION['username'] = $user->username;
                header("Location: dashboard/index.php");
                exit();
            } else { 
                $error = "Password salah cuy!"; 
            }
        } else { 
            $error = "Username tidak ditemukan!"; 
        }
        $stmt->close();
    } else { 
        $error = "Form tidak boleh kosong!"; 
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Masuk User</title>
    <style>
        body { background: lightgray; margin: 0; padding: 50px; text-align: center; font-family: sans-serif; }
        .box { background: white; padding: 30px; margin: auto; width: 400px; border: 1px solid gray; border-radius: 8px; }
        
        /* Style Tambahan untuk Wadah Logo Sekolah */
        .logo-sekolah-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-sekolah-box img {
            width: 90px; /* Atur lebar logo sekolah di sini */
            height: auto;
        }

        h2 { color: green; margin: 0 0 10px 0; }
        .group { text-align: left; margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: black; }
        input { width: 100%; padding: 8px; border: 1px solid gray; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: blue; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        .btn-link { display: inline-block; margin-top: 15px; padding: 8px 15px; background: green; color: white; text-decoration: none; border-radius: 4px; font-weight: bold; }
        .error-box { background: red; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; }
        .success-box { background: green; color: white; padding: 10px; margin-bottom: 15px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>

<div class="box">
    
    <div class="logo-sekolah-box">
        <img src="gambar/logotb.jpg">
    </div>

    <h2>Masuk User</h2>
    <p style="color: black; margin-bottom: 20px;">Masukkan Username dan Password dengan benar!</p>

    <?php if (isset($_SESSION['berhasil'])): ?>
        <div class="success-box"><?= htmlspecialchars($_SESSION['berhasil'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['berhasil']); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-box"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Masuk</button>
    </form>

    <p style="color: black; margin-top: 20px; margin-bottom: 5px;">Belum Memiliki akun?</p>
    <a href="register.php" class="btn-link">Daftar Akun</a>
</div>

</body>
</html>