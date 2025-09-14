<?php

namespace App\Controllers\PPID;

use App\Models\BeritaPPID;
use App\Models\KategoriInformasiPPID;
use Hermawan\DataTables\DataTable;
use App\Controllers\BaseController;

/**
 * BeritaPpidController handles PPID news management functionality
 * 
 * This controller manages PPID and PKRS news creation, editing, deletion, and display
 * with API integration and comprehensive reporting features.
 */
class BeritaPpidController extends BaseController
{
    // Model instances
    private BeritaPPID $beritaPpidModel;
    private KategoriInformasiPPID $kategoriModel;

    /**
     * Initialize the controller
     */
    public function __construct()
    {
        $this->beritaPpidModel = new BeritaPPID();
        $this->kategoriModel = new KategoriInformasiPPID();
        helper('slug');
    }

    /**
     * Display PPID news index page
     */
    public function index(string $tipe = 'ppid')
    {
        $data = [
            'title' => ($tipe === 'ppid') ? 'Berita PPID' : 'Informasi PKRS',
            'tipe' => $tipe
        ];

        return view('backend/beritappid/index', $data);
    }

    /**
     * Display PPID news creation form
     */
    public function create(string $tipe = 'ppid')
    {
        $kategori = $this->getKategoriByType($tipe);
        
        $data = [
            'kategori' => $this->kategoriModel->where('status', 'Y')
                ->whereIn('idkategori', $kategori)
                ->findAll(),
            'tipe' => $tipe
        ];

        return view('backend/beritappid/create', $data);
    }

    /**
     * Get categories by type
     */
    private function getKategoriByType(string $tipe): array
    {
        return ($tipe === 'ppid') ? BeritaPPID::KATEGORI_PPID : BeritaPPID::KATEGORI_PKRS;
    }

    /**
     * Get data for DataTable
     */
    public function getData(string $tipe = 'ppid')
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $kategori = $this->getKategoriByType($tipe);
        $builder = $this->beritaPpidModel->getBerita($kategori);

