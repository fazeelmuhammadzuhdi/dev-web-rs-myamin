<?php

namespace App\Controllers\Backend;

use App\Models\Pesan;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * PesanController handles message management functionality
 * 
 * This controller manages messages including creation, editing, deletion, and display
 * with response handling and status management.
 */
class PesanController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_UNPUBLISHED = 'UP';
    private const STATUS_READ = 'R';
    private const STATUS_UNREAD = 'UR';
    
    // Model instance
    private Pesan $pesanModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->pesanModel = new Pesan();
    }

    /**
     * Display message index page
     */
    public function index()
    {
        $data = ['title' => 'Pesan'];
        return view('backend/pesan/index', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->pesanModel->select('idpesan,tanggal,nama,pesan,status,status_baca,respon')
            ->orderBy('tanggal', 'DESC');

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('status_baca', function ($row) {
                return $this->formatReadStatusBadge($row->status_baca);
            })
            ->edit('tanggal', function ($row) {
                return date('d M Y', strtotime($row->tanggal));
            })
            ->edit('respon', function ($row) {
                return $this->formatResponseColumn($row->respon);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpesan, $row->nama);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === self::STATUS_UNPUBLISHED) {
            return '<span class="badge badge-danger">Unpublish</span>';
        }
        
        return '<span class="badge badge-success">Publish</span>';
    }

    /**
     * Format read status badge
     */
    private function formatReadStatusBadge(string $statusBaca): string
    {
        if ($statusBaca === self::STATUS_UNREAD) {
            return '<span class="badge badge-danger">Belum Dibaca</span>';
        }
        
        return '<span class="badge badge-success">Dibaca</span>';
    }

    /**
     * Format response column
     */
    private function formatResponseColumn(?string $respon): string
    {
        if ($respon) {
            // Strip HTML tags and limit words
            $text = strip_tags($respon);
            return $this->limitWords($text, 10);
        }
        
        return '-';
    }

    /**
     * Limit words in text
     */
    private function limitWords(string $text, int $limit): string
    {
        $words = explode(' ', $text);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . '...';
        }
        return $text;
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
     * Get message validation rules
     */
    private function getMessageValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Nama tidak boleh kosong',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 50 karakter'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required' => 'Email tidak boleh kosong',
                    'valid_email' => 'Format email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter'
                ]
            ],
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required|min_length[5]|max_length[100]',
                'errors' => [
                    'required' => 'Judul tidak boleh kosong',
                    'min_length' => 'Judul minimal 5 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'pesan' => [
                'label' => 'Pesan',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Pesan tidak boleh kosong',
                    'min_length' => 'Pesan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Get response validation rules
     */
    private function getResponseValidationRules(): array
    {
        return [
            'respon' => [
                'label' => 'Respon',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Respon tidak boleh kosong',
                    'min_length' => 'Respon minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new message
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'email', 'judul', 'pesan']);

        $rules = $this->getMessageValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'email', 'judul', 'pesan']);
        }

        $this->pesanModel->insert([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'judul' => $data['judul'],
            'pesan' => $data['pesan'],
            'tanggal' => date('Y-m-d'),
        ]);

        return $this->setSuccessMessage('Pesan Berhasil Terkirim', '/');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        if (!$id) {
            session()->setFlashdata('error', 'Data Tidak Ditemukan');
            return redirect()->back();
        }

        // Update read status
        $this->pesanModel->updateStatusBaca($id);

        $data = ['pesan' => $this->pesanModel->find($id)];
        return view('backend/pesan/edit', $data);
    }

    /**
     * Update message response
     */
    public function update()
    {
        $data = $this->getFormData(['idpesan', 'respon', 'status']);
        $idPesan = $data['idpesan'];

        $rules = $this->getResponseValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['respon']);
        }

        $updateData = [
            'respon' => $data['respon'],
            'status' => $data['status'] ?: self::STATUS_UNPUBLISHED,
            'admin' => 'Admin',
        ];

        $this->pesanModel->update($idPesan, $updateData);

        return $this->setSuccessMessage('Pesan Berhasil Di Jawab', '/pesans');
    }

    /**
     * Delete message
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $pesan = $this->pesanModel->find($id);

        if (!$pesan) {
            return $this->jsonError('Data pesan tidak ditemukan', 404);
        }

        $this->pesanModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}