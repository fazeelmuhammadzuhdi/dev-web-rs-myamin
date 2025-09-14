<?php

namespace App\Models;

use CodeIgniter\Model;

class Referensi extends Model
{
    protected $table            = 'referensi';
    protected $primaryKey       = 'idreferensi';

    protected $allowedFields    = [
        'idreferensi',
        'judul',
        'pengarang',
        'bahasa',
        'kategori',
        'penerbit',
        'tahun',
        'deskripsi',
        'gambar',
        'link',
        'created_at',
    ];

    public function getDataReferensi()
    {
        return $this->select('idreferensi,judul,pengarang,penerbit,link,gambar,');
    }
}
