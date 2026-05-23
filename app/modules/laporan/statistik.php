<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

check_login();

$conn = get_db_connection();

// --- 1. Statistik Kasus per Tahun ---
$query_kasus_tahun = "SELECT YEAR(tanggal_kejadian) as tahun, COUNT(id_kasus) as total 
                      FROM tb_kasus GROUP BY YEAR(tanggal_kejadian) ORDER BY tahun ASC";
$res_kasus_tahun = mysqli_query($conn, $query_kasus_tahun);

$label_tahun = [];
$data_kasus = [];
while ($row = mysqli_fetch_assoc($res_kasus_tahun)) {
    $label_tahun[] = $row['tahun'];
    $data_kasus[] = $row['total'];
}

// --- 2. Statistik Kasus Berdasarkan Status ---
$query_kasus_status = "SELECT status_hukum, COUNT(id_kasus) as total FROM tb_kasus GROUP BY status_hukum";
$res_kasus_status = mysqli_query($conn, $query_kasus_status);

$label_status_kasus = [];
$data_status_kasus = [];
while ($row = mysqli_fetch_assoc($res_kasus_status)) {
    $label_status_kasus[] = ucfirst($row['status_hukum']);
    $data_status_kasus[] = $row['total'];
}

// --- 3. Statistik Pasien Rehab Berdasarkan Status ---
$query_rehab_status = "SELECT status_rehabilitasi, COUNT(id_rehabilitasi) as total FROM tb_pasien_rehabilitasi GROUP BY status_rehabilitasi";
$res_rehab_status = mysqli_query($conn, $query_rehab_status);

$label_status_rehab = [];
$data_status_rehab = [];
while ($row = mysqli_fetch_assoc($res_rehab_status)) {
    $label_status_rehab[] = ucfirst($row['status_rehabilitasi']);
    $data_status_rehab[] = $row['total'];
}

mysqli_close($conn);

include APP_PATH . '/templates/header.php';
include APP_PATH . '/templates/sidebar.php';
include APP_PATH . '/templates/navbar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Statistik Umum</h2>
</div>

<div class="row">
    <!-- Chart 1: Trend Kasus per Tahun -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Trend Kasus per Tahun</h5>
            </div>
            <div class="card-body">
                <canvas id="chartKasusTahun" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart 2: Status Hukum Kasus -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Distribusi Status Hukum Kasus</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatusKasus"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Chart 3: Status Pasien Rehabilitasi -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Distribusi Status Pasien Rehabilitasi</h5>
            </div>
            <div class="card-body">
                <canvas id="chartStatusRehab"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Chart Kasus per Tahun (Line Chart)
    const ctxKasusTahun = document.getElementById('chartKasusTahun').getContext('2d');
    new Chart(ctxKasusTahun, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($label_tahun); ?>,
            datasets: [{
                label: 'Jumlah Kasus',
                data: <?php echo json_encode($data_kasus); ?>,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });

    // 2. Chart Status Kasus (Pie Chart)
    const ctxStatusKasus = document.getElementById('chartStatusKasus').getContext('2d');
    new Chart(ctxStatusKasus, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($label_status_kasus); ?>,
            datasets: [{
                data: <?php echo json_encode($data_status_kasus); ?>,
                backgroundColor: ['#ffc107', '#198754'] // warning, success
            }]
        },
        options: { responsive: true }
    });

    // 3. Chart Status Rehab (Pie Chart)
    const ctxStatusRehab = document.getElementById('chartStatusRehab').getContext('2d');
    new Chart(ctxStatusRehab, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($label_status_rehab); ?>,
            datasets: [{
                data: <?php echo json_encode($data_status_rehab); ?>,
                backgroundColor: ['#0d6efd', '#198754', '#dc3545'] // primary, success, danger
            }]
        },
        options: { responsive: true }
    });

});
</script>

<?php
include APP_PATH . '/templates/footer.php';
?>
