<?php
// app/templates/public_footer.php
?>
    <footer class="public-footer mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white fw-bold"><i class="fas fa-shield-halved text-primary me-2"></i>SI-BNK</h5>
                    <p class="small text-muted">Portal Informasi Resmi Badan Narkotika Kabupaten (BNK). Berkomitmen dalam pengelolaan data kasus narkotik, monitoring status hukum, rehabilitasi pasien, dan penyebaran informasi hukum terkait narkotika.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Tautan Pintas</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>/public/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Beranda</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/profil/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Profil</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/berita/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Berita</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/galeri/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Galeri</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Layanan Publik</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo BASE_URL; ?>/public/produk_hukum/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Produk Hukum</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/buku_tamu/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Buku Tamu</a></li>
                        <li><a href="<?php echo BASE_URL; ?>/public/kontak/index.php"><i class="fas fa-angle-right me-2 text-primary"></i>Hubungi Kami</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Kontak Kantor</h5>
                    <p class="small text-muted mb-2"><i class="fas fa-map-marker-alt text-primary me-2"></i>Jl. Letnan Jenderal S. Parman No.1, Jakarta Barat, Indonesia</p>
                    <p class="small text-muted mb-2"><i class="fas fa-phone text-primary me-2"></i>(021) 80880011</p>
                    <p class="small text-muted mb-2"><i class="fas fa-envelope text-primary me-2"></i>info@bnk.go.id</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="small text-muted mb-0">&copy; <?php echo date('Y'); ?> Badan Narkotika Kabupaten. Hak Cipta Dilindungi.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="text-muted small">Powered by Antigravity Procedural Engine</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
