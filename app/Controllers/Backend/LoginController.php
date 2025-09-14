<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\User;

class LoginController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }


    public function index()
    {
        if (session()->get('idUser')) {
            return redirect()->to(base_url('/home'));
        }

        // $password = password_hash('admin', PASSWORD_DEFAULT);

        // view login
        return view('backend/main/login');
    }

    private function isValidPassword($password)
    {
        // Panjang minimal 8 karakter
        if (strlen($password) < 8) {
            return false;
        }

        // Kombinasi huruf besar, huruf kecil, simbol, dan angka
        if (
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            return false;
        }

        // // Tidak ada perulangan karakter
        // if (preg_match('/(.).*\1/', $password)) {
        //     return false;
        // }

        return true;
    }

    public function login()
    {


        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->user->cekLogin($username);


        $rules = $this->validate([


            'username' => [
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ],

            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Tidak Boleh Kosong'
                ]
            ],
        ]);


        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_username' => $validation->getError('username'),
                'error_password' => $validation->getError('password'),
            ]);
            return redirect()->back()->withInput();
        }

        // Validasi kombinasi huruf besar, huruf kecil, simbol, dan angka
        if (!$this->isValidPassword($password)) {
            session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal 8 karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka.');
            return redirect()->back()->withInput();
        }


        if ($user) {

            if ($user['login_attempts'] >= 3) {
                $timeDiff = time() - strtotime($user['last_attempt']);

                if ($timeDiff < 60) {
                    $remainingTime = 60 - $timeDiff;
                    session()->setFlashdata('error', 'Terlalu banyak percobaan login. Silakan coba lagi dalam ' . $remainingTime . ' detik.');
                    session()->setFlashdata('remainingTime', $remainingTime);
                    return redirect()->back()->withInput();
                } else {
                    $this->user->update($user['iduser'], ['login_attempts' => 0, 'last_attempt' => null]);
                }
            }


            if ($user['status'] === 'A') {
                if (password_verify($password, $user['password'])) {
                    session()->set('idUser', $user['iduser']);
                    session()->set('namaUser', $user['nama']);
                    session()->set('usernameUser', $user['username']);
                    session()->set('roleUser', $user['role']);
                    session()->set('fotoUser', $user['foto']);
                    session()->set('statusUser', $user['status']);
                    // set user last login
                    $this->user->update(
                        $user['iduser'],
                        [
                            'userlastlogin' => date('Y-m-d H:i:s'),
                            'login_attempts' => 0
                        ]
                    );
                    return redirect()->to('/home');
                } else {
                    // Tambah percobaan login dan waktu percobaan terakhir
                    $this->user->update($user['iduser'], [
                        'login_attempts' => $user['login_attempts'] + 1,
                        'last_attempt' => date('Y-m-d H:i:s')
                    ]);
                    session()->setFlashdata('error', 'Password salah');
                    return redirect()->back()->withInput();
                }
            } else {
                session()->setFlashdata('error', 'Akun belum aktif'); // Pesan jika status tidak aktif
                return redirect()->back()->withInput();
            }
        } else {
            session()->setFlashdata('error', 'Username / Password Salah');
            return redirect()->to('/auth');
        }
    }

    public function logout()
    {
        session()->remove('idUser');
        session()->remove('namaUser');
        session()->remove('usernameUser');
        session()->remove('roleUser');
        session()->remove('fotoUser');
        session()->remove('statusUser');
        session()->setFlashdata('success', 'Berhasil keluar');
        return redirect()->to('/auth');
    }
}
