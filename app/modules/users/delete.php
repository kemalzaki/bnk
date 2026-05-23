<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/user_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID User tidak valid.');
}

// Cegah admin menghapus dirinya sendiri
if ($id == $_SESSION['user_id']) {
    redirect_with_message('index.php', 'error', 'Anda tidak dapat menghapus akun Anda sendiri.');
}

$conn = get_db_connection();
$success = delete_user($conn, $id);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Pengguna berhasil dihapus.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus pengguna.');
}
