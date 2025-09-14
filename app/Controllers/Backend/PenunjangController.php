<?php

namespace App\Controllers\Backend;

use App\Models\Penunjang;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * PenunjangController handles support services management functionality
 * 
 * This controller manages support services creation, editing, deletion, and display
 * with proper validation and status management.
 */
class PenunjangController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Penunjang $penunjangModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->penunjangModel = new Penunjang();
    }

    /**
     * Display support services index page
     */
    public function index()
    {
        $data = ['title' => 'Penunjang'];
        return view('backend/penunjang/index', $data);
    }

    /**
     * Display support services creation form
     */
    public function create()
    {
        return view('backend/penunjang/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->penunjangModel->select('idpenunjang,nama,keterangan,status')->orderBy('nama', 'ASC');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpenunjang, $row->nama);
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
     * Format description column
     */
    private function formatDescriptionColumn(?string $keterangan): string
    {
        if ($keterangan) {
            // Strip HTML tags and limit words
            $text = strip_tags($keterangan);
            return limit_words($text, 20);
        }
        
        return '-';
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
     * Get support services validation rules
     */
    private function getSupportServicesValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Penunjang',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama penunjang harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Penunjang',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan penunjang harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new support service
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status', 'keterangan']);

        $rules = $this->getSupportServicesValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        $this->penunjangModel->insert([
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Penunjang Berhasil Ditambahkan', '/penunjang');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['penunjang' => $this->penunjangModel->find($id)];
        return view('backend/penunjang/edit', $data);
    }

    /**
     * Update existing support service
     */
    public function update()
    {
        $data = $this->getFormData(['idpenunjang', 'nama', 'status', 'keterangan']);
        $idPenunjang = $data['idpenunjang'];

        $rules = $this->getSupportServicesValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        $this->penunjangModel->update($idPenunjang, $updateData);

        return $this->setSuccessMessage('Data Penunjang Berhasil Di Update', '/penunjang');
    }

    /**
     * Delete support service
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $penunjang = $this->penunjangModel->find($id);

        if (!$penunjang) {
            return $this->jsonError('Data penunjang tidak ditemukan', 404);
        }

        $this->penunjangModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}