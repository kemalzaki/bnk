<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

$id_kasus = $_GET['id'] ?? null;
if (!$id_kasus || !filter_var($id_kasus, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Kasus tidak valid.');
}

$conn = get_db_connection();
$kasus = get_kasus_by_id($conn, $id_kasus);
mysqli_close($conn);

if (!$kasus) {
    redirect_with_message('index.php', 'error', 'Data kasus tidak ditemukan.');
}

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Kasus</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_kasus" value="<?php echo $kasus['id_kasus']; ?>">
            
            <div class="mb-3">
                <label for="nomor_kasus" class="form-label">Nomor Kasus <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nomor_kasus" name="nomor_kasus" value="<?php echo htmlspecialchars($kasus['nomor_kasus']); ?>" placeholder="Format: NK-YYYY-XXXX" required>
                <div class="form-text">Format wajib: NK-YYYY-XXXX</div>
            </div>
            
            <div class="mb-3">
                <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian" value="<?php echo htmlspecialchars($kasus['tanggal_kejadian']); ?>" max="<?php echo date('Y-m-d'); ?>" required>
            </div>
            
            <div class="mb-4">
                <label for="status_hukum" class="form-label">Status Hukum <span class="text-danger">*</span></label>
                <select class="form-select" id="status_hukum" name="status_hukum" required>
                    <option value="penyidikan" <?php echo ($kasus['status_hukum'] == 'penyidikan') ? 'selected' : ''; ?>>Penyidikan</option>
                    <option value="selesai" <?php echo ($kasus['status_hukum'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Data Kasus</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
