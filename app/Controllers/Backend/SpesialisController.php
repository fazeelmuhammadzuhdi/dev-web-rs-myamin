<?php

namespace App\Controllers\Backend;

use App\Models\Spesialis;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * SpesialisController handles specialization management functionality
 * 
 * This controller manages specialization creation, editing, deletion, and display
 * with proper validation and status management.
 */
class SpesialisController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Spesialis $spesialisModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->spesialisModel = new Spesialis();
    }

    public function index()
    {
        $data['title'] = 'Spesialis';

        return view('backend/spesialis/index', $data);
    }

    public function create()
    {
        return view('backend/spesialis/create');
    }

    public function getData()
    {
        if ($this->request->isAJAX()) {
            $builder = $this->spesialis->select('idspesialis,gelar,nama,status');


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

                    <button type="button" class="btn btn-round btn-danger mx-1" nama="Hapus Data" onclick="hapus(\'' . $row->idspesialis . '\',\'' . $row->gelar . '\')">
                      <i class="feather icon-trash-2"></i>
                    </button>
                

                    <button type="button" class="btn btn-round btn-primary" nama="Edit Data" onclick="edit(\'' . $row->idspesialis . '\')">
                    <i class="feather icon-edit"></i></button>
                    </div>';
                }, 'last')
                ->toJson();
        }
    }


    /**
     * Get specialization validation rules
     */
    private function getSpecializationValidationRules(): array
    {
        return [
            'gelar' => [
                'label' => 'Gelar Spesialis',
                'rules' => 'required|min_length[2]|max_length[50]',
                'errors' => [
                    'required' => 'Gelar spesialis tidak boleh kosong',
                    'min_length' => 'Gelar minimal 2 karakter',
                    'max_length' => 'Gelar maksimal 50 karakter'
                ]
            ],
            'nama' => [
                'label' => 'Nama Spesialis',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama spesialis tidak boleh kosong',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new specialization
     */
    public function save()
    {
        $data = $this->getFormData(['gelar', 'nama', 'status']);

        $rules = $this->getSpecializationValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['gelar', 'nama']);
        }

        $this->spesialisModel->insert([
            'gelar' => $data['gelar'],
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Spesialis Berhasil Ditambahkan', '/spesialis');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['spesialis' => $this->spesialisModel->find($id)];
        return view('backend/spesialis/edit', $data);
    }

    /**
     * Update existing specialization
     */
    public function update()
    {
        $data = $this->getFormData(['idspesialis', 'gelar', 'nama', 'status']);

        $rules = $this->getSpecializationValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['gelar', 'nama']);
        }

        $updateData = [
            'gelar' => $data['gelar'],
            'nama' => $data['nama'],
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->spesialisModel->update($data['idspesialis'], $updateData);

        return $this->setSuccessMessage('Data Spesialis Berhasil Di Update', '/spesialis');
    }




    /**
     * Delete specialization
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $spesialis = $this->spesialisModel->find($id);

        if (!$spesialis) {
            return $this->jsonError('Spesialis tidak ditemukan', 404);
        }

        $this->spesialisModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}
