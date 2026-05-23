<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/user_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;
if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID User tidak valid.');
}

$conn = get_db_connection();
$user = get_user_by_id($conn, $id);
mysqli_close($conn);

if (!$user) {
    redirect_with_message('index.php', 'error', 'Data pengguna tidak ditemukan.');
}

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Pengguna</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">

            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password Baru</label>
                <input type="password" class="form-control" id="password" name="password" minlength="6">
                <div class="form-text">Kosongkan jika tidak ingin mengubah password. Minimal 6 karakter.</div>
            </div>

            <div class="mb-4">
                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                <select class="form-select" id="role" name="role" required>
                    <option value="petugas" <?php echo ($user['role'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Pengguna</button>
        </form>
    </div>
</div>

<?php include APP_PATH . '/templates/footer.php'; ?>
