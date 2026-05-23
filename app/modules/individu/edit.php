<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

$nik = $_GET['nik'] ?? null;
if (!$nik) {
    redirect_with_message('index.php', 'error', 'NIK Individu tidak valid.');
}

$conn = get_db_connection();
$individu = get_individu_by_nik($conn, $nik);
mysqli_close($conn);

if (!$individu) {
    redirect_with_message('index.php', 'error', 'Data individu tidak ditemukan.');
}

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Individu</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="nik_lama" value="<?php echo htmlspecialchars($individu['nik']); ?>">
            
            <div class="mb-3">
                <label for="nik_baru" class="form-label">NIK <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nik_baru" name="nik_baru" value="<?php echo htmlspecialchars($individu['nik']); ?>" maxlength="16" placeholder="Masukkan 16 digit NIK" required>
                <div class="form-text">NIK harus berupa 16 digit angka unik. Jika Anda mengubah NIK ini, pastikan NIK baru belum digunakan.</div>
            </div>
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($individu['nama']); ?>" maxlength="100" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($individu['tanggal_lahir']); ?>" max="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                    <option value="L" <?php echo ($individu['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                    <option value="P" <?php echo ($individu['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($individu['alamat']); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data Individu</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
