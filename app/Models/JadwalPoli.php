<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalPoli extends Model
{
    protected $table            = 'jadwal_poli';
    protected $primaryKey       = 'idjadwalpoli';

    protected $allowedFields    = [
        'idjadwalpoli',
        'poli_id',
        'dokter_id',
        'senin',
        'keterangan_senin',
        'selasa',
        'keterangan_selasa',
        'rabu',
        'keterangan_rabu',
        'kamis',
        'keterangan_kamis',
        'jumat',
        'keterangan_jumat',
        'sabtu',
        'keterangan_sabtu',
        'minggu',
        'keterangan_minggu',
        'created_at',
        'last_update'
    ];


    public function getJadwalPoli()
    {
        return $this->db->table('jadwal_poli')
            ->join('poli', 'poli.idpoli = jadwal_poli.poli_id')
            ->join('dokter', 'dokter.iddokter = jadwal_poli.dokter_id')
            ->select('jadwal_poli.idjadwalpoli,poli.nama,dokter.nama as nama_dokter');
    }

    public function getJadwalWithDokter()
    {
        return $this->db->table('jadwal_poli')
            ->select('dokter.nama AS dokter, keterangan_senin,keterangan_selasa,keterangan_rabu,keterangan_kamis,keterangan_jumat,keterangan_sabtu,senin, selasa, rabu, kamis, jumat, sabtu, minggu,last_update,poli.nama as nama_poli,dokter.nama as nama_dokter')
            ->join('poli', 'poli.idpoli = jadwal_poli.poli_id')
            ->join('dokter', 'dokter.iddokter = jadwal_poli.dokter_id')
            ->where('dokter.status', 'Y')
            // ->select('poli.nama as nama_poli,dokter.nama as nama_dokter', 'jadwal_poli.senin', 'jadwal_poli.selasa', 'jadwal_poli.rabu', 'jadwal_poli.kamis', 'jadwal_poli.jumat', 'jadwal_poli.sabtu', 'jadwal_poli.last_update')
            ->get()
            ->getResultArray();
    }

    public function getFormattedJadwalPoliWithDokter()
    {
        $jadwalRaw = $this->getJadwalWithDokter();

        // Group data by poli
        $jadwalpoli = [];
        foreach ($jadwalRaw as $item) {
            $jadwalpoli[$item['nama_poli']][] = $item;
        }

        return $jadwalpoli;
    }

    // kodingan tanpa spesialis
    // public function getJadwalPoliWithDokter($namaPoli)
    // {
    //     return $this->db->table('jadwal_poli')
    //         ->select('dokter.nama AS dokter, dokter.gambar, senin, keterangan_senin,keterangan_selasa,keterangan_rabu,keterangan_kamis,keterangan_jumat, selasa, rabu, kamis, jumat, sabtu, minggu')
    //         ->join('poli', 'poli.idpoli = jadwal_poli.poli_id')
    //         ->join('dokter', 'dokter.iddokter = jadwal_poli.dokter_id')
    //         ->where('poli.nama', $namaPoli)
    //         ->select('poli.nama as nama_poli,dokter.nama as nama_dokter', 'jadwal_poli.senin', 'jadwal_poli.selasa', 'jadwal_poli.rabu', 'jadwal_poli.kamis', 'jadwal_poli.jumat', 'dokter.gambar')
    //         ->get()
    //         ->getResultArray();
    // }

    // kodingan baru
    public function getJadwalPoliWithDokter($namaPoli)
    {
        return $this->db->table('jadwal_poli')
            ->select('dokter.nama AS dokter, dokter.gambar, senin, keterangan_senin,keterangan_selasa,keterangan_rabu,keterangan_kamis,keterangan_jumat,keterangan_sabtu, selasa, rabu, kamis, jumat, sabtu, minggu,spesialis.nama as nama_spesialis,poli.nama as nama_poli,dokter.nama as nama_dokter')
            ->join('poli', 'poli.idpoli = jadwal_poli.poli_id')
            ->join('dokter', 'dokter.iddokter = jadwal_poli.dokter_id')
            ->join('spesialis', 'spesialis.idspesialis = dokter.spesialis_id')
            ->where('poli.slug', $namaPoli)
            ->where('dokter.status', 'Y')
            ->get()
            ->getResultArray();
    }
}
