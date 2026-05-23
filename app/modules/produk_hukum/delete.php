<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

$id = $_GET['id'] ?? null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Produk Hukum tidak valid.');
}

$conn = get_db_connection();

// Hapus file dokumen dulu
$query = "SELECT file_dokumen FROM tb_produk_hukum WHERE id_produk_hukum = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dokumen = mysqli_fetch_assoc($result);

if ($dokumen && !empty($dokumen['file_dokumen'])) {
    $file_path = PUBLIC_PATH . '/../assets/dokumen/' . $dokumen['file_dokumen'];
    if (file_exists($file_path)) {
        unlink($file_path);
    }
}

$success = delete_produk_hukum($conn, $id);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Dokumen produk hukum berhasil dihapus.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus dokumen.');
}
