<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

require_once '../koneksi.php';
require_once '../query.php';
$db = new Query($conn);

$page = 'buku';
$title = 'Kelola Data Buku';

// LOGIKA IF PHP UNTUK MODE EDIT
$isEdit = false;
$bEdit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && !empty($_GET['id'])) {
    $isEdit = true;
    $bEdit = $db->getIdBuku($_GET['id']);
}

$keyword = isset($_GET['cari']) ? $_GET['cari'] : '';
$listBuku = $db->readBuku($keyword);
?>
<html>

<head>
    <title><?= $title ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background: #f4f6f9;
        }

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

        .inner-content {
            display: flex;
            gap: 20px;
        }

        .kotak-putih {
            background: white;
            padding: 20px;
            border: 1px solid lightgray;
            border-radius: 6px;
        }

        .form-box {
            width: 300px;
        }

        .table-box {
            flex: 1;
        }

        .group {
            margin-bottom: 12px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 13px;
        }

        .btn-green {
            background: #28a745;
            color: white;
            width: 100%;
        }

        .btn-blue {
            background: #0b5ed7;
            color: white;
        }

        .btn-red {
            background: #bb2d3b;
            color: white;
        }

        .btn-gray {
            background: #6c757d;
            color: white;
        }

        .alert {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .success {
            background: #d4edda;
            color: #155724;
        }

        .danger {
            background: #f8d7da;
            color: #721c24;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
            vertical-align: middle;
        }

        th {
            background: #227832;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .img-preview {
            width: 50px;
            height: 65px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <div class="wrapper-utama">
        <?php include '../layouts/sidebar.php'; ?>

        <div class="area-konten">
            <div class="topbar-manual">
                <h2 style="margin: 0;"><?= $title ?></h2>
            </div>

        <?php if (isset($_SESSION['berhasil'])): ?>
            <div class="alert success"><?= $_SESSION['berhasil']; unset($_SESSION['berhasil']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

            <div class="inner-content">
                <div class="kotak-putih form-box">
                    <h3 style="margin-top:0; border-bottom: 2px solid #227832; padding-bottom:5px;">
                        <?= $isEdit ? 'Ubah Buku' : 'Tambah Buku' ?></h3>
                    <form action="<?= $isEdit ? 'proses_edit.php' : 'proses_tambah.php' ?>" method="POST"
                        enctype="multipart/form-data">

                        <?php if ($isEdit): ?>
                        <input type="hidden" name="id_buku" value="<?= $bEdit->id_buku ?>">
                        <?php endif; ?>

                        <div class="group">
                            <label>Judul Buku</label>
                            <input type="text" name="judul_buku"
                                value="<?= $isEdit ? htmlspecialchars($bEdit->judul_buku) : '' ?>"
                                placeholder="Judul buku" >
                        </div>
                        <div class="group">
                            <label>Pengarang</label>
                            <input type="text" name="pengarang_buku"
                                value="<?= $isEdit ? htmlspecialchars($bEdit->pengarang_buku) : '' ?>"
                                placeholder="Nama pengarang" >
                        </div>
                        <div class="group">
                            <label>Penerbit</label>
                            <input type="text" name="penerbit_buku"
                                value="<?= $isEdit ? htmlspecialchars($bEdit->penerbit_buku) : '' ?>"
                                placeholder="Nama penerbit" >
                        </div>
                        <div class="group">
                            <label>Tahun Terbit</label>
                            <input type="number" name="tahun"
                                value="<?= $isEdit ? htmlspecialchars($bEdit->tahun) : '' ?>" placeholder="Contoh: 2026"
                                >
                        </div>
                        <div class="group">
                            <label>Cover Buku</label>
                            <input type="file" name="gambar" <?= $isEdit ? '' : '' ?>>
                        </div>

                        <button type="submit"
                            class="btn btn-green"><?= $isEdit ? 'Simpan Perubahan' : 'Tambah Buku' ?></button>

                        <?php if ($isEdit): ?>
                        <div style="margin-top: 10px;">
                            <a href="index.php" class="btn btn-gray"
                                style="width: 100%; text-align: center; box-sizing: border-box;">Batal</a>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="kotak-putih table-box">
                    <h3 style="margin-top:0; border-bottom: 2px solid #227832; padding-bottom:5px;">Data Buku</h3>

                    <form action="index.php" method="GET"
                        style="display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px;">
                        <input type="text" name="cari" placeholder="Cari judul..."
                            value="<?= htmlspecialchars($keyword) ?>" style="width: 200px;">
                        <button type="submit" class="btn btn-blue">Cari</button>
                    </form>

                    <table>
                        <thead>
                            <tr>
                                <th style="width: 40px;">No</th>
                                <th style="width: 60px;">Cover</th>
                                <th>Judul Buku</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                        $no = 1;
                        if ($listBuku->num_rows > 0):
                            while ($row = $listBuku->fetch_object()): 
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><img src="../gambar/<?= $row->gambar ?>" class="img-preview" alt="Cover"></td>
                                <td><?= htmlspecialchars($row->judul_buku) ?></td>
                                <td><?= htmlspecialchars($row->pengarang_buku) ?></td>
                                <td><?= htmlspecialchars($row->penerbit_buku) ?></td>
                                <td><?= htmlspecialchars($row->tahun) ?></td>
                                <td>
                                    <a href="index.php?aksi=edit&id=<?= $row->id_buku ?>" class="btn btn-blue">Edit</a>
                                    <a href="proses_hapus.php?id=<?= $row->id_buku ?>" class="btn btn-red"
                                        onclick="return confirm('Hapus buku ini?')">Delete</a>
                                </td>
                            </tr>
                            <?php 
                            endwhile; 
                        else:
                        ?>
                            <tr>
                                <td colspan="7" style="text-align: center; color: #666;">Data tidak ditemukan!</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
