<?php

namespace App\Models;

use CodeIgniter\Model;

class Wbs extends Model
{
    protected $table            = 'wbs';
    protected $primaryKey       = 'id_wbs';

    protected $allowedFields    = [
        'id_wbs',
        'nama_pelapor',
        'telepon_pelapor',
        'email_pelapor',
        'tindakan',
        'nama_terlapor',
        'waktu_kejadian',
        'lokasi_kejadian',
        'kronologis',
    ];
}
