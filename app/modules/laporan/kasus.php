<?php
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once APP_PATH . '/queries/kasus_query.php';

check_login();

$filter_status = $_GET['status'] ?? '';
$filter_tahun = $_GET['tahun'] ?? '';

$conn = get_db_connection();

// Custom Query for Report with Filters
$query = "SELECT * FROM tb_kasus WHERE 1=1";
$params = [];
$types = "";

if (!empty($filter_status)) {
    $query .= " AND status_hukum = ?";
    $params[] = $filter_status;
    $types .= "s";
}

if (!empty($filter_tahun)) {
    $query .= " AND YEAR(tanggal_kejadian) = ?";
    $params[] = $filter_tahun;
    $types .= "s";
}

$query .= " ORDER BY tanggal_kejadian DESC";

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
    <h2>Laporan Kasus Narkotika</h2>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form action="" method="GET" class="row g-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Status Hukum</label>
                <select id="status" name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="penyidikan" <?php echo ($filter_status == 'penyidikan') ? 'selected' : ''; ?>>Penyidikan</option>
                    <option value="selesai" <?php echo ($filter_status == 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="tahun" class="form-label">Tahun Kejadian</label>
                <select id="tahun" name="tahun" class="form-select">
                    <option value="">Semua Tahun</option>
                    <?php 
                    $current_year = date('Y');
                    for($y = $current_year; $y >= 2020; $y--) {
                        $selected = ($filter_tahun == $y) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2"><i class="fas fa-filter"></i> Filter</button>
                <a href="kasus.php" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Hasil Laporan</h5>
        <div>
            <!-- Using export.php helper -->
            <a href="export.php?type=excel&laporan=kasus&status=<?php echo urlencode($filter_status); ?>&tahun=<?php echo urlencode($filter_tahun); ?>" class="btn btn-sm btn-success"><i class="fas fa-file-excel"></i> Export Excel</a>
            <a href="export.php?type=pdf&laporan=kasus&status=<?php echo urlencode($filter_status); ?>&tahun=<?php echo urlencode($filter_tahun); ?>" target="_blank" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> Cetak / PDF</a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nomor Kasus</th>
                        <th>Tanggal Kejadian</th>
                        <th>Status Hukum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)): 
                            $status_badge = ($row['status_hukum'] == 'selesai') ? 'bg-success' : 'bg-warning text-dark';
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><strong><?php echo htmlspecialchars($row['nomor_kasus']); ?></strong></td>
                        <td><?php echo format_date($row['tanggal_kejadian']); ?></td>
                        <td><span class="badge <?php echo $status_badge; ?>"><?php echo ucfirst(htmlspecialchars($row['status_hukum'])); ?></span></td>
                    </tr>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data laporan ditemukan pada filter ini.</td>
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
