<?php

namespace App\Controllers\Backend;

use App\Models\Tentang;
use App\Controllers\BaseController;

class TentangController extends BaseController
{
    protected $tentang;

    public function __construct()
    {
        $this->tentang = new Tentang();
    }

    public function index()
    {
        $data['title'] = 'Tentang';
        $data['tentang'] = $this->tentang->first();
        return view('backend/tentang/index', $data);
    }

    public function save()
    {

        // Mendapatkan data input yang telah divalidasi
        $data = [
            'title_tentang' => $this->request->getPost('title_tentang'),
            'konten_tentang' => $this->request->getPost('konten_tentang'),
            'title_sejarah' => $this->request->getPost('title_sejarah'),
            'konten_sejarah' => $this->request->getPost('konten_sejarah'),
            'ketersediaan_tempat_tidur' => $this->request->getPost('ketersediaan_tempat_tidur'),
        ];

        // Periksa apakah ada ID
        $id = $this->request->getPost('id');
        if ($id) {
            // Jika ID ada, update data
            $this->tentang->update($id, $data);
        } else {
            // Jika ID tidak ada, buat data baru
            $this->tentang->insert($data);
        }

        session()->setFlashdata('success', 'Data Tentang Berhasil Di Update');
        return redirect()->to('/tentangs');
    }
}
