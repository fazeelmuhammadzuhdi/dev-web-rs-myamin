<?php

namespace App\Controllers\Backend;

use App\Models\IndikatorMutu;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * IndikatorMutuController handles quality indicator management functionality
 * 
 * This controller manages quality indicator creation, editing, deletion, and display
 * with proper validation and status management.
 */
class IndikatorMutuController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private IndikatorMutu $indikatorMutuModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->indikatorMutuModel = new IndikatorMutu();
    }

    /**
     * Display quality indicator index page
     */
    public function index()
    {
        $data = ['title' => 'Indikator Mutu'];
        return view('backend/indikatormutu/index', $data);
    }

    /**
     * Display quality indicator creation form
     */
    public function create()
    {
        return view('backend/indikatormutu/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->indikatorMutuModel->select('idindikatormutu,nama,status');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idindikatormutu, $row->nama);
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
     * Get quality indicator validation rules
     */
    private function getQualityIndicatorValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Indikator Mutu',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama indikator mutu harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new quality indicator
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status']);

        $rules = $this->getQualityIndicatorValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama']);
        }

        $this->indikatorMutuModel->insert([
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Indikator Mutu Berhasil Ditambahkan', '/indikatormutu');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['indikatormutu' => $this->indikatorMutuModel->find($id)];
        return view('backend/indikatormutu/edit', $data);
    }

    /**
     * Update existing quality indicator
     */
    public function update()
    {
        $data = $this->getFormData(['idindikatormutu', 'nama', 'status']);
        $idIndikatormutu = $data['idindikatormutu'];

        $rules = $this->getQualityIndicatorValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        $this->indikatorMutuModel->update($idIndikatormutu, $updateData);

        return $this->setSuccessMessage('Data Indikator Mutu Berhasil Di Update', '/indikatormutu');
    }

    /**
     * Delete quality indicator
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $indikatorMutu = $this->indikatorMutuModel->find($id);

        if (!$indikatorMutu) {
            return $this->jsonError('Indikator Mutu tidak ditemukan', 404);
        }

        $this->indikatorMutuModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}