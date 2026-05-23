<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

$conn = get_db_connection();
$individu = get_all_individu($conn);
$pusat_rehab = get_all_pusat_rehabilitasi($conn);
mysqli_close($conn);

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pendaftaran Pasien Rehabilitasi</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_register_pasien.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-3">
                <label for="nik" class="form-label">Pilih Individu <span class="text-danger">*</span></label>
                <select class="form-select" id="nik" name="nik" required>
                    <option value="" selected disabled>-- Pilih Individu --</option>
                    <?php if (mysqli_num_rows($individu) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($individu)): ?>
                            <option value="<?php echo htmlspecialchars($row['nik']); ?>">
                                <?php echo htmlspecialchars($row['nik']) . ' - ' . htmlspecialchars($row['nama']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <div class="form-text">Pilih data individu yang akan didaftarkan rehabilitasi. Jika belum ada, <a href="<?php echo BASE_URL; ?>/app/modules/individu/create.php">tambah data individu baru</a>.</div>
            </div>
            
            <div class="mb-3">
                <label for="id_pusat" class="form-label">Pilih Pusat Rehabilitasi <span class="text-danger">*</span></label>
                <select class="form-select" id="id_pusat" name="id_pusat" required>
                    <option value="" selected disabled>-- Pilih Pusat Rehabilitasi --</option>
                    <?php if (mysqli_num_rows($pusat_rehab) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($pusat_rehab)): ?>
                            <option value="<?php echo htmlspecialchars($row['id_pusat']); ?>">
                                <?php echo htmlspecialchars($row['nama_pusat']) . ' (Kapasitas Total: ' . $row['kapasitas'] . ')'; ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="status_rehabilitasi" class="form-label">Status Awal <span class="text-danger">*</span></label>
                <select class="form-select" id="status_rehabilitasi" name="status_rehabilitasi" required>
                    <option value="aktif" selected>Aktif</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Daftarkan Pasien</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
