<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\ProfilPPID;

class ProfilPpidController extends BaseController
{
    protected $profilppid;

    public function __construct()
    {
        $this->profilppid = new ProfilPPID();
    }

    public function index()
    {
        $data['title'] = 'Profil PPID';
        $data['profilppid'] = $this->profilppid->first();
        return view('backend/profilppid/index', $data);
    }

    public function save()
    {

        // Mendapatkan data input yang telah divalidasi
        $data = [
            'visi_input' => $this->request->getPost('visi_input'),
            'misi_input' => $this->request->getPost('misi_input'),
            'tugas_input' => $this->request->getPost('tugas_input'),
            'fungsi_input' => $this->request->getPost('fungsi_input'),
            'maklumat_input' => $this->request->getPost('maklumat_input'),
            'hakekat_input' => $this->request->getPost('hakekat_input'),
            'asas_input' => $this->request->getPost('asas_input'),
            'keterangan_profil_ppid' => $this->request->getPost('keterangan_profil_ppid'),
            'regulasi_kip' => $this->request->getPost('regulasi_kip'),
            'sarana_prasarana' => $this->request->getPost('sarana_prasarana'),
            'standar_biaya' => $this->request->getPost('standar_biaya'),
            'layanan_lansia_difabel' => $this->request->getPost('layanan_lansia_difabel'),
            'tata_cara_pengaduan' => $this->request->getPost('tata_cara_pengaduan'),
            'prosedur_evakuasi' => $this->request->getPost('prosedur_evakuasi'),
        ];

        // Periksa apakah ada ID
        $id = $this->request->getPost('idprofilppid');
        if ($id) {
            // Jika ID ada, update data
            $this->profilppid->update($id, $data);
        } else {
            // Jika ID tidak ada, buat data baru
            $this->profilppid->insert($data);
        }

        session()->setFlashdata('success', 'Data Berhasi Di Update');
        return redirect()->back();
    }

    public function uploadGambarVisimisi()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah 1MB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');

        $profil = $this->profilppid->find($id);

        $file = $this->request->getFile('gambar');
        $newName = "VisiMisi_" . $file->getRandomName();
        $file->move(FCPATH . 'frontend/images/ppid', $newName);

        if (!$profil) {
            $data = [
                'visimisi' => $newName,
            ];
            $this->profilppid->insert($data);
        } else {
            // Hapus gambar lama jika ada
            if (!empty($profil['visimisi'])) {
                $oldImagePath = FCPATH . 'frontend/images/ppid/' . $profil['visimisi'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Update data yang ada
            $this->profilppid->update($id, ['visimisi' => $newName]);
        }
        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }
    public function uploadGambarTugas()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah 1MB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');

        $profil = $this->profilppid->find($id);

        $file = $this->request->getFile('gambar');
        $newName = "Tugas_" . $file->getRandomName();
        $file->move(FCPATH . 'frontend/images/ppid', $newName);

        if (!$profil) {
            $data = [
                'tugas' => $newName,
            ];
            $this->profilppid->insert($data);
        } else {
            // Hapus gambar lama jika ada
            if (!empty($profil['tugas'])) {
                $oldImagePath = FCPATH . 'frontend/images/ppid/' . $profil['tugas'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Update data yang ada
            $this->profilppid->update($id, ['tugas' => $newName]);
        }
        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }

    public function uploadGambarFungsi()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah 1MB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');

        $profil = $this->profilppid->find($id);

        $file = $this->request->getFile('gambar');
        $newName = "Fungsi_" . $file->getRandomName();
        $file->move(FCPATH . 'frontend/images/ppid', $newName);

        if (!$profil) {
            $data = [
                'fungsi' => $newName,
            ];
            $this->profilppid->insert($data);
        } else {
            // Hapus gambar lama jika ada
            if (!empty($profil['fungsi'])) {
                $oldImagePath = FCPATH . 'frontend/images/ppid/' . $profil['fungsi'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Update data yang ada
            $this->profilppid->update($id, ['fungsi' => $newName]);
        }
        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }
}
