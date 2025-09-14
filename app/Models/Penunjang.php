<?php

namespace App\Models;

use CodeIgniter\Model;

class Penunjang extends Model
{
    protected $table            = 'penunjang';
    protected $primaryKey       = 'idpenunjang';

    protected $allowedFields    = [
        'idpenunjang',
        'nama',
        'keterangan',
        'status',
        'created_at',
    ];
}
