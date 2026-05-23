<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

check_login();

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Kasus Baru</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_create.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-3">
                <label for="nomor_kasus" class="form-label">Nomor Kasus <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nomor_kasus" name="nomor_kasus" placeholder="Format: NK-YYYY-XXXX (contoh: NK-2024-0001)" required>
                <div class="form-text">Format wajib: NK-YYYY-XXXX</div>
            </div>
            
            <div class="mb-3">
                <label for="tanggal_kejadian" class="form-label">Tanggal Kejadian <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="tanggal_kejadian" name="tanggal_kejadian" max="<?php echo date('Y-m-d'); ?>" required>
                <div class="form-text">Tanggal kejadian tidak boleh lebih dari hari ini dan minimal tahun 2020.</div>
            </div>
            
            <div class="mb-4">
                <label for="status_hukum" class="form-label">Status Hukum <span class="text-danger">*</span></label>
                <select class="form-select" id="status_hukum" name="status_hukum" required>
                    <option value="" selected disabled>-- Pilih Status Hukum --</option>
                    <option value="penyidikan">Penyidikan</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Kasus</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
