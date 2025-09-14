<?php

namespace App\Models;

use CodeIgniter\Model;

class Album extends Model
{
    protected $table            = 'album';
    protected $primaryKey       = 'idalbum';

    protected $allowedFields    = [
        'idalbum',
        'tanggal',
        'judul',
        'keterangan',
        'status',
        'user_id',
        'slug',
        'created_at',
    ];

    // untuk halaman home
    public function getGaleri()
    {
        return $this->select('album.*, album_list.*')
            ->join('album_list', 'album_list.album_id = album.idalbum')
            ->where('album.status', 'PB')
            ->orderBy('album.idalbum', 'DESC')
            ->groupBy('album.idalbum')
            ->limit(3)
            ->findAll();
    }

    public function getGaleriFoto()
    {
        return $this->select('album.idalbum,album.tanggal, album.judul as judul_album, album.status, album_list.album_id,album_list.gambar,album.slug, album_list.keterangan as list_keterangan')
            ->join('album_list', 'album_list.album_id = album.idalbum')
            ->where('album.status', 'PB')
            ->orderBy('album.tanggal', 'DESC')
            ->groupBy('album.idalbum')
            ->paginate(12);
    }

    public function getGaleriFotoDetail($slug)
    {
        return $this->select('album.idalbum,album.tanggal, album.judul as judul_album, album.status, album_list.album_id,album_list.gambar,album.slug,album.keterangan, album_list.keterangan as list_keterangan')
            ->join('album_list', 'album_list.album_id = album.idalbum')
            ->where('album.status', 'PB')
            ->where('album.slug', $slug)
            ->findAll();
    }
}
