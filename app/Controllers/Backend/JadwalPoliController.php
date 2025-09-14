<?php

namespace App\Controllers\Backend;

use App\Models\Poli;
use App\Models\Dokter;
use App\Models\JadwalPoli;
use App\Models\Spesialis;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * JadwalPoliController handles clinic schedule management functionality
 * 
 * This controller manages clinic schedule creation, editing, deletion, and display
 * with proper validation and relationship management between doctors, clinics, and schedules.
 */
class JadwalPoliController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_INACTIVE = 'N';
    
    // Model instances
    private JadwalPoli $jadwalPoliModel;
    private Dokter $dokterModel;
    private Poli $poliModel;
    private Spesialis $spesialisModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->jadwalPoliModel = new JadwalPoli();
        $this->dokterModel = new Dokter();
        $this->poliModel = new Poli();
        $this->spesialisModel = new Spesialis();
    }

    /**
     * Display clinic schedule index page
     */
    public function index()
    {
        $data = ['title' => 'Jadwal Poli'];
        return view('backend/jadwalpoli/index', $data);
    }

    /**
     * Display clinic schedule creation form
     */
    public function create()
    {
        $data = [
            'dokter' => $this->dokterModel->where('status', self::STATUS_ACTIVE)->findAll(),
            'spesialis' => $this->spesialisModel->where('status', self::STATUS_ACTIVE)->findAll(),
            'poli' => $this->poliModel->where('status', self::STATUS_ACTIVE)->findAll()
        ];

        return view('backend/jadwalpoli/create', $data);
    }

    /**
     * Get data for DataTable
     */
    public function getData()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $builder = $this->jadwalPoliModel->getJadwalPoli();
        
        return DataTable::of($builder)
            ->add('action', function ($row) {
                return $this->formatActionButtons($row->idjadwalpoli, $row->nama_dokter);
            }, 'last')
            ->toJson();
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $namaDokter): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($namaDokter) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get clinic schedule validation rules
     */
    private function getClinicScheduleValidationRules(): array
    {
        return [
            'poli_id' => [
                'label' => 'Nama Poli',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Nama poli harus diisi',
                    'integer' => 'Poli ID harus berupa angka'
                ]
            ],
            'dokter_id' => [
                'label' => 'Nama Dokter',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Nama dokter harus diisi',
                    'integer' => 'Dokter ID harus berupa angka'
                ]
            ]
        ];
    }

    /**
     * Get schedule data for all days
     */
    private function getScheduleData(): array
    {
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
        $scheduleData = [];

        foreach ($days as $day) {
            $scheduleData[$day] = $this->request->getVar($day);
            $scheduleData["keterangan_{$day}"] = $this->request->getVar("keterangan_{$day}");
        }

        return $scheduleData;
    }

    /**
     * Save new clinic schedule
     */
    public function save()
    {
        $data = $this->getFormData(['poli_id', 'dokter_id']);
        $scheduleData = $this->getScheduleData();

        $rules = $this->getClinicScheduleValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['poli_id', 'dokter_id']);
        }

        $insertData = array_merge($data, $scheduleData, [
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->jadwalPoliModel->insert($insertData);

        return $this->setSuccessMessage('Data Jadwal Poli Berhasil Ditambahkan', '/jadwalpoli');
    }

    /**
     * Display edit form
     */
    public function edit($id = null)
    {
        $data = [
            'dokter' => $this->dokterModel->where('status', self::STATUS_ACTIVE)->findAll(),
            'poli' => $this->poliModel->where('status', self::STATUS_ACTIVE)->findAll(),
            'jadwalpoli' => $this->jadwalPoliModel->find($id),
            'spesialis' => $this->spesialisModel->where('status', self::STATUS_ACTIVE)->findAll()
        ];

        return view('backend/jadwalpoli/edit', $data);
    }

    /**
     * Update existing clinic schedule
     */
    public function update()
    {
        $data = $this->getFormData(['idjadwalpoli', 'poli_id', 'dokter_id']);
        $idJadwalpoli = $data['idjadwalpoli'];
        $scheduleData = $this->getScheduleData();

        $rules = $this->getClinicScheduleValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors(['poli_id', 'dokter_id']);
        }

        $updateData = array_merge([
            'dokter_id' => $data['dokter_id'],
            'poli_id' => $data['poli_id']
        ], $scheduleData, [
            'last_update' => date('Y-m-d H:i:s')
        ]);

        $this->jadwalPoliModel->update($idJadwalpoli, $updateData);

        return $this->setSuccessMessage('Data Jadwal Poli Berhasil Di Update', '/jadwalpoli');
    }

    /**
     * Delete clinic schedule
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $jadwalPoli = $this->jadwalPoliModel->find($id);

        if (!$jadwalPoli) {
            return $this->jsonError('Jadwal Poli tidak ditemukan', 404);
        }

        $this->jadwalPoliModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Get doctors by specialization (AJAX)
     */
    public function getDokterSpesialis()
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $spesialisId = $this->request->getVar('spesialis_id');

        if (!$spesialisId) {
            return $this->jsonError('Spesialis ID tidak valid', 400);
        }

        $dokter = $this->dokterModel
            ->where('spesialis_id', $spesialisId)
            ->where('status', self::STATUS_ACTIVE)
            ->findAll();

        $output = '<option value="">Pilih Dokter</option>';
        foreach ($dokter as $row) {
            $output .= '<option value="' . $row['iddokter'] . '">' . esc($row['nama']) . '</option>';
        }

        return $this->jsonSuccess('Data dokter berhasil dimuat', ['html' => $output]);
    }
}