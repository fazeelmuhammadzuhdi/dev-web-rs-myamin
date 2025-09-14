<?php

namespace App\Controllers\Backend;

use App\Models\Banner;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * BannerController handles banner management functionality
 * 
 * This controller manages banner creation, editing, deletion, and display
 * with proper validation, image handling, and status management.
 */
class BannerController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private Banner $bannerModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->bannerModel = new Banner();
    }

    /**
     * Display banner index page
     */
    public function index()
    {
        $data = ['title' => 'Banner'];
        return view('backend/banner/index', $data);
    }

    /**
     * Display banner creation form
     */
    public function create()
    {
        return view('backend/banner/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->bannerModel->select('idbanner,judul,link,gambar,status');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('gambar', function ($row) {
                return $this->formatImageColumn($row->gambar);
            })
            ->edit('link', function ($row) {
                return $this->formatLinkColumn($row->link);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idbanner, $row->judul);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === self::STATUS_PUBLISHED) {
            return '<span class="badge badge-success">Publish</span>';
        }
        
        return '<span class="badge badge-danger">Belum Publish</span>';
    }

    /**
     * Format image column
     */
    private function formatImageColumn(?string $gambar): string
    {
        if ($gambar !== null) {
            $altText = generateAltText($gambar, 'Banner');
            return generateImageWithFallback('banner/' . $gambar, $altText, [
                'width' => 180,
                'height' => 80,
                'class' => 'img-thumbnail'
            ]);
        }
        
        return '';
    }

    /**
     * Format link column
     */
    private function formatLinkColumn(?string $link): string
    {
        if ($link !== null) {
            return generateInternalLinks($link, $link, [
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
                'class' => 'text-truncate d-inline-block',
                'style' => 'max-width: 200px;'
            ]);
        }
        
        return '';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $judul): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($judul) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }


    /**
     * Get banner validation rules
     */
    private function getBannerValidationRules(bool $isCreate = true): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul Banner',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul banner harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'link' => [
                'label' => 'Link Banner',
                'rules' => 'required|valid_url',
                'errors' => [
                    'required' => 'Link banner harus diisi',
                    'valid_url' => 'Format link tidak valid'
                ]
            ]
        ];

        if ($isCreate) {
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
    private function processImageUpload(): array
    {
        $fileFoto = $this->request->getFile('gambar');
        $namaFoto = "Banner_" . $fileFoto->getRandomName();
        
        // Move file to destination
        $fileFoto->move(FCPATH . 'banner', $namaFoto);
        
        // Optimize image
        $optimizedImage = optimizeImageForWeb('banner/' . $namaFoto, [
            'width' => 1920,
            'height' => 600,
            'quality' => 85
        ]);
        
        return [
            'gambar' => $namaFoto,
            'optimized' => $optimizedImage['optimized']
        ];
    }

    /**
     * Save new banner
     */
    public function save()
    {
        $data = $this->getFormData(['judul', 'link', 'status']);

        $rules = $this->getBannerValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'link', 'gambar']);
        }

        $imageData = $this->processImageUpload();

        $this->bannerModel->insert([
            'judul' => $data['judul'],
            'link' => $data['link'],
            'status' => $data['status'] ?: self::STATUS_PUBLISHED,
            'gambar' => $imageData['gambar'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Banner Berhasil Ditambahkan', '/banners');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['banners' => $this->bannerModel->find($id)];
        return view('backend/banner/edit', $data);
    }

    /**
     * Delete old images
     */
    private function deleteOldImages(string $imagePath): void
    {
        if ($imagePath && file_exists(FCPATH . 'banner/' . $imagePath)) {
            $this->deleteFile('banner/' . $imagePath);
        }
    }

    /**
     * Update existing banner
     */
    public function update()
    {
        $data = $this->getFormData(['idbanner', 'judul', 'link', 'status']);
        $idBanner = $data['idbanner'];
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getBannerValidationRules(false);

        // Add image validation if new image is uploaded
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar'] = [
                'label' => 'Gambar Banner',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar banner harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'link', 'gambar']);
        }

        $updateData = [
            'judul' => $data['judul'],
            'link' => $data['link'],
            'status' => $data['status'] ?: self::STATUS_PUBLISHED,
        ];

        // Handle image update
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $existingBanner = $this->bannerModel->find($idBanner);
            
            // Delete old image
            if ($existingBanner && $existingBanner['gambar']) {
                $this->deleteOldImages($existingBanner['gambar']);
            }

            // Process new image
            $imageData = $this->processImageUpload();
            $updateData['gambar'] = $imageData['gambar'];
        }

        $this->bannerModel->update($idBanner, $updateData);

        return $this->setSuccessMessage('Data Banner Berhasil Di Update', '/banners');
    }

    /**
     * Delete banner
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $banner = $this->bannerModel->find($id);

        if (!$banner) {
            return $this->jsonError('Banner tidak ditemukan', 404);
        }

        // Delete associated image
        if ($banner['gambar']) {
            $this->deleteOldImages($banner['gambar']);
        }

        // Delete from database
        $this->bannerModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}
