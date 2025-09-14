<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenProfil extends Model
{
    protected $table            = 'manajemen_profil';
    protected $primaryKey       = 'idmanajemenprofil';

    protected $allowedFields    = [
        'idmanajemenprofil',
        'manajemen_id',
        'nama',
        'jabatan',
        'nip',
        'tempatlahir',
        'tanggallahir',
        'pendidikan',
        'pangkat',
        'gambar',
        'profil_singkat',
        'riwayat_pendidikan',
        'created_at',

    ];

    public function getManajemenProfil()
    {
        return $this->db->table('manajemen_profil')
            ->join('manajemen', 'manajemen.idmanajemen = manajemen_profil.manajemen_id')
            ->select('manajemen_profil.idmanajemenprofil,manajemen_profil.nama as nama_manajemen,manajemen_profil.jabatan,manajemen_profil.pendidikan,manajemen_profil.pangkat')
            ->orderBy('manajemen_profil.manajemen_id', 'asc');
    }


    public function getProfilManajemen()
    {
        return $this->db->table('manajemen_profil')
            ->join('manajemen', 'manajemen.idmanajemen = manajemen_profil.manajemen_id')
            ->select('manajemen_profil.*,manajemen.nama as nama_manajemen,manajemen.status,manajemen.idmanajemen')
            ->where('manajemen.status', 'Y')
            ->orderBy('manajemen_profil.manajemen_id', 'asc')
            ->get()->getResultArray();
    }
}
