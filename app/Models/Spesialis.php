<?php

namespace App\Models;

use CodeIgniter\Model;

class Spesialis extends Model
{
    protected $table            = 'spesialis';
    protected $primaryKey       = 'idspesialis';

    protected $allowedFields    = [
        'idspesialis',
        'gelar',
        'nama',
        'status',
        'created_at',
        'updated_at',
    ];
}
