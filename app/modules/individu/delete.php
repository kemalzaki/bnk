<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/individu_query.php';

check_login();

$nik = $_GET['nik'] ?? null;

if (!$nik) {
    redirect_with_message('index.php', 'error', 'NIK Individu tidak valid.');
}

$conn = get_db_connection();
$success = delete_individu($conn, $nik);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Data individu berhasil dihapus.');
} else {
    redirect_with_message('index.php', 'error', 'Gagal menghapus data individu. Data ini mungkin masih berelasi dengan data rehabilitasi.');
}
