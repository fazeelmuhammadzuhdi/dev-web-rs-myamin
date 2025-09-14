<?php

namespace App\Models;

use CodeIgniter\Model;

class Slider extends Model
{
    protected $table            = 'slider';
    protected $primaryKey       = 'idslider';

    protected $allowedFields    = [
        'idslider',
        'judul',
        'keterangan',
        'gambar',
        'thumbnail',
        'created_at',
    ];
}
