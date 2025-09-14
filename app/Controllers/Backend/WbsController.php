<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Wbs;
use Hermawan\DataTables\DataTable;

/**
 * WbsController handles Whistleblowing System management functionality
 * 
 * This controller manages whistleblowing reports including creation, viewing,
 * and reporting with comprehensive validation and data processing.
 */
class WbsController extends BaseController
{
    // Model instance
    private Wbs $wbsModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->wbsModel = new Wbs();
    }

    /**
     * Display WBS index page
     */
    public function index()
    {
        $data = ['title' => 'Informasi Data Laporan Whistleblowing System'];
        return view('backend/laporwbs/index', $data);
    }

    /**
     * Display WBS creation form
     */
    public function create()
    {
        $data = [
            'title' => 'Formulir Pelaporan Whistleblowing System',
            'tindakans' => $this->getAvailableActions()
        ];
        return view('frontend/whistleblowing', $data);
    }

    /**
     * Get available actions for WBS
     */
    private function getAvailableActions(): array
    {
        return [
            'Fraud',
            'Gratifikasi dan penyuapan',
            'Konflik kepentingan',
            'Pemerasan',
            'Penyalahgunaan aset perusahaan',
            'Penyalahgunaan wewenang',
            'Perilaku yang tidak sesuai dengan etika bisnis dan kerja',
            'Pelanggaran Peraturan Perundang-undangan yang berpotensi mencemarkan nama baik',
            'Pelanggaran Kode Etik',
            'Perbuatan melanggar hukum',
            'Transaksi Mencurigakan',
            'Lain - lain'
        ];
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->wbsModel->select('id_wbs,nama_pelapor,telepon_pelapor,tindakan,nama_terlapor,waktu_kejadian,lokasi_kejadian,kronologis')
            ->orderBy('waktu_kejadian', 'desc');

        return DataTable::of($builder)
            ->edit('waktu_kejadian', function ($row) {
                return '<span class="text-nowrap">' . tanggal_indonesia($row->waktu_kejadian) . '</span>';
            })
            ->edit('tindakan', function ($row) {
                return $this->formatActionsColumn($row->tindakan);
            })
            ->edit('nama_pelapor', function ($row) {
                return '<span class="text-nowrap">' . esc($row->nama_pelapor) . '</span>';
            })
            ->toJson();
    }

    /**
     * Format actions column
     */
    private function formatActionsColumn(string $tindakan): string
    {
        $clean = html_entity_decode($tindakan);
        $tindakans = json_decode($clean, true);

        if (is_array($tindakans)) {
            $badges = '';
            foreach ($tindakans as $item) {
                $badges .= '<span class="badge badge-primary m-1">' . esc($item) . '</span>';
            }
            return $badges;
        }

        return '<span class="text-danger">Format tidak valid</span>';
    }

    /**
     * Get WBS validation rules
     */
    private function getWbsValidationRules(): array
    {
        return [
            'nama_pelapor' => [
                'label' => 'Nama Pelapor',
                'rules' => 'required|alpha_space|max_length[100]',
                'errors' => [
                    'required' => 'Nama pelapor harus diisi',
                    'alpha_space' => 'Nama pelapor hanya boleh mengandung huruf dan spasi',
                    'max_length' => 'Nama pelapor maksimal 100 karakter'
                ]
            ],
            'nama_terlapor' => [
                'label' => 'Nama Terlapor',
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama terlapor harus diisi',
                    'max_length' => 'Nama terlapor maksimal 100 karakter'
                ]
            ],
            'telepon_pelapor' => [
                'label' => 'Nomor Telepon Pelapor',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Nomor telepon pelapor harus diisi',
                    'numeric' => 'Nomor telepon harus berupa angka',
                    'min_length' => 'Nomor telepon minimal 10 digit',
                    'max_length' => 'Nomor telepon maksimal 15 digit'
                ]
            ],
            'email_pelapor' => [
                'label' => 'Email Pelapor',
                'rules' => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required' => 'Email pelapor harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter'
                ]
            ],
            'lokasi_kejadian' => [
                'label' => 'Lokasi Kejadian',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Lokasi kejadian harus diisi',
                    'max_length' => 'Lokasi kejadian maksimal 255 karakter'
                ]
            ],
            'kronologis' => [
                'label' => 'Kronologis Kejadian',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Kronologis kejadian harus diisi',
                    'min_length' => 'Kronologis minimal 20 karakter'
                ]
            ],
            'tindakan' => [
                'label' => 'Tindakan',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tindakan harus dipilih'
                ]
            ],
            'waktu_kejadian' => [
                'label' => 'Waktu Kejadian',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Waktu kejadian harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ]
        ];
    }

    /**
     * Save new WBS report
     */
    public function save()
    {
        $data = $this->getFormData([
            'nama_pelapor', 'nama_terlapor', 'telepon_pelapor', 'email_pelapor',
            'lokasi_kejadian', 'waktu_kejadian', 'kronologis', 'tindakan'
        ]);

        $rules = $this->getWbsValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama_pelapor', 'nama_terlapor', 'telepon_pelapor', 'email_pelapor',
                'lokasi_kejadian', 'kronologis', 'tindakan', 'waktu_kejadian'
            ]);
        }

        // Ensure tindakan is array before encoding
        $tindakan = $data['tindakan'];
        if (!is_array($tindakan)) {
            $tindakan = [$tindakan];
        }

        $this->wbsModel->insert([
            'nama_pelapor' => $data['nama_pelapor'],
            'nama_terlapor' => $data['nama_terlapor'],
            'telepon_pelapor' => $data['telepon_pelapor'],
            'email_pelapor' => $data['email_pelapor'],
            'lokasi_kejadian' => $data['lokasi_kejadian'],
            'kronologis' => $data['kronologis'],
            'tindakan' => json_encode($tindakan),
            'waktu_kejadian' => $data['waktu_kejadian'],
        ]);

        return $this->setSuccessMessage('Data berhasil terkirim!');
    }

    /**
     * Delete WBS report
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $wbs = $this->wbsModel->find($id);

        if (!$wbs) {
            return $this->jsonError('Data laporan tidak ditemukan', 404);
        }

        $this->wbsModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Generate WBS report
     */
    public function cetakLaporanWbs()
    {
        $whistleblowing = $this->wbsModel->findAll();

        $data = [
            'whistleblowing' => $whistleblowing,
            'total' => count($whistleblowing),
            'srcLogoPemrov' => $this->getBase64Image('assets/pemprov.jpg'),
            'srcLogoRsud' => $this->getBase64Image('assets/logo.png')
        ];

        return view('backend/laporwbs/laporan-wbs', $data);
    }

    /**
     * Convert image to base64
     */
    private function getBase64Image(string $imagePath): string
    {
        $fullPath = FCPATH . $imagePath;
        
        if (file_exists($fullPath)) {
            $imageData = base64_encode(file_get_contents($fullPath));
            $imageInfo = getimagesize($fullPath);
            $mimeType = $imageInfo['mime'];
            
            return 'data:' . $mimeType . ';base64,' . $imageData;
        }
        
        return '';
    }
}