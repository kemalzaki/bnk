<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

check_login();
check_role(['admin']);

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Tambah Foto Galeri</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_create.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Foto</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            
            <div class="mb-3">
                <label for="kategori" class="form-label">Kategori</label>
                <select class="form-select" id="kategori" name="kategori" required>
                    <option value="Kegiatan BNK">Kegiatan BNK</option>
                    <option value="Sosialisasi">Sosialisasi</option>
                    <option value="Penghargaan">Penghargaan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label for="gambar" class="form-label">Upload Foto <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg, image/png, image/jpg" required>
                <div class="form-text">Format didukung: JPG, JPEG, PNG. Maksimal 2MB.</div>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan ke Galeri</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
