<?php
// app/queries/individu_query.php

function get_all_individu($conn) {
    $query = "SELECT * FROM tb_individu ORDER BY nama ASC";
    $result = mysqli_query($conn, $query);
    return $result;
}

function get_individu_by_nik($conn, $nik) {
    $query = "SELECT * FROM tb_individu WHERE nik = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_individu($conn, $nik, $nama, $tanggal_lahir, $alamat, $jenis_kelamin) {
    $query = "INSERT INTO tb_individu (nik, nama, tanggal_lahir, alamat, jenis_kelamin) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $nik, $nama, $tanggal_lahir, $alamat, $jenis_kelamin);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_individu($conn, $nik_lama, $nik_baru, $nama, $tanggal_lahir, $alamat, $jenis_kelamin) {
    // Karena NIK adalah Primary Key, jika NIK diubah, kita harus UPDATE NIK juga
    $query = "UPDATE tb_individu SET nik = ?, nama = ?, tanggal_lahir = ?, alamat = ?, jenis_kelamin = ? WHERE nik = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $nik_baru, $nama, $tanggal_lahir, $alamat, $jenis_kelamin, $nik_lama);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_individu($conn, $nik) {
    $query = "DELETE FROM tb_individu WHERE nik = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function check_nik_exist($conn, $nik) {
    $query = "SELECT nik FROM tb_individu WHERE nik = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $nik);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}
