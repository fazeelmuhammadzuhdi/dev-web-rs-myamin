<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Album;
use Hermawan\DataTables\DataTable;

/**
 * AlbumController handles album management functionality
 * 
 * This controller manages album creation, editing, deletion, and display
 * with proper validation, user tracking, and status management.
 */
class AlbumController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_DRAFT = 'DR';
    
    // Model instance
    private Album $albumModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->albumModel = new Album();
        helper('slug');
    }

    /**
     * Display album index page
     */
    public function index()
    {
        $data = ['title' => 'Album'];
        return view('backend/album/index', $data);
    }

    /**
     * Display album creation form
     */
    public function create()
    {
        return view('backend/album/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->albumModel
            ->join('user', 'album.user_id = user.iduser')
            ->select('idalbum,judul,tanggal,keterangan,album.status,nama')
            ->orderBy('tanggal', 'desc')
            ->orderBy('idalbum', 'DESC');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('nama', function ($row) {
                return $this->formatUserBadge($row->nama);
            })
            ->edit('tanggal', function ($row) {
                return $this->formatDateColumn($row->tanggal);
            })
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idalbum, $row->judul);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === self::STATUS_PUBLISHED) {
            return '<span class="badge badge-success" style="font-size: 14px;">Publish</span>';
        }
        
        return '<span class="badge badge-danger" style="font-size: 14px;">Belum Publish</span>';
    }

    /**
     * Format user badge
     */
    private function formatUserBadge(?string $nama): string
    {
        if ($nama) {
            return '<span class="badge badge-info" style="font-size: 14px;">' . esc($nama) . '</span>';
        }
        
        return '<span class="badge badge-warning" style="font-size: 14px;">Administrator</span>';
    }

    /**
     * Format date column
     */
    private function formatDateColumn(string $tanggal): string
    {
        return '<span class="text-nowrap">' . tanggal_indonesia($tanggal) . '</span>';
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
     * Get album validation rules
     */
    private function getAlbumValidationRules(): array
    {
        return [
            'judul' => [
                'label' => 'Judul Album',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Judul album harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 100 karakter'
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Album',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal album harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'status' => [
                'label' => 'Status Album',
                'rules' => 'required|in_list[PB,DR]',
                'errors' => [
                    'required' => 'Status album harus diisi',
                    'in_list' => 'Status harus Publish atau Draft'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Album',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan album harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new album
     */
    public function save()
    {
        $data = $this->getFormData(['judul', 'tanggal', 'status', 'keterangan']);
        $userId = $this->getCurrentUserId();

        $rules = $this->getAlbumValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'tanggal', 'keterangan', 'status']);
        }

        $this->albumModel->insert([
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'keterangan' => $data['keterangan'],
            'user_id' => $userId,
            'slug' => createSlug($data['judul']),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Album Berhasil Ditambahkan', '/album');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['album' => $this->albumModel->find($id)];
        return view('backend/album/edit', $data);
    }

    /**
     * Update existing album
     */
    public function update()
    {
        $data = $this->getFormData(['idalbum', 'judul', 'tanggal', 'status', 'keterangan']);
        $idAlbum = $data['idalbum'];
        $userId = $this->getCurrentUserId();

        $rules = $this->getAlbumValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['judul', 'tanggal', 'keterangan', 'status']);
        }

        $this->albumModel->update($idAlbum, [
            'judul' => $data['judul'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'keterangan' => $data['keterangan'],
            'user_id' => $userId,
            'slug' => createSlug($data['judul']),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->setSuccessMessage('Data Album Berhasil Di Update', '/album');
    }

    /**
     * Delete album
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $album = $this->albumModel->find($id);

        if (!$album) {
            return $this->jsonError('Album tidak ditemukan', 404);
        }

        $this->albumModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}