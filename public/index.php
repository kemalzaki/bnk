<?php
require_once dirname(__DIR__) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$current_page = 'home';
$page_title = 'Beranda';
$page_desc = 'Selamat datang di Portal Resmi Sistem Informasi Badan Narkotika Kabupaten (BNK). Dapatkan informasi terbaru seputar penanganan kasus narkotika dan rehabilitasi.';

$conn = get_db_connection();
$stats = get_public_stats($conn);
$berita_terbaru = get_published_berita($conn, 3);
mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Hero Section -->
<section class="hero-section text-center py-5 d-flex align-items-center" style="min-height: 450px;">
    <div class="container py-4 fade-in-up">
        <h1 class="display-4 fw-bold mb-3">Sistem Informasi Badan Narkotika Kabupaten</h1>
        <p class="lead mb-4 mx-auto" style="max-width: 800px;">
            Mewujudkan masyarakat yang bersih dari penyalahgunaan dan peredaran gelap narkotika melalui transparansi data penanganan kasus, monitoring rehabilitasi, dan penyebaran produk hukum.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?php echo BASE_URL; ?>/public/profil/index.php" class="btn btn-premium-primary btn-lg px-4" id="hero-btn-profil">Tentang BNK</a>
            <a href="<?php echo BASE_URL; ?>/public/buku_tamu/index.php" class="btn btn-premium-outline btn-lg px-4 text-white border-white" id="hero-btn-buku-tamu">Isi Buku Tamu</a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-5" style="background-color: var(--bg-light);">
    <div class="container py-4">
        <div class="text-center mb-5">
            <h2 class="fw-bold" id="section-stats-title">Statistik Pemantauan</h2>
            <p class="text-muted">Data aktual penanganan kasus narkotika dan rehabilitasi di wilayah kabupaten.</p>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="stat-item shadow-sm">
                    <div class="stat-number" id="stat-total-kasus"><?php echo number_format($stats['total_kasus']); ?></div>
                    <div class="fw-bold text-uppercase tracking-wider text-muted small">Total Kasus Terdaftar</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-item shadow-sm border-warning">
                    <div class="stat-number text-warning" id="stat-kasus-penyidikan"><?php echo number_format($stats['kasus_penyidikan']); ?></div>
                    <div class="fw-bold text-uppercase tracking-wider text-muted small">Kasus dalam Penyidikan</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-item shadow-sm border-success">
                    <div class="stat-number text-success" id="stat-kasus-selesai"><?php echo number_format($stats['kasus_selesai']); ?></div>
                    <div class="fw-bold text-uppercase tracking-wider text-muted small">Kasus Selesai</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-item shadow-sm border-info">
                    <div class="stat-number text-info" id="stat-rehab-aktif"><?php echo number_format($stats['total_rehab_aktif']); ?></div>
                    <div class="fw-bold text-uppercase tracking-wider text-muted small">Pasien Rehabilitasi Aktif</div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-item shadow-sm border-success">
                    <div class="stat-number text-success" id="stat-rehab-selesai"><?php echo number_format($stats['total_rehab_selesai']); ?></div>
                    <div class="fw-bold text-uppercase tracking-wider text-muted small">Pasien Selesai Rehab</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-5 bg-white">
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h2 class="fw-bold mb-1" id="section-news-title">Berita & Informasi Terbaru</h2>
                <p class="text-muted mb-0">Ikuti perkembangan terbaru mengenai program BNK dan edukasi anti-narkotika.</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/public/berita/index.php" class="btn btn-premium-outline btn-sm d-none d-md-block" id="news-btn-all">Lihat Semua Berita</a>
        </div>
        
        <div class="row g-4">
            <?php 
            if (mysqli_num_rows($berita_terbaru) > 0):
                while ($row = mysqli_fetch_assoc($berita_terbaru)):
                    $thumbnail_url = !empty($row['thumbnail']) ? BASE_URL . '/assets/images/berita/' . $row['thumbnail'] : 'https://via.placeholder.com/600x400';
            ?>
            <div class="col-lg-4 col-md-6">
                <article class="premium-card h-100">
                    <div class="overflow-hidden" style="height: 200px;">
                        <img src="<?php echo $thumbnail_url; ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small me-3">Berita</span>
                            <small class="text-muted"><i class="far fa-calendar me-2"></i><?php echo format_date($row['created_at']); ?></small>
                        </div>
                        <h4 class="h5 fw-bold mb-3">
                            <a href="<?php echo BASE_URL; ?>/public/berita/detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark hover-primary"><?php echo htmlspecialchars($row['judul']); ?></a>
                        </h4>
                        <p class="text-muted small mb-0">
                            <?php echo htmlspecialchars(substr(strip_tags($row['isi']), 0, 120)) . '...'; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 px-4 pb-4 pt-0">
                        <a href="<?php echo BASE_URL; ?>/public/berita/detail.php?slug=<?php echo $row['slug']; ?>" class="text-primary text-decoration-none fw-bold small">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </article>
            </div>
            <?php 
                endwhile;
            else:
            ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada berita yang dipublikasikan.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="text-center mt-5 d-md-none">
            <a href="<?php echo BASE_URL; ?>/public/berita/index.php" class="btn btn-premium-outline w-100" id="news-btn-all-mobile">Lihat Semua Berita</a>
        </div>
    </div>
</section>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
