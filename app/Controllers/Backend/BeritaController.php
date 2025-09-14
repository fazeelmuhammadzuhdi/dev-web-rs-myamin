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
 */
class BeritaController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    private const STATUS_ACTIVE = 'Y';
    private const MAX_FILE_SIZE = 1024; // 1MB in KB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
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
     */
    public function getData()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        $builder = $this->beritaModel->getBerita();

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('nama', function ($row) {
                return $this->formatUserBadge($row->nama);
            })
            ->edit('title', function ($row) {
                return $this->formatCategoryBadge($row->title);
            })
            ->edit('gambar', function ($row) {
                return $this->formatImageColumn($row->gambar);
            })
            ->edit('tanggal', function ($row) {
                return '<span class="text-nowrap">' . tanggal_indonesia($row->tanggal) . '</span>';
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idberita, $row->judul);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === self::STATUS_PUBLISHED) {
            return '<span class="badge badge-success" style="font-size: 14px;">Publish</span>';
        }
        
        return '<span class="badge badge-danger" style="font-size: 14px;">Belum Publish</span>';
    }

    /**
     * Format user badge
     */
    private function formatUserBadge(?string $nama): string
    {
        if ($nama) {
            return '<span class="badge badge-info" style="font-size: 14px;">' . esc($nama) . '</span>';
        }
        
        return '<span class="badge badge-warning" style="font-size: 14px;">Administrator</span>';
    }

    /**
     * Format category badge
     */
    private function formatCategoryBadge(string $title): string
    {
        $badgeClasses = [
            'Agenda' => 'badge-primary',
            'Berita RS' => 'badge-danger',
            'Informasi Asuransi' => 'badge-success'
        ];

        $badgeClass = $badgeClasses[$title] ?? 'badge-secondary';
        
        return '<span class="badge ' . $badgeClass . ' text-nowrap" style="font-size: 13px;">' . esc($title) . '</span>';
    }

    /**
     * Format image column
     */
    private function formatImageColumn(?string $gambar): string
    {
        if ($gambar === null) {
            return '';
        }

        $imageUrl = base_url('berita/' . $gambar);
        return '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" width="150" height="100"></a>';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $judul): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" judul="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($judul) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" judul="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }


    /**
     * Update existing news article
     */
    public function update()
    {
        $idBerita = $this->request->getVar('idberita');
        $data = $this->getFormData();
        $data['user_id'] = session()->get('idUser') ?? 1;
        $gambar = $this->request->getFile('gambar');

        $rules = $this->getValidationRules(false); // false for update (image optional)

        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $rules['gambar']['rules'] = 'uploaded[gambar]|mime_in[gambar,image/jpeg,image/png]|max_size[gambar,' . self::MAX_FILE_SIZE . ']';
        }

        $validation = \Config\Services::validation();
        $isValid = $validation->withRequest($this->request)->setRules($rules)->run();

        if (!$isValid) {
            return $this->handleValidationErrors();
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

        // Handle image update if new image is uploaded
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            $this->deleteOldImages($idBerita);
            $imageData = $this->processImageUpload();
            $updateData['gambar'] = $imageData['gambar'];
            $updateData['thumbnail'] = $imageData['thumbnail'];
        }

        $this->beritaModel->update($idBerita, $updateData);

        session()->setFlashdata('success', 'Data Berita Berhasil Di Update');
        return redirect()->to('/beritas');
    }

    /**
     * Delete old images when updating
     */
    private function deleteOldImages(int $idBerita): void
    {
        $berita = $this->beritaModel->find($idBerita);
        
        if ($berita && $berita['gambar'] !== null) {
            $oldFotoPath = FCPATH . 'berita/' . $berita['gambar'];
            if (file_exists($oldFotoPath) && !is_dir($oldFotoPath)) {
                unlink($oldFotoPath);
            }

            $oldThumbnailPath = FCPATH . 'berita/' . $berita['thumbnail'];
            if (file_exists($oldThumbnailPath) && !is_dir($oldThumbnailPath)) {
                unlink($oldThumbnailPath);
            }
        }
    }

    /**
     * Save new news article
     */
    public function save()
    {
        $data = $this->getFormData();
        $data['user_id'] = session()->get('idUser');

        $rules = $this->getValidationRules(true); // true for create (image required)

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors();
        }

        $imageData = $this->processImageUpload();
        
        $this->beritaModel->insert([
            'judul' => $data['judul'],
            'user_id' => $data['user_id'],
            'tanggal' => $data['tanggal'],
            'konten' => $data['konten'],
            'kategori_id' => $data['kategori_id'],
            'status' => $data['status'],
            'gambar' => $imageData['gambar'],
            'thumbnail' => $imageData['thumbnail'],
            'slug' => createSlug($data['judul']),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('success', 'Data Berita Berhasil Ditambahkan');
        return redirect()->to('/beritas');
    }


    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'beritas' => $this->beritaModel->find($id),
            'kategori' => $this->kategoriModel->where('status', self::STATUS_ACTIVE)->findAll()
        ];
        
        return view('backend/berita/edit', $data);
    }

    /**
     * Get form data from request
     */
    private function getFormData(): array
    {
        return [
            'kategori_id' => $this->request->getVar('kategori_id'),
            'tanggal' => $this->request->getVar('tanggal'),
            'judul' => $this->request->getVar('judul'),
            'konten' => trim($this->request->getVar('konten')),
            'status' => $this->request->getVar('status')
        ];
    }

    /**
     * Get validation rules
     */
    private function getValidationRules(bool $isCreate = false): array
    {
        $rules = [
            'judul' => [
                'label' => 'Judul Berita',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong']
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong']
            ],
            'status' => [
                'label' => 'Status Berita',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong']
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong']
            ],
            'kategori_id' => [
                'label' => 'Kategori Berita',
                'rules' => 'required',
                'errors' => ['required' => '{field} tidak boleh kosong']
            ]
        ];

        if ($isCreate) {
            $rules['gambar'] = [
                'label' => 'Gambar Berita',
                'rules' => 'uploaded[gambar]|max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => '{field} tidak boleh kosong',
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG atau JPG'
                ]
            ];
        } else {
            $rules['gambar'] = [
                'label' => 'Gambar Berita',
                'rules' => 'max_size[gambar,' . self::MAX_FILE_SIZE . ']|mime_in[gambar,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'max_size' => 'Ukuran {field} maksimum 1MB',
                    'mime_in' => 'Format {field} harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Handle validation errors
     */
    private function handleValidationErrors()
    {
        $validation = \Config\Services::validation();
        session()->setFlashData([
            'error_judul' => $validation->getError('judul'),
            'error_tanggal' => $validation->getError('tanggal'),
            'error_konten' => $validation->getError('konten'),
            'error_gambar' => $validation->getError('gambar'),
            'error_kategori_id' => $validation->getError('kategori_id'),
            'error_status' => $validation->getError('status'),
        ]);
        
        return redirect()->back()->withInput();
    }

    /**
     * Process image upload and create thumbnail
     */
    private function processImageUpload(): array
    {
        $fileFoto = $this->request->getFile('gambar');
        $namaFoto = "Berita_" . $fileFoto->getRandomName();
        $fileFoto->move(FCPATH . 'berita', $namaFoto);

        $namaThumbnail = str_replace('.', '_thumb.', $namaFoto);
        $imageService = service('image');
        $image = $imageService->withFile(FCPATH . 'berita/' . $namaFoto);
        $image->resize(450, 225, true, 'center');
        $image->save(FCPATH . 'berita/' . $namaThumbnail, 90);

        return [
            'gambar' => $namaFoto,
            'thumbnail' => $namaThumbnail
        ];
    }
    /**
     * Delete news article
     */
    public function delete($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access denied']);
        }

        $berita = $this->beritaModel->find($id);

        if (!$berita) {
            return $this->response->setJSON(['error' => 'Data tidak ditemukan']);
        }

        // Delete associated images
        $this->deleteOldImages($id);

        // Delete from database
        $this->beritaModel->delete($id);

        return $this->response->setJSON(['sukses' => 'Data Berhasil Terhapus']);
    }
}
