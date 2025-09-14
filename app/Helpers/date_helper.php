<?php

if (!function_exists('format_indo')) {
    function format_indo($date)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $split = explode('-', $date);
        return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
    }
}

if (!function_exists('tanggal_indonesia')) {
    function tanggal_indonesia($tanggal)
    {
        $hari = [
            'Minggu',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu'
        ];
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $timestamp = strtotime($tanggal);
        $hariIndo = $hari[date('w', $timestamp)];
        $tgl = date('d', $timestamp);
        $bulanIndo = $bulan[(int)date('m', $timestamp)];
        $tahun = date('Y', $timestamp);

        return "$hariIndo, $tgl $bulanIndo $tahun";
    }
}
