<?php

namespace App\Models;

use CodeIgniter\Model;

class Pesan extends Model
{
    protected $table            = 'pesan';
    protected $primaryKey       = 'idpesan';

    protected $allowedFields    = [
        'idpesan',
        'tanggal',
        'nama',
        'email',
        'judul',
        'pesan',
        'status',
        'status_baca',
        'respon',
        'admin',
    ];

    public function updateStatusBaca($id)
    {
        return $this->update($id, ['status_baca' => 'RD']);
    }
}
