<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

require_once "../koneksi.php";
require_once "../query.php";
$db = new Query($conn);

$page = 'user';
$title = 'Kelola Data User';

// LOGIKA IF PHP UNTUK MODE EDIT (Pake GET URL)
$isEdit = false;
$uEdit = null;
if (isset($_GET['aksi']) && $_GET['aksi'] == 'edit' && !empty($_GET['id'])) {
    $isEdit = true;
    $uEdit = $db->getIdUser($_GET['id']);
}

// Ambil keyword pencarian jika ada
$keyword = isset($_GET['cari']) ? $_GET['cari'] : "";
$listUser = $db->readUser($keyword);
?>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?></title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f6f9; margin: 0; padding: 0; }
        .flex-main { display: flex; min-height: 100vh; }
        .content { flex: 1; display: flex; flex-direction: column; }
        .inner-content { padding: 0 30px 30px 30px; display: flex; gap: 20px; }
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
        .btn-orange { background: #ffc107; color: black; padding: 4px 8px; font-size: 12px; }
        .btn-red { background: #dc3545; color: white; padding: 4px 8px; font-size: 12px; }
        .alert { padding: 10px; border-radius: 6px; margin: 15px 30px 0 30px; font-size: 14px; font-weight: bold; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background: #227832; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
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
                <h3><?= $isEdit ? "Ubah Data User" : "Tambah User"; ?></h3>
                
                <form action="<?= $isEdit ? 'proses_edit.php' : 'proses_tambah.php'; ?>" method="POST">
                    
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id_user" value="<?= $uEdit->id_user; ?>">
                    <?php endif; ?>

                    <div class="group">
                        <label>Nama Lengkap:</label>
                        <input type="text" name="nama_user" value="<?= $isEdit ? htmlspecialchars($uEdit->nama_user) : ''; ?>" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="group">
                        <label>Username:</label>
                        <input type="text" name="username" value="<?= $isEdit ? htmlspecialchars($uEdit->username) : ''; ?>" placeholder="Masukkan username" required>
                    </div>
                    <div class="group">
                        <label>Password:</label>
                        <input type="password" name="password" placeholder="<?= $isEdit ? 'Kosongkan jika tidak diganti' : 'Masukkan password'; ?>" <?= $isEdit ? '' : 'required'; ?>>
                    </div>
                    
                    <button type="submit" class="btn btn-green"><?= $isEdit ? "Simpan Perubahan" : "Simpan Data"; ?></button>
                    
                    <?php if ($isEdit): ?>
                        <div style="margin-top: 10px; text-align: center;">
                            <a href="index.php" class="btn btn-gray" style="width: 100%; box-sizing: border-box;">Batal Edit</a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="card table-box">
                <h3>Daftar Admin / User</h3>
                
                <form action="index.php" method="GET" style="display: flex; justify-content: flex-end; gap: 8px; margin-bottom: 10px;">
                    <input type="text" name="cari" placeholder="Cari nama atau username..." value="<?= htmlspecialchars($keyword); ?>" style="width: 220px;">
                    <button type="submit" class="btn btn-blue">Cari</button>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Waktu Terdaftar</th>
                            <th style="width: 110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($listUser->num_rows > 0):
                            while ($row = $listUser->fetch_object()): 
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row->nama_user); ?></td>
                            <td><?= htmlspecialchars($row->username); ?></td>
                            <td><?= $row->create_at; ?></td>
                            <td>
                                <a href="index.php?aksi=edit&id=<?= $row->id_user; ?>" class="btn btn-orange">Ubah</a>
                                <a href="proses_hapus.php?id=<?= $row->id_user; ?>" class="btn btn-red" onclick="return confirm('Yakin mau menghapus user ini?')">Hapus</a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else:
                        ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #666;">Data tidak ditemukan!</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>   