<?php

namespace App\Controllers\PPID;

use App\Controllers\BaseController;
use App\Models\FormulirPPID;
use Hermawan\DataTables\DataTable;

/**
 * FormulirPpidController handles PPID information request form management
 * 
 * This controller manages PPID information request forms including creation,
 * editing, deletion, and reporting with comprehensive validation and file handling.
 */
class FormulirPpidController extends BaseController
{
    // Constants for better maintainability
    private const MAX_FILE_SIZE = 2048; // 2MB
    private const ALLOWED_IMAGE_TYPES = 'image/jpeg,image/png,image/jpg';
    
    // Model instance
    private FormulirPPID $formulirPpidModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->formulirPpidModel = new FormulirPPID();
    }

    /**
     * Display PPID form index page
     */
    public function index()
    {
        $data = ['title' => 'Formulir Permintaan Informasi PPID'];
        return view('backend/formppid/index', $data);
    }

    /**
     * Display PPID form creation page
     */
    public function create()
    {
        $data = ['title' => 'Formulir Permintaan Informasi PPID'];
        return view('backend/formppid/permohonan_informasi_create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->formulirPpidModel->select('idpesanppid,ktp,nama_pemohon_informasi,nomor_telepon_pemohon,email_pemohon,tanggal,informasi_dibutuhkan_pemohon,alasan_permintaan_pemohon,cara_memperoleh_informasi,cara_mengirim_bahan_informasi,keterangan')
            ->orderBy('tanggal', 'desc')
            ->orderBy('idpesanppid', 'DESC');

        return DataTable::of($builder)
            ->edit('tanggal', function ($row) {
                return date('d M Y', strtotime($row->tanggal));
            })
            ->edit('ktp', function ($row) {
                return $this->formatKtpColumn($row->ktp);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpesanppid, $row->nama_pemohon_informasi);
            }, 'last')
            ->toJson();
    }

    /**
     * Format KTP column
     */
    private function formatKtpColumn(?string $ktp): string
    {
        if ($ktp) {
            $imageUrl = base_url('ktp/' . $ktp);
            return '<img src="' . $imageUrl . '" width="100" height="60" style="cursor:pointer;" 
                data-toggle="modal" data-target="#ktpModal" 
                onclick="showKtpModal(\'' . $imageUrl . '\')" alt="KTP Image">';
        }
        
        return '<span class="text-danger">Tidak ada KTP</span>';
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
     * Get PPID form validation rules
     */
    private function getPpidFormValidationRules(bool $requireKtp = false): array
    {
        $rules = [
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
            'email_pemohon' => [
                'label' => 'Email Pemohon',
                'rules' => 'required|valid_email|max_length[100]',
                'errors' => [
                    'required' => 'Email pemohon harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter'
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
            'alasan_permintaan_pemohon' => [
                'label' => 'Alasan Permintaan Pemohon',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Alasan permintaan pemohon harus diisi',
                    'max_length' => 'Alasan maksimal 255 karakter'
                ]
            ],
            'cara_memperoleh_informasi' => [
                'label' => 'Cara Memperoleh Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Cara memperoleh informasi harus diisi',
                    'max_length' => 'Cara memperoleh informasi maksimal 255 karakter'
                ]
            ],
            'format_bahan_informasi' => [
                'label' => 'Format Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Format bahan informasi harus diisi',
                    'max_length' => 'Format bahan informasi maksimal 255 karakter'
                ]
            ],
            'cara_mengirim_bahan_informasi' => [
                'label' => 'Cara Mengirim Bahan Informasi',
                'rules' => 'required|max_length[255]',
                'errors' => [
                    'required' => 'Cara mengirim bahan informasi harus diisi',
                    'max_length' => 'Cara mengirim bahan informasi maksimal 255 karakter'
                ]
            ]
        ];

        if ($requireKtp) {
            $rules['ktp'] = [
                'label' => 'KTP',
                'rules' => 'uploaded[ktp]|max_size[ktp,' . self::MAX_FILE_SIZE . ']|mime_in[ktp,' . self::ALLOWED_IMAGE_TYPES . ']',
                'errors' => [
                    'uploaded' => 'KTP harus diisi',
                    'max_size' => 'Ukuran KTP maksimum ' . self::MAX_FILE_SIZE . 'KB',
                    'mime_in' => 'Format KTP harus JPEG, PNG atau JPG'
                ]
            ];
        }

        return $rules;
    }

    /**
     * Process KTP upload
     */
    private function processKtpUpload(): ?string
    {
        $fileFoto = $this->request->getFile('ktp');
        
        if ($fileFoto->isValid() && !$fileFoto->hasMoved()) {
            $namaFoto = "Ktp_" . $fileFoto->getRandomName();
            $fileFoto->move(FCPATH . 'ktp', $namaFoto);
            
            // Optimize image
            optimizeImageForWeb('ktp/' . $namaFoto, [
                'width' => 800,
                'height' => 600,
                'quality' => 85
            ]);
            
            return $namaFoto;
        }
        
        return null;
    }

    /**
     * Delete old KTP file
     */
    private function deleteOldKtp(string $ktpPath): void
    {
        if ($ktpPath && file_exists(FCPATH . 'ktp/' . $ktpPath)) {
            $this->deleteFile('ktp/' . $ktpPath);
        }
    }

    /**
     * Save PPID form
     */
    public function formulirPPIDSave()
    {
        $data = $this->getFormData([
            'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
            'email_pemohon', 'informasi_dibutuhkan_pemohon', 'alasan_permintaan_pemohon',
            'cara_memperoleh_informasi', 'format_bahan_informasi', 'cara_mengirim_bahan_informasi',
            'keterangan'
        ]);

        $rules = $this->getPpidFormValidationRules(true);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
                'email_pemohon', 'informasi_dibutuhkan_pemohon', 'alasan_permintaan_pemohon',
                'cara_memperoleh_informasi', 'format_bahan_informasi', 'cara_mengirim_bahan_informasi',
                'ktp'
            ]);
        }

        $newKtp = $this->processKtpUpload();
        if (!$newKtp) {
            session()->setFlashdata('error_ktp', 'KTP harus diisi');
            return redirect()->back()->withInput();
        }

        $this->formulirPpidModel->insert([
            'nama_pemohon_informasi' => $data['nama_pemohon_informasi'],
            'alamat_pemohon' => $data['alamat_pemohon'],
            'nomor_telepon_pemohon' => $data['nomor_telepon_pemohon'],
            'email_pemohon' => $data['email_pemohon'],
            'informasi_dibutuhkan_pemohon' => $data['informasi_dibutuhkan_pemohon'],
            'alasan_permintaan_pemohon' => $data['alasan_permintaan_pemohon'],
            'cara_memperoleh_informasi' => $data['cara_memperoleh_informasi'],
            'format_bahan_informasi' => $data['format_bahan_informasi'],
            'cara_mengirim_bahan_informasi' => $data['cara_mengirim_bahan_informasi'],
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $data['keterangan'] ?? '',
            'ktp' => $newKtp,
        ]);

        $userId = session()->get('idUser');
        if ($userId) {
            return $this->setSuccessMessage('Data berhasil disimpan!', 'pesanppid');
        } else {
            return $this->setSuccessMessage('Data berhasil disimpan!');
        }
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'pesanppid' => $this->formulirPpidModel->find($id),
            'title' => 'Form Edit Formulir Permintaan Informasi PPID'
        ];
        return view('backend/formppid/permohonan_informasi_edit', $data);
    }

    /**
     * Update PPID form
     */
    public function update()
    {
        $data = $this->getFormData([
            'idpesanppid', 'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
            'email_pemohon', 'informasi_dibutuhkan_pemohon', 'alasan_permintaan_pemohon',
            'cara_memperoleh_informasi', 'format_bahan_informasi', 'cara_mengirim_bahan_informasi',
            'keterangan'
        ]);
        
        $idpesanppid = $data['idpesanppid'];

        $rules = $this->getPpidFormValidationRules(false);

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'nama_pemohon_informasi', 'alamat_pemohon', 'nomor_telepon_pemohon',
                'email_pemohon', 'informasi_dibutuhkan_pemohon', 'alasan_permintaan_pemohon',
                'cara_memperoleh_informasi', 'format_bahan_informasi', 'cara_mengirim_bahan_informasi'
            ]);
        }

        $updateData = [
            'nama_pemohon_informasi' => $data['nama_pemohon_informasi'],
            'alamat_pemohon' => $data['alamat_pemohon'],
            'nomor_telepon_pemohon' => $data['nomor_telepon_pemohon'],
            'email_pemohon' => $data['email_pemohon'],
            'informasi_dibutuhkan_pemohon' => $data['informasi_dibutuhkan_pemohon'],
            'alasan_permintaan_pemohon' => $data['alasan_permintaan_pemohon'],
            'cara_memperoleh_informasi' => $data['cara_memperoleh_informasi'],
            'format_bahan_informasi' => $data['format_bahan_informasi'],
            'cara_mengirim_bahan_informasi' => $data['cara_mengirim_bahan_informasi'],
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $data['keterangan'] ?? '',
        ];

        $this->formulirPpidModel->update($idpesanppid, $updateData);

        return $this->setSuccessMessage('Data Permohonan Informasi Berhasil Di Update', '/pesanppid');
    }

    /**
     * Delete PPID form
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $formulir = $this->formulirPpidModel->find($id);

        if (!$formulir) {
            return $this->jsonError('Data formulir tidak ditemukan', 404);
        }

        // Delete associated KTP file
        if ($formulir['ktp']) {
            $this->deleteOldKtp($formulir['ktp']);
        }

        $this->formulirPpidModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Generate PPID information request report
     */
    public function cetakLaporanPermintaanInformasiPPID()
    {
        $permintaaninformasi = $this->formulirPpidModel->findAll();

        $data = [
            'permintaaninformasi' => $permintaaninformasi,
            'title' => 'Laporan Permintaan Informasi PPID',
            'total' => count($permintaaninformasi),
            'srcLogoPemrov' => $this->getBase64Image('assets/pemprov.jpg'),
            'srcLogoRsud' => $this->getBase64Image('assets/logo.png')
        ];

        return view('backend/formppid/laporan_permintaan_informasi', $data);
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