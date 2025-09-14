<?php

namespace App\Models;

use CodeIgniter\Model;

class IndikatorMutu extends Model
{
    protected $table            = 'indikator_mutu';
    protected $primaryKey       = 'idindikatormutu';

    protected $allowedFields    = [
        'idindikatormutu',
        'nama',
        'status',
        'created_at',
    ];

    // get all data indikator mutu
    public function getIndikatorMutu()
    {
        return $this->where('status', 'Y')->findAll();
    }
}
