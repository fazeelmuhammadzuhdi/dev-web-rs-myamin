<?php

namespace App\Models;

use CodeIgniter\Model;

class FormulirPPID extends Model
{
    protected $table            = 'pesan_ppid';
    protected $primaryKey       = 'idpesanppid';

    protected $allowedFields    = [
        'idpesanppid',
        'tanggal',
        'nama_pemohon_informasi',
        // 'nomor_ktp_pemohon',
        'keterangan',
        'alamat_pemohon',
        'nomor_telepon_pemohon',
        'email_pemohon',
        'informasi_dibutuhkan_pemohon',
        'alasan_permintaan_pemohon',
        'cara_memperoleh_informasi',
        'format_bahan_informasi',
        'cara_mengirim_bahan_informasi',
        'keterangan',
        'ktp'
    ];
}
