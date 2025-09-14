<?php

namespace App\Models;

use CodeIgniter\Model;

class Manajemen extends Model
{
    protected $table            = 'manajemen';
    protected $primaryKey       = 'idmanajemen';

    protected $allowedFields    = [
        'idmanajemen',
        'nama',
        'status',
        'created_at',
    ];
}
