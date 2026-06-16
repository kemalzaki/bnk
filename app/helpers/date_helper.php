<?php
// app/helpers/date_helper.php

function format_date($date, $format = 'd-m-Y') {
    if (empty($date)) return '-';
    $d = new DateTime($date);
    return $d->format($format);
}

function calculate_age($birth_date) {
    if (empty($birth_date)) return 0;
    $bday = new DateTime($birth_date);
    $today = new DateTime('today');
    $diff = $today->diff($bday);
    return $diff->y;
}

function get_age_category($age) {
    if ($age < 0) return 'Tidak Valid';
    if ($age <= 5) return 'Balita';
    if ($age <= 11) return 'Anak-anak';
    if ($age <= 18) return 'Remaja';
    if ($age <= 59) return 'Dewasa';
    return 'Lansia';
}

function validate_future_date($date) {
    $d = new DateTime($date);
    $today = new DateTime('today');
    return $d <= $today;
}

function validate_min_year($date, $min_year = 2020) {
    $d = new DateTime($date);
    return (int)$d->format('Y') >= $min_year;
}
