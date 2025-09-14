<?php

namespace App\Models;

use CodeIgniter\Model;

class Pengaduan extends Model
{
    protected $table            = 'pengaduan';
    protected $primaryKey       = 'idpengaduan';

    protected $allowedFields    = [
        'idpengaduan',
        'nama',
        'alamat',
        'nomor_hp',
        'kronologi',
        'created_at'
    ];
}
