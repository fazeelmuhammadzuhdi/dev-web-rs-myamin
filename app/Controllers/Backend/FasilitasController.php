<?php

namespace App\Controllers\Backend;

use App\Models\Fasilitas;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * FasilitasController handles facility management functionality
 * 
 * This controller manages facility creation, editing, deletion, and display
 * with proper validation and status management.
 */
class FasilitasController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Fasilitas $fasilitasModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->fasilitasModel = new Fasilitas();
    }

    /**
     * Display facility index page
     */
    public function index()
    {
        $data = ['title' => 'Fasilitas'];
        return view('backend/fasilitas/index', $data);
    }

    /**
     * Display facility creation form
     */
    public function create()
    {
        return view('backend/fasilitas/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->fasilitasModel->select('idfasilitas,nama,keterangan,status');
        
        return DataTable::of($builder)
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idfasilitas, $row->nama);
            }, 'last')
            ->toJson();
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
     * Get facility validation rules
     */
    private function getFacilityValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Fasilitas',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama fasilitas harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Fasilitas',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan fasilitas harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new facility
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status', 'keterangan']);

        $rules = $this->getFacilityValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        $this->fasilitasModel->insert([
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Fasilitas Berhasil Ditambahkan', '/fasilitasumum');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['fasilitas' => $this->fasilitasModel->find($id)];
        return view('backend/fasilitas/edit', $data);
    }

    /**
     * Update existing facility
     */
    public function update()
    {
        $data = $this->getFormData(['idfasilitas', 'nama', 'status', 'keterangan']);
        $idFasilitas = $data['idfasilitas'];

        $rules = $this->getFacilityValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        $updateData = [
            'nama' => $data['nama'],
            'keterangan' => $data['keterangan'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
        ];

        $this->fasilitasModel->update($idFasilitas, $updateData);

        return $this->setSuccessMessage('Data Fasilitas Berhasil Di Update', '/fasilitasumum');
    }

    /**
     * Delete facility
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $fasilitas = $this->fasilitasModel->find($id);

        if (!$fasilitas) {
            return $this->jsonError('Fasilitas tidak ditemukan', 404);
        }

        $this->fasilitasModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}