<?php

namespace App\Models;

use CodeIgniter\Model;

class Video extends Model
{
    protected $table            = 'video';
    protected $primaryKey       = 'idvideo';

    protected $allowedFields    = [
        'idvideo',
        'tanggal',
        'judul',
        'slug',
        'link',
        'thumbnail',
        'status',
        'created_at',
    ];
}
