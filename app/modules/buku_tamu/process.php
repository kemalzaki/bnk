<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message('index.php', 'error', 'Token keamanan tidak valid.');
}

$id = $_POST['id_buku_tamu'] ?? '';
$action = $_POST['action'] ?? '';

if (!filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Buku Tamu tidak valid.');
}

$conn = get_db_connection();

if ($action === 'publish') {
    $success = update_status_buku_tamu($conn, $id, 'publish');
    $msg = $success ? 'Pesan berhasil dipublikasikan ke halaman publik.' : 'Gagal mempublikasikan pesan.';
    $type = $success ? 'success' : 'error';
} elseif ($action === 'pending') {
    $success = update_status_buku_tamu($conn, $id, 'pending');
    $msg = $success ? 'Pesan berhasil disembunyikan dari halaman publik.' : 'Gagal menyembunyikan pesan.';
    $type = $success ? 'success' : 'error';
} elseif ($action === 'delete') {
    $success = delete_buku_tamu($conn, $id);
    $msg = $success ? 'Pesan berhasil dihapus.' : 'Gagal menghapus pesan.';
    $type = $success ? 'success' : 'error';
} else {
    $msg = 'Aksi tidak valid.';
    $type = 'error';
}

mysqli_close($conn);
redirect_with_message('index.php', $type, $msg);
