<?php

namespace App\Models;

use CodeIgniter\Model;

class BeritaPPID extends Model
{
    protected $table            = 'berita_ppid';
    protected $primaryKey       = 'idberita';

    const KATEGORI_PPID = [1, 2, 3];
    const KATEGORI_PKRS = [4, 5, 6];

    protected $allowedFields    = [
        'idberita',
        'judul',
        'user_id',
        'tanggal',
        'konten',
        'kategori_id',
        'tahun',
        'jangka',
        'penanggung_jawab',
        'status',
        'filetype',
        'link',
        'viewberita',
        'slug',
        'download',
        'tempat',
        'nm_status',

    ];

    public function getExistingData()
    {
        return $this->select('idberita')->findAll();
    }

    public function getBerita($kategoriIds)

    {
        $builder = $this->db->table('berita_ppid')
            ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
            ->select('berita_ppid.idberita, berita_ppid.status, berita_ppid.judul, kategori_informasi_ppid.title, berita_ppid.tanggal, berita_ppid.link');

        if (is_array($kategoriIds)) {
            $builder->whereIn('berita_ppid.kategori_id', $kategoriIds);
        } else {
            $builder->where('berita_ppid.kategori_id', $kategoriIds);
        }

        return $builder->orderBy('berita_ppid.tanggal', 'DESC');

        // return $this->db->table('berita_ppid')
        //     ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
        //     ->select('berita_ppid.idberita, berita_ppid.status, berita_ppid.judul, kategori_informasi_ppid.title, berita_ppid.tanggal, berita_ppid.link')
        //     if (is_array($kategoriIds)) {
        //         ->whereIn('berita_ppid.kategori_id', $kategoriIds)
        //     }else{
        //         ->where('berita_ppid.kategori_id', $kategoriIds)
        //     }


        //     ->orderBy('berita_ppid.tanggal', 'DESC');
    }



    public function getAllDataPPID($kategoriIds)
    {
        return $this->db->table('berita_ppid')
            ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
            ->whereIn('kategori_informasi_ppid.idkategori', $kategoriIds)
            ->where('berita_ppid.nm_status', 'Publish')
            ->orderBy('berita_ppid.tanggal', 'DESC');
    }

    public function incrementViewCount($id)
    {
        $this->update($id, [
            'viewberita' => $this->getViews($id) + 1
        ]);
    }

    private function getViews($id)
    {
        $newsItem = $this->find($id);
        return $newsItem['viewberita'];
    }



    public function countBeritaByKategori($kategoriIds)
    {
        return $this->db->table('berita_ppid')
            ->select('kategori_informasi_ppid.title , kategori_informasi_ppid.idkategori, count(berita_ppid.kategori_id) as total')
            ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
            ->where('kategori_informasi_ppid.status', 'Y')
            ->where('berita_ppid.nm_status', 'Publish')
            ->whereIn('kategori_informasi_ppid.idkategori', $kategoriIds)
            ->groupBy('berita_ppid.kategori_id')
            ->get()->getResultArray();
    }

    public function getBeritaPpidById($idBeritaPpid)
    {
        return $this->db->table('berita_ppid')
            ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
            ->where('idberita', $idBeritaPpid)
            ->get()->getRowArray();
    }

    // beria popular

    public function getPopularBerita($kategoriIds)
    {
        return $this->db->table('berita_ppid')
            ->select('idberita, judul, viewberita')
            ->where('nm_status', 'Publish')
            ->whereIn('kategori_id', $kategoriIds)
            ->orderBy('viewberita', 'DESC')
            ->limit(6)
            ->get()->getResultArray();
    }

    // berita terbaru
    public function getLatestBerita($kategoriIds)
    {
        return $this->db->table('berita_ppid')
            ->select('idberita, judul, tanggal')
            ->where('nm_status', 'Publish')
            ->whereIn('berita_ppid.kategori_id', $kategoriIds)
            ->orderBy('tanggal', 'DESC')
            ->limit(6)
            ->get()->getResultArray();
    }

    // berita terabanyak di download
    public function getMostDownloadedBerita()
    {
        return $this->db->table('berita_ppid')
            ->select('idberita, judul, download')
            ->where('nm_status', 'Publish')
            ->orderBy('download', 'DESC')
            ->limit(5)
            ->get()->getResultArray();
    }
}
