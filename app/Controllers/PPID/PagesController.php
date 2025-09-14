<?php

namespace App\Controllers\PPID;

use App\Models\PagesPPID;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * PagesController handles PPID pages management functionality
 * 
 * This controller manages PPID pages including creation, editing, deletion, and display
 * with image handling and content management.
 */
class PagesController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private PagesPPID $pagesModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->pagesModel = new PagesPPID();
        helper('slug');
    }

    /**
     * Display pages index page
     */
    public function index()
    {
        $data = ['title' => 'Pages'];
        return view('backend/pages/index', $data);
    }

    /**
     * Display pages creation form
     */
    public function create()
    {
        return view('backend/pages/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->pagesModel->select('idpages,title,konten,status');

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('konten', function ($row) {
                return $this->formatContentColumn($row->konten);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpages, $row->title);
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
        
        return '<span class="badge badge-danger">Tidak Aktif</span>';
    }

    /**
     * Format content column
     */
    private function formatContentColumn(?string $konten): string
    {
        if ($konten) {
            // Strip HTML tags and limit length
            $text = strip_tags($konten);
            return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
        }
        
        return '-';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $title): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($title) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get pages validation rules
     */
    private function getPagesValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'title' => [
                'label' => 'Judul Pages',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul pages harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'konten' => [
                'label' => 'Konten Pages',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Konten pages harus diisi',
                    'min_length' => 'Konten minimal 10 karakter'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Banner',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar banner harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Banner',
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
            $namaFoto = "ppid_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'frontend/images/ppid', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('frontend/images/ppid/' . $namaFoto, [
                'width' => 800,
                'height' => 600,
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
        if ($imagePath && file_exists(FCPATH . 'frontend/images/ppid/' . $imagePath)) {
            $this->deleteFile('frontend/images/ppid/' . $imagePath);
        }
    }

    /**
     * Save new page
     */
    public function save()
    {
        $data = $this->getFormData(['title', 'konten', 'status']);

        $rules = $this->getPagesValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['title', 'konten', 'gambar']);
        }

        $newImage = $this->processImageUpload();
        if (!$newImage) {
            session()->setFlashdata('error_gambar', 'Gambar banner harus diisi');
            return redirect()->back()->withInput();
        }

        $this->pagesModel->insert([
            'title' => $data['title'],
            'konten' => $data['konten'],
            'slug' => createSlug($data['title']),
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'gambar' => $newImage,
        ]);

        return $this->setSuccessMessage('Data Pages Berhasil Ditambahkan', '/page');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['page' => $this->pagesModel->find($id)];
        return view('backend/pages/edit', $data);
    }

    /**
     * Update existing page
     */
    public function update()
    {
        $data = $this->getFormData(['idpages', 'title', 'konten', 'status']);
        $idPages = $data['idpages'];

        $gambar = $this->request->getFile('gambar');
        $requireImage = $gambar->isValid() && !$gambar->hasMoved();
        
        $rules = $this->getPagesValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['title', 'konten', 'gambar']);
        }

        $updateData = [
            'title' => $data['title'],
            'konten' => $data['konten'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['title']),
        ];

        // Handle image update
        if ($requireImage) {
            $existingPage = $this->pagesModel->find($idPages);
            if ($existingPage && $existingPage['gambar']) {
                $this->deleteOldImage($existingPage['gambar']);
            }
            
            $newImage = $this->processImageUpload();
            if ($newImage) {
                $updateData['gambar'] = $newImage;
            }
        }

        $this->pagesModel->update($idPages, $updateData);

        return $this->setSuccessMessage('Data Berhasil Di Update', '/page');
    }

    /**
     * Delete page
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $page = $this->pagesModel->find($id);

        if (!$page) {
            return $this->jsonError('Data page tidak ditemukan', 404);
        }

        // Delete associated image
        if ($page['gambar']) {
            $this->deleteOldImage($page['gambar']);
        }

        $this->pagesModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}