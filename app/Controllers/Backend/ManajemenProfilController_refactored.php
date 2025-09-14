<?php

namespace App\Controllers\Backend;

use App\Models\Manajemen;
use App\Models\ManajemenProfil;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * ManajemenProfilController handles management profile functionality
 * 
 * This controller manages management profiles including creation, editing, deletion, and display
 * with image handling and relationship management with management departments.
 */
class ManajemenProfilController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instances
    private ManajemenProfil $manajemenProfilModel;
    private Manajemen $manajemenModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->manajemenProfilModel = new ManajemenProfil();
        $this->manajemenModel = new Manajemen();
    }

    /**
     * Display management profile index page
     */
    public function index()
    {
        $data = ['title' => 'Manajemen Profil'];
        return view('backend/manajemenprofil/index', $data);
    }

    /**
     * Display management profile creation form
     */
    public function create()
    {
        $data = [
            'manajemen' => $this->manajemenModel->orderBy('idmanajemen', 'asc')
                ->where('status', 'Y')
                ->findAll()
        ];
        return view('backend/manajemenprofil/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->manajemenProfilModel->getManajemenProfil();

        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idmanajemenprofil, $row->nama_manajemen);
            }, 'last')
            ->toJson();
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $nama): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($nama) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get management profile validation rules
     */
    private function getManagementProfileValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'manajemen_id' => [
                'label' => 'Nama Ruangan Manajemen',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Nama ruangan manajemen harus dipilih',
                    'integer' => 'Manajemen harus berupa angka',
                    'greater_than' => 'Manajemen harus dipilih'
                ]
            ],
            'jabatan' => [
                'label' => 'Jabatan',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Jabatan harus diisi',
                    'min_length' => 'Jabatan minimal 3 karakter',
                    'max_length' => 'Jabatan maksimal 100 karakter'
                ]
            ],
            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama manajemen harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'nip' => [
                'label' => 'NIP',
                'rules' => 'required|min_length[8]|max_length[20]',
                'errors' => [
                    'required' => 'NIP harus diisi',
                    'min_length' => 'NIP minimal 8 karakter',
                    'max_length' => 'NIP maksimal 20 karakter'
                ]
            ],
            'tempatlahir' => [
                'label' => 'Tempat Lahir',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Tempat lahir harus diisi',
                    'min_length' => 'Tempat lahir minimal 3 karakter',
                    'max_length' => 'Tempat lahir maksimal 50 karakter'
                ]
            ],
            'tanggallahir' => [
                'label' => 'Tanggal Lahir',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal lahir harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'pendidikan' => [
                'label' => 'Pendidikan',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Pendidikan harus diisi',
                    'min_length' => 'Pendidikan minimal 3 karakter',
                    'max_length' => 'Pendidikan maksimal 100 karakter'
                ]
            ],
            'pangkat' => [
                'label' => 'Pangkat',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Pangkat harus diisi',
                    'min_length' => 'Pangkat minimal 3 karakter',
                    'max_length' => 'Pangkat maksimal 50 karakter'
                ]
            ],
            'profil_singkat' => [
                'label' => 'Profil Singkat',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Profil singkat harus diisi',
                    'min_length' => 'Profil singkat minimal 20 karakter'
                ]
            ],
            'riwayat_pendidikan' => [
                'label' => 'Riwayat Pendidikan',
                'rules' => 'max_length[500]',
                'errors' => [
                    'max_length' => 'Riwayat pendidikan maksimal 500 karakter'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Manajemen',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar manajemen harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Manajemen',
                'rules' => 'max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process image upload
     */
    private function processImageUpload(): ?string
    {
        $fileFoto = $this->request->getFile('gambar');
        
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = "ManajemenProfil_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'manajemenprofil', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('manajemenprofil/' . $namaFoto, [
                'width' => 300,
                'height' => 400,
                'quality' => 85
            ]);
            
            return $namaFoto;
        }
        
        return null;
    }

    /**
     * Delete old image
     */
    private function deleteOldImage(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'manajemenprofil/' . $imagePath)) {
            $this->deleteFile('manajemenprofil/' . $imagePath);
        }
    }

    /**
     * Save new management profile
     */
    public function save()
    {
        $data = $this->getFormData([
            'manajemen_id', 'jabatan', 'nama', 'nip', 'tempatlahir', 'tanggallahir',
            'pendidikan', 'pangkat', 'profil_singkat', 'riwayat_pendidikan'
        ]);

        $rules = $this->getManagementProfileValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'manajemen_id', 'jabatan', 'nama', 'nip', 'tempatlahir', 'tanggallahir',
                'pendidikan', 'pangkat', 'profil_singkat', 'gambar'
            ]);
        }

        $newImage = $this->processImageUpload();
        if (!$newImage) {
            session()->setFlashdata('error_gambar', 'Gambar manajemen harus diisi');
            return redirect()->back()->withInput();
        }

        $this->manajemenProfilModel->insert([
            'manajemen_id' => $data['manajemen_id'],
            'jabatan' => $data['jabatan'],
            'nama' => $data['nama'],
            'nip' => $data['nip'],
            'tempatlahir' => $data['tempatlahir'],
            'tanggallahir' => $data['tanggallahir'],
            'pendidikan' => $data['pendidikan'],
            'pangkat' => $data['pangkat'],
            'profil_singkat' => $data['profil_singkat'],
            'rtiwayat_pendidikan' => $data['riwayat_pendidikan'] ?? null,
            'gambar' => $newImage,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Manajemen Profil Berhasil Ditambahkan', '/manajemenprofils');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'manajemen' => $this->manajemenModel->orderBy('idmanajemen', 'asc')
                ->where('status', 'Y')
                ->findAll(),
            'manajemenprofil' => $this->manajemenProfilModel->find($id)
        ];
        return view('backend/manajemenprofil/edit', $data);
    }

    /**
     * Update existing management profile
     */
    public function update()
    {
        $data = $this->getFormData([
            'idmanajemenprofil', 'manajemen_id', 'jabatan', 'nama', 'nip', 'tempatlahir',
            'tanggallahir', 'pendidikan', 'pangkat', 'profil_singkat', 'riwayat_pendidikan'
        ]);
        
        $idManajemenprofil = $data['idmanajemenprofil'];

        $gambar = $this->request->getFile('gambar');
        $requireImage = $gambar->isValid() && !$gambar->hasMoved();
        
        $rules = $this->getManagementProfileValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'manajemen_id', 'jabatan', 'nama', 'nip', 'tempatlahir', 'tanggallahir',
                'pendidikan', 'pangkat', 'gambar'
            ]);
        }

        $updateData = [
            'manajemen_id' => $data['manajemen_id'],
            'jabatan' => $data['jabatan'],
            'nama' => $data['nama'],
            'nip' => $data['nip'],
            'tempatlahir' => $data['tempatlahir'],
            'tanggallahir' => $data['tanggallahir'],
            'pendidikan' => $data['pendidikan'],
            'pangkat' => $data['pangkat'],
            'profil_singkat' => $data['profil_singkat'],
            'riwayat_pendidikan' => $data['riwayat_pendidikan'] ?? null,
        ];

        // Handle image update
        if ($requireImage) {
            $existingProfile = $this->manajemenProfilModel->find($idManajemenprofil);
            if ($existingProfile && $existingProfile['gambar']) {
                $this->deleteOldImage($existingProfile['gambar']);
            }
            
            $newImage = $this->processImageUpload();
            if ($newImage) {
                $updateData['gambar'] = $newImage;
            }
        }

        $this->manajemenProfilModel->update($idManajemenprofil, $updateData);

        return $this->setSuccessMessage('Data Manajemen Profil Berhasil Di Update', '/manajemenprofils');
    }

    /**
     * Delete management profile
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $manajemenProfil = $this->manajemenProfilModel->find($id);

        if (!$manajemenProfil) {
            return $this->jsonError('Data manajemen profil tidak ditemukan', 404);
        }

        // Delete associated image
        if ($manajemenProfil['gambar']) {
            $this->deleteOldImage($manajemenProfil['gambar']);
        }

        $this->manajemenProfilModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}