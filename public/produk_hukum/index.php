<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$current_page = 'produk_hukum';
$page_title = 'Produk Hukum';
$page_desc = 'Cari dan unduh dokumen peraturan perundang-undangan, undang-undang narkotika, peraturan presiden, dan peraturan BNN.';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : '';

$conn = get_db_connection();

// Build Query
$query = "SELECT * FROM tb_produk_hukum WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND judul LIKE ?";
    $search_param = "%$search%";
    $params[] = $search_param;
    $types .= "s";
}

if (!empty($kategori)) {
    $query .= " AND kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}

$query .= " ORDER BY created_at DESC";

$stmt = mysqli_prepare($conn, $query);
if (count($params) > 0) {
    if (count($params) === 2) {
        mysqli_stmt_bind_param($stmt, $types, $params[0], $params[1]);
    } else {
        mysqli_stmt_bind_param($stmt, $types, $params[0]);
    }
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
mysqli_stmt_close($stmt);

mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Produk Hukum & Regulasi</h1>
        <p class="text-muted lead mb-0">Database produk hukum resmi seputar penanganan dan pencegahan penyalahgunaan narkotika</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Search & Filter Area -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Filter Dokumen</h5>
                    <form action="index.php" method="GET">
                        <!-- Search input -->
                        <div class="mb-3">
                            <label for="search" class="form-label small fw-bold">Kata Kunci</label>
                            <input type="text" class="form-control" id="search-doc-input" name="search" placeholder="Cari judul..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        
                        <!-- Kategori filter -->
                        <div class="mb-4">
                            <label for="kategori" class="form-label small fw-bold">Kategori Regulasi</label>
                            <select class="form-select" id="filter-kategori-select" name="kategori">
                                <option value="">Semua Kategori</option>
                                <option value="Undang-Undang" <?php echo ($kategori == 'Undang-Undang') ? 'selected' : ''; ?>>Undang-Undang</option>
                                <option value="Peraturan Pemerintah" <?php echo ($kategori == 'Peraturan Pemerintah') ? 'selected' : ''; ?>>Peraturan Pemerintah</option>
                                <option value="Peraturan Presiden" <?php echo ($kategori == 'Peraturan Presiden') ? 'selected' : ''; ?>>Peraturan Presiden</option>
                                <option value="Peraturan BNN" <?php echo ($kategori == 'Peraturan BNN') ? 'selected' : ''; ?>>Peraturan BNN</option>
                                <option value="Surat Edaran" <?php echo ($kategori == 'Surat Edaran') ? 'selected' : ''; ?>>Surat Edaran</option>
                                <option value="Lainnya" <?php echo ($kategori == 'Lainnya') ? 'selected' : ''; ?>>Lainnya</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-premium-primary w-100" id="filter-doc-btn">Terapkan Filter</button>
                        <?php if (!empty($search) || !empty($kategori)): ?>
                            <a href="index.php" class="btn btn-light w-100 mt-2 text-danger small fw-bold">Reset</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Documents Table Area -->
        <div class="col-lg-9">
            <div class="premium-card p-4">
                <h5 class="fw-bold mb-4">Daftar Dokumen Regulasi</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="8%">No</th>
                                <th>Judul Produk Hukum</th>
                                <th width="20%">Kategori</th>
                                <th width="20%" class="text-center">Aksi</th>
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
                                <td>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['judul']); ?></div>
                                    <small class="text-muted"><i class="far fa-calendar me-1"></i> Diunggah: <?php echo format_date($row['created_at']); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-2.5 py-1.5 rounded"><?php echo htmlspecialchars($row['kategori']); ?></span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="detail.php?id=<?php echo $row['id_produk_hukum']; ?>" class="btn btn-sm btn-outline-primary" title="Lihat Detail"><i class="fas fa-eye"></i> Detail</a>
                                        <a href="download.php?id=<?php echo $row['id_produk_hukum']; ?>" class="btn btn-sm btn-premium-primary" title="Download File"><i class="fas fa-download"></i> Unduh</a>
                                    </div>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else: 
                            ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Belum ada dokumen produk hukum yang memenuhi kriteria pencarian.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
