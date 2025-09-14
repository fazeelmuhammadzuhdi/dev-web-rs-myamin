<?php

namespace App\Controllers\Backend;

use App\Models\Video;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * VideoController handles video management functionality
 * 
 * This controller manages video creation, editing, deletion, and display
 * with proper validation, thumbnail handling, and status management.
 */
class VideoController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private Video $videoModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->videoModel = new Video();
        helper('slug');
    }

    /**
     * Display video index page
     */
    public function index()
    {
        $data = ['title' => 'Video'];
        return view('backend/video/index', $data);
    }

    /**
     * Display video creation form
     */
    public function create()
    {
        return view('backend/video/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->videoModel->select('idvideo,judul,tanggal,link,status,thumbnail')->orderBy('tanggal', 'DESC');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('thumbnail', function ($row) {
                return $this->formatThumbnailColumn($row->thumbnail);
            })
            ->edit('link', function ($row) {
                return $this->formatLinkColumn($row->link, $row->judul);
            })
            ->edit('tanggal', function ($row) {
                return $this->formatDateColumn($row->tanggal);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idvideo, $row->judul);
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
     * Format thumbnail column
     */
    private function formatThumbnailColumn(?string $thumbnail): string
    {
        if ($thumbnail !== null) {
            $imageUrl = base_url('video/' . $thumbnail);
            $altText = generateAltText($thumbnail, 'Video Thumbnail');
            return '<a href="' . $imageUrl . '" target="_blank" rel="noopener noreferrer">' . 
                   generateImageWithFallback('video/' . $thumbnail, $altText, [
                       'width' => 100,
                       'height' => 80,
                       'class' => 'img-thumbnail'
                   ]) . '</a>';
        }
        
        return '';
    }

    /**
     * Format link column
     */
    private function formatLinkColumn(?string $link, string $judul): string
    {
        if ($link !== null) {
            return generateInternalLinks($link, $judul, [
                'target' => '_blank',
                'rel' => 'noopener noreferrer',
                'class' => 'text-truncate d-inline-block',
                'style' => 'max-width: 200px;'
            ]);
        }
        
        return '';
    }

    /**
     * Format date column
     */
    private function formatDateColumn(string $tanggal): string
    {
        return date('d M Y', strtotime($tanggal));
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
     * Get video validation rules
     */
    private function getVideoValidationRules(bool $isCreate = true): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul Video',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul video harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'link' => [
                'label' => 'Link Video',
                'rules' => 'required|valid_url',
                'errors' => [
                    'required' => 'Link video harus diisi',
                    'valid_url' => 'Format link tidak valid'
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Video',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal video harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ]
        ];

        if ($isCreate) {
            $rules['thumbnail'] = [
                'label' => 'Thumbnail Video',
                'rules' => 'max_size[thumbnail,' . self::MAX_FILE_SIZE . ']|mime_in[thumbnail,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran thumbnail maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format thumbnail harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['thumbnail'] = [
                'label' => 'Thumbnail Video',
                'rules' => 'max_size[thumbnail,' . self::MAX_FILE_SIZE . ']|mime_in[thumbnail,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran thumbnail maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format thumbnail harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process thumbnail upload
     */
    private function processThumbnailUpload(): ?string
    {
        $fileThumbnail = $this->request->getFile('thumbnail');
        
        if ($fileThumbnail && $fileThumbnail->isValid() && !$fileThumbnail->hasMoved()) {
            $namaThumbnail = "vidtumb_" . $fileThumbnail->getRandomName();
            $fileThumbnail->move(FCPATH . 'video', $namaThumbnail);
            
            // Optimize thumbnail
            optimizeImageForWeb('video/' . $namaThumbnail, [
                'width' => 300,
                'height' => 200,
                'quality' => 85
            ]);
            
            return $namaThumbnail;
        }
        
        return null;
    }

    /**
     * Save new video
     */
    public function save()
    {
        $data = $this->getFormData(['judul', 'link', 'tanggal', 'status']);

        $rules = $this->getVideoValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'link', 'tanggal', 'thumbnail']);
        }

        $thumbnail = $this->processThumbnailUpload();

        $this->videoModel->insert([
            'judul' => $data['judul'],
            'slug' => createSlug($data['judul']),
            'link' => $data['link'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'] ?: self::STATUS_PUBLISHED,
            'thumbnail' => $thumbnail,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Video Berhasil Ditambahkan', '/videos');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['video' => $this->videoModel->find($id)];
        return view('backend/video/edit', $data);
    }

    /**
     * Delete old thumbnails
     */
    private function deleteOldThumbnails(string $thumbnailPath): void
    {
        if ($thumbnailPath && file_exists(FCPATH . 'video/' . $thumbnailPath)) {
            $this->deleteFile('video/' . $thumbnailPath);
        }
    }

    /**
     * Update existing video
     */
    public function update()
    {
        $data = $this->getFormData(['idvideo', 'judul', 'link', 'tanggal', 'status']);
        $idVideo = $data['idvideo'];

        $rules = $this->getVideoValidationRules(false);

        // Add thumbnail validation if new thumbnail is uploaded
        $thumbnail = $this->request->getFile('thumbnail');
        if ($thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $rules['thumbnail'] = [
                'label' => 'Thumbnail Video',
                'rules' => 'uploaded[thumbnail]|max_size[thumbnail,' . self::MAX_FILE_SIZE . ']|mime_in[thumbnail,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Thumbnail video harus diisi',
                    'max_size' => 'Ukuran thumbnail maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format thumbnail harus JPEG, PNG atau JPG'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'link', 'tanggal', 'thumbnail']);
        }

        $updateData = [
            'judul' => $data['judul'],
            'slug' => createSlug($data['judul']),
            'link' => $data['link'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'] ?: self::STATUS_PUBLISHED,
        ];

        // Handle thumbnail update
        if ($thumbnail->isValid() && !$thumbnail->hasMoved()) {
            $existingVideo = $this->videoModel->find($idVideo);
            
            // Delete old thumbnail
            if ($existingVideo && $existingVideo['thumbnail']) {
                $this->deleteOldThumbnails($existingVideo['thumbnail']);
            }

            // Process new thumbnail
            $newThumbnail = $this->processThumbnailUpload();
            $updateData['thumbnail'] = $newThumbnail;
        }

        $this->videoModel->update($idVideo, $updateData);

        return $this->setSuccessMessage('Data Video Berhasil Di Update', '/videos');
    }

    /**
     * Delete video
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $video = $this->videoModel->find($id);

        if (!$video) {
            return $this->jsonError('Video tidak ditemukan', 404);
        }

        // Delete associated thumbnail
        if ($video['thumbnail']) {
            $this->deleteOldThumbnails($video['thumbnail']);
        }

        // Delete from database
        $this->videoModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}