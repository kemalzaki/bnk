<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

check_login();

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Pusat Rehabilitasi</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_create_pusat.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-3">
                <label for="nama_pusat" class="form-label">Nama Pusat Rehabilitasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_pusat" name="nama_pusat" maxlength="100" required>
            </div>
            
            <div class="mb-3">
                <label for="kapasitas" class="form-label">Kapasitas (Orang) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="kapasitas" name="kapasitas" min="1" max="1000" required>
                <div class="form-text">Range kapasitas: 1 - 1000 orang.</div>
            </div>

            <div class="mb-4">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data Pusat Rehab</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
