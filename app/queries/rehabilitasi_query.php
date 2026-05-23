<?php
// app/queries/rehabilitasi_query.php

// === PUSAT REHABILITASI ===

function get_all_pusat_rehabilitasi($conn) {
    $query = "SELECT * FROM tb_pusat_rehabilitasi ORDER BY nama_pusat ASC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function insert_pusat_rehabilitasi($conn, $nama_pusat, $alamat, $kapasitas) {
    $query = "INSERT INTO tb_pusat_rehabilitasi (nama_pusat, alamat, kapasitas) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $nama_pusat, $alamat, $kapasitas);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function get_pusat_rehabilitasi_by_id($conn, $id_pusat) {
    $query = "SELECT * FROM tb_pusat_rehabilitasi WHERE id_pusat = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pusat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

// === PASIEN REHABILITASI ===

function get_all_pasien_rehabilitasi($conn) {
    $query = "SELECT p.*, i.nama, c.nama_pusat 
              FROM tb_pasien_rehabilitasi p 
              JOIN tb_individu i ON p.nik = i.nik 
              JOIN tb_pusat_rehabilitasi c ON p.id_pusat = c.id_pusat 
              ORDER BY p.created_at DESC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function register_pasien_rehabilitasi($conn, $nik, $id_pusat, $status_rehabilitasi) {
    $query = "INSERT INTO tb_pasien_rehabilitasi (nik, id_pusat, status_rehabilitasi) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sis", $nik, $id_pusat, $status_rehabilitasi);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_status_rehabilitasi($conn, $id_rehabilitasi, $status_rehabilitasi) {
    $query = "UPDATE tb_pasien_rehabilitasi SET status_rehabilitasi = ? WHERE id_rehabilitasi = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status_rehabilitasi, $id_rehabilitasi);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function check_kapasitas($conn, $id_pusat) {
    // Menghitung jumlah pasien aktif di suatu pusat rehab
    $query = "SELECT COUNT(*) as total_aktif FROM tb_pasien_rehabilitasi WHERE id_pusat = ? AND status_rehabilitasi = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_pusat);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    $total_aktif = $data['total_aktif'];
    
    // Ambil kapasitas maksimal
    $pusat = get_pusat_rehabilitasi_by_id($conn, $id_pusat);
    
    if (!$pusat) return false;
    
    // Jika masih ada sisa kapasitas
    return $total_aktif < $pusat['kapasitas'];
}

function check_pasien_aktif($conn, $nik) {
    $query = "SELECT id_rehabilitasi FROM tb_pasien_rehabilitasi WHERE nik = ? AND status_rehabilitasi = 'aktif'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
