<?php

namespace App\Controllers\Backend;

use App\Models\IndikatorMutu;
use App\Models\IndikatorMutuList;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * IndikatorMutuListController handles quality indicator list management functionality
 * 
 * This controller manages quality indicator lists including creation, editing, deletion, and display
 * with image handling and relationship management with quality indicators.
 */
class IndikatorMutuListController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instances
    private IndikatorMutuList $indikatorMutuListModel;
    private IndikatorMutu $indikatorMutuModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->indikatorMutuListModel = new IndikatorMutuList();
        $this->indikatorMutuModel = new IndikatorMutu();
    }

    /**
     * Display quality indicator list index page
     */
    public function index()
    {
        $data = ['title' => 'Indikator Mutu List'];
        return view('backend/indikatormutulist/index', $data);
    }

    /**
     * Display quality indicator list creation form
     */
    public function create()
    {
        $data = ['indikatormutu' => $this->indikatorMutuModel->findAll()];
        return view('backend/indikatormutulist/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->indikatorMutuListModel->getIndikatorMutuList();

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
                return $this->formatActionButtons($row->idindikatormutulist, $row->nama);
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
            $imageUrl = base_url('indikatormutulist/' . $gambar);
            return '<a href="' . $imageUrl . '" target="_blank" rel="noopener noreferrer">
                <img src="' . $imageUrl . '" width="200" height="60" alt="Quality Indicator Image">
            </a>';
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
     * Get quality indicator list validation rules
     */
    private function getQualityIndicatorListValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'indikator_mutu_id' => [
                'label' => 'Nama Indikator Mutu',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Nama indikator mutu harus diisi',
                    'integer' => 'Indikator mutu harus berupa angka',
                    'greater_than' => 'Indikator mutu harus dipilih'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Indikator Mutu List',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan indikator mutu list harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Indikator Mutu List',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar indikator mutu list harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Indikator Mutu List',
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
            $namaFoto = "Indikatormutulist_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'indikatormutulist', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('indikatormutulist/' . $namaFoto, [
                'width' => 400,
                'height' => 300,
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
        if ($imagePath && file_exists(FCPATH . 'indikatormutulist/' . $imagePath)) {
            $this->deleteFile('indikatormutulist/' . $imagePath);
        }
    }

    /**
     * Save new quality indicator list
     */
    public function save()
    {
        $data = $this->getFormData(['indikator_mutu_id', 'keterangan', 'status']);

        $rules = $this->getQualityIndicatorListValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['indikator_mutu_id', 'keterangan', 'gambar']);
        }

        $newImage = $this->processImageUpload();
        if (!$newImage) {
            session()->setFlashdata('error_gambar', 'Gambar indikator mutu list harus diisi');
            return redirect()->back()->withInput();
        }

        $this->indikatorMutuListModel->insert([
            'indikator_mutu_id' => $data['indikator_mutu_id'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'gambar' => $newImage,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Indikatormutulist Berhasil Ditambahkan', '/indikatormutulists');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'indikatormutu' => $this->indikatorMutuModel->findAll(),
            'indikatormutulists' => $this->indikatorMutuListModel->find($id)
        ];
        return view('backend/indikatormutulist/edit', $data);
    }

    /**
     * Update existing quality indicator list
     */
    public function update()
    {
        $data = $this->getFormData(['idindikatormutulist', 'indikator_mutu_id', 'keterangan', 'status']);
        $idIndikatormutulist = $data['idindikatormutulist'];

        $gambar = $this->request->getFile('gambar');
        $requireImage = $gambar->isValid() && !$gambar->hasMoved();
        
        $rules = $this->getQualityIndicatorListValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['indikator_mutu_id', 'keterangan', 'gambar']);
        }

        $updateData = [
            'indikator_mutu_id' => $data['indikator_mutu_id'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        // Handle image update
        if ($requireImage) {
            $existingList = $this->indikatorMutuListModel->find($idIndikatormutulist);
            if ($existingList && $existingList['gambar']) {
                $this->deleteOldImage($existingList['gambar']);
            }
            
            $newImage = $this->processImageUpload();
            if ($newImage) {
                $updateData['gambar'] = $newImage;
            }
        }

        $this->indikatorMutuListModel->update($idIndikatormutulist, $updateData);

        return $this->setSuccessMessage('Data Indikator Mutu List Berhasil Di Update', '/indikatormutulists');
    }

    /**
     * Delete quality indicator list
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $indikatorMutuList = $this->indikatorMutuListModel->find($id);

        if (!$indikatorMutuList) {
            return $this->jsonError('Data indikator mutu list tidak ditemukan', 404);
        }

        // Delete associated image
        if ($indikatorMutuList['gambar']) {
            $this->deleteOldImage($indikatorMutuList['gambar']);
        }

        $this->indikatorMutuListModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}