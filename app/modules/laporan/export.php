<?php
require_once dirname(__DIR__, 2) . '/config/app.php';

check_login();

$type = $_GET['type'] ?? ''; // 'excel' or 'pdf'
$laporan = $_GET['laporan'] ?? ''; // 'kasus' or 'rehabilitasi'

if (!in_array($type, ['excel', 'pdf']) || !in_array($laporan, ['kasus', 'rehabilitasi'])) {
    die("Parameter tidak valid.");
}

$conn = get_db_connection();

if ($laporan === 'kasus') {
    $filter_status = $_GET['status'] ?? '';
    $filter_tahun = $_GET['tahun'] ?? '';

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
    if (!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($type === 'excel') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Laporan_Kasus_BNK_' . date('Ymd') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('No', 'Nomor Kasus', 'Tanggal Kejadian', 'Status Hukum'));
        
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, array(
                $no++, 
                $row['nomor_kasus'], 
                $row['tanggal_kejadian'], 
                ucfirst($row['status_hukum'])
            ));
        }
        fclose($output);
        exit;
    } else { // PDF / Print View
        $title = "Laporan Kasus Narkotika";
        $headers = ['No', 'Nomor Kasus', 'Tanggal Kejadian', 'Status Hukum'];
        $data = [];
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                $no++, 
                $row['nomor_kasus'], 
                format_date($row['tanggal_kejadian']), 
                ucfirst($row['status_hukum'])
            ];
        }
    }
} 
else if ($laporan === 'rehabilitasi') {
    $filter_status = $_GET['status'] ?? '';
    $filter_pusat = $_GET['pusat'] ?? '';

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
    if (!empty($params)) mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($type === 'excel') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Laporan_Rehabilitasi_BNK_' . date('Ymd') . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array('No', 'NIK', 'Nama Pasien', 'Pusat Rehabilitasi', 'Status', 'Tanggal Daftar'));
        
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, array(
                $no++, 
                "'" . $row['nik'], // prevent scientific notation in excel
                $row['nama'], 
                $row['nama_pusat'], 
                ucfirst($row['status_rehabilitasi']),
                $row['created_at']
            ));
        }
        fclose($output);
        exit;
    } else { // PDF / Print View
        $title = "Laporan Pasien Rehabilitasi";
        $headers = ['No', 'NIK', 'Nama Pasien', 'Pusat Rehabilitasi', 'Status', 'Tanggal Daftar'];
        $data = [];
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                $no++, 
                $row['nik'], 
                $row['nama'], 
                $row['nama_pusat'],
                ucfirst($row['status_rehabilitasi']),
                format_date($row['created_at'])
            ];
        }
    }
}

// Print HTML View for PDF Printing
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak <?php echo $title; ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Print / Save as PDF</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>

    <div class="header">
        <h1>BADAN NARKOTIKA KABUPATEN</h1>
        <p><?php echo $title; ?></p>
        <p>Tanggal Cetak: <?php echo format_date(date('Y-m-d')); ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <?php foreach($headers as $h): ?>
                    <th><?php echo $h; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($data)): ?>
                <tr>
                    <td colspan="<?php echo count($headers); ?>" style="text-align: center;">Tidak ada data.</td>
                </tr>
            <?php else: ?>
                <?php foreach($data as $row): ?>
                    <tr>
                        <?php foreach($row as $col): ?>
                            <td><?php echo htmlspecialchars($col); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Admin BNK</strong></p>
    </div>
</body>
</html>
