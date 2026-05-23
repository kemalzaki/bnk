<?php
// app/queries/konten_query.php

// === BERITA ===
function get_all_berita($conn) {
    $query = "SELECT * FROM tb_berita ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_berita_by_id($conn, $id) {
    $query = "SELECT * FROM tb_berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_berita($conn, $judul, $slug, $thumbnail, $isi, $status) {
    $query = "INSERT INTO tb_berita (judul, slug, thumbnail, isi, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $judul, $slug, $thumbnail, $isi, $status);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function update_berita($conn, $id, $judul, $slug, $thumbnail, $isi, $status) {
    $query = "UPDATE tb_berita SET judul = ?, slug = ?, thumbnail = ?, isi = ?, status = ? WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $judul, $slug, $thumbnail, $isi, $status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_berita($conn, $id) {
    $query = "DELETE FROM tb_berita WHERE id_berita = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === GALERI ===
function get_all_galeri($conn) {
    $query = "SELECT * FROM tb_galeri ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function insert_galeri($conn, $judul, $gambar, $kategori) {
    $query = "INSERT INTO tb_galeri (judul, gambar, kategori) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $judul, $gambar, $kategori);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_galeri($conn, $id) {
    $query = "DELETE FROM tb_galeri WHERE id_galeri = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === PRODUK HUKUM ===
function get_all_produk_hukum($conn) {
    $query = "SELECT * FROM tb_produk_hukum ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function get_produk_hukum_by_id($conn, $id) {
    $query = "SELECT * FROM tb_produk_hukum WHERE id_produk_hukum = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $data;
}

function insert_produk_hukum($conn, $judul, $file_dokumen, $kategori) {
    $query = "INSERT INTO tb_produk_hukum (judul, file_dokumen, kategori) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $judul, $file_dokumen, $kategori);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_produk_hukum($conn, $id) {
    $query = "DELETE FROM tb_produk_hukum WHERE id_produk_hukum = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === BUKU TAMU ===
function get_all_buku_tamu($conn) {
    $query = "SELECT * FROM tb_buku_tamu ORDER BY created_at DESC";
    return mysqli_query($conn, $query);
}

function update_status_buku_tamu($conn, $id, $status) {
    $query = "UPDATE tb_buku_tamu SET status = ? WHERE id_buku_tamu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

function delete_buku_tamu($conn, $id) {
    $query = "DELETE FROM tb_buku_tamu WHERE id_buku_tamu = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}

// === PROFIL BNK ===
function get_profil_bnk($conn) {
    // Profil BNK biasanya hanya 1 baris (id=1)
    $query = "SELECT * FROM tb_profil_bnk LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    // Jika kosong, insert data kosong
    if (mysqli_num_rows($result) == 0) {
        mysqli_query($conn, "INSERT INTO tb_profil_bnk (visi, misi, sejarah, struktur_organisasi, kontak) VALUES ('-', '-', '-', '-', '-')");
        return get_profil_bnk($conn);
    }
    
    return mysqli_fetch_assoc($result);
}

function update_profil_bnk($conn, $id, $visi, $misi, $sejarah, $struktur, $kontak) {
    $query = "UPDATE tb_profil_bnk SET visi = ?, misi = ?, sejarah = ?, struktur_organisasi = ?, kontak = ? WHERE id_profil = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $visi, $misi, $sejarah, $struktur, $kontak, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $success;
}
