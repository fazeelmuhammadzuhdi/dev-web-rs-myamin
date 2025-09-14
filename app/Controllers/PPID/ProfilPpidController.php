<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\ProfilPPID;

/**
 * ProfilPpidController handles PPID profile management functionality
 * 
 * This controller manages PPID profile information including content management
 * and image uploads for various sections with comprehensive validation.
 */
class ProfilPpidController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpg,image/jpeg,image/png';
    
    // Model instance
    private ProfilPPID $profilPpidModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->profilPpidModel = new ProfilPPID();
    }

    /**
     * Display PPID profile index page
     */
    public function index()
    {
        $data = [
            'title' => 'Profil PPID',
            'profilppid' => $this->profilPpidModel->first()
        ];
        return view('backend/profilppid/index', $data);
    }

    /**
     * Get PPID profile validation rules
     */
    private function getProfileValidationRules(): array
    {
        return [
            'visi_input' => [
                'label' => 'Visi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Visi harus diisi',
                    'min_length' => 'Visi minimal 10 karakter'
                ]
            ],
            'misi_input' => [
                'label' => 'Misi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Misi harus diisi',
                    'min_length' => 'Misi minimal 10 karakter'
                ]
            ],
            'tugas_input' => [
                'label' => 'Tugas',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Tugas harus diisi',
                    'min_length' => 'Tugas minimal 10 karakter'
                ]
            ],
            'fungsi_input' => [
                'label' => 'Fungsi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Fungsi harus diisi',
                    'min_length' => 'Fungsi minimal 10 karakter'
                ]
            ],
            'maklumat_input' => [
                'label' => 'Maklumat',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Maklumat harus diisi',
                    'min_length' => 'Maklumat minimal 10 karakter'
                ]
            ],
            'hakekat_input' => [
                'label' => 'Hakekat',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Hakekat harus diisi',
                    'min_length' => 'Hakekat minimal 10 karakter'
                ]
            ],
            'asas_input' => [
                'label' => 'Asas',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Asas harus diisi',
                    'min_length' => 'Asas minimal 10 karakter'
                ]
            ],
            'keterangan_profil_ppid' => [
                'label' => 'Keterangan Profil PPID',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan profil PPID harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ],
            'regulasi_kip' => [
                'label' => 'Regulasi KIP',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Regulasi KIP harus diisi',
                    'min_length' => 'Regulasi minimal 10 karakter'
                ]
            ],
            'sarana_prasarana' => [
                'label' => 'Sarana Prasarana',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Sarana prasarana harus diisi',
                    'min_length' => 'Sarana prasarana minimal 10 karakter'
                ]
            ],
            'standar_biaya' => [
                'label' => 'Standar Biaya',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Standar biaya harus diisi',
                    'min_length' => 'Standar biaya minimal 10 karakter'
                ]
            ],
            'layanan_lansia_difabel' => [
                'label' => 'Layanan Lansia Difabel',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Layanan lansia difabel harus diisi',
                    'min_length' => 'Layanan minimal 10 karakter'
                ]
            ],
            'tata_cara_pengaduan' => [
                'label' => 'Tata Cara Pengaduan',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Tata cara pengaduan harus diisi',
                    'min_length' => 'Tata cara minimal 10 karakter'
                ]
            ],
            'prosedur_evakuasi' => [
                'label' => 'Prosedur Evakuasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Prosedur evakuasi harus diisi',
                    'min_length' => 'Prosedur minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save PPID profile
     */
    public function save()
    {
        $data = $this->getFormData([
            'idprofilppid', 'visi_input', 'misi_input', 'tugas_input', 'fungsi_input',
            'maklumat_input', 'hakekat_input', 'asas_input', 'keterangan_profil_ppid',
            'regulasi_kip', 'sarana_prasarana', 'standar_biaya', 'layanan_lansia_difabel',
            'tata_cara_pengaduan', 'prosedur_evakuasi'
        ]);

        $rules = $this->getProfileValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'visi_input', 'misi_input', 'tugas_input', 'fungsi_input',
                'maklumat_input', 'hakekat_input', 'asas_input', 'keterangan_profil_ppid',
                'regulasi_kip', 'sarana_prasarana', 'standar_biaya', 'layanan_lansia_difabel',
                'tata_cara_pengaduan', 'prosedur_evakuasi'
            ]);
        }

        $profileData = [
            'visi_input' => $data['visi_input'],
            'misi_input' => $data['misi_input'],
            'tugas_input' => $data['tugas_input'],
            'fungsi_input' => $data['fungsi_input'],
            'maklumat_input' => $data['maklumat_input'],
            'hakekat_input' => $data['hakekat_input'],
            'asas_input' => $data['asas_input'],
            'keterangan_profil_ppid' => $data['keterangan_profil_ppid'],
            'regulasi_kip' => $data['regulasi_kip'],
            'sarana_prasarana' => $data['sarana_prasarana'],
            'standar_biaya' => $data['standar_biaya'],
            'layanan_lansia_difabel' => $data['layanan_lansia_difabel'],
            'tata_cara_pengaduan' => $data['tata_cara_pengaduan'],
            'prosedur_evakuasi' => $data['prosedur_evakuasi'],
        ];

        // Save or update profile
        if ($data['idprofilppid']) {
            $this->profilPpidModel->update($data['idprofilppid'], $profileData);
        } else {
            $this->profilPpidModel->insert($profileData);
        }

        return $this->setSuccessMessage('Data Berhasil Di Update');
    }

    /**
     * Get image upload validation rules
     */
    private function getImageUploadValidationRules(): array
    {
        return [
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|is_image[gambar]|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah ' . self::MAX_FILE_SIZE . 'KB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ];
    }

    /**
     * Process image upload
     */
    private function processImageUpload(string $prefix): ?string
    {
        $file = $this->request->getFile('gambar');
        
        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $prefix . "_" . $file->getRandomName();
            $file->move(FCPATH . 'frontend/images/ppid', $newName);
            
            // Optimize image
            optimizeImageForWeb('frontend/images/ppid/' . $newName, [
                'width' => 800,
                'height' => 600,
                'quality' => 85
            ]);
            
            return $newName;
        }
        
        return null;
    }

    /**
     * Delete old image
     */
    private function deleteOldImage(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'frontend/images/ppid/' . $imagePath)) {
            $this->deleteFile('frontend/images/ppid/' . $imagePath);
        }
    }

    /**
     * Upload Visi Misi image
     */
    public function uploadGambarVisimisi()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->getImageUploadValidationRules());

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');
        $profil = $this->profilPpidModel->find($id);

        $newName = $this->processImageUpload('VisiMisi');
        if (!$newName) {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal mengupload gambar']);
        }

        if (!$profil) {
            $this->profilPpidModel->insert(['visimisi' => $newName]);
        } else {
            // Delete old image
            if (!empty($profil['visimisi'])) {
                $this->deleteOldImage($profil['visimisi']);
            }
            $this->profilPpidModel->update($id, ['visimisi' => $newName]);
        }

        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }

    /**
     * Upload Tugas image
     */
    public function uploadGambarTugas()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->getImageUploadValidationRules());

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');
        $profil = $this->profilPpidModel->find($id);

        $newName = $this->processImageUpload('Tugas');
        if (!$newName) {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal mengupload gambar']);
        }

        if (!$profil) {
            $this->profilPpidModel->insert(['tugas' => $newName]);
        } else {
            // Delete old image
            if (!empty($profil['tugas'])) {
                $this->deleteOldImage($profil['tugas']);
            }
            $this->profilPpidModel->update($id, ['tugas' => $newName]);
        }

        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }

    /**
     * Upload Fungsi image
     */
    public function uploadGambarFungsi()
    {
        $validation = \Config\Services::validation();
        $validation->setRules($this->getImageUploadValidationRules());

        if (!$this->validate($validation->getRules())) {
            return $this->response->setJSON(['success' => false, 'error' => $validation->getError('gambar')]);
        }

        $id = $this->request->getPost('idprofilppid');
        $profil = $this->profilPpidModel->find($id);

        $newName = $this->processImageUpload('Fungsi');
        if (!$newName) {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal mengupload gambar']);
        }

        if (!$profil) {
            $this->profilPpidModel->insert(['fungsi' => $newName]);
        } else {
            // Delete old image
            if (!empty($profil['fungsi'])) {
                $this->deleteOldImage($profil['fungsi']);
            }
            $this->profilPpidModel->update($id, ['fungsi' => $newName]);
        }

        return $this->response->setJSON(['success' => true, 'filePath' => base_url('frontend/images/ppid/' . $newName)]);
    }
}