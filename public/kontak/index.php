<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

$current_page = 'kontak';
$page_title = 'Kontak Kami';
$page_desc = 'Hubungi kantor Badan Narkotika Kabupaten (BNK): Alamat kantor, nomor telepon, email resmi, dan peta lokasi.';

$conn = get_db_connection();
$profil = get_profil_bnk($conn);
mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Hubungi Kami</h1>
        <p class="text-muted lead mb-0">Pertanyaan dan layanan administrasi terpadu satu pintu</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Contact Information List -->
        <div class="col-lg-5">
            <div class="premium-card p-4 p-md-5 h-100">
                <h4 class="fw-bold mb-4 border-bottom pb-2">Informasi Kantor</h4>
                
                <div class="d-flex flex-column gap-4">
                    <!-- Alamat -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="fas fa-map-location-dot fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Alamat Utama</span>
                            <span class="fw-bold text-dark" style="white-space: pre-wrap;"><?php echo htmlspecialchars($profil['kontak']); ?></span>
                        </div>
                    </div>
                    
                    <!-- Telepon -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="fas fa-phone-volume fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Layanan Telepon</span>
                            <span class="fw-bold text-dark">(021) 80880011</span>
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="fas fa-envelope-open-text fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Surel Resmi</span>
                            <span class="fw-bold text-dark">info@bnk.go.id</span>
                        </div>
                    </div>

                    <!-- Jam Kerja -->
                    <div class="d-flex align-items-start gap-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="fas fa-clock-rotate-left fs-4"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Jam Pelayanan</span>
                            <span class="fw-bold text-dark">Senin - Jumat: 08.00 - 16.00 WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Interactive Map and Feedback Form Link -->
        <div class="col-lg-7">
            <div class="premium-card p-4 h-100 d-flex flex-column">
                <h4 class="fw-bold mb-4 border-bottom pb-2">Peta Lokasi Kantor</h4>
                
                <!-- Mock Map visual -->
                <div class="bg-light border rounded-4 flex-grow-1 mb-4 d-flex flex-column align-items-center justify-content-center p-5 text-center min-height-300">
                    <i class="fas fa-map-marked-alt text-primary mb-3" style="font-size: 4rem;"></i>
                    <h5 class="fw-bold text-dark">Badan Narkotika Kabupaten</h5>
                    <p class="small text-muted mb-3">Jl. Letnan Jenderal S. Parman No.1, Jakarta Barat, Indonesia</p>
                    <a href="https://maps.google.com" target="_blank" class="btn btn-premium-outline btn-sm"><i class="fas fa-location-arrow me-2"></i> Buka di Google Maps</a>
                </div>
                
                <div class="alert alert-info border-0 mb-0 d-flex align-items-center gap-3">
                    <i class="fas fa-lightbulb fs-4 text-primary"></i>
                    <div>
                        <span class="small d-block">Ingin mengirimkan pertanyaan atau aduan secara online?</span>
                        <a href="<?php echo BASE_URL; ?>/public/buku_tamu/index.php" class="fw-bold text-decoration-none">Gunakan halaman Buku Tamu <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
