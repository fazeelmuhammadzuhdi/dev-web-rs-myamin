<?php

namespace App\Models;

use CodeIgniter\Model;

class Berita extends Model
{
    protected $table            = 'berita';
    protected $primaryKey       = 'idberita';

    protected $allowedFields    = [
        'idberita',
        'judul',
        'user_id',
        'tanggal',
        'konten',
        'kategori_id',
        'status',
        'gambar',
        'thumbnail',
        'vimg',
        'viewberita',
        'slug',
        'created_at',
    ];

    public function getBerita()
    {
        return $this->db->table('berita')
            ->join('kategori', 'berita.kategori_id = kategori.idkategori')
            ->join('user', 'berita.user_id = user.iduser')
            ->select('berita.idberita, berita.judul,kategori.title,berita.tanggal,berita.status,berita.gambar,user.nama')
            ->orderBy('tanggal', 'desc')
            ->orderBy('idberita', 'desc');
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

    public function countBeritaByKategori()
    {
        return $this->db->table('berita')
            ->select('kategori.title , kategori.idkategori, count(berita.kategori_id) as total')
            ->join('kategori', 'berita.kategori_id = kategori.idkategori')
            ->where('kategori.status', 'Y')
            ->groupBy('berita.kategori_id')
            ->get()->getResultArray();
    }

    // public function getGrafikBerita()
    // {
    //     $currentYear = date('Y');

    //     // Query untuk mengambil jumlah postingan berita per bulan pada tahun yang sedang berlangsung
    //     $query = $this->db->query(
    //         "
    //          SELECT MONTH(berita.tanggal) AS bulan,
    //         kategori.title AS kategori,
    //         COUNT(*) AS jumlah
    //     FROM
    //         berita
    //     JOIN
    //         kategori ON berita.kategori_id = kategori.idkategori
    //     WHERE
    //         YEAR(berita.tanggal) = $currentYear
    //     GROUP BY
    //         bulan, kategori;
    //     "
    //     );

    //     return $query->getResultArray();
    // }

    public function getGrafikBerita()
    {
        $currentYear = date('Y');

        $query = $this->db->query(
            "
        SELECT
            MONTH(berita.tanggal) AS bulan,
            kategori.title AS kategori,
            COUNT(*) AS jumlah
        FROM
            berita
        JOIN
            kategori ON berita.kategori_id = kategori.idkategori
        WHERE
            YEAR(berita.tanggal) = $currentYear
        GROUP BY
            MONTH(berita.tanggal), kategori.title
        ;
        "
        );

        return $query->getResultArray();
    }
}
