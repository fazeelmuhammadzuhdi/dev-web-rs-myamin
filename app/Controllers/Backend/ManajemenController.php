<?php

namespace App\Controllers\Backend;

use App\Models\Manajemen;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * ManajemenController handles management team management functionality
 * 
 * This controller manages management team creation, editing, deletion, and display
 * with proper validation and status management.
 */
class ManajemenController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Manajemen $manajemenModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->manajemenModel = new Manajemen();
    }

    /**
     * Display management team index page
     */
    public function index()
    {
        $data = ['title' => 'Manajemen'];
        return view('backend/manajemen/index', $data);
    }

    /**
     * Display management team creation form
     */
    public function create()
    {
        return view('backend/manajemen/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->manajemenModel->select('idmanajemen,nama,status');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idmanajemen, $row->nama);
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
     * Get management team validation rules
     */
    private function getManagementValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Manajemen',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama manajemen harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new management team member
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status']);

        $rules = $this->getManagementValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama']);
        }

        $this->manajemenModel->insert([
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Manajemen Berhasil Ditambahkan', '/manajemen');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['manajemen' => $this->manajemenModel->find($id)];
        return view('backend/manajemen/edit', $data);
    }

    /**
     * Update existing management team member
     */
    public function update()
    {
        $data = $this->getFormData(['idmanajemen', 'nama', 'status']);
        $idManajemen = $data['idmanajemen'];

        $rules = $this->getManagementValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        $this->manajemenModel->update($idManajemen, $updateData);

        return $this->setSuccessMessage('Data Manajemen Berhasil Di Update', '/manajemen');
    }

    /**
     * Delete management team member
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $manajemen = $this->manajemenModel->find($id);

        if (!$manajemen) {
            return $this->jsonError('Data manajemen tidak ditemukan', 404);
        }

        $this->manajemenModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}