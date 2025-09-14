<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriInformasiPPID extends Model
{
    protected $table            = 'kategori_informasi_ppid';
    protected $primaryKey       = 'idkategori';

    protected $allowedFields    = [
        'idkategori',
        'title',
        'status',
        'slug',
        'created_at',
    ];
}
