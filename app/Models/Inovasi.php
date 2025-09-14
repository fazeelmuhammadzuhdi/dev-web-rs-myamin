<?php

namespace App\Models;

use CodeIgniter\Model;

class Inovasi extends Model
{
    protected $table            = 'inovasi';
    protected $primaryKey       = 'idinovasi';

    protected $allowedFields    = [
        'idinovasi',
        'judul',
        'tahun',
        'jenis',
        'tujuan',
        'manfaat',
        'rancang',
        'tahapan',
        'digital',
        'inisiator',
        'hasil',
        'ujicoba',
        'implementasi',
        'urusan',
        'panduan_teknis',
        'link_youtube',
    ];
}
