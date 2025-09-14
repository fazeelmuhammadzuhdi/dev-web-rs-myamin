<?php

namespace App\Controllers\PPID;

use App\Models\KategoriInformasiPPID;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * KategoriInformasiController handles PPID information category management
 * 
 * This controller manages PPID information categories including creation,
 * editing, deletion, and display with slug generation and status management.
 */
class KategoriInformasiController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private KategoriInformasiPPID $kategoriInformasiModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->kategoriInformasiModel = new KategoriInformasiPPID();
        helper('slug');
    }

    /**
     * Display category index page
     */
    public function index()
    {
        $data = ['title' => 'Kategori'];
        return view('backend/kategori-informasi-ppid/index', $data);
    }

    /**
     * Display category creation form
     */
    public function create()
    {
        return view('backend/kategori-informasi-ppid/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->kategoriInformasiModel->select('idkategori,title,slug,status');

        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idkategori, $row->title);
            }, 'last')
            ->toJson();
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
     * Get category validation rules
     */
    private function getCategoryValidationRules(): array
    {
        return [
            'idkategori' => [
                'label' => 'ID Kategori',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'ID kategori harus diisi',
                    'integer' => 'ID kategori harus berupa angka',
                    'greater_than' => 'ID kategori harus lebih dari 0'
                ]
            ],
            'title' => [
                'label' => 'Judul Kategori',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul kategori harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ]
        ];
    }

    /**
     * Check if category ID already exists
     */
    private function isCategoryIdExists(int $idKategori, ?int $excludeId = null): bool
    {
        $builder = $this->kategoriInformasiModel->where('idkategori', $idKategori);
        
        if ($excludeId) {
            $builder->where('idkategori !=', $excludeId);
        }
        
        return $builder->countAllResults() > 0;
    }

    /**
     * Save new category
     */
    public function save()
    {
        $data = $this->getFormData(['idkategori', 'title', 'status']);
        $idKategori = (int)$data['idkategori'];

        $rules = $this->getCategoryValidationRules();

        // Add unique validation for ID
        if ($this->isCategoryIdExists($idKategori)) {
            session()->setFlashData([
                'error_kategori' => 'ID kategori sudah digunakan'
            ]);
            return redirect()->back()->withInput();
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['idkategori', 'title']);
        }

        $this->kategoriInformasiModel->insert([
            'idkategori' => $idKategori,
            'title' => $data['title'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['title']),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Kategori Berhasil Ditambahkan', '/kategoris-ppid');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['kategori' => $this->kategoriInformasiModel->find($id)];
        return view('backend/kategori-informasi-ppid/edit', $data);
    }

    /**
     * Update existing category
     */
    public function update()
    {
        $data = $this->getFormData(['idkategori', 'old_idkategori', 'title', 'status']);
        $idKategori = (int)$data['idkategori'];
        $oldIdKategori = (int)$data['old_idkategori'];

        $rules = $this->getCategoryValidationRules();

        // Add unique validation for ID if changed
        if ($idKategori !== $oldIdKategori && $this->isCategoryIdExists($idKategori)) {
            session()->setFlashData([
                'error_kategori' => 'ID kategori sudah digunakan'
            ]);
            return redirect()->back()->withInput();
        }

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['idkategori', 'title']);
        }

        $updateData = [
            'title' => $data['title'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['title']),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Update non-key fields first
        $this->kategoriInformasiModel->update($oldIdKategori, $updateData);

        // Update primary key if changed
        if ($idKategori !== $oldIdKategori) {
            $this->kategoriInformasiModel->query("UPDATE kategori SET idkategori = ? WHERE idkategori = ?", [$idKategori, $oldIdKategori]);
        }

        return $this->setSuccessMessage('Data Kategori Berhasil Di Update', '/kategoris-ppid');
    }

    /**
     * Delete category
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $kategori = $this->kategoriInformasiModel->find($id);

        if (!$kategori) {
            return $this->jsonError('Data kategori tidak ditemukan', 404);
        }

        $this->kategoriInformasiModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}