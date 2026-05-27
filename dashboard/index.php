<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}
?>
<html>

<head>
    <title>Dashboard Perpustakaan | Home</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: #f4f6f9;
        }

        /* Cuma ini flex-nya biar sidebar ama konten bisa sebaris */
        .wrapper-utama {
            display: flex;
            min-height: 100vh;
        }

        .area-konten {
            flex: 1;
            padding: 20px;
        }

        /* Topbar disatukan langsung */
        .topbar-manual {
            background: white;
            padding: 15px;
            border-bottom: 1px solid lightgray;
            margin-bottom: 20px;
        }

        /* Kotak info ala kadarnya */
        .kotak-info {
            background: #eaf5ec;
            border: 1px solid #c3e6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="wrapper-utama">

        <?php include '../layouts/sidebar.php'; ?>

        <div class="area-konten">

            <div class="topbar-manual">
                <h2 style="margin: 0;">Dashboard Perpustakaan | Home</h2>
            </div>

            <div class="kotak-info">
                <h4>Informasi</h4>
                <p>Selamat Datang di Manajemen Perpustakaan. Temukan ribuan koleksi literasi dalam genggaman Anda. Gunakan fitur pencarian untuk menemukan referensi tugas atau sekadar mencari bacaan akhir pekan yang menarik.</p>
            </div>

            <img src="../gambar/backgroundTb.jpg" style="width: 100%; max-width: 600px; border-radius: 8px;"
                alt="Perpustakaan">

        </div>
    </div>

</body>

</html>
