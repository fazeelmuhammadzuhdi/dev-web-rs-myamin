<?php

namespace App\Models;

use CodeIgniter\Model;

class Rawat extends Model
{
    protected $table            = 'rawat';
    protected $primaryKey       = 'idrawat';

    protected $allowedFields    = [
        'idrawat',
        'nama',
        'keterangan',
        'status',
        'slug',
        'created_at',
    ];
}
