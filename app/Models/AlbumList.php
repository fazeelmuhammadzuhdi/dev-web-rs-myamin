<?php

namespace App\Models;

use CodeIgniter\Model;

class AlbumList extends Model
{
    protected $table            = 'album_list';
    protected $primaryKey       = 'idalbumlist';

    protected $allowedFields    = [
        'idalbumlist',
        'album_id',
        'keterangan',
        'gambar',
        'thumbnail',
        'created_at',
    ];


    // public function getAlbumList()
    // {
    //     return $this->db->table('album_list')
    //         ->join('album', 'album_list.album_id = album.idalbum')
    //         ->select('album_list.idalbumlist, album.judul,album.tanggal, album_list.gambar');
    // }

    public function getAlbumList()
    {
        return $this->db->table('album_list')
            ->join('album', 'album_list.album_id = album.idalbum')
            ->join('user', 'album.user_id = user.iduser')
            ->select('album_list.idalbumlist, album.judul, album.tanggal, album.status,user.nama')
            ->orderBy('album.tanggal', 'DESC')
            ->groupBy('album_list.album_id');
        // ->having('count > 1');
    }
}
