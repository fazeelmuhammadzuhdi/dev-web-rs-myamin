<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\Inovasi;
use Hermawan\DataTables\DataTable;

/**
 * InovasiController handles innovation management functionality
 * 
 * This controller manages innovation data including creation, editing, deletion, and display
 * with comprehensive validation for all innovation fields.
 */
class InovasiController extends BaseController
{
    // Model instance
    private Inovasi $inovasiModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->inovasiModel = new Inovasi();
    }

    /**
     * Display innovation index page
     */
    public function index()
    {
        $data = ['title' => 'List Inovasi'];
        return view('backend/inovasi/index', $data);
    }

    /**
     * Display innovation creation form
     */
    public function create()
    {
        return view('backend/inovasi/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->inovasiModel->select('idinovasi,judul,tahun,jenis');

        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idinovasi, $row->judul);
            }, 'last')
            ->toJson();
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
     * Get innovation validation rules
     */
    private function getInnovationValidationRules(): array
    {
        return [
            'judul' => [
                'label' => 'Judul Inovasi',
                'rules' => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Judul inovasi harus diisi',
                    'min_length' => 'Judul minimal 5 karakter',
                    'max_length' => 'Judul maksimal 255 karakter'
                ]
            ],
            'tahun' => [
                'label' => 'Tahun Inovasi',
                'rules' => 'required|integer|greater_than[2000]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required' => 'Tahun inovasi harus diisi',
                    'integer' => 'Tahun harus berupa angka',
                    'greater_than' => 'Tahun harus lebih dari 2000',
                    'less_than_equal_to' => 'Tahun tidak boleh lebih dari tahun sekarang'
                ]
            ],
            'jenis' => [
                'label' => 'Jenis Inovasi',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Jenis inovasi harus diisi',
                    'min_length' => 'Jenis minimal 3 karakter',
                    'max_length' => 'Jenis maksimal 100 karakter'
                ]
            ],
            'tujuan' => [
                'label' => 'Tujuan Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Tujuan inovasi harus diisi',
                    'min_length' => 'Tujuan minimal 10 karakter'
                ]
            ],
            'manfaat' => [
                'label' => 'Manfaat Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Manfaat inovasi harus diisi',
                    'min_length' => 'Manfaat minimal 10 karakter'
                ]
            ],
            'rancang' => [
                'label' => 'Rancang Bangun Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Rancang bangun inovasi harus diisi',
                    'min_length' => 'Rancang bangun minimal 10 karakter'
                ]
            ],
            'tahapan' => [
                'label' => 'Tahapan Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Tahapan inovasi harus diisi',
                    'min_length' => 'Tahapan minimal 10 karakter'
                ]
            ],
            'digital' => [
                'label' => 'Digital Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Digital inovasi harus diisi',
                    'min_length' => 'Digital minimal 10 karakter'
                ]
            ],
            'inisiator' => [
                'label' => 'Inisiator Inovasi',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Inisiator inovasi harus diisi',
                    'min_length' => 'Inisiator minimal 3 karakter',
                    'max_length' => 'Inisiator maksimal 100 karakter'
                ]
            ],
            'hasil' => [
                'label' => 'Hasil Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Hasil inovasi harus diisi',
                    'min_length' => 'Hasil minimal 10 karakter'
                ]
            ],
            'ujicoba' => [
                'label' => 'Uji Coba Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Uji coba inovasi harus diisi',
                    'min_length' => 'Uji coba minimal 10 karakter'
                ]
            ],
            'implementasi' => [
                'label' => 'Implementasi Inovasi',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Implementasi inovasi harus diisi',
                    'min_length' => 'Implementasi minimal 10 karakter'
                ]
            ],
            'urusan' => [
                'label' => 'Urusan',
                'rules' => 'max_length[255]',
                'errors' => [
                    'max_length' => 'Urusan maksimal 255 karakter'
                ]
            ],
            'panduan_teknis' => [
                'label' => 'Panduan Teknis',
                'rules' => 'max_length[255]',
                'errors' => [
                    'max_length' => 'Panduan teknis maksimal 255 karakter'
                ]
            ],
            'link_youtube' => [
                'label' => 'Link YouTube',
                'rules' => 'valid_url',
                'errors' => [
                    'valid_url' => 'Format URL YouTube tidak valid'
                ]
            ]
        ];
    }

    /**
     * Save new innovation
     */
    public function save()
    {
        $data = $this->getFormData([
            'judul', 'tahun', 'jenis', 'tujuan', 'manfaat', 'rancang', 'tahapan',
            'digital', 'inisiator', 'hasil', 'ujicoba', 'implementasi', 'urusan',
            'panduan_teknis', 'link_youtube'
        ]);

        $rules = $this->getInnovationValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'tahun', 'jenis', 'tujuan', 'manfaat', 'rancang', 'tahapan',
                'digital', 'inisiator', 'hasil', 'ujicoba', 'implementasi'
            ]);
        }

        $this->inovasiModel->insert([
            'judul' => $data['judul'],
            'tahun' => $data['tahun'],
            'jenis' => $data['jenis'],
            'tujuan' => $data['tujuan'],
            'manfaat' => $data['manfaat'],
            'rancang' => $data['rancang'],
            'tahapan' => $data['tahapan'],
            'digital' => $data['digital'],
            'inisiator' => $data['inisiator'],
            'hasil' => $data['hasil'],
            'ujicoba' => $data['ujicoba'],
            'implementasi' => $data['implementasi'],
            'urusan' => $data['urusan'] ?? null,
            'panduan_teknis' => $data['panduan_teknis'] ?? null,
            'link_youtube' => $data['link_youtube'] ?? null,
        ]);

        return $this->setSuccessMessage('Data Inovasi Berhasil Ditambahkan', '/inovasis');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['inovasi' => $this->inovasiModel->find($id)];
        return view('backend/inovasi/edit', $data);
    }

    /**
     * Update existing innovation
     */
    public function update()
    {
        $data = $this->getFormData([
            'idinovasi', 'judul', 'tahun', 'jenis', 'tujuan', 'manfaat', 'rancang', 'tahapan',
            'digital', 'inisiator', 'hasil', 'ujicoba', 'implementasi', 'urusan',
            'panduan_teknis', 'link_youtube'
        ]);
        
        $idinovasi = $data['idinovasi'];

        $rules = $this->getInnovationValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'tahun', 'jenis', 'tujuan', 'manfaat', 'rancang', 'tahapan',
                'digital', 'inisiator', 'hasil', 'ujicoba', 'implementasi'
            ]);
        }

        $updateData = [
            'judul' => $data['judul'],
            'tahun' => $data['tahun'],
            'jenis' => $data['jenis'],
            'tujuan' => $data['tujuan'],
            'manfaat' => $data['manfaat'],
            'rancang' => $data['rancang'],
            'tahapan' => $data['tahapan'],
            'digital' => $data['digital'],
            'inisiator' => $data['inisiator'],
            'hasil' => $data['hasil'],
            'ujicoba' => $data['ujicoba'],
            'implementasi' => $data['implementasi'],
            'urusan' => $data['urusan'] ?? null,
            'panduan_teknis' => $data['panduan_teknis'] ?? null,
            'link_youtube' => $data['link_youtube'] ?? null,
        ];

        $this->inovasiModel->update($idinovasi, $updateData);

        return $this->setSuccessMessage('Data Inovasi Berhasil Di Update', '/inovasis');
    }

    /**
     * Delete innovation
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $inovasi = $this->inovasiModel->find($id);

        if (!$inovasi) {
            return $this->jsonError('Data inovasi tidak ditemukan', 404);
        }

        $this->inovasiModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}