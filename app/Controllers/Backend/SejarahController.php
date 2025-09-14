<?php

namespace App\Controllers\Backend;

use App\Models\Sejarah;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * SejarahController handles hospital history management functionality
 * 
 * This controller manages hospital history timeline creation, editing, deletion, and display
 * with proper validation and chronological organization.
 */
class SejarahController extends BaseController
{
    // Model instance
    private Sejarah $sejarahModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->sejarahModel = new Sejarah();
    }

    /**
     * Display history index page
     */
    public function index()
    {
        $data = ['title' => 'Sejarah'];
        return view('backend/sejarah/index', $data);
    }

    /**
     * Display history creation form
     */
    public function create()
    {
        return view('backend/sejarah/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->sejarahModel->select('idsejarah,tahun,keterangan,judul')->orderBy('tahun', 'ASC');
        
        return DataTable::of($builder)
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idsejarah, $row->tahun);
            }, 'last')
            ->toJson();
    }

    /**
     * Format description column
     */
    private function formatDescriptionColumn(?string $keterangan): string
    {
        if ($keterangan) {
            // Strip HTML tags and limit length
            $text = strip_tags($keterangan);
            return strlen($text) > 100 ? substr($text, 0, 100) . '...' : $text;
        }
        
        return '-';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $tahun): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($tahun) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get history validation rules
     */
    private function getHistoryValidationRules(): array
    {
        return [
            'tahun' => [
                'label' => 'Tahun Sejarah',
                'rules' => 'required|integer|greater_than[1900]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required' => 'Tahun sejarah harus diisi',
                    'integer' => 'Tahun harus berupa angka',
                    'greater_than' => 'Tahun harus lebih dari 1900',
                    'less_than_equal_to' => 'Tahun tidak boleh lebih dari tahun sekarang'
                ]
            ],
            'judul' => [
                'label' => 'Judul Sejarah',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul sejarah harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Sejarah',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan sejarah harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new history entry
     */
    public function save()
    {
        $data = $this->getFormData(['tahun', 'judul', 'keterangan']);

        $rules = $this->getHistoryValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['tahun', 'judul', 'keterangan']);
        }

        $this->sejarahModel->insert([
            'tahun' => $data['tahun'],
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
        ]);

        return $this->setSuccessMessage('Data Sejarah Berhasil Ditambahkan', '/sejarahs');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['sejarahs' => $this->sejarahModel->find($id)];
        return view('backend/sejarah/edit', $data);
    }

    /**
     * Update existing history entry
     */
    public function update()
    {
        $data = $this->getFormData(['idsejarah', 'tahun', 'judul', 'keterangan']);
        $idSejarah = $data['idsejarah'];

        $rules = $this->getHistoryValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['tahun', 'judul', 'keterangan']);
        }

        $updateData = [
            'tahun' => $data['tahun'],
            'judul' => $data['judul'],
            'keterangan' => $data['keterangan'],
        ];

        $this->sejarahModel->update($idSejarah, $updateData);

        return $this->setSuccessMessage('Data Sejarah Berhasil Di Update', '/sejarahs');
    }

    /**
     * Delete history entry
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $sejarah = $this->sejarahModel->find($id);

        if (!$sejarah) {
            return $this->jsonError('Data sejarah tidak ditemukan', 404);
        }

        $this->sejarahModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}