<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$current_page = 'berita';
$page_title = 'Berita & Artikel';
$page_desc = 'Ikuti berita terkini, edukasi bahaya narkoba, dan artikel penanganan kasus narkotika dari Badan Narkotika Kabupaten.';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : 'Semua';

$conn = get_db_connection();

// Pagination Config
$limit = 6;
$page = isset($_GET['page']) && filter_var($_GET['page'], FILTER_VALIDATE_INT) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Base query count & fetch
$count_query = "SELECT COUNT(*) as total FROM tb_berita WHERE status = 'publish'";
$fetch_query = "SELECT * FROM tb_berita WHERE status = 'publish'";
$types = "";
$params = [];

if (!empty($search)) {
    $count_query .= " AND (judul LIKE ? OR isi LIKE ?)";
    $fetch_query .= " AND (judul LIKE ? OR isi LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "ss";
}

$fetch_query .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

// Count total
$stmt_count = mysqli_prepare($conn, $count_query);
if (!empty($search)) {
    mysqli_stmt_bind_param($stmt_count, "ss", $search_param, $search_param);
}
mysqli_stmt_execute($stmt_count);
$count_res = mysqli_stmt_get_result($stmt_count);
$total_rows = mysqli_fetch_assoc($count_res)['total'];
mysqli_stmt_close($stmt_count);

$total_pages = ceil($total_rows / $limit);

// Fetch data
$stmt_fetch = mysqli_prepare($conn, $fetch_query);
if (count($params) === 4) {
    mysqli_stmt_bind_param($stmt_fetch, $types, $params[0], $params[1], $params[2], $params[3]);
} else {
    mysqli_stmt_bind_param($stmt_fetch, $types, $params[0], $params[1]);
}
mysqli_stmt_execute($stmt_fetch);
$result = mysqli_stmt_get_result($stmt_fetch);
mysqli_stmt_close($stmt_fetch);

mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Berita & Informasi Terbaru</h1>
        <p class="text-muted lead mb-0">Portal edukasi anti-narkotika dan rilis pers resmi BNK</p>
    </div>
</section>

<div class="container py-5">
    <div class="row g-4">
        <!-- Main News Column -->
        <div class="col-lg-8">
            <!-- Search & Filter Bar -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <form action="index.php" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" class="form-control bg-light border-0 shadow-none" id="search-news-input" name="search" placeholder="Cari berita atau artikel..." value="<?php echo htmlspecialchars($search); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-premium-primary w-100" id="search-news-btn">Cari Berita</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($search)): ?>
                <div class="alert alert-light border shadow-sm mb-4 d-flex justify-content-between align-items-center">
                    <span>Menampilkan hasil pencarian untuk: <strong>"<?php echo htmlspecialchars($search); ?>"</strong> (<?php echo $total_rows; ?> ditemukan)</span>
                    <a href="index.php" class="text-decoration-none small text-danger fw-bold"><i class="fas fa-times"></i> Bersihkan</a>
                </div>
            <?php endif; ?>

            <!-- News Grid -->
            <div class="row g-4">
                <?php 
                if (mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                        $thumbnail_url = !empty($row['thumbnail']) ? BASE_URL . '/assets/images/berita/' . $row['thumbnail'] : 'https://via.placeholder.com/600x400';
                ?>
                <div class="col-md-6">
                    <article class="premium-card h-100">
                        <div class="overflow-hidden" style="height: 180px;">
                            <img src="<?php echo $thumbnail_url; ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($row['judul']); ?>">
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill small me-3">Berita</span>
                                <small class="text-muted"><i class="far fa-calendar me-2"></i><?php echo format_date($row['created_at']); ?></small>
                            </div>
                            <h4 class="h6 fw-bold mb-3" style="min-height: 48px; line-height: 1.4;">
                                <a href="detail.php?slug=<?php echo $row['slug']; ?>" class="text-decoration-none text-dark hover-primary"><?php echo htmlspecialchars($row['judul']); ?></a>
                            </h4>
                            <p class="text-muted small mb-0">
                                <?php echo htmlspecialchars(substr(strip_tags($row['isi']), 0, 100)) . '...'; ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 px-4 pb-4 pt-0">
                            <a href="detail.php?slug=<?php echo $row['slug']; ?>" class="text-primary text-decoration-none fw-bold small">Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </article>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-light p-5 rounded-4 border border-dashed">
                        <i class="far fa-newspaper text-muted mb-3" style="font-size: 4rem;"></i>
                        <p class="text-muted mb-0">Tidak ada berita yang cocok dengan pencarian Anda.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav class="mt-5">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link shadow-none" href="index.php?search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>"><i class="fas fa-chevron-left"></i></a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo ($page === $i) ? 'active' : ''; ?>">
                                <a class="page-link shadow-none" href="index.php?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            <a class="page-link shadow-none" href="index.php?search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>"><i class="fas fa-chevron-right"></i></a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>

        <!-- Sidebar Columns -->
        <div class="col-lg-4">
            <!-- Info Widget -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Kategori Edukasi</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <a href="#" class="text-decoration-none text-dark small"><i class="fas fa-angle-right me-2 text-primary"></i>Pencegahan Dini</a>
                            <span class="badge bg-light text-muted rounded-pill">12</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <a href="#" class="text-decoration-none text-dark small"><i class="fas fa-angle-right me-2 text-primary"></i>Rilis Pers & Kasus</a>
                            <span class="badge bg-light text-muted rounded-pill">8</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            <a href="#" class="text-decoration-none text-dark small"><i class="fas fa-angle-right me-2 text-primary"></i>Info Rehabilitasi</a>
                            <span class="badge bg-light text-muted rounded-pill">5</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Campaign Banner widget -->
            <div class="card bg-primary text-white border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-ban-smoking mb-3 fs-1 text-white-50"></i>
                    <h4 class="fw-bold mb-2">Perangi Narkoba!</h4>
                    <p class="small text-white-50">Sayangi diri dan keluarga Anda. Laporkan aktivitas peredaran gelap narkotika di sekitar Anda segera.</p>
                    <a href="<?php echo BASE_URL; ?>/public/kontak/index.php" class="btn btn-light btn-sm fw-bold text-primary mt-2">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
