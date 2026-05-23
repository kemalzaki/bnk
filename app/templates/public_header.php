<?php
// app/templates/public_header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$current_page = $current_page ?? 'home';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'Portal Resmi BNK'; ?> - Sistem Informasi BNK</title>
    <meta name="description" content="<?php echo $page_desc ?? 'Portal Informasi Resmi Badan Narkotika Kabupaten (BNK) untuk penanganan kasus narkotika dan rehabilitasi.'; ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo BASE_URL; ?>/assets/css/public.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg public-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="<?php echo BASE_URL; ?>/public/index.php" id="nav-logo">
                <i class="fas fa-shield-halved text-primary me-2 fs-3"></i>
                <div>
                    <span class="d-block lh-1 fs-5">SI-BNK</span>
                    <span class="d-block text-muted fs-7 font-monospace fw-normal" style="font-size: 0.65rem; letter-spacing: 1px;">PORTAL RESMI</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavPublic">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavPublic">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item <?php echo ($current_page === 'home') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/index.php" id="nav-home">Beranda</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'profil') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/profil/index.php" id="nav-profil">Profil</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'berita') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/berita/index.php" id="nav-berita">Berita</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'galeri') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/galeri/index.php" id="nav-galeri">Galeri</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'produk_hukum') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/produk_hukum/index.php" id="nav-produk-hukum">Produk Hukum</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'buku_tamu') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/buku_tamu/index.php" id="nav-buku-tamu">Buku Tamu</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page === 'kontak') ? 'active' : ''; ?>">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>/public/kontak/index.php" id="nav-kontak">Kontak</a>
                    </li>
                    <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                        <a class="btn btn-premium-outline btn-sm px-3" href="<?php echo BASE_URL; ?>/admin/dashboard.php" id="btn-login-admin">
                            <i class="fas fa-user-lock me-1"></i> Admin Panel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
