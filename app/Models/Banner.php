<?php

namespace App\Models;

use CodeIgniter\Model;

class Banner extends Model
{
    protected $table            = 'banner';
    protected $primaryKey       = 'idbanner';

    protected $allowedFields    = [
        'idbanner',
        'judul',
        'link',
        'gambar',
        'status',
        'created_at',
    ];
}
