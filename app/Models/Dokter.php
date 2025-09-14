<?php

namespace App\Models;

use CodeIgniter\Model;

class Dokter extends Model
{
    protected $table            = 'dokter';
    protected $primaryKey       = 'iddokter';

    protected $allowedFields    = [
        'iddokter',
        'nip',
        'nama',
        'spesialis_id',
        'keterangan',
        'gambar',
        'thumbnail',
        'status',
        'created_at',
        'updated_at',
    ];

    public function getDokter()
    {
        return $this->db->table('dokter')
            ->join('spesialis', 'dokter.spesialis_id = spesialis.idspesialis')
            ->select('dokter.iddokter, dokter.nip, dokter.nama as nama_dokter, spesialis.nama,dokter.status,dokter.gambar')
            // ->where('dokter.status', 'Y')
            ->orderBy('spesialis.nama', 'asc')
            ->orderBy('dokter.nama', 'asc');
    }

    public function getDokterWithSpesialis()
    {
        return $this->db->table('dokter')
            ->join('spesialis', 'dokter.spesialis_id = spesialis.idspesialis')
            ->select('dokter.iddokter, dokter.nip, dokter.nama, spesialis.nama as nama_spesialis, dokter.gambar')
            ->where('dokter.status', 'Y');
    }
}
