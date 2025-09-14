<?php

namespace App\Models;

use CodeIgniter\Model;

class FAQ extends Model
{
    protected $table            = 'faq';
    protected $primaryKey       = 'idfaq';

    protected $allowedFields    = [
        'idfaq',
        'pertanyaan',
        'jawaban',
        'created_at',
    ];
}
