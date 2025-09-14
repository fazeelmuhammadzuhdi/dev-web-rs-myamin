<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'iduser';

    protected $allowedFields    = [
        'iduser',
        'username',
        'password',
        'nama',
        'status',
        'foto',
        'usercreate',
        'userlastlogin',
        'role',
        'login_attempts',
        'last_attempt',

    ];

    public function cekLogin($username)
    {
        return $this->db->table('user')
            ->where(array('username' => $username))
            ->get()->getRowArray();
    }
}