        return DataTable::of($builder)
            ->edit('status', function ($row) {
                return $this->formatStatusBadge($row->status);
            })
            ->edit('link', function ($row) {
                return $this->formatLinkColumn($row->link);
            })
            ->edit('tanggal', function ($row) {
                return tanggal_indonesia($row->tanggal);
            })
            ->add('action', function ($row) use ($tipe) {
                return $this->formatActionButtons($row->idberita, $row->judul, $tipe);
            }, 'last')
            ->toJson();
    }

    /**
     * Format status badge
     */
    private function formatStatusBadge(string $status): string
    {
        if ($status === 'Y') {
            return '<span class="badge badge-success">Publish</span>';
        }
        
        return '<span class="badge badge-danger">Belum Publish</span>';
    }

    /**
     * Format link column
     */
    private function formatLinkColumn(?string $link): string
    {
        if ($link) {
            return '<a href="' . esc($link) . '" target="_blank" rel="noopener noreferrer">' . esc($link) . '</a>';
        }
        
        return '';
    }

    /**
     * Format action buttons
     */
    private function formatActionButtons(int $id, string $judul, string $tipe): string
    {
        return '<div class="d-flex" role="group">
            <button type="button" class="btn btn-round btn-danger mx-1" title="Hapus Data" onclick="hapus(\'' . $id . '\',\'' . esc($judul) . '\')">
                <i class="feather icon-trash-2"></i>
            </button>
            <button type="button" class="btn btn-round btn-primary" title="Edit Data" onclick="edit(\'' . $id . '\',\'' . $tipe . '\')">
                <i class="feather icon-edit"></i>
            </button>
        </div>';
    }

    /**
     * Get PPID news validation rules
     */
    private function getPpidNewsValidationRules(): array
    {
        return [
            'judul' => [
                'label' => 'Judul Berita PPID',
                'rules' => 'required|min_length[5]|max_length[255]',
                'errors' => [
                    'required' => 'Judul berita PPID harus diisi',
                    'min_length' => 'Judul minimal 5 karakter',
                    'max_length' => 'Judul maksimal 255 karakter'
                ]
            ],
            'tahun' => [
                'label' => 'Tahun Berita PPID',
                'rules' => 'required|integer|greater_than[2000]|less_than_equal_to[' . date('Y') . ']',
                'errors' => [
                    'required' => 'Tahun berita PPID harus diisi',
                    'integer' => 'Tahun harus berupa angka',
                    'greater_than' => 'Tahun harus lebih dari 2000',
                    'less_than_equal_to' => 'Tahun tidak boleh lebih dari tahun sekarang'
                ]
            ],
            'jangka' => [
                'label' => 'Jangka Berita PPID',
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'Jangka berita PPID harus diisi',
                    'min_length' => 'Jangka minimal 3 karakter',
                    'max_length' => 'Jangka maksimal 50 karakter'
                ]
            ],
            'penanggung_jawab' => [
                'label' => 'Penanggung Jawab Berita PPID',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Penanggung jawab berita PPID harus diisi',
                    'min_length' => 'Penanggung jawab minimal 3 karakter',
                    'max_length' => 'Penanggung jawab maksimal 100 karakter'
                ]
            ],
            'tempat' => [
                'label' => 'Tempat Berita PPID',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Tempat berita PPID harus diisi',
                    'min_length' => 'Tempat minimal 3 karakter',
                    'max_length' => 'Tempat maksimal 100 karakter'
                ]
            ],
            'link' => [
                'label' => 'Link Berita PPID',
                'rules' => 'required|valid_url',
                'errors' => [
                    'required' => 'Link berita PPID harus diisi',
                    'valid_url' => 'Format URL tidak valid'
                ]
            ],
            'filetype' => [
                'label' => 'Filetype Berita PPID',
                'rules' => 'required|in_list[pdf.png,doc.png,xls.png,ppt.png]',
                'errors' => [
                    'required' => 'Filetype berita PPID harus diisi',
                    'in_list' => 'Filetype harus PDF, DOC, XLS, atau PPT'
                ]
            ],
            'tanggal' => [
                'label' => 'Tanggal Berita PPID',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => 'Tanggal berita PPID harus diisi',
                    'valid_date' => 'Format tanggal tidak valid'
                ]
            ],
            'konten' => [
                'label' => 'Konten Berita',
                'rules' => 'required|min_length[10]',
                'errors' => [
                    'required' => 'Konten berita harus diisi',
                    'min_length' => 'Konten minimal 10 karakter'
                ]
            ],
            'kategori_id' => [
                'label' => 'Kategori Berita',
                'rules' => 'required|integer',
                'errors' => [
                    'required' => 'Kategori berita harus diisi',
                    'integer' => 'Kategori harus berupa angka'
                ]
            ]
        ];
    }

    /**
     * Save new PPID news
     */
    public function save(string $tipe = 'ppid')
    {
        $data = $this->getFormData([
            'kategori_id', 'judul', 'tanggal', 'tahun', 'jangka', 'penanggung_jawab',
            'tempat', 'link', 'filetype', 'konten', 'status'
        ]);

        $rules = $this->getPpidNewsValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'tanggal', 'konten', 'tahun', 'jangka', 'penanggung_jawab',
                'tempat', 'link', 'filetype', 'kategori_id', 'status'
            ]);
        }

        $this->beritaPpidModel->insert([
            'judul' => $data['judul'],
            'user_id' => session()->get('idUser'),
            'tanggal' => $data['tanggal'],
            'konten' => $data['konten'],
            'kategori_id' => $data['kategori_id'],
            'status' => $data['status'],
            'tahun' => $data['tahun'],
            'jangka' => $data['jangka'],
            'penanggung_jawab' => $data['penanggung_jawab'],
            'tempat' => $data['tempat'],
            'link' => $data['link'],
            'filetype' => $data['filetype'],
            'slug' => createSlug($data['judul']),
            'nm_status' => 'Publish',
        ]);

        return $this->setSuccessMessage('Data Berita PPID Berhasil Ditambahkan', '/beritappid/' . $tipe);
    }

    /**
     * Display edit form
     */
    public function edit($id = null, string $tipe = 'ppid')
    {
        $kategori = $this->getKategoriByType($tipe);
        
        $data = [
            'beritappid' => $this->beritaPpidModel->find($id),
            'kategori' => $this->kategoriModel->where('status', 'Y')
                ->whereIn('idkategori', $kategori)
                ->findAll(),
            'tipe' => $tipe
        ];
        
        return view('backend/beritappid/edit', $data);
    }

    /**
     * Update existing PPID news
     */
    public function update()
    {
        $data = $this->getFormData([
            'tipe', 'idberita', 'kategori_id', 'judul', 'tanggal', 'tahun', 'jangka',
            'penanggung_jawab', 'tempat', 'link', 'filetype', 'konten', 'status'
        ]);
        
        $tipe = $data['tipe'];
        $idBerita = $data['idberita'];

        $rules = $this->getPpidNewsValidationRules();

        if (!$this->validate($rules)) {
            return $this->handleValidationErrors([
                'judul', 'tanggal', 'konten', 'tahun', 'jangka', 'penanggung_jawab',
                'tempat', 'link', 'filetype', 'kategori_id', 'status'
            ]);
        }

        $updateData = [
            'judul' => $data['judul'],
            'user_id' => session()->get('idUser'),
            'tanggal' => $data['tanggal'],
            'konten' => $data['konten'],
            'kategori_id' => $data['kategori_id'],
            'status' => $data['status'],
            'tahun' => $data['tahun'],
            'jangka' => $data['jangka'],
            'penanggung_jawab' => $data['penanggung_jawab'],
            'tempat' => $data['tempat'],
            'link' => $data['link'],
            'filetype' => $data['filetype'],
            'slug' => createSlug($data['judul']),
            'nm_status' => 'Publish',
        ];

        $this->beritaPpidModel->update($idBerita, $updateData);

        return $this->setSuccessMessage('Data Berita PPID Berhasil Di Update', '/beritappid/' . $tipe);
    }

    /**
     * Delete PPID news
     */
    public function delete($id = null)
    {
        if (!$this->isAjaxRequest()) {
            return $this->jsonError('Access denied', 403);
        }

        $berita = $this->beritaPpidModel->find($id);

        if (!$berita) {
            return $this->jsonError('Data berita tidak ditemukan', 404);
        }

        $this->beritaPpidModel->delete($id);

        return $this->jsonSuccess('Data Berhasil Terhapus');
    }

    /**
     * Display API data page
     */
    public function apiIndex()
    {
        $data = [
            'title' => 'List Berita PPID API',
            'beritaPpid' => $this->getDataApi(),
            'existingData' => $this->beritaPpidModel->getExistingData(),
        ];

        // Create array for easier checking
        $existingIds = array_column($data['existingData'], 'idberita');
        $data['existingIds'] = $existingIds;

        return view('backend/beritappid/dataapippid', $data);
    }

    /**
     * Get data from external API
     */
    public function getDataApi(): array
    {
        $apiUrl = 'https://ppid.sumbarprov.go.id/api/cluster-data?id_instansi=99';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            return [];
        }

        $data = json_decode($response, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Generate public information report
     */
    public function cetakLaporanInformasiPublik()
    {
        $startYear = $this->request->getGet('startYear');
        $endYear = $this->request->getGet('endYear');

        $title = 'Laporan Daftar Informasi Publik RSUD. Prof. H. Muhammad. Yamin, SH';
        if ($startYear && $endYear) {
            $title .= ' Tahun ' . $startYear . ' Hingga ' . $endYear;
        }

        $builder = $this->beritaPpidModel->builder();
        $builder->whereIn('kategori_id', BeritaPPID::KATEGORI_PPID);

        if ($startYear) {
            $builder->where('tahun >=', $startYear);
        }
        if ($endYear) {
            $builder->where('tahun <=', $endYear);
        }

        $builder->orderBy('tanggal', 'DESC');
        $beritaPpid = $builder->get()->getResultArray();

        $data = [
            'title' => $title,
            'beritaPpid' => $beritaPpid,
            'total' => count($beritaPpid),
            'srcLogoPemrov' => $this->getBase64Image('assets/pemprov.jpg'),
            'srcLogoRsud' => $this->getBase64Image('assets/logo.png'),
            'startYear' => $startYear,
            'endYear' => $endYear
        ];

        return view('backend/beritappid/laporan-dip', $data);
    }

    /**
     * Save selected API data
     */
    public function saveSelectedData()
    {
        $selectedData = $this->request->getPost('pilih');

        if (empty($selectedData)) {
            return redirect()->to('/apiberitappid')->with('error', 'Tidak ada data yang dipilih.');
        }

        foreach ($selectedData as $beritaJson) {
            $berita = json_decode($beritaJson, true);

            if (!$berita || !isset($berita['id_content'])) {
                continue;
            }

            $existingData = $this->beritaPpidModel->where('idberita', $berita['id_content'])->first();
            if ($existingData) {
                return redirect()->to('/apiberitappid')->with('error', 'Data dengan ID Content ' . $berita['id_content'] . ' sudah ada di database.');
            }

            $data = [
                'idberita' => $berita['id_content'],
                'judul' => $berita['title_content'],
                'user_id' => $berita['id_user'],
                'tanggal' => $berita['created'],
                'konten' => $berita['title_sub_category'],
                'kategori_id' => $berita['id_category'],
                'tahun' => $berita['tahun'],
                'jangka' => $berita['jangka_waktu'],
                'tempat' => $berita['tgl_dan_tempat'],
                'penanggung_jawab' => $berita['penanggung_jawab'],
                'filetype' => 'pdf.png',
                'link' => $berita['downloads'],
                'download' => $berita['hits'],
                'slug' => createSlug($berita['title_content']),
                'nm_status' => $berita['nm_status'],
            ];

            $this->beritaPpidModel->insert($data);
        }

        return redirect()->to('/apiberitappid')->with('success', 'Data berhasil disimpan.');
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