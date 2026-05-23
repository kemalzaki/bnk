<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id || !filter_var($id, FILTER_VALIDATE_INT)) {
    die("ID Dokumen tidak valid.");
}

$conn = get_db_connection();
$dokumen = get_produk_hukum_by_id($conn, $id);
mysqli_close($conn);

if (!$dokumen) {
    die("Dokumen tidak ditemukan.");
}

$filename = basename($dokumen['file_dokumen']); // Ensure no directory traversal
$file_path = PUBLIC_PATH . '/../assets/dokumen/' . $filename;

if (file_exists($file_path)) {
    // Tentukan mime type
    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mime_type = 'application/octet-stream';
    
    if ($file_ext === 'pdf') {
        $mime_type = 'application/pdf';
    } elseif ($file_ext === 'doc') {
        $mime_type = 'application/msword';
    } elseif ($file_ext === 'docx') {
        $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    }
    
    // Set headers untuk force download
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $mime_type);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    
    // Bersihkan output buffer
    ob_clean();
    flush();
    
    // Kirim file ke client
    readfile($file_path);
    exit;
} else {
    die("File fisik tidak ditemukan di server.");
}
