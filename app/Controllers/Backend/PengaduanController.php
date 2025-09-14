<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Pengaduan;
use Config\Services;
use Hermawan\DataTables\DataTable;

/**
 * PengaduanController handles public complaint management functionality
 * 
 * This controller manages public complaint creation, viewing, and deletion
 * with proper validation, WhatsApp notification, and data management.
 */
class PengaduanController extends BaseController
{
    // Model instance
    private Pengaduan $pengaduanModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->pengaduanModel = new Pengaduan();
    }

    /**
     * Display complaint index page
     */
    public function index()
    {
        $data = ['title' => 'Pengaduan Masyarakat PPT Bunga Seroja'];
        return view('backend/pengaduan/index', $data);
    }

    /**
     * Display complaint creation form
     */
    public function create()
    {
        $data = ['title' => 'Formulir Pengaduan Masyarakat PPT Bunga Seroja'];
        return view('frontend/pengaduan-bunga-seroja', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->pengaduanModel
            ->select('idpengaduan,nama,nomor_hp,alamat,kronologi,created_at')
            ->orderBy('created_at', 'desc')
            ->orderBy('idpengaduan', 'DESC');
        
        return DataTable::of($builder)
            ->edit('created_at', function ($row) {
                return $this->formatDateColumn($row->created_at);
            })
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idpengaduan, $row->nama);
            }, 'last')
            ->toJson();
    }

    /**
     * Format date column
     */
    private function formatDateColumn(string $createdAt): string
    {
        return tanggal_indonesia($createdAt);
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
        </div>';
    }

    /**
     * Get complaint validation rules
     */
    private function getComplaintValidationRules(): array
    {
        return [
            'nama' => [
                'label' => 'Nama',
                'rules' => 'required|alpha_space|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama tidak boleh kosong',
                    'alpha_space' => 'Nama hanya boleh mengandung huruf dan spasi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter'
                ]
            ],
            'alamat' => [
                'label' => 'Alamat',
                'rules' => 'required|min_length[10]|max_length[255]',
                'errors' => [
                    'required' => 'Alamat tidak boleh kosong',
                    'min_length' => 'Alamat minimal 10 karakter',
                    'max_length' => 'Alamat maksimal 255 karakter'
                ]
            ],
            'nomor_hp' => [
                'label' => 'Nomor Handphone',
                'rules' => 'required|numeric|min_length[10]|max_length[15]',
                'errors' => [
                    'required' => 'Nomor handphone tidak boleh kosong',
                    'numeric' => 'Nomor handphone harus berupa angka',
                    'min_length' => 'Nomor handphone minimal 10 digit',
                    'max_length' => 'Nomor handphone maksimal 15 digit'
                ]
            ],
            'kronologi' => [
                'label' => 'Kronologi Kejadian',
                'rules' => 'required|min_length[20]',
                'errors' => [
                    'required' => 'Kronologi kejadian tidak boleh kosong',
                    'min_length' => 'Kronologi minimal 20 karakter'
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
            $whatsappService = Services::whatsapp();

            $pesan = "🚨 *LAPORAN MASYARAKAT PPT BUNGA SEROJA*\n" .
                "────────────────────────────\n" .
                "👤 *Nama:* {$data['nama']}\n" .
                "🏠 *Alamat:* {$data['alamat']}\n" .
                "📞 *Nomor HP:* {$data['nomor_hp']}\n" .
                "────────────────────────────\n" .
                "📜 *Kronologi Kejadian:*\n{$data['kronologi']}\n" .
                "────────────────────────────";

            return $whatsappService->sendMessageToAdmin($pesan);
        } catch (\Exception $e) {
            log_message('error', 'WhatsApp notification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save new complaint
     */
    public function save()
    {
        $data = $this->getFormData(['nama', 'alamat', 'nomor_hp', 'kronologi']);

        $rules = $this->getComplaintValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['nama', 'alamat', 'nomor_hp', 'kronologi']);
        }

        // Send WhatsApp notification
        $whatsappSent = $this->sendWhatsAppNotification($data);

        if (!$whatsappSent) {
            session()->setFlashData('error', 'Gagal mengirim notifikasi WhatsApp!');
            return redirect()->back()->withInput();
        }

        // Save to database
        $this->pengaduanModel->insert([
            'nama' => $data['nama'],
            'alamat' => $data['alamat'],
            'nomor_hp' => $data['nomor_hp'],
            'kronologi' => $data['kronologi'],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->setSuccessMessage('Data Berhasil Terkirim!');
    }

    /**
     * Delete complaint
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $pengaduan = $this->pengaduanModel->find($id);

        if (!$pengaduan) {
            return $this->jsonError('Data pengaduan tidak ditemukan', 404);
        }

        $this->pengaduanModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }
}