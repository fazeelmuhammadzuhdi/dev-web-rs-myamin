<?php

namespace App\Models;

use CodeIgniter\Model;

class IndikatorMutuList extends Model
{
    protected $table            = 'indikator_mutu_list';
    protected $primaryKey       = 'idindikatormutulist';

    protected $allowedFields    = [
        'idindikatormutulist',
        'indikator_mutu_id',
        'keterangan',
        'status',
        'gambar',
        'created_at',
    ];

    public function getIndikatorMutuList()
    {
        return $this->db->table('indikator_mutu_list')
            ->join('indikator_mutu', 'indikator_mutu.idindikatormutu = indikator_mutu_list.indikator_mutu_id')
            ->select('indikator_mutu_list.idindikatormutulist,indikator_mutu.nama,indikator_mutu_list.keterangan,indikator_mutu_list.status,indikator_mutu_list.gambar');
    }


    public function getIndikatorMutuListById($id)
    {
        return $this->db->table('indikator_mutu_list')
            ->where('status', 'Y')
            ->where('indikator_mutu_id', $id)->get()->getResultArray();
    }
}
