<?php
// app/queries/dashboard_query.php

function get_dashboard_stats($conn) {
    $stats = [
        'total_kasus' => 0,
        'total_rehab' => 0,
        'total_berita' => 0,
        'total_tamu' => 0
    ];

    // Total Kasus
    $q_kasus = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kasus");
    if ($q_kasus) $stats['total_kasus'] = mysqli_fetch_assoc($q_kasus)['total'];

    // Total Pasien Rehab Aktif
    $q_rehab = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pasien_rehabilitasi WHERE status_rehabilitasi = 'aktif'");
    if ($q_rehab) $stats['total_rehab'] = mysqli_fetch_assoc($q_rehab)['total'];

    // Total Berita Publikasi
    $q_berita = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_berita WHERE status = 'publish'");
    if ($q_berita) $stats['total_berita'] = mysqli_fetch_assoc($q_berita)['total'];

    // Total Buku Tamu Pending
    $q_tamu = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_buku_tamu WHERE status = 'pending'");
    if ($q_tamu) $stats['total_tamu'] = mysqli_fetch_assoc($q_tamu)['total'];

    return $stats;
}
