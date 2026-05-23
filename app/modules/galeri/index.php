<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$conn = get_db_connection();
$result = get_all_galeri($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Galeri</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Foto Baru</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="row">
            <?php 
            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)): 
                    $gambar_url = BASE_URL . '/assets/images/galeri/' . $row['gambar'];
            ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <img src="<?php echo $gambar_url; ?>" class="card-img-top" alt="Galeri" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title"><?php echo htmlspecialchars($row['judul']); ?></h6>
                        <p class="card-text small text-muted"><i class="fas fa-folder"></i> <?php echo htmlspecialchars($row['kategori']); ?></p>
                    </div>
                    <div class="card-footer bg-white border-top-0 text-center">
                        <a href="delete.php?id=<?php echo $row['id_galeri']; ?>" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Hapus foto ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </div>
                </div>
            </div>
            <?php 
                endwhile; 
            else: 
            ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">Belum ada foto di galeri.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
mysqli_close($conn);
include APP_PATH . '/templates/footer.php';
?>
