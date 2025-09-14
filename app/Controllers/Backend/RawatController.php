<?php

namespace App\Controllers\Backend;

use App\Models\Rawat;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * RawatController handles inpatient care management functionality
 * 
 * This controller manages inpatient care creation, editing, deletion, and display
 * with proper validation, base64 image handling, and status management.
 */
class RawatController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instance
    private Rawat $rawatModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->rawatModel = new Rawat();
        helper('string');
        helper('slug');
    }

    /**
     * Display inpatient care index page
     */
    public function index()
    {
        $data = ['title' => 'Rawat'];
        return view('backend/rawat/index', $data);
    }

    /**
     * Display inpatient care creation form
     */
    public function create()
    {
        return view('backend/rawat/create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->rawatModel->select('idrawat,nama,keterangan,status')->orderBy('nama', 'ASC');
        
        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('keterangan', function ($row) {
                return $this->formatDescriptionColumn($row->keterangan);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idrawat, $row->nama);
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
            $text = strip_empty_p_tags(limit_words($text, 40));
            return $text;
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
     * Save base64 images from content
     */
    private function saveBase64Images(string $konten): string
    {
        // Find all base64 images in content
        preg_match_all('/data:image\/(\w+);base64,([^"]+)/', $konten, $matches);

        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $base64Image) {
                // Extract base64 data
                list($type, $data) = explode(';', $base64Image);
                list(, $data) = explode(',', $data);

                // Decode base64 data
                $data = base64_decode($data);

                // Create random filename
                $fileName = uniqid() . '.jpg';

                // Storage path
                $filePath = FCPATH . 'uploadGaleri/' . $fileName;

                // Save image to server
                if (file_put_contents($filePath, $data)) {
                    // Replace base64 content with saved image URL
                    $konten = str_replace($base64Image, base_url('public/uploadGaleri/' . $fileName), $konten);
                } else {
                    // Log error
                    log_message('error', 'Failed to save base64 image: ' . $fileName);
                }
            }
        }

        return $konten;
    }

    /**
     * Get inpatient care validation rules
     */
    private function getInpatientCareValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama Rawat',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama rawat harus diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'keterangan' => [
                'label' => 'Keterangan Rawat',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Keterangan rawat harus diisi',
                    'min_length' => 'Keterangan minimal 10 karakter'
                ]
            ]
        ];
    }

    /**
     * Save new inpatient care
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'status', 'keterangan']);

        $rules = $this->getInpatientCareValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        // Process base64 images in content
        $keterangan = $this->saveBase64Images($data['keterangan']);

        $this->rawatModel->insert([
            'nama' => $data['nama'],
            'keterangan' => $keterangan,
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['nama']),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Rawat Berhasil Ditambahkan', '/rawat');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['rawat' => $this->rawatModel->find($id)];
        return view('backend/rawat/edit', $data);
    }

    /**
     * Update existing inpatient care
     */
    public function update()
    {
        $data = $this->getFormData(['idrawat', 'nama', 'status', 'keterangan']);
        $idRawat = $data['idrawat'];

        $rules = $this->getInpatientCareValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'keterangan']);
        }

        // Process base64 images in content
        $keterangan = $this->saveBase64Images($data['keterangan']);

        $updateData = [
            'nama' => $data['nama'],
            'keterangan' => $keterangan,
            'status' => $data['status'] ?: self::STATUS_ACTIVE,
            'slug' => createSlug($data['nama']),
        ];

        $this->rawatModel->update($idRawat, $updateData);

        return $this->setSuccessMessage('Data Rawat Berhasil Di Update', '/rawat');
    }

    /**
     * Delete inpatient care
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $rawat = $this->rawatModel->find($id);

        if (!$rawat) {
            return $this->jsonError('Data rawat tidak ditemukan', 404);
        }

        $this->rawatModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}