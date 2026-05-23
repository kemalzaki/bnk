<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$current_page = 'buku_tamu';
$page_title = 'Buku Tamu';
$page_desc = 'Kirimkan kritik, saran, maupun pertanyaan seputar Pencegahan dan Rehabilitasi kepada Badan Narkotika Kabupaten.';

$conn = get_db_connection();
$result = get_published_buku_tamu($conn);
mysqli_close($conn);

// Generate CSRF Token
$csrf_token = generate_csrf_token();

// Generate math CAPTCHA
$num1 = rand(1, 9);
$num2 = rand(1, 9);
$_SESSION['captcha_num1'] = $num1;
$_SESSION['captcha_num2'] = $num2;
$_SESSION['captcha_result'] = $num1 + $num2;

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Buku Tamu & Hubungi Kami</h1>
        <p class="text-muted lead mb-0">Sampaikan aspirasi, aduan, saran, maupun pertanyaan Anda secara resmi</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Guest Book Submission Form -->
        <div class="col-lg-5">
            <div class="premium-card p-4">
                <h5 class="fw-bold mb-4"><i class="far fa-edit text-primary me-2"></i>Kirim Pesan Baru</h5>
                
                <?php display_flash_message(); ?>
                
                <form action="process.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                    
                    <!-- Nama -->
                    <div class="mb-3">
                        <label for="nama" class="form-label small fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama Anda..." required>
                    </div>
                    
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold">Alamat Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nama@email.com" required>
                    </div>
                    
                    <!-- Pesan -->
                    <div class="mb-3">
                        <label for="pesan" class="form-label small fw-bold">Isi Pesan <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="pesan" name="pesan" rows="5" placeholder="Tuliskan saran atau aduan Anda..." required></textarea>
                    </div>
                    
                    <!-- Captcha -->
                    <div class="mb-4">
                        <label for="captcha" class="form-label small fw-bold">Keamanan (CAPTCHA) <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-secondary px-3 py-2 fs-6 me-3">Berapa hasil: <?php echo $num1; ?> + <?php echo $num2; ?>?</span>
                        </div>
                        <input type="number" class="form-control" id="captcha" name="captcha" placeholder="Jawaban angka..." required>
                        <div class="form-text">Verifikasi bahwa Anda adalah manusia.</div>
                    </div>
                    
                    <button type="submit" class="btn btn-premium-primary w-100" id="submit-guestbook-btn">Kirim Pesan</button>
                </form>
            </div>
        </div>

        <!-- Published Messages List -->
        <div class="col-lg-7">
            <div class="premium-card p-4" style="max-height: 800px; overflow-y: auto;">
                <h5 class="fw-bold mb-4"><i class="far fa-comments text-primary me-2"></i>Pesan Buku Tamu Terbaru</h5>
                
                <div class="d-flex flex-column gap-3">
                    <?php 
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                    <div class="bg-light p-4 rounded-4 border">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($row['nama']); ?></h6>
                                <span style="font-size: 0.75rem;" class="text-muted"><i class="far fa-envelope me-1"></i> <?php echo htmlspecialchars($row['email']); ?></span>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary small"><?php echo format_date($row['created_at']); ?></span>
                        </div>
                        <p class="mb-0 text-dark small" style="white-space: pre-wrap;"><?php echo htmlspecialchars($row['pesan']); ?></p>
                    </div>
                    <?php 
                        endwhile;
                    else: 
                    ?>
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-comment-dots fs-1 mb-3"></i>
                        <p class="mb-0">Belum ada pesan tamu yang dipublikasikan.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
