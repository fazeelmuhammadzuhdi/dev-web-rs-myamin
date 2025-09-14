<?php

namespace App\Controllers\Backend;

use App\Models\Referensi;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * ReferensiController handles reference management functionality
 * 
 * This controller manages reference materials including books, articles,
 * and other resources with image handling and link management.
 */
class ReferensiController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 1024; // 1MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private Referensi $referensiModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->referensiModel = new Referensi();
    }

    /**
     * Display reference index page
     */
    public function index()
    {
        $data = ['title' => 'Referensi'];
        return view('backend/referensi/index', $data);
    }

    /**
     * Display reference creation form
     */
    public function create()
    {
        return view('backend/referensi/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->referensiModel->getDataReferensi();
        
        return DataTable::of($builder)
            ->edit('gambar', function ($row) {
                return $this->formatImageColumn($row->gambar);
            })
            ->edit('link', function ($row) {
                return $this->formatLinkColumn($row->link);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idreferensi, $row->judul);
            }, 'last')
            ->toJson();
    }

    /**
     * Format image column
     */
    private function formatImageColumn(?string $gambar): string
    {
        if ($gambar) {
            $imageUrl = base_url('referensi/' . $gambar);
            return '<a href="' . $imageUrl . '" target="_blank">
                <img src="' . $imageUrl . '" width="100" height="100" alt="Reference Image">
            </a>';
        }
        
        return '';
    }

    /**
     * Format link column
     */
    private function formatLinkColumn(?string $link): string
    {
        if ($link) {
            return '<a href="' . esc($link) . '" target="_blank" rel="noopener noreferrer">' . esc($link) . '</a>';
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
     * Get reference validation rules
     */
    private function getReferenceValidationRules(bool $requireImage = false): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 255 karakter'
                ]
            ],
            'pengarang' => [
                'label' => 'Nama Pengarang',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama pengarang harus diisi',
                    'min_length' => 'Nama pengarang minimal 3 karakter',
                    'max_length' => 'Nama pengarang maksimal 100 karakter'
                ]
            ],
            'bahasa' => [
                'label' => 'Bahasa',
                'rules' => 'required|in_list[Indonesia,English,Other]',
                'errors' => [
                    'required' => 'Bahasa harus diisi',
                    'in_list' => 'Bahasa harus Indonesia, English, atau Other'
                ]
            ],
            'kategori' => [
                'label' => 'Kategori',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Kategori harus diisi',
                    'min_length' => 'Kategori minimal 3 karakter',
                    'max_length' => 'Kategori maksimal 50 karakter'
                ]
            ],
            'penerbit' => [
                'label' => 'Penerbit',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Penerbit harus diisi',
                    'min_length' => 'Penerbit minimal 3 karakter',
                    'max_length' => 'Penerbit maksimal 100 karakter'
                ]
            ],
            'tahun' => [
                'label' => 'Tahun',
                'rules' => 'required|integer|greater_than[1900]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required' => 'Tahun harus diisi',
                    'integer' => 'Tahun harus berupa angka',
                    'greater_than' => 'Tahun harus lebih dari 1900',
                    'less_than_equal_to' => 'Tahun tidak boleh lebih dari tahun sekarang'
                ]
            ],
            'deskripsi' => [
                'label' => 'Deskripsi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Deskripsi harus diisi',
                    'min_length' => 'Deskripsi minimal 10 karakter'
                ]
            ],
            'link' => [
                'label' => 'Link Referensi',
                'rules' => 'required|valid_url',
                'errors' => [
                    'required' => 'Link referensi harus diisi',
                    'valid_url' => 'Format URL tidak valid'
                ]
            ]
        ];

        if ($requireImage) {
            $rules['gambar'] = [
                'label' => 'Gambar Referensi',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'Gambar referensi harus diisi',
                    'max_size' => 'Ukuran gambar maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format gambar harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Referensi',
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
            $namaFoto = "Referensi_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'referensi', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('referensi/' . $namaFoto, [
                'width' => 300,
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
        if ($imagePath && file_exists(FCPATH . 'referensi/' . $imagePath)) {
            $this->deleteFile('referensi/' . $imagePath);
        }
    }

    /**
     * Save new reference
     */
    public function save()
    {
        $data = $this->getFormData([
            'judul', 'pengarang', 'bahasa', 'kategori', 'penerbit', 
            'tahun', 'deskripsi', 'link'
        ]);

        $rules = $this->getReferenceValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'pengarang', 'bahasa', 'kategori', 'penerbit', 
                'tahun', 'deskripsi', 'link', 'gambar'
            ]);
        }

        $newImage = $this->processImageUpload();
        if (!$newImage) {
            session()->setFlashdata('error_gambar', 'Gambar referensi harus diisi');
            return redirect()->back()->withInput();
        }

        $this->referensiModel->insert([
            'judul' => $data['judul'],
            'pengarang' => $data['pengarang'],
            'bahasa' => $data['bahasa'],
            'kategori' => $data['kategori'],
            'penerbit' => $data['penerbit'],
            'tahun' => $data['tahun'],
            'deskripsi' => $data['deskripsi'],
            'link' => $data['link'],
            'gambar' => $newImage,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Referensi Berhasil Ditambahkan', '/referensis');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['referensi' => $this->referensiModel->find($id)];
        return view('backend/referensi/edit', $data);
    }

    /**
     * Update existing reference
     */
    public function update()
    {
        $data = $this->getFormData([
            'idreferensi', 'judul', 'pengarang', 'bahasa', 'kategori', 
            'penerbit', 'tahun', 'deskripsi', 'link'
        ]);
        $idReferensi = $data['idreferensi'];

        $gambar = $this->request->getFile('gambar');
        $requireImage = $gambar->isValid() && !$gambar->hasMoved();
        
        $rules = $this->getReferenceValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'pengarang', 'bahasa', 'kategori', 'penerbit', 
                'tahun', 'deskripsi', 'link', 'gambar'
            ]);
        }

        $updateData = [
            'judul' => $data['judul'],
            'pengarang' => $data['pengarang'],
            'bahasa' => $data['bahasa'],
            'kategori' => $data['kategori'],
            'penerbit' => $data['penerbit'],
            'tahun' => $data['tahun'],
            'deskripsi' => $data['deskripsi'],
            'link' => $data['link'],
        ];

        // Handle image update
        if ($requireImage) {
            $existingReferensi = $this->referensiModel->find($idReferensi);
            if ($existingReferensi && $existingReferensi['gambar']) {
                $this->deleteOldImage($existingReferensi['gambar']);
            }
            
            $newImage = $this->processImageUpload();
            if ($newImage) {
                $updateData['gambar'] = $newImage;
            }
        }

        $this->referensiModel->update($idReferensi, $updateData);

        return $this->setSuccessMessage('Data Referensi Berhasil Di Update', '/referensis');
    }

    /**
     * Delete reference
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $referensi = $this->referensiModel->find($id);

        if (!$referensi) {
            return $this->jsonError('Data referensi tidak ditemukan', 404);
        }

        // Delete associated image
        if ($referensi['gambar']) {
            $this->deleteOldImage($referensi['gambar']);
        }

        $this->referensiModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}