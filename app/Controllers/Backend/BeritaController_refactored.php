<?php

namespace App\Controllers\Backend;

use App\Models\Berita;
use App\Models\Kategori;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * BeritaController handles news management functionality
 * 
 * This controller manages news creation, editing, deletion, and display
 * with proper validation and file handling.
 * 
 * REFACTORED: Now uses protected methods from BaseController for better code reusability
 */
class BeritaController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    private const STATUS_ACTIVE = 'Y';
    
    // Model instances
    private Berita $beritaModel;
    private Kategori $kategoriModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->beritaModel = new Berita();
        $this->kategoriModel = new Kategori();
        helper('slug');
    }

    /**
     * Display news index page
     */
    public function index()
    {
        $data = ['title' => 'Berita'];
        return view('backend/berita/index', $data);
    }

    /**
     * Display news creation form
     */
    public function create()
    {
        $data = [
            'kategori' => $this->kategoriModel
                ->where('status', self::STATUS_ACTIVE)
                ->findAll()
        ];
        
        return view('backend/berita/create', $data);
    }

    /**
     * Get data for DataTable
     * REFACTORED: Now uses protected methods from BaseController
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->beritaModel->getBerita();

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                // ✅ Using protected method from BaseController
                return $this->formatStatusBadge($row->status, [
                    'PB' => ['class' => 'badge-success', 'text' => 'Publish'],
                    'DR' => ['class' => 'badge-warning', 'text' => 'Draft']
                ]);
            })
            ->edit('nama', function ($row) {
                // ✅ Using protected method from BaseController
                return $this->formatUserBadge($row->nama);
            })
            ->edit('title', function ($row) {
                // ✅ Using protected method from BaseController with custom styling
                return $this->formatCategoryBadge($row->title);
            })
            ->edit('gambar', function ($row) {
                // ✅ Using protected method from BaseController
                return $this->formatImageColumn($row->gambar, 150, 100);
            })
            ->edit('tanggal', function ($row) {
                return '<span class="text-nowrap">' . tanggal_indonesia($row->tanggal) . '</span>';
            })
            ->add('action', function ($row) {
                // ✅ Using protected method from BaseController
                return $this->formatActionButtons($row->idberita, $row->judul);
            }, 'last')
            ->toJson();
    }

    /**
     * Save new news article
     * REFACTORED: Now uses protected methods from BaseController
     */
    public function save()
    {
        $data = $this->getFormData(['judul', 'tanggal', 'konten', 'kategori_id', 'status']);
        $data['user_id'] = $this->getCurrentUserId();
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getValidationRules(true); // true for create (image required)

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'tanggal', 'konten', 'kategori_id', 'gambar']);
        }

        $insertData = [
            'judul' => $data['judul'],
            'user_id' => $data['user_id'],
            'tanggal' => $data['tanggal'],
            'konten' => $data['konten'],
            'kategori_id' => $data['kategori_id'],
            'status' => $data['status'] ?: self::STATUS_DRAFT,
            'slug' => createSlug($data['judul']),
        ];

        // Handle image upload using BaseController method
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            if ($this->moveUploadedFile($gambar, 'berita')) {
                $insertData['gambar'] = $gambar->getName();
            }
        }

        $this->beritaModel->insert($insertData);

        return $this->setSuccessMessage('Berita berhasil ditambahkan', '/beritas');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'berita' => $this->beritaModel->find($id),
            'kategori' => $this->kategoriModel
                ->where('status', self::STATUS_ACTIVE)
                ->findAll()
        ];
        
        return view('backend/berita/edit', $data);
    }

    /**
     * Update existing news article
     * REFACTORED: Now uses protected methods from BaseController
     */
    public function update()
    {
        $idBerita = $this->request->getVar('idberita');
        $data = $this->getFormData(['judul', 'tanggal', 'konten', 'kategori_id', 'status']);
        $data['user_id'] = $this->getCurrentUserId();
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getValidationRules(false); // false for update (image optional)

        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar']['rules'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png]|max_size[gambar,' . self::MAX_FILE_SIZE . ']';
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'tanggal', 'konten', 'kategori_id']);
        }

        $updateData = [
            'judul' => $data['judul'],
            'user_id' => $data['user_id'],
            'tanggal' => $data['tanggal'],
            'konten' => $data['konten'],
            'kategori_id' => $data['kategori_id'],
            'status' => $data['status'],
            'slug' => createSlug($data['judul']),
        ];

        // Handle image update using BaseController method
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Delete old image
            $this->deleteOldImages($idBerita);
            
            // Upload new image
            if ($this->moveUploadedFile($gambar, 'berita')) {
                $updateData['gambar'] = $gambar->getName();
            }
        }

        $this->beritaModel->update($idBerita, $updateData);

        return $this->setSuccessMessage('Berita berhasil diupdate', '/beritas');
    }

    /**
     * Delete news article
     * REFACTORED: Now uses protected methods from BaseController
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $berita = $this->beritaModel->find($id);

        if (!$berita) {
            return $this->jsonError('Berita tidak ditemukan', 404);
        }

        // Delete associated images
        $this->deleteOldImages($id);

        $this->beritaModel->delete($id);

        return $this->jsonSuccess('Berita berhasil dihapus');
    }

    /**
     * Get validation rules for news
     * REFACTORED: Now uses protected methods from BaseController
     */
    private function getValidationRules(bool $isCreate = false): array
    {
        $commonRules = $this->getCommonValidationRules();
        $commonErrors = $this->getCommonErrorMessages();

        $rules = [
            'judul' => [
                'label' => 'Judul Berita',
                'rules' => $commonRules['required'] . '|' . $commonRules['min_length_3'] . '|' . $commonRules['max_length_200'],
                'errors' => $commonErrors
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita',
                'rules' => $commonRules['required'],
                'errors' => $commonErrors
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => $commonRules['required'] . '|' . $commonRules['min_length_3'],
                'errors' => $commonErrors
            ],
            'kategori_id' => [
                'label' => 'Kategori',
                'rules' => $commonRules['required'] . '|' . $commonRules['numeric'],
                'errors' => $commonErrors
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar',
                'rules' => 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png]|max_size[gambar,' . self::MAX_FILE_SIZE . ']',
                'errors' => [
                    'uploaded' => 'Gambar harus diupload',
                    'mime_in' => 'Format gambar harus JPEG atau PNG',
                    'max_size' => 'Ukuran gambar maksimal ' . self::MAX_FILE_SIZE . 'KB'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Delete old images when updating
     * REFACTORED: Now uses protected method from BaseController
     */
    private function deleteOldImages(int $idBerita): void
    {
        $berita = $this->beritaModel->find($idBerita);
        
        if ($berita && $berita['gambar']) {
            $imagePath = FCPATH . 'berita/' . $berita['gambar'];
            $this->deleteFile($imagePath);
        }
    }

    /**
     * Custom category badge formatting for BeritaController
     * This overrides the BaseController method with specific styling
     */
    private function formatCategoryBadge(string $title): string
    {
        $badgeClasses = [
            'Agenda' => 'badge-primary',
            'Berita RS' => 'badge-danger',
            'Informasi Asuransi' => 'badge-success'
        ];

        $badgeClass = $badgeClasses[$title] ?? 'badge-secondary';
        
        return '<span class="badge ' . $badgeClass . ' text-nowrap" style="font-size: 13px;">' . htmlspecialchars($title) . '</span>';
    }
}