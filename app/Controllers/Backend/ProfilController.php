<?php

namespace App\Controllers\Backend;

use App\Models\Profil;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ProfilController extends BaseController
{
    protected $profil;

    public function __construct()
    {
        $this->profil = new Profil();
    }

    public function index()
    {
        $data['title'] = 'Profil';
        $data['profil'] = $this->profil->first();
        return view('backend/profil/index', $data);
    }

    public function save()
    {

        // $rules = $this->validate([
        //     'nama' => 'required',
        //     'visi' => 'required',
        //     'misi' => 'required',
        //     'motto' => 'required',
        //     'alamat' => 'required',
        //     'telepon' => 'required',
        //     'fax' => 'required',
        //     'email' => 'required|valid_email',
        //     'gambar' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        // ]);

        // $validation =  \Config\Services::validation();


        // if (!$rules) {
        //     session()->setFlashData([
        //         'error_nama' => $validation->getError('nama'),
        //         'error_visi' => $validation->getError('visi'),
        //         'error_misi' => $validation->getError('misi'),
        //         'error_motto' => $validation->getError('motto'),
        //         'error_alamat' => $validation->getError('alamat'),
        //         'error_telepon' => $validation->getError('telepon'),
        //         'error_fax' => $validation->getError('fax'),
        //         'error_email' => $validation->getError('email'),
        //     ]);
        //     return redirect()->back()->withInput();
        // }

        // Mendapatkan data input yang telah divalidasi
        $data = [
            'nama' => $this->request->getPost('nama'),
            'visi' => $this->request->getPost('visi'),
            'misi' => $this->request->getPost('misi'),
            'motto' => $this->request->getPost('motto'),
            'alamat' => $this->request->getPost('alamat'),
            'telepon' => $this->request->getPost('telepon'),
            'fax' => $this->request->getPost('fax'),
            'email' => $this->request->getPost('email'),
            'tugas' => $this->request->getPost('tugas'),
        ];

        // Tangani file upload jika ada
        $fileFoto = $this->request->getFile('gambar');
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $idProfil = $this->request->getPost('idprofil');

            // Jika ada ID, cari profil lama dan hapus gambar lama jika ada
            if ($idProfil) {
                $profil = $this->profil->find($idProfil);
                if ($profil && $profil['gambar']) {
                    $oldFotoPath = FCPATH . 'profil/' . $profil['gambar'];
                    if (file_exists($oldFotoPath)) {
                        unlink($oldFotoPath);
                    }
                }
            }

            // Simpan gambar baru
            $newFotoName = 'Profil_' . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'profil', $newFotoName);
            $data['gambar'] = $newFotoName;
        }


        // Periksa apakah ada ID
        $id = $this->request->getPost('idprofil');
        if ($id) {
            // Jika ID ada, update data
            $this->profil->update($id, $data);
        } else {
            // Jika ID tidak ada, buat data baru
            $this->profil->insert($data);
        }

        session()->setFlashdata('success', 'Data Profil Berhasil Di Update');
        return redirect()->to('/profils');
    }
}
