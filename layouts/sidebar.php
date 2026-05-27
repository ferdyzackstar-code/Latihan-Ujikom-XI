<style>
    .sidebar-biasa {
        width: 220px;
        background: darkgreen;
        color: white;
        padding: 20px 10px;
        font-family: sans-serif;
    }

    .sidebar-biasa a {
        display: block;
        background: white;
        color: darkgreen;
        padding: 10px;
        margin-bottom: 10px;
        text-decoration: none;
        font-weight: bold;
        text-align: center;
        border-radius: 4px;
    }

    .sidebar-biasa .btn-logout {
        background: #63d959;
        color: darkgreen;
    }
</style>

<div class="sidebar-biasa">
    <h3 style="text-align: center; margin-bottom: 20px;">MENU</h3>

    <a href="../dashboard/index.php">Dashboard</a>
    <a href="../user/index.php">Data User</a>
    <a href="../buku/index.php">Data Buku</a>

    <div style="border-top: 1px solid white; margin-top: 40px; padding-top: 15px; text-align: center;">
        <p style="font-size: 14px; margin-bottom: 10px;">Halo,
            <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></p>
        <a href="../logout.php" class="btn-logout">Logout</a>
    </div>
</div>
