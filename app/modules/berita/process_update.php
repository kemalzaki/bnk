<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/konten_query.php';

check_login();
check_role(['admin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$id_berita = $_POST['id_berita'] ?? null;
if (!$id_berita || !filter_var($id_berita, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Berita tidak valid.');
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!validate_csrf_token($csrf_token)) {
    redirect_with_message("edit.php?id=$id_berita", 'error', 'Token keamanan tidak valid.');
}

$judul = sanitize_input($_POST['judul'] ?? '');
$isi = $_POST['isi'] ?? ''; // tags are allowed
$status = sanitize_input($_POST['status'] ?? '');

if (!validate_required($judul) || !validate_required($isi)) {
    redirect_with_message("edit.php?id=$id_berita", 'error', 'Judul dan isi wajib diisi.');
}

if (!validate_enum($status, ['draft', 'publish'])) {
    redirect_with_message("edit.php?id=$id_berita", 'error', 'Status tidak valid.');
}

$conn = get_db_connection();

// Ambil data berita lama
$berita = get_berita_by_id($conn, $id_berita);
if (!$berita) {
    mysqli_close($conn);
    redirect_with_message('index.php', 'error', 'Berita tidak ditemukan.');
}

// Generate Slug baru berdasarkan judul baru
$slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
$slug = $slug . '-' . time();

$thumbnail = $berita['thumbnail'];

// Upload Image jika ada file baru yang diunggah
if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
    $file_type = $_FILES['thumbnail']['type'];
    $file_size = $_FILES['thumbnail']['size'];
    $file_tmp = $_FILES['thumbnail']['tmp_name'];
    
    if (!in_array($file_type, $allowed_types)) {
        mysqli_close($conn);
        redirect_with_message("edit.php?id=$id_berita", 'error', 'Format gambar tidak didukung. Gunakan JPG atau PNG.');
    }
    
    if ($file_size > 2 * 1024 * 1024) { // 2MB
        mysqli_close($conn);
        redirect_with_message("edit.php?id=$id_berita", 'error', 'Ukuran gambar maksimal 2MB.');
    }
    
    $ext = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
    $new_thumbnail = 'berita_' . time() . '.' . $ext;
    $upload_path = PUBLIC_PATH . '/../assets/images/berita/' . $new_thumbnail;
    
    if (move_uploaded_file($file_tmp, $upload_path)) {
        // Hapus thumbnail lama jika ada
        if (!empty($thumbnail)) {
            $old_file_path = PUBLIC_PATH . '/../assets/images/berita/' . $thumbnail;
            if (file_exists($old_file_path)) {
                unlink($old_file_path);
            }
        }
        $thumbnail = $new_thumbnail;
    } else {
        mysqli_close($conn);
        redirect_with_message("edit.php?id=$id_berita", 'error', 'Gagal mengupload gambar baru.');
    }
}

$success = update_berita($conn, $id_berita, $judul, $slug, $thumbnail, $isi, $status);
mysqli_close($conn);

if ($success) {
    redirect_with_message('index.php', 'success', 'Berita berhasil diperbarui.');
} else {
    redirect_with_message("edit.php?id=$id_berita", 'error', 'Gagal memperbarui berita.');
}
