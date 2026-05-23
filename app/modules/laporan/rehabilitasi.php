<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/rehabilitasi_query.php';

check_login();

$filter_status = $_GET['status'] ?? '';
$filter_pusat = $_GET['pusat'] ?? '';

$conn = get_db_connection();
$list_pusat = get_all_pusat_rehabilitasi($conn);

// Custom Query for Report with Filters
$query = "SELECT p.*, i.nama, i.nik, c.nama_pusat 
          FROM tb_pasien_rehabilitasi p 
          JOIN tb_individu i ON p.nik = i.nik 
          JOIN tb_pusat_rehabilitasi c ON p.id_pusat = c.id_pusat 
          WHERE 1=1";
$params = [];
$types = "";

if (!empty($filter_status)) {
    $query .= " AND p.status_rehabilitasi = ?";
    $params[] = $filter_status;
    $types .= "s";
}

if (!empty($filter_pusat)) {
    $query .= " AND p.id_pusat = ?";
    $params[] = $filter_pusat;
    $types .= "i";
}

$query .= " ORDER BY p.created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Laporan Pasien Rehabilitasi</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status Rehabilitasi</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo ($filter_status == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="selesai" <?php echo ($filter_status == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                    <option value="drop-out" <?php echo ($filter_status == 'drop-out') ? 'selected' : ''; ?>>Drop-Out</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="pusat" class="form-label">Pusat Rehabilitasi</label>
                <select id="pusat" name="pusat" class="form-select">
                    <option value="">Semua Pusat</option>
                    <?php 
                    if (mysqli_num_rows($list_pusat) > 0) {
                        mysqli_data_seek($list_pusat, 0);
                        while ($row = mysqli_fetch_assoc($list_pusat)) {
                            $selected = ($filter_pusat == $row['id_pusat']) ? 'selected' : '';
                            echo "<option value='{$row['id_pusat']}' $selected>{$row['nama_pusat']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter</button>
                <a href="rehabilitasi.php" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Hasil Laporan</h5>
        <div>
            <a href="export.php?type=excel&laporan=rehabilitasi&status=<?php echo urlencode($filter_status); ?>&pusat=<?php echo urlencode($filter_pusat); ?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
            <a href="export.php?type=pdf&laporan=rehabilitasi&status=<?php echo urlencode($filter_status); ?>&pusat=<?php echo urlencode($filter_pusat); ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Cetak / PDF</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pasien (NIK)</th>
                        <th>Pusat Rehabilitasi</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong> <br><small><?php echo htmlspecialchars($row['nik']); ?></small></td>
                        <td><?php echo htmlspecialchars($row['nama_pusat']); ?></td>
                        <td><?php echo ucfirst($row['status_rehabilitasi']); ?></td>
                        <td><?php echo format_date($row['created_at']); ?></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data laporan ditemukan pada filter ini.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
include APP_PATH . '/templates/footer.php';
?>
