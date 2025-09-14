<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Dokter Model
 * 
 * Handles doctor information with proper validation and business logic
 */
class Dokter extends Model
{
    // Constants for better maintainability
    public const STATUS_ACTIVE = 'Y';
    public const STATUS_INACTIVE = 'N';
    
    protected $table            = 'dokter';
    protected $primaryKey       = 'iddokter';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'nip',
        'nama',
        'spesialis_id',
        'keterangan',
        'gambar',
        'thumbnail',
        'status',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nip'           => 'required|min_length[3]|max_length[20]|is_unique[dokter.nip,iddokter,{iddokter}]',
        'nama'          => 'required|min_length[3]|max_length[100]',
        'spesialis_id'  => 'required|integer',
        'keterangan'    => 'required|min_length[10]',
        'status'        => 'required|in_list[Y,N]'
    ];
    
    protected $validationMessages   = [
        'nip' => [
            'required' => 'NIP dokter harus diisi',
            'min_length' => 'NIP minimal 3 karakter',
            'max_length' => 'NIP maksimal 20 karakter',
            'is_unique' => 'NIP sudah digunakan'
        ],
        'nama' => [
            'required' => 'Nama dokter harus diisi',
            'min_length' => 'Nama minimal 3 karakter',
            'max_length' => 'Nama maksimal 100 karakter'
        ],
        'spesialis_id' => [
            'required' => 'Spesialis harus dipilih',
            'integer' => 'Spesialis ID harus berupa angka'
        ],
        'keterangan' => [
            'required' => 'Keterangan harus diisi',
            'min_length' => 'Keterangan minimal 10 karakter'
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus Aktif atau Tidak Aktif'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get doctors with specialization for admin
     */
    public function getDokter()
    {
        return $this->db->table('dokter')
            ->join('spesialis', 'dokter.spesialis_id = spesialis.idspesialis')
            ->select('dokter.iddokter, dokter.nip, dokter.nama as nama_dokter, spesialis.nama, dokter.status, dokter.gambar')
            ->orderBy('spesialis.nama', 'asc')
            ->orderBy('dokter.nama', 'asc');
    }

    /**
     * Get active doctors with specialization for frontend
     */
    public function getDokterWithSpesialis()
    {
        return $this->db->table('dokter')
            ->join('spesialis', 'dokter.spesialis_id = spesialis.idspesialis')
            ->select('dokter.iddokter, dokter.nip, dokter.nama, spesialis.nama as nama_spesialis, dokter.gambar, dokter.keterangan')
            ->where('dokter.status', self::STATUS_ACTIVE);
    }

    /**
     * Get doctors by specialization
     */
    public function getDoctorsBySpesialis(int $spesialisId): array
    {
        return $this->where('spesialis_id', $spesialisId)
            ->where('status', self::STATUS_ACTIVE)
            ->orderBy('nama', 'asc')
            ->findAll();
    }

    /**
     * Get active doctors
     */
    public function getActiveDoctors(): array
    {
        return $this->where('status', self::STATUS_ACTIVE)
            ->orderBy('nama', 'asc')
            ->findAll();
    }

    /**
     * Search doctors by name
     */
    public function searchDoctors(string $keyword): array
    {
        return $this->like('nama', $keyword)
            ->where('status', self::STATUS_ACTIVE)
            ->orderBy('nama', 'asc')
            ->findAll();
    }

    /**
     * Get doctor statistics
     */
    public function getDoctorStats(): array
    {
        return [
            'total' => $this->countAllResults(),
            'active' => $this->where('status', self::STATUS_ACTIVE)->countAllResults(),
            'inactive' => $this->where('status', self::STATUS_INACTIVE)->countAllResults()
        ];
    }

    /**
     * Get doctors grouped by specialization
     */
    public function getDoctorsGroupedBySpesialis(): array
    {
        $spesialisModel = new \App\Models\Spesialis();
        $spesialisList = $spesialisModel->where('status', self::STATUS_ACTIVE)->findAll();
        
        $result = [];
        foreach ($spesialisList as $spesialis) {
            $doctors = $this->getDoctorsBySpesialis($spesialis['idspesialis']);
            if (!empty($doctors)) {
                $result[$spesialis['idspesialis']] = [
                    'spesialis' => $spesialis,
                    'doctors' => $doctors
                ];
            }
        }
        
        return $result;
    }
}
