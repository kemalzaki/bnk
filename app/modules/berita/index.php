<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']); // Hanya admin yang boleh mengelola konten website

$conn = get_db_connection();
$result = get_all_berita($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Berita</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tulis Berita Baru</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Thumbnail</th>
                        <th>Judul Berita</th>
                        <th width="15%">Tanggal Dibuat</th>
                        <th width="10%">Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $badge_class = ($row['status'] == 'publish') ? 'bg-success' : 'bg-warning text-dark';
                            $thumbnail = !empty($row['thumbnail']) ? BASE_URL . '/assets/images/berita/' . $row['thumbnail'] : 'https://via.placeholder.com/150';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><img src="<?php echo $thumbnail; ?>" alt="Thumbnail" class="img-thumbnail" style="max-width: 100px;"></td>
                        <td><strong><?php echo htmlspecialchars($row['judul']); ?></strong></td>
                        <td><?php echo format_date($row['created_at']); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?php echo $row['id_berita']; ?>" class="btn btn-sm btn-warning me-1" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?id=<?php echo $row['id_berita']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus berita ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data berita.</td>
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
