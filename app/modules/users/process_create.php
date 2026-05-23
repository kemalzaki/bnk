<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/user_query.php';

check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php"); exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('create.php', 'error', 'Token keamanan tidak valid.');
}

$nama_lengkap = sanitize_input($_POST['nama_lengkap'] ?? '');
$username = sanitize_input($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = sanitize_input($_POST['role'] ?? '');

if (!validate_required($nama_lengkap) || !validate_required($username) || !validate_required($password)) {
    redirect_with_message('create.php', 'error', 'Semua field wajib diisi.');
}

if (strlen($password) < 6) {
    redirect_with_message('create.php', 'error', 'Password minimal 6 karakter.');
}

if (!validate_enum($role, ['admin', 'petugas'])) {
    redirect_with_message('create.php', 'error', 'Role tidak valid.');
}

$conn = get_db_connection();

if (check_username_exist($conn, $username)) {
    mysqli_close($conn);
    redirect_with_message('create.php', 'error', 'Username sudah digunakan.');
}

$success = insert_user($conn, $username, $password, $nama_lengkap, $role);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Pengguna berhasil ditambahkan.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menambahkan pengguna.');
}
