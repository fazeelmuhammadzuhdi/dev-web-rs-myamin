<?php

namespace App\Controllers\Backend;

use App\Models\Album;
use App\Models\AlbumList;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * AlbumListController handles album list management functionality
 * 
 * This controller manages album lists including creation, editing, deletion, and display
 * with multiple image handling and relationship management with albums.
 */
class AlbumListController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_UNPUBLISHED = 'NP';
    
    // Model instances
    private AlbumList $albumListModel;
    private Album $albumModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->albumListModel = new AlbumList();
        $this->albumModel = new Album();
    }

    /**
     * Display album list index page
     */
    public function index()
    {
        $data = ['title' => 'Album List'];
        return view('backend/albumlist/index', $data);
    }

    /**
     * Display album list creation form
     */
    public function create()
    {
        $data = [
            'album' => $this->albumModel->orderBy('idalbum', 'desc')->findAll()
        ];
        return view('backend/albumlist/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->albumListModel->getAlbumList();

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('tanggal', function ($row) {
                return date('d M Y', strtotime($row->tanggal));
            })
            ->edit('nama', function ($row) {
                return $this->formatNameBadge($row->nama);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idalbumlist, $row->judul);
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
     * Format name badge
     */
    private function formatNameBadge(?string $nama): string
    {
        if ($nama) {
            return '<span class="badge badge-info" style="font-size: 14px;">' . esc($nama) . '</span>';
        }
        
        return '<span class="badge badge-warning" style="font-size: 14px;">Administrator</span>';
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
            <button type="button" class="btn btn-round btn-primary mx-1" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
            <button type="button" class="btn btn-round btn-info mx-1" title="Detail Data" onclick="detail(\'' . $id . '\')">
                <i class="feather icon-info"></i>
            </button>
        </div>';
    }

    /**
     * Get album list validation rules
     */
    private function getAlbumListValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'album_id' => [
                'label' => 'Nama Album',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Nama album harus dipilih',
                    'integer' => 'Album harus berupa angka',
                    'greater_than' => 'Album harus dipilih'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Albumlist',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan albumlist harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Albumlist',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar albumlist harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Albumlist',
                'rules' => 'max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process multiple image uploads
     */
    private function processMultipleImageUploads(): array
    {
        $files = $this->request->getFiles('gambar');
        $uploadedFiles = [];

        if (isset($files['gambar']) && is_array($files['gambar'])) {
            foreach ($files['gambar'] as $file) {
                if ($file->isValid() && in_array($file->getClientMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
                    $namaFoto = "Albumlist_" . $file->getRandomName();
                    $file->move(FCPATH . 'albumlist', $namaFoto);
                    
                    // Optimize image
                    optimizeImageForWeb('albumlist/' . $namaFoto, [
                        'width' => 800,
                        'height' => 600,
                        'quality' => 85
                    ]);
                    
                    $uploadedFiles[] = $namaFoto;
                }
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete old images
     */
    private function deleteOldImages(string $imageString): void
    {
        if ($imageString) {
            $imageArray = explode(',', $imageString);
            foreach ($imageArray as $image) {
                $imagePath = FCPATH . 'albumlist/' . trim($image);
                if (file_exists($imagePath) && !is_dir($imagePath)) {
                    $this->deleteFile('albumlist/' . trim($image));
                }
            }
        }
    }

    /**
     * Save new album list
     */
    public function save()
    {
        $data = $this->getFormData(['album_id', 'keterangan']);

        $rules = $this->getAlbumListValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['album_id', 'keterangan', 'gambar']);
        }

        $uploadedFiles = $this->processMultipleImageUploads();
        if (empty($uploadedFiles)) {
            session()->setFlashdata('error_gambar', 'Gambar albumlist harus diisi');
            return redirect()->back()->withInput();
        }

        // Insert each image as separate record
        foreach ($uploadedFiles as $fileName) {
            $this->albumListModel->insert([
                'album_id' => $data['album_id'],
                'keterangan' => $data['keterangan'],
                'gambar' => $fileName,
                'thumbnail' => $fileName,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->setSuccessMessage('Data Album List Berhasil Ditambahkan', '/albumlists');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'album' => $this->albumModel->orderBy('idalbum', 'desc')->findAll(),
            'albumlists' => $this->albumListModel->find($id)
        ];
        return view('backend/albumlist/edit', $data);
    }

    /**
     * Display detail page
     */
    public function detail($id = null)
    {
        $albumList = $this->albumListModel->find($id);
        $data = [
            'albumlists' => $albumList,
            'title' => 'Detail Album List',
            'foto' => $this->albumListModel->where('album_id', $albumList['album_id'])->findAll()
        ];

        return view('backend/albumlist/detail', $data);
    }

    /**
     * Update existing album list
     */
    public function update()
    {
        $data = $this->getFormData(['idalbumlist', 'album_id', 'keterangan']);
        $idAlbumlist = $data['idalbumlist'];

        $files = $this->request->getFiles();
        $hasNewImages = !empty($files['gambar']);
        
        $rules = $this->getAlbumListValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['album_id', 'keterangan', 'gambar']);
        }

        $updateData = [
            'album_id' => $data['album_id'],
            'keterangan' => $data['keterangan'],
        ];

        // Handle image update
        if ($hasNewImages) {
            $albumList = $this->albumListModel->find($idAlbumlist);
            if ($albumList && $albumList['gambar']) {
                $this->deleteOldImages($albumList['gambar']);
            }

            $uploadedFiles = $this->processMultipleImageUploads();
            if (!empty($uploadedFiles)) {
                $newGambar = implode(',', $uploadedFiles);
                $updateData['gambar'] = $newGambar;
                $updateData['thumbnail'] = $newGambar;
            }
        }

        $this->albumListModel->update($idAlbumlist, $updateData);

        return $this->setSuccessMessage('Data Albumlist Berhasil Di Update', '/albumlists');
    }

    /**
     * Delete album list and all related images
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $albumList = $this->albumListModel->find($id);

        if (!$albumList) {
            return $this->jsonError('Data tidak ditemukan', 404);
        }

        $albumId = $albumList['album_id'];

        // Find all related album lists
        $relatedLists = $this->albumListModel->where('album_id', $albumId)->findAll();

        // Delete all related images
        foreach ($relatedLists as $relatedList) {
            if ($relatedList['gambar']) {
                $this->deleteOldImages($relatedList['gambar']);
            }
        }

        // Delete all related records
        $this->albumListModel->where('album_id', $albumId)->delete();

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Upload single image via AJAX
     */
    public function uploadImage()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'gambar' => [
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|is_image[gambar]|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Tidak ada file yang diupload',
                    'max_size' => 'Ukuran file maksimal adalah ' . self::MAX_FILE_SIZE . 'KB',
                    'is_image' => 'File yang diupload bukan gambar',
                    'mime_in' => 'Format gambar harus JPG, JPEG, atau PNG',
                ],
            ],
        ]);

        if (!$this->validate($validation->getRules())) {
            return $this->jsonError($validation->getError('gambar'));
        }

        $id = $this->request->getPost('id');
        $item = $this->albumListModel->find($id);

        // Delete old image if exists
        if ($item && !empty($item['gambar'])) {
            $this->deleteOldImages($item['gambar']);
        }

        $file = $this->request->getFile('gambar');
        $newName = "Albumlist_" . $file->getRandomName();
        $file->move(FCPATH . 'albumlist', $newName);
        
        // Optimize image
        optimizeImageForWeb('albumlist/' . $newName, [
            'width' => 800,
            'height' => 600,
            'quality' => 85
        ]);

        $filePath = base_url('albumlist/' . $newName);

        // Update database
        $this->albumListModel->update($id, [
            'gambar' => $newName,
            'thumbnail' => $newName
        ]);

        return $this->jsonSuccess('Image uploaded successfully', ['filePath' => $filePath]);
    }

    /**
     * Add images to existing album
     */
    public function tambahGambar()
    {
        $data = $this->getFormData(['album_id']);

        $rules = [
            'gambar' => [
                'label' => 'Gambar Albumlist',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar albumlist harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG, atau JPG'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['gambar']);
        }

        $uploadedFiles = $this->processMultipleImageUploads();
        if (empty($uploadedFiles)) {
            session()->setFlashdata('error_gambar', 'File yang diunggah tidak valid');
            return redirect()->back()->withInput();
        }

        // Insert each image as separate record
        foreach ($uploadedFiles as $fileName) {
            $this->albumListModel->insert([
                'album_id' => $data['album_id'],
                'gambar' => $fileName,
                'thumbnail' => $fileName,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return $this->setSuccessMessage('Data Album List Berhasil Ditambahkan', '/albumlists');
    }

    /**
     * Delete single image via AJAX
     */
    public function hapusGambar()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Akses tidak valid', 403);
        }

        $id = $this->request->getPost('id');
        $data = $this->albumListModel->find($id);

        if (!$data) {
            return $this->jsonError('Data tidak ditemukan', 404);
        }

        // Delete image file
        if ($data['gambar']) {
            $this->deleteOldImages($data['gambar']);
        }

        // Delete from database
        $this->albumListModel->delete($id);

        return $this->jsonSuccess('Gambar berhasil dihapus');
    }
}