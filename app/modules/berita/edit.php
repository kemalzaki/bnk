<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Berita tidak valid.');
}

$conn = get_db_connection();
$berita = get_berita_by_id($conn, $id);
mysqli_close($conn);

if (!$berita) {
    redirect_with_message('index.php', 'error', 'Data berita tidak ditemukan.');
}

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Berita</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_berita" value="<?php echo $berita['id_berita']; ?>">
            
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Berita <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo htmlspecialchars($berita['judul']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label d-block">Thumbnail Saat Ini</label>
                <?php if (!empty($berita['thumbnail'])): ?>
                    <img src="<?php echo BASE_URL . '/assets/images/berita/' . $berita['thumbnail']; ?>" alt="Thumbnail" class="img-thumbnail mb-2" style="max-width: 200px;">
                <?php else: ?>
                    <p class="text-muted">Tidak ada thumbnail.</p>
                <?php endif; ?>
                
                <label for="thumbnail" class="form-label d-block">Ganti Thumbnail (Opsional)</label>
                <input type="file" class="form-control" id="thumbnail" name="thumbnail" accept="image/jpeg, image/png, image/jpg">
                <div class="form-text">Biarkan kosong jika tidak ingin mengganti. Format didukung: JPG, JPEG, PNG. Maksimal 2MB.</div>
            </div>

            <div class="mb-3">
                <label for="isi" class="form-label">Isi Berita <span class="text-danger">*</span></label>
                <textarea class="form-control" id="isi" name="isi" rows="10" required><?php echo htmlspecialchars($berita['isi']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft" <?php echo ($berita['status'] == 'draft') ? 'selected' : ''; ?>>Draft (Simpan sementara)</option>
                    <option value="publish" <?php echo ($berita['status'] == 'publish') ? 'selected' : ''; ?>>Publish (Tampilkan ke Publik)</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
