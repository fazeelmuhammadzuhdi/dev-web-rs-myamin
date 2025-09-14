<?php

namespace App\Models;

use CodeIgniter\Model;

class KeberatanInformasiPPID extends Model
{
    protected $table            = 'keberatan_informasi_ppid';
    protected $primaryKey       = 'idkeberataninformasippid';

    protected $allowedFields    = [
        'idkeberataninformasippid',
        'tanggal',
        'nama_pemohon_informasi',
        'alamat_pemohon',
        'nomor_telepon_pemohon',
        'informasi_dibutuhkan_pemohon',
        'alasan_pengajuan',
        'pekerjaan',
        'keterangan'
    ];
}
