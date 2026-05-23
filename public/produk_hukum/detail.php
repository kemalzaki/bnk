<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    header("Location: index.php");
    exit;
}

$conn = get_db_connection();
$dokumen = get_produk_hukum_by_id($conn, $id);
mysqli_close($conn);

if (!$dokumen) {
    header("Location: index.php");
    exit;
}

$current_page = 'produk_hukum';
$page_title = $dokumen['judul'];
$page_desc = 'Detail produk hukum: ' . htmlspecialchars($dokumen['judul']) . ' - Kategori: ' . htmlspecialchars($dokumen['kategori']);

$file_path = BASE_URL . '/assets/dokumen/' . $dokumen['file_dokumen'];
$file_ext = strtolower(pathinfo($dokumen['file_dokumen'], PATHINFO_EXTENSION));

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Detail Produk Hukum</h1>
        <p class="text-muted lead mb-0">Informasi lengkap dan salinan dokumen resmi regulasi</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Metadata Info Card -->
        <div class="col-lg-4">
            <div class="premium-card p-4 mb-4">
                <h5 class="fw-bold mb-3 border-bottom pb-2">Informasi Dokumen</h5>
                <div class="mb-3">
                    <span class="text-muted small d-block">Judul Dokumen</span>
                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($dokumen['judul']); ?></span>
                </div>
                <div class="mb-3">
                    <span class="text-muted small d-block">Kategori Regulasi</span>
                    <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fs-7 mt-1"><?php echo htmlspecialchars($dokumen['kategori']); ?></span>
                </div>
                <div class="mb-4">
                    <span class="text-muted small d-block">Tanggal Diunggah</span>
                    <span class="text-dark"><i class="far fa-calendar-alt me-2 text-primary"></i><?php echo format_date($dokumen['created_at']); ?></span>
                </div>
                
                <div class="d-flex flex-column gap-2">
                    <a href="download.php?id=<?php echo $dokumen['id_produk_hukum']; ?>" class="btn btn-premium-primary w-100"><i class="fas fa-download me-2"></i> Unduh Salinan Dokumen</a>
                    <a href="index.php" class="btn btn-premium-outline w-100"><i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar</a>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-2"><i class="fas fa-circle-info text-primary me-2"></i>Catatan Informasi</h6>
                    <p class="small text-muted mb-0">Dokumen yang dipublikasikan merupakan dokumen salinan resmi regulasi BNK maupun Undang-Undang Republik Indonesia untuk kepentingan transparansi informasi publik.</p>
                </div>
            </div>
        </div>

        <!-- Document Preview Area -->
        <div class="col-lg-8">
            <div class="premium-card p-4 h-100 d-flex flex-column" style="min-height: 600px;">
                <h5 class="fw-bold mb-4"><i class="far fa-file-pdf text-danger me-2"></i>Pratinjau Dokumen</h5>
                
                <?php if ($file_ext === 'pdf'): ?>
                    <div class="flex-grow-1 rounded border overflow-hidden" style="min-height: 500px;">
                        <iframe src="<?php echo $file_path; ?>" width="100%" height="100%" style="border: none;"></iframe>
                    </div>
                <?php else: ?>
                    <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center bg-light rounded border border-dashed py-5">
                        <i class="far fa-file-word text-primary mb-3" style="font-size: 5rem;"></i>
                        <p class="text-muted mb-3">Dokumen berformat <strong>.<?php echo $file_ext; ?></strong> tidak mendukung pratinjau langsung.</p>
                        <a href="download.php?id=<?php echo $dokumen['id_produk_hukum']; ?>" class="btn btn-premium-primary px-4"><i class="fas fa-download me-2"></i> Unduh untuk Membuka</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
