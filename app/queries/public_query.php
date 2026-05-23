<?php
// app/queries/public_query.php

function get_published_berita($conn, $limit = 0) {
    $query = "SELECT * FROM tb_berita WHERE status = 'publish' ORDER BY created_at DESC";
    if ($limit > 0) {
        $query .= " LIMIT $limit";
    }
    return mysqli_query($conn, $query);
}

function get_berita_by_slug($conn, $slug) {
    $query = "SELECT * FROM tb_berita WHERE slug = ? AND status = 'publish'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $slug);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function get_published_galeri($conn) {
    $query = "SELECT * FROM tb_galeri ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_published_produk_hukum($conn) {
    $query = "SELECT * FROM tb_produk_hukum ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_published_buku_tamu($conn) {
    $query = "SELECT * FROM tb_buku_tamu WHERE status = 'publish' ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function insert_buku_tamu($conn, $nama, $email, $pesan) {
    $query = "INSERT INTO tb_buku_tamu (nama, email, pesan, status) VALUES (?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function get_public_stats($conn) {
    $stats = [];
    
    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kasus");
    $stats['total_kasus'] = $q ? mysqli_fetch_assoc($q)['total'] : 0;

    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pasien_rehabilitasi WHERE status_rehabilitasi = 'aktif'");
    $stats['total_rehab_aktif'] = $q ? mysqli_fetch_assoc($q)['total'] : 0;

    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_pasien_rehabilitasi WHERE status_rehabilitasi = 'selesai'");
    $stats['total_rehab_selesai'] = $q ? mysqli_fetch_assoc($q)['total'] : 0;

    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kasus WHERE status_hukum = 'penyidikan'");
    $stats['kasus_penyidikan'] = $q ? mysqli_fetch_assoc($q)['total'] : 0;

    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM tb_kasus WHERE status_hukum = 'selesai'");
    $stats['kasus_selesai'] = $q ? mysqli_fetch_assoc($q)['total'] : 0;

    return $stats;
}
