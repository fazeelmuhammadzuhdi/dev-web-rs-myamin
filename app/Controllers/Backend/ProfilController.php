<?php

namespace App\Controllers\Backend;

use App\Models\Profil;
use App\Controllers\BaseController;

/**
 * ProfilController handles hospital profile management functionality
 * 
 * This controller manages hospital profile information including
 * basic details, vision, mission, contact information, and logo.
 */
class ProfilController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private Profil $profilModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->profilModel = new Profil();
    }

    /**
     * Display profile index page
     */
    public function index()
    {
        $data = [
            'title' => 'Profil',
            'profil' => $this->profilModel->first()
        ];
        return view('backend/profil/index', $data);
    }

    /**
     * Get profile validation rules
     */
    private function getProfileValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Rumah Sakit',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama rumah sakit harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'visi' => [
                'label' => 'Visi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Visi harus diisi',
                    'min_length' => 'Visi minimal 10 karakter'
                ]
            ],
            'misi' => [
                'label' => 'Misi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Misi harus diisi',
                    'min_length' => 'Misi minimal 10 karakter'
                ]
            ],
            'motto' => [
                'label' => 'Motto',
                'rules' => 'required|min_length[5]|max_length[200]',
                'errors' => [
                    'required' => 'Motto harus diisi',
                    'min_length' => 'Motto minimal 5 karakter',
                    'max_length' => 'Motto maksimal 200 karakter'
                ]
            ],
            'alamat' => [
                'label' => 'Alamat',
                'rules' => 'required|min_length[10]|max_length[255]',
                'errors' => [
                    'required' => 'Alamat harus diisi',
                    'min_length' => 'Alamat minimal 10 karakter',
                    'max_length' => 'Alamat maksimal 255 karakter'
                ]
            ],
            'telepon' => [
                'label' => 'Telepon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Telepon harus diisi',
                    'numeric' => 'Telepon harus berupa angka',
                    'min_length' => 'Telepon minimal 10 digit',
                    'max_length' => 'Telepon maksimal 15 digit'
                ]
            ],
            'fax' => [
                'label' => 'Fax',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Fax harus diisi',
                    'numeric' => 'Fax harus berupa angka',
                    'min_length' => 'Fax minimal 10 digit',
                    'max_length' => 'Fax maksimal 15 digit'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter'
                ]
            ],
            'tugas' => [
                'label' => 'Tugas Pokok',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Tugas pokok harus diisi',
                    'min_length' => 'Tugas pokok minimal 10 karakter'
                ]
            ],
            'gambar' => [
                'label' => 'Logo',
                'rules' => 'max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran logo maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format logo harus JPEG, PNG atau JPG'
                ]
            ]
        ];
    }

    /**
     * Process image upload
     */
    private function processImageUpload(): ?string
    {
        $fileFoto = $this->request->getFile('gambar');
        
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $newFotoName = 'Profil_' . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'profil', $newFotoName);
            
            // Optimize image
            optimizeImageForWeb('profil/' . $newFotoName, [
                'width' => 300,
                'height' => 300,
                'quality' => 85
            ]);
            
            return $newFotoName;
        }
        
        return null;
    }

    /**
     * Delete old image
     */
    private function deleteOldImage(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'profil/' . $imagePath)) {
            $this->deleteFile('profil/' . $imagePath);
        }
    }

    /**
     * Save or update profile
     */
    public function save()
    {
        $data = $this->getFormData([
            'idprofil', 'nama', 'visi', 'misi', 'motto', 'alamat', 
            'telepon', 'fax', 'email', 'tugas'
        ]);

        $rules = $this->getProfileValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama', 'visi', 'misi', 'motto', 'alamat', 
                'telepon', 'fax', 'email', 'tugas', 'gambar'
            ]);
        }

        $profileData = [
            'nama' => $data['nama'],
            'visi' => $data['visi'],
            'misi' => $data['misi'],
            'motto' => $data['motto'],
            'alamat' => $data['alamat'],
            'telepon' => $data['telepon'],
            'fax' => $data['fax'],
            'email' => $data['email'],
            'tugas' => $data['tugas'],
        ];

        // Handle image upload
        $newImage = $this->processImageUpload();
        if ($newImage) {
            // Delete old image if updating
            if ($data['idprofil']) {
                $existingProfil = $this->profilModel->find($data['idprofil']);
                if ($existingProfil && $existingProfil['gambar']) {
                    $this->deleteOldImage($existingProfil['gambar']);
                }
            }
            $profileData['gambar'] = $newImage;
        }

        // Save or update profile
        if ($data['idprofil']) {
            $this->profilModel->update($data['idprofil'], $profileData);
        } else {
            $this->profilModel->insert($profileData);
        }

        return $this->setSuccessMessage('Data Profil Berhasil Di Update', '/profils');
    }
}