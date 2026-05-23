<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

$id_kasus = $_GET['id'] ?? null;

if (!$id_kasus || !filter_var($id_kasus, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Kasus tidak valid.');
}

$conn = get_db_connection();
$success = delete_kasus($conn, $id_kasus);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data kasus berhasil dihapus.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus data kasus.');
}
