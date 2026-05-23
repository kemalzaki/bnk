<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

$conn = get_db_connection();
$result = get_all_kasus($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Kasus</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kasus</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor Kasus</th>
                        <th>Tanggal Kejadian</th>
                        <th>Status Hukum</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $status_badge = ($row['status_hukum'] == 'selesai') ? 'bg-success' : 'bg-warning text-dark';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nomor_kasus']); ?></strong></td>
                        <td><?php echo format_date($row['tanggal_kejadian']); ?></td>
                        <td><span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst(htmlspecialchars($row['status_hukum'])); ?></span></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?php echo $row['id_kasus']; ?>" class="btn btn-sm btn-info text-white" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?id=<?php echo $row['id_kasus']; ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data kasus ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data kasus ditemukan.</td>
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
