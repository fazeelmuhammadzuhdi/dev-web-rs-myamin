<?php

namespace App\Models;

use CodeIgniter\Model;

class Poli extends Model
{
    protected $table            = 'poli';
    protected $primaryKey       = 'idpoli';

    protected $allowedFields    = [
        'idpoli',
        'nama',
        'keterangan',
        'status',
        'gambar',
        'slug',
        'created_at',
    ];
}
