<?php

namespace App\Controllers\Backend;

use App\Models\Dokter;
use App\Models\Spesialis;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * DokterController handles doctor management functionality
 * 
 * This controller manages doctors including creation, editing, deletion, and display
 * with image handling and relationship management with specializations.
 */
class DokterController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instances
    private Dokter $dokterModel;
    private Spesialis $spesialisModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->dokterModel = new Dokter();
        $this->spesialisModel = new Spesialis();
    }

    /**
     * Display doctor index page
     */
    public function index()
    {
        $data = ['title' => 'Dokter'];
        return view('backend/dokter/index', $data);
    }

    /**
     * Display doctor creation form
     */
    public function create()
    {
        $data = [
            'spesialis' => $this->spesialisModel
                ->where('status', self::STATUS_ACTIVE)
                ->orderBy('nama', 'asc')
                ->findAll()
        ];
        return view('backend/dokter/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->dokterModel->getDokter();

        return DataTable::of($builder)
            ->edit('nip', function ($row) {
                return '<span class="badge badge-primary text-white">' . esc($row->nip) . '</span>';
            })
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('gambar', function ($row) {
                return $this->formatImageColumn($row->gambar);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->iddokter, $row->nama_dokter);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === self::STATUS_ACTIVE) {
            return '<span class="badge badge-success">Aktif</span>';
        }
        
        return '<span class="badge badge-warning">Tidak Aktif</span>';
    }

    /**
     * Format image column
     */
    private function formatImageColumn(?string $gambar): string
    {
        if ($gambar) {
            $imageUrl = base_url('dokter/' . $gambar);
            return '<a href="' . $imageUrl . '" target="_blank" rel="noopener noreferrer">
                <img src="' . $imageUrl . '" width="110" height="90" alt="Doctor Photo">
            </a>';
        }
        
        return '';
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
     * Get doctor validation rules
     */
    private function getDoctorValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'spesialis_id' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Nama spesialis harus dipilih',
                    'integer' => 'Spesialis harus berupa angka',
                    'greater_than' => 'Spesialis harus dipilih'
                ]
            ],
            'nip' => [
                'label' => 'NIP Dokter',
                'rules' => 'required|min_length[8]|max_length[20]',
                'errors' => [
                    'required' => 'NIP dokter harus diisi',
                    'min_length' => 'NIP minimal 8 karakter',
                    'max_length' => 'NIP maksimal 20 karakter'
                ]
            ],
            'nama' => [
                'label' => 'Nama Dokter',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama dokter harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Dokter',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar dokter harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Dokter',
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
            $namaFoto = "Dokter_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'dokter', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('dokter/' . $namaFoto, [
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
        if ($imagePath && file_exists(FCPATH . 'dokter/' . $imagePath)) {
            $this->deleteFile('dokter/' . $imagePath);
        }
    }

    /**
     * Save new doctor
     */
    public function save()
    {
        $data = $this->getFormData(['spesialis_id', 'nip', 'nama', 'keterangan', 'status']);

        $rules = $this->getDoctorValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'spesialis_id', 'nip', 'nama', 'keterangan', 'gambar'
            ]);
        }

        $newImage = $this->processImageUpload();
        if (!$newImage) {
            session()->setFlashdata('error_gambar', 'Gambar dokter harus diisi');
            return redirect()->back()->withInput();
        }

        $this->dokterModel->insert([
            'spesialis_id' => $data['spesialis_id'],
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'gambar' => $newImage,
            'thumbnail' => $newImage,
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Dokter Berhasil Ditambahkan', '/dokters');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'spesialis' => $this->spesialisModel
                ->where('status', self::STATUS_ACTIVE)
                ->orderBy('nama', 'asc')
                ->findAll(),
            'dokters' => $this->dokterModel->find($id)
        ];
        return view('backend/dokter/edit', $data);
    }

    /**
     * Update existing doctor
     */
    public function update()
    {
        $data = $this->getFormData(['iddokter', 'spesialis_id', 'nip', 'nama', 'keterangan', 'status']);
        $idDokter = $data['iddokter'];

        $gambar = $this->request->getFile('gambar');
        $requireImage = $gambar->isValid() && !$gambar->hasMoved();
        
        $rules = $this->getDoctorValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'spesialis_id', 'nip', 'nama', 'keterangan', 'gambar'
            ]);
        }

        $updateData = [
            'spesialis_id' => $data['spesialis_id'],
            'nip' => $data['nip'],
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle image update
        if ($requireImage) {
            $existingDoctor = $this->dokterModel->find($idDokter);
            if ($existingDoctor && $existingDoctor['gambar']) {
                $this->deleteOldImage($existingDoctor['gambar']);
            }
            
            $newImage = $this->processImageUpload();
            if ($newImage) {
                $updateData['gambar'] = $newImage;
                $updateData['thumbnail'] = $newImage;
            }
        }

        $this->dokterModel->update($idDokter, $updateData);

        return $this->setSuccessMessage('Data Dokter Berhasil Di Update', '/dokters');
    }

    /**
     * Delete doctor
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $dokter = $this->dokterModel->find($id);

        if (!$dokter) {
            return $this->jsonError('Data dokter tidak ditemukan', 404);
        }

        // Delete associated image
        if ($dokter['gambar']) {
            $this->deleteOldImage($dokter['gambar']);
        }

        $this->dokterModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}