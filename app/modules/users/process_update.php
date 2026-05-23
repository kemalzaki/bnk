<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/user_query.php';

check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
$id_user = $_POST['id_user'] ?? '';

if (!validate_csrf_token($csrf_token)) {
    redirect_with_message("edit.php?id=$id_user", 'error', 'Token keamanan tidak valid.');
}

if (!filter_var($id_user, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID User tidak valid.');
}

$nama_lengkap = sanitize_input($_POST['nama_lengkap'] ?? '');
$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = sanitize_input($_POST['role'] ?? '');

if (!validate_required($nama_lengkap) || !validate_required($username)) {
    redirect_with_message("edit.php?id=$id_user", 'error', 'Nama dan username wajib diisi.');
}

if (!validate_enum($role, ['admin', 'petugas'])) {
    redirect_with_message("edit.php?id=$id_user", 'error', 'Role tidak valid.');
}

$conn = get_db_connection();

if (check_username_exist($conn, $username, $id_user)) {
    mysqli_close($conn);
    redirect_with_message("edit.php?id=$id_user", 'error', 'Username sudah digunakan oleh pengguna lain.');
}

$success = update_user($conn, $id_user, $username, $nama_lengkap, $role);

// Update password jika diisi
if (!empty($password)) {
    if (strlen($password) < 6) {
        mysqli_close($conn);
        redirect_with_message("edit.php?id=$id_user", 'error', 'Password minimal 6 karakter.');
    }
    update_user_password($conn, $id_user, $password);
}

mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data pengguna berhasil diperbarui.');
} else {
    redirect_with_message("edit.php?id=$id_user", 'error', 'Gagal memperbarui data pengguna.');
}
