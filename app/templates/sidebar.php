<?php
// app/templates/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? 'petugas';
?>
<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header">
        <h4>BNK Admin Panel</h4>
    </div>

    <ul class="list-unstyled components">
        <li class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
        </li>
        
        <li class="<?php echo ($current_page == 'kasus' || strpos($_SERVER['REQUEST_URI'], '/kasus/') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/app/modules/kasus/index.php"><i class="fas fa-balance-scale me-2"></i> Manajemen Kasus</a>
        </li>
        
        <li class="<?php echo ($current_page == 'individu' || strpos($_SERVER['REQUEST_URI'], '/individu/') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/app/modules/individu/index.php"><i class="fas fa-users me-2"></i> Manajemen Individu</a>
        </li>
        
        <li class="<?php echo ($current_page == 'rehabilitasi' || strpos($_SERVER['REQUEST_URI'], '/rehabilitasi/') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/app/modules/rehabilitasi/index.php"><i class="fas fa-hospital-user me-2"></i> Rehabilitasi</a>
        </li>
        
        <li>
            <a href="#laporanSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-file-alt me-2"></i> Laporan & Statistik</a>
            <ul class="collapse list-unstyled" id="laporanSubmenu">
                <li><a href="<?php echo BASE_URL; ?>/app/modules/laporan/kasus.php">Laporan Kasus</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/laporan/rehabilitasi.php">Laporan Rehabilitasi</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/laporan/statistik.php">Statistik Umum</a></li>
            </ul>
        </li>

        <?php if ($role === 'admin'): ?>
        <li>
            <a href="#kontenSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-globe me-2"></i> Konten Website</a>
            <ul class="collapse list-unstyled" id="kontenSubmenu">
                <li><a href="<?php echo BASE_URL; ?>/app/modules/berita/index.php">Berita</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/galeri/index.php">Galeri</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/produk_hukum/index.php">Produk Hukum</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/buku_tamu/index.php">Buku Tamu</a></li>
                <li><a href="<?php echo BASE_URL; ?>/app/modules/profil_bnk/index.php">Profil BNK</a></li>
            </ul>
        </li>
        
        <li class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/users/') !== false) ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>/app/modules/users/index.php"><i class="fas fa-user-cog me-2"></i> Manajemen Pengguna</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>
