<?php
require_once dirname(dirname(__DIR__)) . '/app/config/app.php';
require_once APP_PATH . '/queries/public_query.php';

$current_page = 'galeri';
$page_title = 'Galeri Kegiatan';
$page_desc = 'Lihat galeri foto dokumentasi kegiatan sosialisasi, kampanye anti-narkoba, dan program rehabilitasi BNK.';

$conn = get_db_connection();
$result = get_published_galeri($conn);

// Collect categories for filter tabs
$categories = ['Semua'];
$photos = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $photos[] = $row;
        if (!in_array($row['kategori'], $categories) && !empty($row['kategori'])) {
            $categories[] = $row['kategori'];
        }
    }
}
mysqli_close($conn);

include APP_PATH . '/templates/public_header.php';
?>

<!-- Header Banner -->
<section class="py-5 bg-dark text-white text-center" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
    <div class="container py-3">
        <h1 class="fw-bold mb-2">Galeri Kegiatan BNK</h1>
        <p class="text-muted lead mb-0">Dokumentasi program kerja, sosialisasi, dan aksi Pencegahan Pemberantasan Penyalahgunaan dan Peredaran Gelap Narkotika (P4GN)</p>
    </div>
</section>

<div class="container py-5">
    <!-- Filter Tabs -->
    <div class="d-flex justify-content-center flex-wrap gap-2 mb-5">
        <?php foreach ($categories as $cat): ?>
            <button class="btn btn-sm btn-premium-outline <?php echo ($cat === 'Semua') ? 'active' : ''; ?> filter-btn" data-category="<?php echo htmlspecialchars($cat); ?>">
                <?php echo htmlspecialchars($cat); ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- Gallery Grid -->
    <div class="row g-4" id="gallery-grid">
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <?php $img_url = BASE_URL . '/assets/images/galeri/' . $photo['gambar']; ?>
                <div class="col-lg-3 col-md-4 col-sm-6 gallery-item" data-category="<?php echo htmlspecialchars($photo['kategori']); ?>">
                    <div class="premium-card h-100 overflow-hidden cursor-pointer" onclick="openLightbox('<?php echo $img_url; ?>', '<?php echo htmlspecialchars($photo['judul']); ?>')">
                        <div class="overflow-hidden position-relative" style="height: 200px;">
                            <img src="<?php echo $img_url; ?>" class="w-100 h-100 object-fit-cover" alt="<?php echo htmlspecialchars($photo['judul']); ?>">
                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-20 d-flex align-items-center justify-content-center opacity-0 hover-opacity-100 transition-smooth" style="transition: all 0.3s;">
                                <i class="fas fa-search-plus text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-dark mb-1 text-truncate"><?php echo htmlspecialchars($photo['judul']); ?></h6>
                            <span class="badge bg-secondary-subtle text-secondary px-2.5 py-1 rounded small"><?php echo htmlspecialchars($photo['kategori']); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="bg-light p-5 rounded-4 border border-dashed">
                    <i class="far fa-images text-muted mb-3" style="font-size: 4rem;"></i>
                    <p class="text-muted mb-0">Belum ada foto yang diunggah ke galeri.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Lightbox Modal -->
<div class="modal fade" id="lightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-none" data-bs-dismiss="modal" aria-label="Close" style="z-index: 999;"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="lightboxImage" src="" class="img-fluid rounded shadow-lg" style="max-height: 80vh;" alt="">
                <h5 id="lightboxTitle" class="text-white fw-bold mt-3 text-shadow"></h5>
            </div>
        </div>
    </div>
</div>

<script>
// Filter Functionality
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        // Toggle active class
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const cat = this.getAttribute('data-category');
        document.querySelectorAll('.gallery-item').forEach(item => {
            if (cat === 'Semua' || item.getAttribute('data-category') === cat) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Lightbox Open Function
function openLightbox(url, title) {
    document.getElementById('lightboxImage').src = url;
    document.getElementById('lightboxTitle').innerText = title;
    var myModal = new bootstrap.Modal(document.getElementById('lightboxModal'));
    myModal.show();
}
</script>

<style>
.cursor-pointer {
    cursor: pointer;
}
.hover-opacity-100:hover {
    opacity: 1 !important;
}
.text-shadow {
    text-shadow: 0 2px 4px rgba(0,0,0,0.8);
}
</style>

<?php
include APP_PATH . '/templates/public_footer.php';
?>
