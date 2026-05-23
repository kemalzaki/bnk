<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

$conn = get_db_connection();
$result = get_all_individu($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Individu</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Individu</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Umur</th>
                        <th>L/P</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $umur = calculate_age($row['tanggal_lahir']);
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nik']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td><?php echo $umur; ?> Tahun</td>
                        <td><?php echo ($row['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
                        <td class="text-center">
                            <a href="edit.php?nik=<?php echo urlencode($row['nik']); ?>" class="btn btn-sm btn-info text-white" title="Edit"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?nik=<?php echo urlencode($row['nik']); ?>" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus data individu ini?');"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data individu ditemukan.</td>
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
