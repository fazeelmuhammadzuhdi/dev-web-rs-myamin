<?php

namespace App\Models;

use CodeIgniter\Model;

class Sejarah extends Model
{
    protected $table            = 'sejarah';
    protected $primaryKey       = 'idsejarah';

    protected $allowedFields    = [
        'idsejarah',
        'tahun',
        'keterangan',
        'judul',
    ];
}
