<?php

namespace App\Models;

use CodeIgniter\Model;

class Tentang extends Model
{
    protected $table            = 'tentang';
    protected $primaryKey       = 'id';

    protected $allowedFields    = [
        'id',
        'title_tentang',
        'konten_tentang',
        'title_sejarah',
        'konten_sejarah',
        'ketersediaan_tempat_tidur'
    ];
}
