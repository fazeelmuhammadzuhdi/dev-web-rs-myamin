<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Kategori;
use Hermawan\DataTables\DataTable;

/**
 * KategoriController handles category management functionality
 * 
 * This controller manages category creation, editing, deletion, and display
 * with proper validation and slug generation.
 */
class KategoriController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Kategori $kategoriModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->kategoriModel = new Kategori();
        helper('slug');
    }

    public function index()
    {
        $data['title'] = 'Kategori';

        return view('backend/kategori/index', $data);
    }

    public function create()
    {
        return view('backend/kategori/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->kategori->select('idkategori,title,slug,status');


            return DataTable::of($builder)
                ->edit('status', function ($row) {
                    if ($row->status == 'Y') {
                        return '<span class="badge badge-success">Aktif</span>';
                    } else {
                        return '<span class="badge badge-warning">Tidak Aktif</span>';
                    }
                })
                ->add('action', function ($row) {
                    return  '<div class="d-flex " role="group">

                    <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $row->idkategori . '\',\'' . $row->title . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $row->idkategori . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    /**
     * Get category validation rules
     */
    private function getCategoryValidationRules(): array
    {
        return [
            'idkategori' => [
                'label' => 'ID Kategori',
                'rules' => 'required|min_length[3]|max_length[20]',
                'errors' => [
                    'required' => 'ID Kategori tidak boleh kosong',
                    'min_length' => 'ID Kategori minimal 3 karakter',
                    'max_length' => 'ID Kategori maksimal 20 karakter'
                ]
            ],
            'title' => [
                'label' => 'Judul Kategori',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul kategori tidak boleh kosong',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new category
     */
    public function save()
    {
        $data = $this->getFormData(['idkategori', 'title', 'status']);

        $rules = $this->getCategoryValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['idkategori', 'title']);
        }

        $this->kategoriModel->insert([
            'idkategori' => $data['idkategori'],
            'title' => $data['title'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['title']),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Kategori Berhasil Ditambahkan', '/kategoris');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['kategori' => $this->kategoriModel->find($id)];
        return view('backend/kategori/edit', $data);
    }

    /**
     * Update existing category
     */
    public function update()
    {
        $data = $this->getFormData(['idkategori', 'old_idkategori', 'title', 'status']);
        $idKategori = $data['idkategori'];
        $oldIdKategori = $data['old_idkategori'];

        $rules = $this->getCategoryValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['idkategori', 'title']);
        }

        // Update non-key fields first
        $updateData = [
            'title' => $data['title'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['title']),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->kategoriModel->update($oldIdKategori, $updateData);

        // Update primary key if changed
        if ($idKategori !== $oldIdKategori) {
            $this->kategoriModel->query("UPDATE kategori SET idkategori = ? WHERE idkategori = ?", [$idKategori, $oldIdKategori]);
        }

        return $this->setSuccessMessage('Data Kategori Berhasil Di Update', '/kategoris');
    }


    /**
     * Delete category
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $kategori = $this->kategoriModel->find($id);

        if (!$kategori) {
            return $this->jsonError('Kategori tidak ditemukan', 404);
        }

        $this->kategoriModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}
