<?php

namespace App\Controllers\Backend;

use App\Models\Rawat;
use App\Models\TempatTidur;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * TempatTidurController handles bed management functionality
 * 
 * This controller manages bed availability tracking for different room classes
 * with proper validation and relationship management with inpatient care units.
 */
class TempatTidurController extends BaseController
{
    // Model instances
    private TempatTidur $tempatTidurModel;
    private Rawat $rawatModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->tempatTidurModel = new TempatTidur();
        $this->rawatModel = new Rawat();
    }

    /**
     * Display bed management index page
     */
    public function index()
    {
        $data = ['title' => 'Tempat Tidur'];
        return view('backend/tempattidur/index', $data);
    }

    /**
     * Display bed management creation form
     */
    public function create()
    {
        $data = ['rawat' => $this->rawatModel->findAll()];
        return view('backend/tempattidur/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->tempatTidurModel->getTempatTidur();
        
        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idtempattidur, $row->nama);
            }, 'last')
            ->toJson();
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
     * Get bed management validation rules
     */
    private function getBedValidationRules(): array
    {
        return [
            'rawat_id' => [
                'label' => 'Nama Ruangan Rawat',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Nama ruangan rawat harus diisi',
                    'integer' => 'ID ruangan rawat harus berupa angka'
                ]
            ],
            'vip_isi' => [
                'label' => 'Tempat Tidur VIP Isi',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur VIP isi harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'vip_kosong' => [
                'label' => 'Tempat Tidur VIP Kosong',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur VIP kosong harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'utama_isi' => [
                'label' => 'Tempat Tidur Utama Isi',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur utama isi harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'utama_kosong' => [
                'label' => 'Tempat Tidur Utama Kosong',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur utama kosong harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas1_isi' => [
                'label' => 'Tempat Tidur Kelas 1 Isi',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 1 isi harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas1_kosong' => [
                'label' => 'Tempat Tidur Kelas 1 Kosong',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 1 kosong harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas2_isi' => [
                'label' => 'Tempat Tidur Kelas 2 Isi',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 2 isi harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas2_kosong' => [
                'label' => 'Tempat Tidur Kelas 2 Kosong',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 2 kosong harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas3_isi' => [
                'label' => 'Tempat Tidur Kelas 3 Isi',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 3 isi harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ],
            'kelas3_kosong' => [
                'label' => 'Tempat Tidur Kelas 3 Kosong',
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Tempat tidur kelas 3 kosong harus diisi',
                    'integer' => 'Jumlah harus berupa angka',
                    'greater_than_equal_to' => 'Jumlah tidak boleh negatif'
                ]
            ]
        ];
    }

    /**
     * Get bed data from form
     */
    private function getBedData(): array
    {
        return $this->getFormData([
            'rawat_id', 'vip_isi', 'vip_kosong', 'utama_isi', 'utama_kosong',
            'kelas1_isi', 'kelas1_kosong', 'kelas2_isi', 'kelas2_kosong',
            'kelas3_isi', 'kelas3_kosong'
        ]);
    }

    /**
     * Save new bed data
     */
    public function save()
    {
        $data = $this->getBedData();

        $rules = $this->getBedValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'rawat_id', 'vip_isi', 'vip_kosong', 'utama_isi', 'utama_kosong',
                'kelas1_isi', 'kelas1_kosong', 'kelas2_isi', 'kelas2_kosong',
                'kelas3_isi', 'kelas3_kosong'
            ]);
        }

        $insertData = array_merge($data, [
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->tempatTidurModel->insert($insertData);

        return $this->setSuccessMessage('Data Tempat Tidur Berhasil Ditambahkan', '/tempattidur');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'rawat' => $this->rawatModel->findAll(),
            'tempattidur' => $this->tempatTidurModel->find($id)
        ];
        return view('backend/tempattidur/edit', $data);
    }

    /**
     * Update existing bed data
     */
    public function update()
    {
        $data = $this->getFormData(['idtempattidur']);
        $idTempatTidur = $data['idtempattidur'];
        $bedData = $this->getBedData();

        $rules = $this->getBedValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'rawat_id', 'vip_isi', 'vip_kosong', 'utama_isi', 'utama_kosong',
                'kelas1_isi', 'kelas1_kosong', 'kelas2_isi', 'kelas2_kosong',
                'kelas3_isi', 'kelas3_kosong'
            ]);
        }

        $updateData = array_merge($bedData, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->tempatTidurModel->update($idTempatTidur, $updateData);

        return $this->setSuccessMessage('Data Tempat Tidur Berhasil Di Update', '/tempattidur');
    }

    /**
     * Delete bed data
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $tempatTidur = $this->tempatTidurModel->find($id);

        if (!$tempatTidur) {
            return $this->jsonError('Data tempat tidur tidak ditemukan', 404);
        }

        $this->tempatTidurModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}