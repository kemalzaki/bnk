<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

$current_page = 'profil';
$page_title = 'Profil BNK';
$page_desc = 'Kenali lebih dekat Badan Narkotika Kabupaten (BNK): Sejarah, Visi & Misi, Struktur Organisasi, dan Layanan kami.';

$conn = get_db_connection();
$profil = get_profil_bnk($conn);
mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Profil Badan Narkotika Kabupaten</h1>
        <p class="text-muted lead mb-0">Mengenal Visi, Misi, Sejarah, dan Struktur Organisasi BNK</p>
    </div>
</section>

<!-- Content Section -->
<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Navigation (Nav tabs vertically on desktop) -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px; z-index: 10;">
                <div class="card-body p-3">
                    <ul class="nav flex-column nav-pills gap-2" id="v-pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start active py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-sambutan" type="button"><i class="fas fa-bullhorn me-2 text-primary"></i> Sambutan Kepala</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-tupoksi" type="button"><i class="fas fa-list-check me-2 text-primary"></i> Tupoksi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-kondisi" type="button"><i class="fas fa-file-alt me-2 text-primary"></i> Kondisi Umum</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-renstra" type="button"><i class="fas fa-chart-line me-2 text-primary"></i> Rencana Strategis</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-struktur" type="button"><i class="fas fa-sitemap me-2 text-primary"></i> Struktur Organisasi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start py-2.5 px-3 border-0" data-bs-toggle="pill" data-bs-target="#v-pills-visi" type="button"><i class="fas fa-eye me-2 text-primary"></i> Visi & Misi</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="col-lg-9">
            <div class="tab-content" id="v-pills-tabContent">
                
                <!-- Sambutan Kepala -->
                <div class="tab-pane fade show active" id="v-pills-sambutan">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Sambutan Kepala BNK</h3>
                        <div class="row g-4 align-items-center">
                            <div class="col-md-4 text-center">
                                <div class="bg-light p-3 rounded-circle shadow-sm d-inline-block">
                                    <i class="fas fa-user-tie text-muted" style="font-size: 100px;"></i>
                                </div>
                                <h5 class="fw-bold mt-3 mb-1">Drs. Kemal, M.Si</h5>
                                <p class="text-muted small">Kepala Badan Narkotika Kabupaten</p>
                            </div>
                            <div class="col-md-8">
                                <p class="fst-italic">"<?php echo nl2br(htmlspecialchars($profil['sambutan'])); ?>"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tupoksi -->
                <div class="tab-pane fade" id="v-pills-tupoksi">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Tugas Pokok & Fungsi (Tupoksi)</h3>
                        <p class="text-dark" style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($profil['tupoksi']); ?></p>
                    </div>
                </div>

                <!-- Kondisi Umum -->
                <div class="tab-pane fade" id="v-pills-kondisi">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Kondisi Umum BNK</h3>
                        <p class="text-dark" style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($profil['kondisi_umum']); ?></p>
                    </div>
                </div>

                <!-- Renstra -->
                <div class="tab-pane fade" id="v-pills-renstra">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Rencana Strategis (Renstra)</h3>
                        <p class="text-dark" style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($profil['renstra']); ?></p>
                    </div>
                </div>

                <!-- Struktur Organisasi -->
                <div class="tab-pane fade" id="v-pills-struktur">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Struktur Organisasi</h3>
                        <div class="p-4 bg-light rounded border border-dashed text-center">
                            <i class="fas fa-sitemap text-primary mb-3 fs-1"></i>
                            <h5 class="fw-bold mb-3">Bagan Kepengurusan BNK</h5>
                            <div class="text-start mx-auto d-inline-block">
                                <p class="text-dark" style="white-space: pre-wrap; font-family: monospace; font-size: 1rem;"><?php echo htmlspecialchars($profil['struktur_organisasi']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visi & Misi -->
                <div class="tab-pane fade" id="v-pills-visi">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Visi & Misi BNK</h3>
                        <div class="mb-5">
                            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-award me-2"></i>Visi</h5>
                            <div class="p-3 bg-light rounded border-start border-primary border-4">
                                <p class="mb-0 fw-medium lead text-dark"><?php echo nl2br(htmlspecialchars($profil['visi'])); ?></p>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold text-primary mb-3"><i class="fas fa-list-check me-2"></i>Misi</h5>
                            <div class="p-3 bg-light rounded border-start border-teal border-4">
                                <p class="mb-0 text-dark" style="white-space: pre-wrap;"><?php echo htmlspecialchars($profil['misi']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Layanan BNK -->
                <div class="tab-pane fade" id="v-pills-layanan">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="fw-bold mb-4 border-bottom pb-2">Informasi Layanan</h3>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 bg-light border-0">
                                    <div class="card-body p-4">
                                        <i class="fas fa-hand-holding-medical text-primary fs-2 mb-3"></i>
                                        <h5 class="fw-bold">Layanan Rehabilitasi Pasien</h5>
                                        <p class="small text-muted mb-0">Pendaftaran rehabilitasi bagi pengguna narkoba secara sukarela dengan metode medis dan sosial di pusat rehabilitasi mitra kami.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 bg-light border-0">
                                    <div class="card-body p-4">
                                        <i class="fas fa-gavel text-primary fs-2 mb-3"></i>
                                        <h5 class="fw-bold">Konsultasi Hukum & Regulasi</h5>
                                        <p class="small text-muted mb-0">Akses produk hukum serta regulasi seputar peredaran narkotika untuk mengedukasi instansi, sekolah, dan masyarakat luas.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 bg-light border-0">
                                    <div class="card-body p-4">
                                        <i class="fas fa-school text-primary fs-2 mb-3"></i>
                                        <h5 class="fw-bold">Pencegahan & Sosialisasi</h5>
                                        <p class="small text-muted mb-0">Permohonan penyuluhan anti-narkotika untuk lembaga sekolah, karang taruna, maupun korporasi swasta.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 bg-light border-0">
                                    <div class="card-body p-4">
                                        <i class="fas fa-file-circle-check text-primary fs-2 mb-3"></i>
                                        <h5 class="fw-bold">Surat Keterangan Bebas Narkoba (SKBN)</h5>
                                        <p class="small text-muted mb-0">Fasilitasi tes urine resmi dan penerbitan SKBN untuk keperluan pendaftaran sekolah, pekerjaan, maupun CPNS.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
