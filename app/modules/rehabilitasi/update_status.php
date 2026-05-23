<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';

check_login();

$id_rehabilitasi = $_GET['id'] ?? null;
if (!$id_rehabilitasi || !filter_var($id_rehabilitasi, FILTER_VALIDATE_INT)) {
    redirect_with_message('index.php', 'error', 'ID Rehabilitasi tidak valid.');
}

$conn = get_db_connection();
// Fetch single data for this ID
$query = "SELECT p.*, i.nama, c.nama_pusat 
          FROM tb_pasien_rehabilitasi p 
          JOIN tb_individu i ON p.nik = i.nik 
          JOIN tb_pusat_rehabilitasi c ON p.id_pusat = c.id_pusat 
          WHERE p.id_rehabilitasi = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id_rehabilitasi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pasien = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($conn);

if (!$pasien) {
    redirect_with_message('index.php', 'error', 'Data pasien rehabilitasi tidak ditemukan.');
}

$csrf_token = generate_csrf_token();

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Update Status Pasien Rehabilitasi</h2>
    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<?php display_flash_message(); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="process_update_status.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
            <input type="hidden" name="id_rehabilitasi" value="<?php echo $pasien['id_rehabilitasi']; ?>">
            
            <div class="mb-3">
                <label class="form-label">Nama Pasien</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien['nama']) . ' (' . htmlspecialchars($pasien['nik']) . ')'; ?>" disabled>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Pusat Rehabilitasi</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($pasien['nama_pusat']); ?>" disabled>
            </div>

            <div class="mb-4">
                <label for="status_rehabilitasi" class="form-label">Status Rehabilitasi Saat Ini <span class="text-danger">*</span></label>
                <select class="form-select" id="status_rehabilitasi" name="status_rehabilitasi" required>
                    <option value="aktif" <?php echo ($pasien['status_rehabilitasi'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="selesai" <?php echo ($pasien['status_rehabilitasi'] == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                    <option value="drop-out" <?php echo ($pasien['status_rehabilitasi'] == 'drop-out') ? 'selected' : ''; ?>>Drop-Out</option>
                </select>
                <div class="form-text">Perhatian: Pasien hanya bisa mendaftar rehab kembali jika status terakhir bukan aktif.</div>
            </div>
            
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbarui Status</button>
        </form>
    </div>
</div>

<?php
include APP_PATH . '/templates/footer.php';
?>
