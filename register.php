<?php
session_start();
require_once "koneksi.php";

if (isset($_SESSION['username'])) {
    header("Location: dashboard/index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama     = trim($_POST['nama_user']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($nama) && !empty($username) && !empty($password)) {
        
        // ANTISIPASI SQL INJECTION: Cek dulu ketersediaan username dengan Prepared Statement
        $cek = $conn->prepare("SELECT id_user FROM tbl_user WHERE username = ?");
        $cek->bind_param("s", $username);
        $cek->execute();
        $hasil_cek = $cek->get_result();

        if ($hasil_cek->num_rows > 0) {
            $error = "Gagal mendaftar, username sudah digunakan!";
        } else {
            // Password Hashing Aman
            $password_aman = password_hash($password, PASSWORD_DEFAULT);

            // ANTISIPASI SQL INJECTION: Insert data baru pakai Prepared Statement
            $stmt = $conn->prepare("INSERT INTO tbl_user (nama_user, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $username, $password_aman);
            $simpan = $stmt->execute();

            if ($simpan) {
                $_SESSION['berhasil'] = "Registrasi berhasil! Silakan login.";
                header("Location: login.php");
                exit();
            } else { 
                $error = "Terjadi kesalahan saat mendaftar!"; 
            }
            $stmt->close();
        }
        $cek->close();
    } else { 
        $error = "Semua form wajib diisi!"; 
    }
}
?>
<html>
<head>
    <title>Pendaftaran User</title>
    <style>
        body { 
            background: lightgray; 
            margin: 0; 
            padding: 50px; 
            text-align: center; 
            font-family: sans-serif; 
        }

        .box { 
            background: white; 
            padding: 30px; 
            margin: auto; 
            width: 370px; 
            border: 1px solid gray; 
            border-radius: 8px; 
        }
        
        .logo-sekolah-box {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo-sekolah-box img {
            width: 100px; 
            height: 100px;
        }

        .group { 
            text-align: left; 
            margin-bottom: 15px; 
        }

        label { 
            font-weight: bold; 
        }

        input { 
            width: 100%; 
            padding: 10px; 
            border: 1px solid gray; 
            border-radius: 10px; 
        }

        button { 
            width: 100%; 
            padding: 10px; 
            background: blue; 
            color: white; 
            border: none; 
            border-radius: 10px; 
            cursor: pointer; 
        }

        .btn-link { 
            display: inline-block; 
            padding: 15px 15px; 
            background: green; 
            color: white; 
            text-decoration: none; 
            border-radius: 10px; 
            font-weight: bold; }

        .error-box { 
            background: red; 
            color: white; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 10px; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="box">

    <div class="logo-sekolah-box">
        <img src="gambar/logotb.jpg">
    </div>

    <h2>Pendaftaran User</h2>
    <p>Silahkan di isi dengan benar!</p>

    <?php if (isset($error)): ?>
        <div class="error-box"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_user" placeholder="Masukkan Nama Lengkap">
        </div>
        <div class="group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username">
        </div>
        <div class="group"> 
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password">
        </div>
        <button type="submit">Daftar</button>
    </form>

    <p style="color: gray; font-size: small;">Sudah memiliki akun?</p>
    <a href="login.php" class="btn-link">Masuk</a>
</div>

</body>
</html>