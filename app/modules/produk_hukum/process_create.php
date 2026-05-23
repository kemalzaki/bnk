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
    redirect_with_message('create.php', 'error', 'Token keamanan tidak valid.');
}

$judul = sanitize_input($_POST['judul'] ?? '');
$kategori = sanitize_input($_POST['kategori'] ?? '');

if (!validate_required($judul) || !validate_required($kategori)) {
    redirect_with_message('create.php', 'error', 'Judul dan kategori wajib diisi.');
}

// Upload Dokumen
$file_dokumen = '';
if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $file_type = $_FILES['file_dokumen']['type'];
    $file_size = $_FILES['file_dokumen']['size'];
    $file_tmp = $_FILES['file_dokumen']['tmp_name'];
    
    // Fallback mime type check via extension
    $ext = strtolower(pathinfo($_FILES['file_dokumen']['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['pdf', 'doc', 'docx'];
    
    if (!in_array($file_type, $allowed_types) && !in_array($ext, $allowed_ext)) {
        redirect_with_message('create.php', 'error', 'Format dokumen tidak didukung. Gunakan PDF, DOC, atau DOCX.');
    }
    
    if ($file_size > 5 * 1024 * 1024) { // 5MB
        redirect_with_message('create.php', 'error', 'Ukuran file maksimal 5MB.');
    }
    
    $file_dokumen = 'dokumen_' . time() . '.' . $ext;
    $upload_path = PUBLIC_PATH . '/../assets/dokumen/' . $file_dokumen;
    
    if (!move_uploaded_file($file_tmp, $upload_path)) {
        redirect_with_message('create.php', 'error', 'Gagal mengupload dokumen.');
    }
} else {
    redirect_with_message('create.php', 'error', 'Dokumen wajib diupload.');
}

$conn = get_db_connection();
$success = insert_produk_hukum($conn, $judul, $file_dokumen, $kategori);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Produk hukum berhasil diupload.');
} else {
    redirect_with_message('create.php', 'error', 'Gagal menyimpan data produk hukum.');
}
