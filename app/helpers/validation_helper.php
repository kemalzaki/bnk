<?php
// app/helpers/validation_helper.php

function validate_required($data) {
    return !empty(trim($data));
}

function validate_nik($nik) {
    if (!preg_match('/^[0-9]{16}$/', $nik)) {
        return false;
    }
    return true;
}

function validate_nomor_kasus($nomor_kasus) {
    // Format: NK-YYYY-XXXX (contoh: NK-2024-0001)
    if (!preg_match('/^NK-\d{4}-\d{4}$/', $nomor_kasus)) {
        return false;
    }
    return true;
}

function validate_enum($value, $allowed_values) {
    return in_array($value, $allowed_values);
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validate_kapasitas($kapasitas) {
    $kapasitas = filter_var($kapasitas, FILTER_VALIDATE_INT);
    if ($kapasitas === false || $kapasitas < 1 || $kapasitas > 1000) {
        return false;
    }
    return true;
}

function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
