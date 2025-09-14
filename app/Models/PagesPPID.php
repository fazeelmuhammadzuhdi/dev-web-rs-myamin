<?php

namespace App\Models;

use CodeIgniter\Model;

class PagesPPID extends Model
{
    protected $table            = 'pages';
    protected $primaryKey       = 'idpages';

    protected $allowedFields    = [
        'idpages',
        'title',
        'konten',
        'status',
        'slug',
        'gambar',
    ];
}
