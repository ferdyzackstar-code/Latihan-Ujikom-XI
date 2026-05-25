<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

$page = 'buku';
$title = 'Kelola Data Buku';

// LOGIKA IF PHP UNTUK MODE EDIT (Pake GET URL)
$isEdit = false;
$bEdit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && !empty($_GET['id'])) {
    $isEdit = true;
    $bEdit = $db->getIdBuku($_GET['id']);
}

// Ambil keyword pencarian jika ada
$keyword = isset($_GET['cari']) ? $_GET['cari'] : "";
$listBuku = $db->readBuku($keyword);
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .flex-main { display: flex; min-height: 100vh; }
        .content { flex: 1; display: flex; flex-direction: column; }
        .inner-content { padding: 10px 30px 30px 30px; display: flex; gap: 20px; }
        .card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); box-sizing: border-box; }
        .form-box { width: 320px; }
        .table-box { flex: 1; }
        h3 { margin-top: 0; color: #155724; border-bottom: 2px solid #227832; padding-bottom: 8px; }
        .group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; }
        input { width: 100%; padding: 8px 10px; border: 2px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        input:focus { border-color: #007bff; outline: none; }
        .btn { padding: 8px 15px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; font-size: 14px; }
        .btn-blue { background: #007bff; color: white; }
        .btn-green { background: #28a745; color: white; width: 100%; }
        .btn-gray { background: #6c757d; color: white; }
        .btn-blue { background: #0b5ed7; color: white; padding: 6px 12px; font-size: 13px; }
        .btn-red { background: #bb2d3b; color: white; padding: 6px 12px; font-size: 13px; }
        .alert { padding: 10px; border-radius: 6px; margin: 15px 30px 0 30px; font-size: 14px; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; vertical-align: middle; }
        th { background: #227832; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        .img-preview { width: 60px; height: 80px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="flex-main">
    <?php include '../layouts/sidebar.php'; ?>

    <div class="content">
        <?php include '../layouts/topbar.php'; ?>

        <?php if (isset($_SESSION['berhasil'])): ?>
            <div class="alert success"><?= $_SESSION['berhasil']; unset($_SESSION['berhasil']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="inner-content">
            
            <div class="card form-box">
                <h3><?= $isEdit ? "Ubah Data Buku" : "Tambah Buku"; ?></h3>
                
                <form action="<?= $isEdit ? 'proses_edit.php' : 'proses_tambah.php'; ?>" method="POST" enctype="multipart/form-data">
                    
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id_buku" value="<?= $bEdit->id_buku; ?>">
                    <?php endif; ?>

                    <div class="group">
                        <label>Judul Buku</label>
                        <input type="text" name="judul_buku" value="<?= $isEdit ? htmlspecialchars($bEdit->judul_buku) : ''; ?>" placeholder="Masukkan judul" required>
                    </div>
                    <div class="group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang_buku" value="<?= $isEdit ? htmlspecialchars($bEdit->pengarang_buku) : ''; ?>" placeholder="Masukkan pengarang" required>
                    </div>
                    <div class="group">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit_buku" value="<?= $isEdit ? htmlspecialchars($bEdit->penerbit_buku) : ''; ?>" placeholder="Masukkan penerbit" required>
                    </div>
                    <div class="group">
                        <label>Tahun Terbit</label>
                        <input type="number" name="tahun" value="<?= $isEdit ? htmlspecialchars($bEdit->tahun) : ''; ?>" placeholder="Contoh: 2024" required>
                    </div>
                    <div class="group">
                        <label>Cover Buku</label>
                        <input type="file" name="gambar" <?= $isEdit ? '' : 'required'; ?>>
                        <?php if ($isEdit): ?>
                            <small style="color: blue; display:block; margin-top:5px;">Kosongkan jika gambar tidak diganti</small>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn btn-green"><?= $isEdit ? "Simpan Perubahan" : "Tambah Buku"; ?></button>
                    
                    <?php if ($isEdit): ?>
                        <div style="margin-top: 10px; text-align: center;">
                            <a href="index.php" class="btn btn-gray" style="width: 100%; box-sizing: border-box;">Batal Edit</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card table-box">
                <h3>Data Buku</h3>
                
                <form action="index.php" method="GET" style="display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px;">
                    <input type="text" name="cari" placeholder="Cari judul atau pengarang..." value="<?= htmlspecialchars($keyword); ?>" style="width: 220px;">
                    <button type="submit" class="btn btn-blue">Cari</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th style="width: 70px;">Cover</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($listBuku->num_rows > 0):
                            while ($row = $listBuku->fetch_object()): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <img src="../gambar/<?= $row->gambar; ?>" class="img-preview" alt="Cover">
                            </td>
                            <td><?= htmlspecialchars($row->judul_buku); ?></td>
                            <td><?= htmlspecialchars($row->pengarang_buku); ?></td>
                            <td><?= htmlspecialchars($row->penerbit_buku); ?></td>
                            <td><?= htmlspecialchars($row->tahun); ?></td>
                            <td>
                                <a href="index.php?aksi=edit&id=<?= $row->id_buku; ?>" class="btn btn-blue">Edit</a>
                                <a href="proses_hapus.php?id=<?= $row->id_buku; ?>" class="btn btn-red" onclick="return confirm('Yakin mau menghapus buku ini?')">Delete</a>
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

<?php include '../layouts/footer.php'; ?>