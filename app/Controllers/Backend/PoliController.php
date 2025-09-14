<?php

namespace App\Controllers\Backend;

use App\Models\Poli;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * PoliController handles clinic management functionality
 * 
 * This controller manages clinic creation, editing, deletion, and display
 * with proper validation, image handling, and status management.
 */
class PoliController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private Poli $poliModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->poliModel = new Poli();
        helper('slug');
    }

    /**
     * Display clinic index page
     */
    public function index()
    {
        $data = ['title' => 'Poli'];
        return view('backend/poli/index', $data);
    }

    /**
     * Display clinic creation form
     */
    public function create()
    {
        return view('backend/poli/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->poliModel->select('idpoli,nama,keterangan,status,gambar')->orderBy('nama', 'ASC');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('gambar', function ($row) {
                return $this->formatImageColumn($row->gambar);
            })
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpoli, $row->nama);
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
        if ($gambar !== null) {
            $altText = generateAltText($gambar, 'Poli');
            return generateImageWithFallback('polikliniks/' . $gambar, $altText, [
                'width' => 50,
                'height' => 50,
                'class' => 'img-thumbnail'
            ]);
        }
        
        return '';
    }

    /**
     * Format description column
     */
    private function formatDescriptionColumn(?string $keterangan): string
    {
        if ($keterangan) {
            // Strip HTML tags and limit length
            $text = strip_tags($keterangan);
            return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
        }
        
        return '-';
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
     * Get clinic validation rules
     */
    private function getClinicValidationRules(bool $isCreate = true): array
    {
        $rules = [
            'nama' => [
                'label' => 'Nama Poli',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama poli harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Poli',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan poli harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar Poli',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar poli harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Poli',
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
    private function processImageUpload(): string
    {
        $fileFoto = $this->request->getFile('gambar');
        $namaFoto = $fileFoto->getRandomName();
        
        // Move file to destination
        $fileFoto->move(FCPATH . 'polikliniks', $namaFoto);
        
        // Optimize image
        optimizeImageForWeb('polikliniks/' . $namaFoto, [
            'width' => 300,
            'height' => 200,
            'quality' => 85
        ]);
        
        return $namaFoto;
    }

    /**
     * Save new clinic
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status', 'keterangan']);

        $rules = $this->getClinicValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan', 'gambar']);
        }

        $namaFoto = $this->processImageUpload();

        $this->poliModel->insert([
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['nama']),
            'gambar' => $namaFoto,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Poli Berhasil Ditambahkan', '/poly');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['poli' => $this->poliModel->find($id)];
        return view('backend/poli/edit', $data);
    }

    /**
     * Delete old images
     */
    private function deleteOldImages(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'polikliniks/' . $imagePath)) {
            $this->deleteFile('polikliniks/' . $imagePath);
        }
    }

    /**
     * Update existing clinic
     */
    public function update()
    {
        $data = $this->getFormData(['idpoli', 'nama', 'status', 'keterangan']);
        $idPoli = $data['idpoli'];
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getClinicValidationRules(false);

        // Add image validation if new image is uploaded
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar'] = [
                'label' => 'Gambar Poli',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar poli harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan', 'gambar']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['nama']),
        ];

        // Handle image update
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $existingPoli = $this->poliModel->find($idPoli);
            
            // Delete old image
            if ($existingPoli && $existingPoli['gambar']) {
                $this->deleteOldImages($existingPoli['gambar']);
            }

            // Process new image
            $namaFoto = $this->processImageUpload();
            $updateData['gambar'] = $namaFoto;
        }

        $this->poliModel->update($idPoli, $updateData);

        return $this->setSuccessMessage('Data Poli Berhasil Di Update', '/poly');
    }

    /**
     * Delete clinic
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $poli = $this->poliModel->find($id);

        if (!$poli) {
            return $this->jsonError('Data poli tidak ditemukan', 404);
        }

        // Delete associated image
        if ($poli['gambar']) {
            $this->deleteOldImages($poli['gambar']);
        }

        // Delete from database
        $this->poliModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}