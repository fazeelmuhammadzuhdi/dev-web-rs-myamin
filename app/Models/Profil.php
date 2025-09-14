<?php

namespace App\Models;

use CodeIgniter\Model;

class Profil extends Model
{
    protected $table            = 'profil';
    protected $primaryKey       = 'idprofil';

    protected $allowedFields    = [
        'idprofil',
        'nama',
        'visi',
        'misi',
        'motto',
        'alamat',
        'telepon',
        'fax',
        'email',
        'gambar',
        'tugas',
        'created_at'
    ];
}
