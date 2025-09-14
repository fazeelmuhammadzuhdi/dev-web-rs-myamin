<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\KeberatanInformasiPPID;
use Hermawan\DataTables\DataTable;

/**
 * KeberatanInformasiController handles PPID information objection management
 * 
 * This controller manages PPID information objections including creation,
 * editing, deletion, and reporting with WhatsApp notification integration.
 */
class KeberatanInformasiController extends BaseController
{
    // Model instance
    private KeberatanInformasiPPID $keberatanInformasiModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->keberatanInformasiModel = new KeberatanInformasiPPID();
    }

    /**
     * Display objection index page
     */
    public function index()
    {
        $data = ['title' => 'Formulir Keberatan Atas Permohonan Informasi PPID'];
        return view('backend/formppid/keberatan_informasi', $data);
    }

    /**
     * Display objection creation form
     */
    public function create()
    {
        return view('backend/formppid/keberatan_informasi_create');
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->keberatanInformasiModel->select('idkeberataninformasippid,nama_pemohon_informasi,pekerjaan,nomor_telepon_pemohon,tanggal,informasi_dibutuhkan_pemohon,alasan_pengajuan,keterangan')
            ->orderBy('tanggal', 'desc')
            ->orderBy('idkeberataninformasippid', 'DESC');

        return DataTable::of($builder)
            ->edit('tanggal', function ($row) {
                return date('d M Y', strtotime($row->tanggal));
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idkeberataninformasippid, $row->nama_pemohon_informasi);
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
     * Get objection validation rules
     */
    private function getObjectionValidationRules(): array
    {
        return [
            'nama_pemohon_informasi' => [
                'label' => 'Nama Pemohon Informasi',
                'rules' => 'required|alpha_space|max_length[50]',
                'errors' => [
                    'required' => 'Nama pemohon informasi harus diisi',
                    'alpha_space' => 'Nama hanya boleh mengandung huruf dan spasi',
                    'max_length' => 'Nama maksimal 50 karakter'
                ]
            ],
            'alamat_pemohon' => [
                'label' => 'Alamat Pemohon',
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Alamat pemohon harus diisi',
                    'max_length' => 'Alamat maksimal 100 karakter'
                ]
            ],
            'pekerjaan' => [
                'label' => 'Pekerjaan',
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Pekerjaan harus diisi',
                    'max_length' => 'Pekerjaan maksimal 50 karakter'
                ]
            ],
            'nomor_telepon_pemohon' => [
                'label' => 'Nomor Telepon Pemohon',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Nomor telepon pemohon harus diisi',
                    'numeric' => 'Nomor telepon harus berupa angka',
                    'min_length' => 'Nomor telepon minimal 10 digit',
                    'max_length' => 'Nomor telepon maksimal 15 digit'
                ]
            ],
            'informasi_dibutuhkan_pemohon' => [
                'label' => 'Informasi Dibutuhkan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Informasi dibutuhkan pemohon harus diisi',
                    'max_length' => 'Informasi maksimal 255 karakter'
                ]
            ],
            'alasan_pengajuan' => [
                'label' => 'Alasan Pengajuan',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Alasan pengajuan harus diisi',
                    'max_length' => 'Alasan maksimal 255 karakter'
                ]
            ]
        ];
    }

    /**
     * Send WhatsApp notification
     */
    private function sendWhatsAppNotification(array $data): bool
    {
        try {
            $whatsappService = \Config\Services::whatsapp();
            
            $pesan = "*📄 Keberatan Atas Permohonan Informasi PPID*\n\n" .
                "*👤 Nama:* " . $data['nama_pemohon_informasi'] . "\n" .
                "*🏠 Alamat:* " . $data['alamat_pemohon'] . "\n" .
                "*📞 Telepon:* " . $data['nomor_telepon_pemohon'] . "\n" .
                "*💼 Pekerjaan:* " . $data['pekerjaan'] . "\n" .
                "*📌 Informasi yang Dibutuhkan:*\n" . $data['informasi_dibutuhkan_pemohon'] . "\n" .
                "*📝 Alasan Pengajuan:*\n" . $data['alasan_pengajuan'];

            return $whatsappService->sendMessageToAdmin($pesan);
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save objection form
     */
    public function formulirKeberatanInformasiPPIDSave()
    {
        $data = $this->getFormData([
            'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
            'informasi_dibutuhkan_pemohon', 'alasan_pengajuan', 'pekerjaan', 'keterangan'
        ]);

        $rules = $this->getObjectionValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
                'informasi_dibutuhkan_pemohon', 'alasan_pengajuan', 'pekerjaan'
            ]);
        }

        // Send WhatsApp notification
        $whatsappSent = $this->sendWhatsAppNotification($data);
        if (!$whatsappSent) {
            session()->setFlashData('error', 'Gagal mengirim pesan WhatsApp!');
            return redirect()->back()->withInput();
        }

        $this->keberatanInformasiModel->insert([
            'nama_pemohon_informasi' => $data['nama_pemohon_informasi'],
            'alamat_pemohon' => $data['alamat_pemohon'],
            'nomor_telepon_pemohon' => $data['nomor_telepon_pemohon'],
            'informasi_dibutuhkan_pemohon' => $data['informasi_dibutuhkan_pemohon'],
            'alasan_pengajuan' => $data['alasan_pengajuan'],
            'pekerjaan' => $data['pekerjaan'],
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $data['keterangan'] ?? null
        ]);

        $userId = session()->get('idUser');
        if ($userId) {
            return $this->setSuccessMessage('Data berhasil disimpan!', '/keberataninformasi');
        } else {
            return $this->setSuccessMessage('Data berhasil disimpan!');
        }
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = ['keberataninformasippid' => $this->keberatanInformasiModel->find($id)];
        return view('backend/formppid/keberatan_informasi_edit', $data);
    }

    /**
     * Update objection form
     */
    public function update()
    {
        $data = $this->getFormData([
            'idkeberataninformasippid', 'nama_pemohon_informasi', 'alamat_pemohon',
            'nomor_telepon_pemohon', 'informasi_dibutuhkan_pemohon', 'alasan_pengajuan',
            'pekerjaan', 'keterangan'
        ]);
        
        $idkeberataninformasippid = $data['idkeberataninformasippid'];

        $rules = $this->getObjectionValidationRules();
        
        // Add keterangan validation for update
        $rules['keterangan'] = [
            'label' => 'Keterangan',
            'rules' => 'required|max_length[255]',
            'errors' => [
                'required' => 'Keterangan harus diisi',
                'max_length' => 'Keterangan maksimal 255 karakter'
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
                'informasi_dibutuhkan_pemohon', 'alasan_pengajuan', 'pekerjaan', 'keterangan'
            ]);
        }

        $updateData = [
            'nama_pemohon_informasi' => $data['nama_pemohon_informasi'],
            'alamat_pemohon' => $data['alamat_pemohon'],
            'nomor_telepon_pemohon' => $data['nomor_telepon_pemohon'],
            'informasi_dibutuhkan_pemohon' => $data['informasi_dibutuhkan_pemohon'],
            'alasan_pengajuan' => $data['alasan_pengajuan'],
            'pekerjaan' => $data['pekerjaan'],
            'keterangan' => $data['keterangan']
        ];

        $this->keberatanInformasiModel->update($idkeberataninformasippid, $updateData);

        return $this->setSuccessMessage('Data Keberatan Informasi Berhasil Di Update', '/keberataninformasi');
    }

    /**
     * Delete objection form
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $keberatan = $this->keberatanInformasiModel->find($id);

        if (!$keberatan) {
            return $this->jsonError('Data keberatan tidak ditemukan', 404);
        }

        $this->keberatanInformasiModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Generate objection report
     */
    public function cetakLaporanKeberatanInformasiPPID()
    {
        $keberataninformasi = $this->keberatanInformasiModel->findAll();

        $data = [
            'keberataninformasi' => $keberataninformasi,
            'title' => 'Laporan Keberatan Informasi PPID',
            'total' => count($keberataninformasi),
            'srcLogoPemrov' => $this->getBase64Image('assets/pemprov.jpg'),
            'srcLogoRsud' => $this->getBase64Image('assets/logo.png')
        ];

        return view('backend/formppid/laporan_keberatan_informasi', $data);
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