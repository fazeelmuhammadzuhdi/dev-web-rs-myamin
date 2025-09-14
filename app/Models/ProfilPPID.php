<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilPPID extends Model
{

    protected $table            = 'profil_ppid';
    protected $primaryKey       = 'idprofilppid';

    protected $allowedFields    = [
        'regulasi_kip',
        'visi_input',
        'misi_input',
        'tugas_input',
        'fungsi_input',
        'maklumat_input',
        'hakekat_input',
        'asas_input',
        'sarana_prasarana',
        'keterangan_profil_ppid',
        'standar_biaya',
        'layanan_lansia_difabel',
        'tata_cara_pengaduan',
        'prosedur_evakuasi'
    ];
}
