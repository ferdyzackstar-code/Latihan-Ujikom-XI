<?php
session_start();
// Proteksi halaman: jika belum login, tendang kembali ke form login
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Menentukan status link active pada sidebar
$page = 'dashboard';
$title = 'Dashboard Perpustakaan | Home';
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .flex-main { display: flex; min-height: 100vh; }
        .content { flex: 1; padding: 0; display: flex; flex-direction: column; }
        .inner-content { padding: 0 30px 30px 30px; }
        .info-card { background: #eaf5ec; border: 1px solid #c3e6cb; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
        .info-card h3 { margin-top: 0; color: #155724; font-size: 18px; }
        .info-card p { margin: 0; color: #222; font-size: 14px; line-height: 1.6; }
        .banner-img { width: 100%; max-width: 800px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); }
    </style>
</head>
<body>

<div class="flex-main">
    <?php include '../layouts/sidebar.php'; ?>

    <div class="content">
        <?php include '../layouts/topbar.php'; ?>

        <div class="inner-content">
            <div class="info-card">
                <h3>Informasi</h3>
                <p>"Selamat Datang di Manajemen Perpustakaan. Temukan ribuan koleksi literasi dalam genggaman Anda. Gunakan fitur pencarian untuk menemukan referensi tugas atau sekadar mencari bacaan akhir pekan yang menarik."</p>
            </div>
            
            <img src="../gambar/backgroundTb.jpg" class="banner-img" alt="Gedung Perpustakaan">
        </div>

    </div>
</div>

<?php include '../layouts/footer.php'; ?>