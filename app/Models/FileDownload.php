<?php

namespace App\Models;

use CodeIgniter\Model;

class FileDownload extends Model
{
    protected $table            = 'file_download';
    protected $primaryKey       = 'idfile';

    protected $allowedFields    = [
        'idfile',
        'tanggalfile',
        'uploadfile',
        'keteranganfile',
    ];
}
