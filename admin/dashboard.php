<?php
require_once dirname(__DIR__) . '/app/config/app.php';

// Proteksi halaman admin
check_login();

// Ambil statistik 
require_once APP_PATH . '/queries/dashboard_query.php';
$conn = get_db_connection();
$stats = get_dashboard_stats($conn);
mysqli_close($conn);

$total_kasus = $stats['total_kasus'];
$total_rehab = $stats['total_rehab'];
$total_berita = $stats['total_berita'];
$total_tamu = $stats['total_tamu'];

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <span class="text-muted"><?php echo format_date(date('Y-m-d'), 'd F Y'); ?></span>
</div>

<?php display_flash_message(); ?>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Total Kasus</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_kasus; ?></h2>
                    </div>
                    <i class="fas fa-balance-scale fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="<?php echo BASE_URL; ?>/app/modules/kasus/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Pasien Rehab</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_rehab; ?></h2>
                    </div>
                    <i class="fas fa-hospital-user fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="<?php echo BASE_URL; ?>/app/modules/rehabilitasi/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Berita Publik</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_berita; ?></h2>
                    </div>
                    <i class="fas fa-newspaper fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="<?php echo BASE_URL; ?>/app/modules/berita/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-danger h-100 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title text-uppercase mb-0">Pesan Tamu</h6>
                        <h2 class="mt-2 mb-0"><?php echo $total_tamu; ?></h2>
                    </div>
                    <i class="fas fa-envelope fa-3x opacity-50"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="<?php echo BASE_URL; ?>/app/modules/buku_tamu/index.php">Lihat Detail</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Selamat Datang di Sistem Informasi BNK</h5>
            </div>
            <div class="card-body">
                <p>Aplikasi ini digunakan untuk manajemen data kasus narkotika, rehabilitasi, dan pengelolaan konten portal publik BNK.</p>
                <p>Gunakan menu di sebelah kiri untuk menavigasi fitur-fitur sistem.</p>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
