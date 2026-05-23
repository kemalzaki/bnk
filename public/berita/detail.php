<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$conn = get_db_connection();
$berita = get_berita_by_slug($conn, $slug);

if (!$berita) {
    mysqli_close($conn);
    // Redirect to news index if slug not found
    header("Location: index.php");
    exit;
}

// Get recent news for sidebar
$recent_news = get_published_berita($conn, 4);
mysqli_close($conn);

$current_page = 'berita';
$page_title = $berita['judul'];
$page_desc = htmlspecialchars(substr(strip_tags($berita['isi']), 0, 150)) . '...';

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Meta -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <div class="d-flex justify-content-center align-items-center mb-3">
            <span class="badge bg-primary px-3 py-2 rounded-pill small me-3">Berita Resmi</span>
            <small class="text-white-50"><i class="far fa-calendar me-2"></i><?php echo format_date($berita['created_at']); ?></small>
        </div>
        <h1 class="fw-bold mb-0 mx-auto" style="max-width: 900px;"><?php echo htmlspecialchars($berita['judul']); ?></h1>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="premium-card p-4 p-md-5 mb-4">
                <!-- Thumbnail image -->
                <?php if (!empty($berita['thumbnail'])): ?>
                    <div class="rounded-4 overflow-hidden mb-4 shadow-sm" style="max-height: 450px;">
                        <img src="<?php echo BASE_URL . '/assets/images/berita/' . $berita['thumbnail']; ?>" class="w-100 object-fit-cover" alt="<?php echo htmlspecialchars($berita['judul']); ?>">
                    </div>
                <?php endif; ?>
                
                <!-- Article Content -->
                <div class="article-content text-dark" style="line-height: 1.8; font-size: 1.1rem; white-space: pre-wrap;">
                    <?php echo nl2br(htmlspecialchars($berita['isi'])); ?>
                </div>
                
                <hr class="my-5">
                
                <div class="d-flex justify-content-between align-items-center">
                    <a href="index.php" class="btn btn-premium-outline btn-sm"><i class="fas fa-arrow-left me-2"></i> Kembali ke Berita</a>
                    <div class="d-flex gap-2">
                        <span class="small text-muted me-2">Bagikan:</span>
                        <a href="#" class="text-muted hover-primary me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-muted hover-primary me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted hover-primary"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Recent News Widget -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Berita Terbaru Lainnya</h5>
                    <div class="d-flex flex-column gap-3">
                        <?php 
                        if (mysqli_num_rows($recent_news) > 0):
                            while ($row = mysqli_fetch_assoc($recent_news)):
                                if ($row['id_berita'] === $berita['id_berita']) continue;
                                $row_thumb = !empty($row['thumbnail']) ? BASE_URL . '/assets/images/berita/' . $row['thumbnail'] : 'https://via.placeholder.com/150';
                        ?>
                        <div class="d-flex align-items-start gap-3">
                            <img src="<?php echo $row_thumb; ?>" class="rounded object-fit-cover" style="width: 80px; height: 60px;" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.95rem; line-height: 1.3;">
                                    <a href="detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark hover-primary"><?php echo htmlspecialchars($row['judul']); ?></a>
                                </h6>
                                <small class="text-muted text-xs"><?php echo format_date($row['created_at']); ?></small>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <p class="text-muted small mb-0">Tidak ada berita terbaru lainnya.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Campaign Info widget -->
            <div class="card bg-dark text-white border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-user-shield mb-3 fs-1 text-white-50"></i>
                    <h5 class="fw-bold mb-2">Konsultasi Rehabilitasi</h5>
                    <p class="small text-white-50">Pusat layanan rehabilitasi BNK siap mendampingi pemulihan kerabat Anda secara sukarela tanpa hukuman pidana.</p>
                    <a href="<?php echo BASE_URL; ?>/public/buku_tamu/index.php" class="btn btn-premium-primary btn-sm mt-2">Daftar Konsultasi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
