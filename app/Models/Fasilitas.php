<?php

namespace App\Models;

use CodeIgniter\Model;

class Fasilitas extends Model
{
    protected $table            = 'fasilitas';
    protected $primaryKey       = 'idfasilitas';

    protected $allowedFields    = [
        'idfasilitas',
        'nama',
        'keterangan',
        'status',
        'slug',
        'created_at',
    ];
}
