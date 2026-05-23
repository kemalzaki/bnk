<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$conn = get_db_connection();
$result = get_all_produk_hukum($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Produk Hukum</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Upload Produk Hukum</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul Dokumen</th>
                        <th width="20%">Kategori</th>
                        <th width="15%">Tanggal Upload</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $file_url = BASE_URL . '/assets/dokumen/' . $row['file_dokumen'];
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['judul']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                        <td><?php echo format_date($row['created_at']); ?></td>
                        <td class="text-center">
                            <a href="<?php echo $file_url; ?>" target="_blank" class="btn btn-sm btn-info text-white" title="Lihat/Download"><i class="fas fa-download"></i></a>
                            <a href="delete.php?id=<?php echo $row['id_produk_hukum']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus dokumen ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada produk hukum.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
mysqli_close($conn);
include APP_PATH . '/templates/footer.php';
?>
