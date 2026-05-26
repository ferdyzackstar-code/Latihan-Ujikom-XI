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
                $error = "Password salah!"; 
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
<html>
<head>
    <title>Masuk User</title>
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
            font-weight: bold; 
        }

        .error-box { 
            background: red; 
            color: white; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 10px; 
            font-weight: bold; 
        }

        .success-box { 
            background: green; 
            color: white; 
            padding: 10px; 
            margin-bottom: 15px; 
            border-radius: 4px; 
            font-weight: bold; 
        }
        
    </style>
</head>
<body>

<div class="box">
    
    <div class="logo-sekolah-box">
        <img src="gambar/logotb.jpg">
    </div>

    <h2>Masuk User</h2>
    <p>Masukkan Username dan Password dengan benar!</p>

    <?php if (isset($_SESSION['berhasil'])): ?>
        <div class="success-box"><?= htmlspecialchars($_SESSION['berhasil'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['berhasil']); ?></div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div class="error-box">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="group"> 
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan Username">
        </div>
        <div class="group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan Password">
        </div>
        <button type="submit">Masuk</button>
    </form>

    <p style="color: gray; font-size: small;">Belum Memiliki akun?</p>
    <a href="register.php" class="btn-link">Daftar Akun</a>
</div>

</body>
</html>