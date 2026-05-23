<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';

check_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
$id_rehabilitasi = $_POST['id_rehabilitasi'] ?? '';

if (!validate_csrf_token($csrf_token)) {
    redirect_with_message("update_status.php?id=$id_rehabilitasi", 'error', 'Token keamanan tidak valid.');
}

if (!filter_var($id_rehabilitasi, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Rehabilitasi tidak valid.');
}

$status_rehabilitasi = sanitize_input($_POST['status_rehabilitasi'] ?? '');

// Validasi
if (!validate_required($status_rehabilitasi)) {
    redirect_with_message("update_status.php?id=$id_rehabilitasi", 'error', 'Status rehabilitasi wajib dipilih.');
}

if (!validate_enum($status_rehabilitasi, ['aktif', 'selesai', 'drop-out'])) {
    redirect_with_message("update_status.php?id=$id_rehabilitasi", 'error', 'Status rehabilitasi tidak valid.');
}

$conn = get_db_connection();
$success = update_status_rehabilitasi($conn, $id_rehabilitasi, $status_rehabilitasi);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Status pasien rehabilitasi berhasil diperbarui.');
} else {
    redirect_with_message("update_status.php?id=$id_rehabilitasi", 'error', 'Gagal memperbarui status pasien.');
}
