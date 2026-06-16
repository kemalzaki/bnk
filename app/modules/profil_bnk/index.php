<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$conn = get_db_connection();
$profil = get_profil_bnk($conn);
mysqli_close($conn);

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Profil BNK</h2>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_profil" value="<?php echo $profil['id_profil']; ?>">

            <ul class="nav nav-tabs mb-4" id="profilTab" role="tablist">
                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-sambutan" type="button">Sambutan Kepala</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-tupoksi" type="button">Tupoksi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kondisi" type="button">Kondisi Umum</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-renstra" type="button">Renstra</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-struktur" type="button">Struktur Organisasi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-visi" type="button">Visi & Misi</button></li>
                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-kontak" type="button">Kontak</button></li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-sambutan">
                    <div class="mb-3">
                        <label for="sambutan" class="form-label fw-bold">Sambutan Kepala BNK</label>
                        <textarea class="form-control" id="sambutan" name="sambutan" rows="6"><?php echo htmlspecialchars($profil['sambutan']); ?></textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-tupoksi">
                    <div class="mb-3">
                        <label for="tupoksi" class="form-label fw-bold">Tugas Pokok dan Fungsi (Tupoksi)</label>
                        <textarea class="form-control" id="tupoksi" name="tupoksi" rows="10"><?php echo htmlspecialchars($profil['tupoksi']); ?></textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-kondisi">
                    <div class="mb-3">
                        <label for="kondisi_umum" class="form-label fw-bold">Kondisi Umum</label>
                        <textarea class="form-control" id="kondisi_umum" name="kondisi_umum" rows="10"><?php echo htmlspecialchars($profil['kondisi_umum']); ?></textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-renstra">
                    <div class="mb-3">
                        <label for="renstra" class="form-label fw-bold">Rencana Strategis (Renstra)</label>
                        <textarea class="form-control" id="renstra" name="renstra" rows="10"><?php echo htmlspecialchars($profil['renstra']); ?></textarea>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab-struktur">
                    <div class="mb-3">
                        <label for="struktur_organisasi" class="form-label fw-bold">Struktur Organisasi</label>
                        <textarea class="form-control" id="struktur_organisasi" name="struktur_organisasi" rows="10"><?php echo htmlspecialchars($profil['struktur_organisasi']); ?></textarea>
                        <div class="form-text">Anda bisa menuliskan nama jabatan dan pejabat, satu per baris.</div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-visi">
                    <div class="mb-3">
                        <label for="visi" class="form-label fw-bold">Visi BNK</label>
                        <textarea class="form-control" id="visi" name="visi" rows="4"><?php echo htmlspecialchars($profil['visi']); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="misi" class="form-label fw-bold">Misi BNK</label>
                        <textarea class="form-control" id="misi" name="misi" rows="6"><?php echo htmlspecialchars($profil['misi']); ?></textarea>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-kontak">
                    <div class="mb-3">
                        <label for="kontak" class="form-label fw-bold">Informasi Kontak</label>
                        <textarea class="form-control" id="kontak" name="kontak" rows="6"><?php echo htmlspecialchars($profil['kontak']); ?></textarea>
                        <div class="form-text">Masukkan alamat kantor, nomor telepon, email, dan informasi kontak lainnya.</div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan Profil BNK</button>
            </div>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
