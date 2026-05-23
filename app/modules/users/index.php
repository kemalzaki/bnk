<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/user_query.php';

check_login();
check_role(['admin']);

$conn = get_db_connection();
$result = get_all_users($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Pengguna</h2>
    <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Pengguna</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th width="12%">Role</th>
                        <th width="15%">Dibuat</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                            $role_badge = ($row['role'] == 'admin') ? 'bg-danger' : 'bg-info';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><span class="badge <?php echo $role_badge; ?>"><?php echo ucfirst($row['role']); ?></span></td>
                        <td><?php echo format_date($row['created_at']); ?></td>
                        <td class="text-center">
                            <a href="edit.php?id=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-edit"></i></a>
                            <?php if ($row['id_user'] != $_SESSION['user_id']): ?>
                            <a href="delete.php?id=<?php echo $row['id_user']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?');"><i class="fas fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" class="text-center">Belum ada data pengguna.</td></tr>
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
