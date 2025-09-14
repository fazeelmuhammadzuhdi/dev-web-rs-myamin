<?php

namespace App\Controllers\Backend;

use DOMDocument;
use App\Models\User;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

class UserController extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $data['title'] = 'User';

        return view('backend/user/index', $data);
    }

    public function create()
    {
        return view('backend/user/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->user->select('iduser,username,nama,role,status');
            return DataTable::of($builder)
                ->edit('role', function ($row) {
                    if ($row->role == 'SU') {
                        return '<span class="badge badge-success">Super Admin</span>';
                    } else {
                        return '<span class="badge badge-danger">User</span>';
                    }
                })
                ->edit('status', function ($row) {
                    if ($row->status == 'A') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-danger">Tidak Aktif</span>';
                    }
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->iduser . '\',\'' . $row->username . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->iduser . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
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

    public function save()
    {
        $nama = $this->request->getVar('nama');
        $role = $this->request->getVar('role');
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        // Validasi kata sandi
        if (!$this->isValidPassword($password)) {
            session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal 8 karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka');
            return redirect()->back()->withInput();
        }

        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'username' => [
                'label' => 'Username User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],
            'password' => [
                'label' => 'Password User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
                'error_username' => $validation->getError('username'),
                'error_password' => $validation->getError('password'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $this->user->insert([
                'nama' => $nama,
                'username' => $username,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'role' => $role ? $role : 'US',
                'usercreate' => date('Y-m-d H:i:s'),
            ]);

            session()->setFlashdata('success', 'Data User Berhasil Di Tambahkan');
            return redirect()->to('/users');
        }
    }

    public function edit($id = null)
    {
        $data['user'] = $this->user->find($id);
        return view('backend/user/edit', $data);
    }

    public function update()
    {

        $idUser = $this->request->getVar('iduser');
        $findById = $this->user->find($this->request->getPost('iduser'));
        $nama = $this->request->getVar('nama');
        $role = $this->request->getVar('role');
        $username = $this->request->getVar('username');
        $status = $this->request->getVar('status');

        $password = $this->request->getVar('password');


        // Jika password dan konfirmasi password diisi, lakukan validasi
        if (!empty($password) || !empty($confirmpassword)) {
            // Validasi kata sandi
            if (!$this->isValidPassword($password)) {
                session()->setFlashdata('error_password', 'Password tidak valid. Pastikan panjang minimal 8 karakter, terdiri dari huruf besar, huruf kecil, simbol, dan angka');
                return redirect()->back()->withInput();
            }
            $password = password_hash($this->request->getVar('password'), PASSWORD_DEFAULT);
        }



        $rules = $this->validate([

            'nama' => [
                'label' => 'Nama User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

            'username' => [
                'label' => 'Username User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} tidak boleh kosong',
                ]
            ],

        ]);

        if (!$rules) {
            $validation = \Config\Services::validation();
            session()->setFlashData([
                'error_nama' => $validation->getError('nama'),
                'error_username' => $validation->getError('username'),
            ]);
            return redirect()->back()->withInput();
        } else {
            $data = [
                'nama' => $nama,
                'username' => $username,
                'password' => ($this->request->getPost('password')) ? $password : $findById['password'],
                'role' => $role ? $role : 'US',
                'status' => $status ? $status : 'A',
            ];

            $this->user->update($idUser, $data);

            session()->setFlashdata('success', 'Data User Berhasil Di Update');
            return redirect()->to('/users');
        }
    }

    public function delete($id = null)
    {
        if ($this->request->isAJAX()) {
            $username = $this->user->find($id);

            if ($username) {
                $this->user->delete($id);

                $json = [
                    'sukses' => 'Data Berhasil Terhapus'
                ];
                echo json_encode($json);
            }
        }
    }
}
