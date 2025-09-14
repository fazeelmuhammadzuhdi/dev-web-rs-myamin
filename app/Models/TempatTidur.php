<?php

namespace App\Models;

use CodeIgniter\Model;

class TempatTidur extends Model
{
    protected $table            = 'tempat_tidur';
    protected $primaryKey       = 'idtempattidur';

    protected $allowedFields    = [
        'idtempattidur',
        'rawat_id',
        'vip_isi',
        'vip_kosong',
        'utama_isi',
        'utama_kosong',
        'kelas1_isi',
        'kelas1_kosong',
        'kelas2_isi',
        'kelas2_kosong',
        'kelas3_isi',
        'kelas3_kosong',
        'created_at',
        'updated_at',
    ];

    public function getTempatTidur()
    {
        return $this->db->table('tempat_tidur')
            ->join('rawat', 'rawat.idrawat = tempat_tidur.rawat_id')
            ->select('tempat_tidur.idtempattidur,rawat.nama,tempat_tidur.vip_isi,tempat_tidur.vip_kosong,tempat_tidur.utama_isi,tempat_tidur.utama_kosong,tempat_tidur.kelas1_isi,tempat_tidur.kelas1_kosong,tempat_tidur.kelas2_isi,tempat_tidur.kelas2_kosong,tempat_tidur.kelas3_isi,tempat_tidur.kelas3_kosong');
        // ->orderBy('tempat_tidur.idtempattidur', 'asc');
    }
}
