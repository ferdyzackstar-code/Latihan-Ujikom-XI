<div style="width: 240px; background: #227832; color: white; min-height: 100vh; padding: 20px 15px; box-sizing: border-box; display: flex; flex-direction: column; justify-content: space-between;">
    <div>
        <h2 style="text-align: center; margin-bottom: 30px; letter-spacing: 1px; font-size: 22px;">MENU</h2>
        
        <a href="../dashboard/index.php" style="display: block; background: <?= $page == 'dashboard' ? '#fff' : 'transparent' ?>; color: <?= $page == 'dashboard' ? '#227832' : '#fff' ?>; padding: 12px; margin-bottom: 10px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center; transition: 0.2s;">🏠 Dashboard</a>
        
        <a href="../user/index.php" style="display: block; background: <?= $page == 'user' ? '#fff' : 'transparent' ?>; color: <?= $page == 'user' ? '#227832' : '#fff' ?>; padding: 12px; margin-bottom: 10px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center; transition: 0.2s;">👥 Data User</a>
        
        <a href="../buku/index.php" style="display: block; background: <?= $page == 'buku' ? '#fff' : 'transparent' ?>; color: <?= $page == 'buku' ? '#227832' : '#fff' ?>; padding: 12px; margin-bottom: 10px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center; transition: 0.2s;">📖 Data Buku</a>
    </div>

    <div style="border-top: 1px solid rgba(255,255,255,0.3); padding-top: 15px; text-align: center;">
        <p style="margin-bottom: 10px; font-size: 14px;">Halo, <strong><?= $_SESSION['username']; ?></strong></p>
        <a href="../logout.php" style="display: block; background: #a2d9b1; color: #227832; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: bold; text-align: center;">Logout</a>
    </div>
</div>  