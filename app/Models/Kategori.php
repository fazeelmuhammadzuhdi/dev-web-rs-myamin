<?php

namespace App\Models;

use CodeIgniter\Model;

class Kategori extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'idkategori';

    protected $allowedFields    = [
        'idkategori',
        'title',
        'status',
        'slug',
        'created_at',
    ];
}
