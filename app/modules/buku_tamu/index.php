<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$conn = get_db_connection();
$result = get_all_buku_tamu($conn);

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Buku Tamu</h2>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Nama Pengirim</th>
                        <th width="35%">Pesan</th>
                        <th width="15%">Tanggal Masuk</th>
                        <th width="10%">Status</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $badge_class = ($row['status'] == 'publish') ? 'bg-success' : 'bg-warning text-dark';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama']); ?></strong>
                            <br><small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                        </td>
                        <td><?php echo nl2br(htmlspecialchars($row['pesan'])); ?></td>
                        <td><?php echo format_date($row['created_at']); ?></td>
                        <td><span class="badge <?php echo $badge_class; ?>"><?php echo ucfirst($row['status']); ?></span></td>
                        <td class="text-center">
                            <form action="process.php" method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="id_buku_tamu" value="<?php echo $row['id_buku_tamu']; ?>">
                                <?php if ($row['status'] == 'pending'): ?>
                                    <input type="hidden" name="action" value="publish">
                                    <button type="submit" class="btn btn-sm btn-success" title="Publish ke Publik"><i class="fas fa-check"></i></button>
                                <?php else: ?>
                                    <input type="hidden" name="action" value="pending">
                                    <button type="submit" class="btn btn-sm btn-warning text-dark" title="Sembunyikan dari Publik"><i class="fas fa-eye-slash"></i></button>
                                <?php endif; ?>
                            </form>
                            
                            <form action="process.php" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pesan ini?');">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="id_buku_tamu" value="<?php echo $row['id_buku_tamu']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Belum ada pesan buku tamu.</td>
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
