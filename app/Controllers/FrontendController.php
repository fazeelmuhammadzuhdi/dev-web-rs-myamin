<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Album;
use App\Models\AlbumList;
use App\Models\Banner;
use App\Models\Berita;
use App\Models\BeritaPPID;
use App\Models\Dokter;
use App\Models\Fasilitas;
use App\Models\FormulirPPID;
use App\Models\IndikatorMutu;
use App\Models\IndikatorMutuList;
use App\Models\Inovasi;
use App\Models\JadwalPoli;
use App\Models\Kategori;
use App\Models\KeberatanInformasiPPID;
use App\Models\ManajemenProfil;
use App\Models\PagesPPID;
use App\Models\PageVisit;
use App\Models\Pesan;
use App\Models\Polling;
use App\Models\Profil;
use App\Models\Referensi;
use App\Models\Slider;
use App\Models\Spesialis;
use App\Models\Tentang;
use App\Models\Video;
use App\Models\Poli;
use App\Models\Rawat;
use App\Models\Penunjang;
use App\Models\ProfilPPID;
use App\Models\Sejarah;

/**
 * FrontendController handles all frontend-related functionality
 * 
 * This controller manages the public-facing pages of the application,
 * including news, galleries, doctor information, and PPID content.
 */
class FrontendController extends BaseController
{
    // Constants for better maintainability
    private const STATUS_PUBLISHED = 'PB';
    private const STATUS_ACTIVE = 'Y';
    private const STATUS_READ = 'RD';
    private const DEFAULT_PAGINATION_LIMIT = 8;
    private const GALLERY_PAGINATION_LIMIT = 9;
    private const REFERENCE_PAGINATION_LIMIT = 12;
    private const NEWS_LIMIT = 3;
    private const SIDEBAR_NEWS_LIMIT = 8;
    
    // Model instances
    private array $models = [];

    // protected function loadBeritaKategori($kategori)
    // {
    //     return [
    //         'countBeritaPPID'   => $this->beritappid->countBeritaByKategori($kategori),
    //         'popularBeritaPPID' => $this->beritappid->getPopularBerita($kategori),
    //         'beritaTerbaruPPID' => $this->beritappid->getLatestBerita($kategori),
    //         'total'             => $this->beritappid
    //             ->where('nm_status', 'Publish')
    //             ->whereIn('kategori_id', $kategori)
    //             ->countAllResults(),
    //     ];
    // }

    private function sidebarData(string $context): array
    {
        if ($context === 'ppid') {
            $kategori = BeritaPPID::KATEGORI_PPID;
            $title    = 'Daftar Informasi Publik';
            $url      = 'dip';
            $header = 'Kategori Informasi PPID';
        } else {
            $kategori = BeritaPPID::KATEGORI_PKRS;
            $title    = 'Media Informasi PKRS';
            $url      = 'pkrs';
            $header = 'Kategori Informasi PKRS';
        }

        return [
            'title'             => $title,
            'url'               => $url,
            'header'            => $header,
            'countBeritaPPID'   => $this->beritappid->countBeritaByKategori($kategori),
            'popularBeritaPPID' => $this->beritappid->getPopularBerita($kategori),
            'beritaTerbaruPPID' => $this->beritappid->getLatestBerita($kategori),
            'total'             => $this->beritappid->where('nm_status', 'Publish')->whereIn('kategori_id', $kategori)->countAllResults(),
        ];
    }



    /**
     * Initialize the controller and load required models
     */
    public function __construct()
    {
        $this->initializeModels();
        helper('string');
    }

    /**
     * Initialize all required models
     */
    private function initializeModels(): void
    {
        $modelClasses = [
            'tentangkami' => Tentang::class,
            'sejarah' => Sejarah::class,
            'visimisi' => Profil::class,
            'polling' => Polling::class,
            'slider' => Slider::class,
            'berita' => Berita::class,
            'kategori' => Kategori::class,
            'video' => Video::class,
            'profil' => Profil::class,
            'jadwalpoli' => JadwalPoli::class,
            'banner' => Banner::class,
            'albumlist' => AlbumList::class,
            'album' => Album::class,
            'indikatormutulist' => IndikatorMutuList::class,
            'indikatormutu' => IndikatorMutu::class,
            'spesialis' => Spesialis::class,
            'dokter' => Dokter::class,
            'profilmanajemen' => ManajemenProfil::class,
            'referensi' => Referensi::class,
            'pesan' => Pesan::class,
            'poli' => Poli::class,
            'rawat' => Rawat::class,
            'penunjang' => Penunjang::class,
            'fasilitas' => Fasilitas::class,
            'pages' => PagesPPID::class,
            'beritappid' => BeritaPPID::class,
            'pagevisit' => PageVisit::class,
            'profilPPID' => ProfilPPID::class,
            'permohononanInformasi' => FormulirPPID::class,
            'keberatanInformasi' => KeberatanInformasiPPID::class,
            'daftarinovasi' => Inovasi::class,
        ];

        foreach ($modelClasses as $property => $class) {
            $this->models[$property] = new $class();
        }
    }

    /**
     * Get model instance by name
     */
    private function getModel(string $name)
    {
        return $this->models[$name] ?? null;
    }

    /**
     * Display the home page with latest content
     */
    public function index()
    {
        $this->trackPageVisit();
        
        $data = array_merge(
            $this->getHomePageNewsData(),
            $this->getHomePageMediaData(),
            $this->getHomePageProfileData(),
            $this->getHomePageSidebarData(),
            ['title' => 'Home']
        );

        return view('frontend/main/home', $data);
    }

    /**
     * Track page visit for analytics
     */
    private function trackPageVisit(): void
    {
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();
        $date = date('Y-m-d');
        
        $this->getModel('pagevisit')->incrementCount($date, $ipAddress, $userAgent);
    }

    /**
     * Get news data for home page
     */
    private function getHomePageNewsData(): array
    {
        $beritaModel = $this->getModel('berita');
        
        // Get latest news
        $beritaNew = $beritaModel
            ->orderBy('tanggal', 'DESC')
            ->orderBy('idberita', 'DESC')
            ->where('status', self::STATUS_PUBLISHED)
            ->first();

        $beritaOne = [];
        if ($beritaNew) {
            $beritaOne = $beritaModel
                ->orderBy('tanggal', 'DESC')
                ->where('status', self::STATUS_PUBLISHED)
                ->whereNotIn('idberita', [$beritaNew['idberita']])
                ->limit(6)
                ->findAll();
        }

        return [
            'beritaNew' => $beritaNew,
            'beritaOne' => $beritaOne,
            'berita' => $beritaModel
                ->orderBy('tanggal', 'DESC')
                ->orderBy('idberita', 'DESC')
                ->where('status', self::STATUS_PUBLISHED)
                ->limit(self::NEWS_LIMIT)
                ->findAll()
        ];
    }

    /**
     * Get media data for home page
     */
    private function getHomePageMediaData(): array
    {
        return [
            'videoNew' => $this->getModel('video')
                ->orderBy('tanggal', 'DESC')
                ->where('status', self::STATUS_PUBLISHED)
                ->limit(self::NEWS_LIMIT)
                ->findAll(),
            'slider' => $this->getModel('slider')->findAll(),
            'banner' => $this->getModel('banner')
                ->orderBy('idbanner', 'ASC')
                ->where('status', self::STATUS_PUBLISHED)
                ->findAll(),
            'galeri' => $this->getModel('album')->getGaleri()
        ];
    }

    /**
     * Get profile data for home page
     */
    private function getHomePageProfileData(): array
    {
        $profil = $this->getModel('profil')->first();
        
        return [
            'profil' => $profil,
            'parsedMisi' => $this->parseMisiContent($profil['misi'] ?? ''),
            'tentangKami' => $this->getModel('tentangkami')->first()
        ];
    }

    /**
     * Get sidebar data for home page
     */
    private function getHomePageSidebarData(): array
    {
        return [
            'polling' => $this->getModel('polling')->getFormattedPollingData(),
            'vote' => $this->getModel('polling')->getPollingData(),
            'jadwalpoli' => $this->getModel('jadwalpoli')->getFormattedJadwalPoliWithDokter(),
            'pesanNew' => $this->getModel('pesan')
                ->where('status', self::STATUS_PUBLISHED)
                ->where('status_baca', self::STATUS_READ)
                ->orderBy('tanggal', 'DESC')
                ->findAll()
        ];
    }

    /**
     * Parse mission content from HTML
     */
    private function parseMisiContent(string $misiHtml): array
    {
        if (empty($misiHtml)) {
            return [];
        }

        $parsedMisi = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($misiHtml);
        $divs = $dom->getElementsByTagName('p');

        foreach ($divs as $div) {
            $textContent = trim($div->textContent);
            $cleanedText = preg_replace('/^\d+\.\s*/', '', $textContent);
            $items = preg_split('/\d+\.\s+/', $cleanedText, -1, PREG_SPLIT_NO_EMPTY);
            
            foreach ($items as $item) {
                $parsedMisi[] = trim($item);
            }
        }

        return $parsedMisi;
    }

    /**
     * Display about us page
     */
    public function tentangKami()
    {
        $data = array_merge(
            $this->getCommonPageData(),
            [
                'tentangKami' => $this->getModel('tentangkami')->first(),
                'profil' => $this->getModel('profil')->first(),
                'slider' => $this->getModel('slider')
                    ->orderBy('idslider', 'ASC')
                    ->limit(5)
                    ->findAll(),
                'title' => 'Tentang Kami'
            ]
        );

        return view('frontend/tentang-kami', $data);
    }

    /**
     * Display organization structure page
     */
    public function strukturOrganisasi()
    {
        $data = [
            'title' => 'Struktur Organisasi',
            'tentangKami' => $this->getModel('tentangkami')->first()
        ];
        
        return view('frontend/struktur-organisasi', $data);
    }

    /**
     * Display history page
     */
    public function sejarah()
    {
        $data = [
            'tentangKami' => $this->getModel('tentangkami')->first(),
            'sejarah' => $this->getModel('sejarah')
                ->orderBy('tahun', 'ASC')
                ->findAll(),
            'title' => 'Sejarah & Perkembangan'
        ];
        
        return view('frontend/sejarah', $data);
    }

    /**
     * Display vision and mission page
     */
    public function visiMisi()
    {
        $data = [
            'visiMisi' => $this->getModel('visimisi')->first(),
            'title' => 'Visi & Misi'
        ];

        return view('frontend/visi-misi', $data);
    }

    /**
     * Display quality indicators page
     */
    public function indikatorMutu()
    {
        $data = [
            'indikatorMutu' => $this->getModel('indikatormutu')->getIndikatorMutu(),
            'title' => 'Indikator Mutu'
        ];
        
        return view('frontend/indikator-mutu', $data);
    }

    /**
     * Display management profile page
     */
    public function profilManajemen()
    {
        $data = [
            'profilManajemen' => $this->getModel('profilmanajemen')->getProfilManajemen(),
            'title' => 'Profil Manajemen'
        ];

        return view('frontend/profil-manajemen', $data);
    }

    /**
     * Get common data used across multiple pages
     */
    private function getCommonPageData(): array
    {
        return [
            'vote' => $this->getModel('polling')->getPollingData(),
            'polling' => $this->getModel('polling')->getFormattedPollingData()
        ];
    }



    /**
     * Display doctors page with search functionality
     */
    public function dokterKami()
    {
        $searchParams = $this->getDoctorSearchParams();
        $spesialisList = $this->getActiveSpesialis();
        
        if ($this->hasSearchCriteria($searchParams)) {
            return $this->displayDoctorSearchResults($searchParams, $spesialisList);
        }
        
        return $this->displayAllDoctorsBySpesialis($searchParams, $spesialisList);
    }

    /**
     * Get search parameters from request
     */
    private function getDoctorSearchParams(): array
    {
        return [
            'nama_spesialis' => $this->request->getGet('nama_spesialis'),
            'nama_dokter' => $this->request->getGet('nama_dokter')
        ];
    }

    /**
     * Get all active spesialis
     */
    private function getActiveSpesialis(): array
    {
        return $this->getModel('spesialis')
            ->where('status', self::STATUS_ACTIVE)
            ->orderBy('nama', 'asc')
            ->findAll();
    }

    /**
     * Check if search criteria exists
     */
    private function hasSearchCriteria(array $searchParams): bool
    {
        return !empty($searchParams['nama_spesialis']) || !empty($searchParams['nama_dokter']);
    }

    /**
     * Display search results for doctors
     */
    private function displayDoctorSearchResults(array $searchParams, array $spesialisList): string
    {
        $dokterResults = $this->searchDoctors($searchParams);
        
        return view('frontend/dokter', [
            'spesialisList' => $spesialisList,
            'dokterResults' => $dokterResults,
            'selectedSpesialis' => $searchParams['nama_spesialis'],
            'searchedNamaDokter' => $searchParams['nama_dokter'],
        ]);
    }

    /**
     * Search doctors based on criteria
     */
    private function searchDoctors(array $searchParams): array
    {
        $dokterQuery = $this->getModel('dokter')->getDokterWithSpesialis();

        if (!empty($searchParams['nama_spesialis'])) {
            $dokterQuery->where('spesialis_id', $searchParams['nama_spesialis']);
        }

        if (!empty($searchParams['nama_dokter'])) {
            $dokterQuery->like('dokter.nama', $searchParams['nama_dokter']);
        }

        return $dokterQuery->orderBy('nama', 'asc')->get()->getResultArray();
    }

    /**
     * Display all doctors grouped by spesialis
     */
    private function displayAllDoctorsBySpesialis(array $searchParams, array $spesialisList): string
    {
        $data = [
            'spesialis' => $this->getDoctorsBySpesialis($spesialisList),
            'spesialisList' => $spesialisList,
            'selectedSpesialis' => $searchParams['nama_spesialis'],
            'searchedNamaDokter' => $searchParams['nama_dokter'],
            'dokterResults' => []
        ];

        return view('frontend/dokter', $data);
    }

    /**
     * Get doctors grouped by spesialis
     */
    private function getDoctorsBySpesialis(array $spesialisList): array
    {
        $spesialisData = [];
        
        foreach ($spesialisList as $spesialis) {
            $dokter = $this->getModel('dokter')
                ->where('status', self::STATUS_ACTIVE)
                ->where('spesialis_id', $spesialis['idspesialis'])
                ->orderBy('nama', 'asc')
                ->findAll();

            $spesialisData[$spesialis['idspesialis']] = [
                'nama' => $spesialis['nama'],
                'dokter' => $dokter,
            ];
        }

        return $spesialisData;
    }


    /**
     * Display photo gallery page
     */
    public function galeriFoto()
    {
        $albumModel = $this->getModel('album');
        
        $data = [
            'galeriFoto' => $albumModel->getGaleriFoto(),
            'pager' => $albumModel->pager,
            'title' => 'Galeri Foto'
        ];

        return view('frontend/galeri-foto', $data);
    }

    /**
     * Display photo gallery detail page
     */
    public function galeriFotoDetail($slug)
    {
        $data = [
            'galeriFotoDetail' => $this->getModel('album')->getGaleriFotoDetail($slug),
            'title' => 'Galeri Foto Detail'
        ];

        return view('frontend/galeri-foto-detail', $data);
    }

    /**
     * Display video gallery page
     */
    public function galeriVideo()
    {
        $videoModel = $this->getModel('video');
        
        $data = [
            'galeriVideo' => $videoModel
                ->where('status', self::STATUS_PUBLISHED)
                ->orderBy('tanggal', 'DESC')
                ->paginate(self::GALLERY_PAGINATION_LIMIT),
            'pager' => $videoModel->pager,
            'title' => 'Galeri Video'
        ];

        return view('frontend/galeri-video', $data);
    }

    /**
     * Display news page with search functionality
     */
    public function berita()
    {
        $searchQuery = $this->request->getGet('q');
        $beritaModel = $this->getModel('berita');
        
        $data = array_merge(
            $this->getNewsSidebarData($beritaModel),
            [
                'kategori' => $beritaModel->countBeritaByKategori(),
                'berita' => $this->getNewsData($beritaModel, $searchQuery),
                'searchQuery' => $searchQuery,
                'pager' => $beritaModel->pager,
                'title' => 'Berita'
            ]
        );

        return view('frontend/berita', $data);
    }

    /**
     * Get news data based on search query
     */
    private function getNewsData($beritaModel, ?string $searchQuery): array
    {
        $query = $beritaModel
            ->where('status', self::STATUS_PUBLISHED)
            ->orderBy('tanggal', 'desc')
            ->orderBy('idberita', 'desc');

        if ($searchQuery) {
            $query->like('judul', $searchQuery);
        }

        return $query->paginate(self::DEFAULT_PAGINATION_LIMIT);
    }

    /**
     * Get sidebar data for news page
     */
    private function getNewsSidebarData($beritaModel): array
    {
        return [
            'beritaMostView' => $beritaModel
                ->orderBy('viewberita', 'desc')
                ->where('status', self::STATUS_PUBLISHED)
                ->limit(self::SIDEBAR_NEWS_LIMIT)
                ->findAll(),
            'beritaTerbaru' => $beritaModel
                ->orderBy('tanggal', 'desc')
                ->orderBy('idberita', 'desc')
                ->where('status', self::STATUS_PUBLISHED)
                ->limit(self::SIDEBAR_NEWS_LIMIT)
                ->findAll()
        ];
    }

    /**
     * Display news detail page
     */
    public function beritaDetail($slug)
    {
        $beritaModel = $this->getModel('berita');
        $beritaDetail = $beritaModel->where('slug', $slug)->first();

        if (!$beritaDetail) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita Tidak Ditemukan');
        }

        // Increment view count
        $beritaModel->incrementViewCount($beritaDetail['idberita']);
        
        // Refresh data after view count increment
        $beritaDetail = $beritaModel->where('slug', $slug)->first();

        $data = array_merge(
            $this->getNewsSidebarData($beritaModel),
            [
                'kategori' => $beritaModel->countBeritaByKategori(),
                'beritaDetail' => $beritaDetail,
                'beritaLainnya' => $this->getRelatedNews($beritaModel, $beritaDetail['kategori_id'], $slug),
                'title' => 'Detail Berita'
            ]
        );

        return view('frontend/berita-detail', $data);
    }

    /**
     * Get related news from the same category
     */
    private function getRelatedNews($beritaModel, int $kategoriId, string $currentSlug): array
    {
        return $beritaModel
            ->where('kategori_id', $kategoriId)
            ->where('slug !=', $currentSlug)
            ->orderBy('tanggal', 'desc')
            ->limit(4)
            ->findAll();
    }

    /**
     * Display news by category
     */
    public function beritaKategori($idkategori)
    {
        $kategori = $this->getModel('kategori')
            ->where('status', self::STATUS_ACTIVE)
            ->where('idkategori', $idkategori)
            ->first();

        if (!$kategori) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Kategori Tidak Ditemukan');
        }

        $beritaModel = $this->getModel('berita');
        
        $data = array_merge(
            $this->getNewsSidebarData($beritaModel),
            [
                'berita' => $beritaModel
                    ->where('kategori_id', $kategori['idkategori'])
                    ->where('status', self::STATUS_PUBLISHED)
                    ->orderBy('tanggal', 'desc')
                    ->paginate(10),
                'pager' => $beritaModel->pager,
                'selectedKategori' => $idkategori,
                'kategori' => $beritaModel->countBeritaByKategori(),
                'title' => 'Berita Kategori'
            ]
        );

        return view('frontend/berita-kategori', $data);
    }

    /**
     * Display reference corner page with search functionality
     */
    public function pojokReferensi()
    {
        $searchParams = $this->getReferenceSearchParams();
        $referensiModel = $this->getModel('referensi');
        
        $data = [
            'referensi' => $this->getReferenceData($referensiModel, $searchParams),
            'namaKategori' => $searchParams['nama_kategori'],
            'namaJudul' => $searchParams['nama_judul'],
            'pager' => $referensiModel->pager,
            'title' => 'Pojok Referensi'
        ];

        return view('frontend/pojok-referensi', $data);
    }

    /**
     * Get reference search parameters
     */
    private function getReferenceSearchParams(): array
    {
        return [
            'nama_kategori' => $this->request->getGet('nama_kategori'),
            'nama_judul' => $this->request->getGet('nama_judul')
        ];
    }

    /**
     * Get reference data based on search criteria
     */
    private function getReferenceData($referensiModel, array $searchParams): array
    {
        $query = $referensiModel->orderBy('judul', 'asc');

        if (!empty($searchParams['nama_kategori'])) {
            $query->where('kategori', $searchParams['nama_kategori']);
        }

        if (!empty($searchParams['nama_judul'])) {
            $query->groupStart()
                ->like('judul', $searchParams['nama_judul'])
                ->orLike('pengarang', $searchParams['nama_judul'])
                ->orLike('penerbit', $searchParams['nama_judul'])
                ->groupEnd();
        }

        return $query->paginate(self::REFERENCE_PAGINATION_LIMIT);
    }


    /**
     * Display contact page
     */
    public function kontak()
    {
        $data = array_merge(
            $this->getCommonPageData(),
            [
                'profil' => $this->getModel('profil')->first(),
                'title' => 'Kontak'
            ]
        );

        return view('frontend/kontak', $data);
    }

    /**
     * Display IGD (Emergency Room) page
     */
    public function igd()
    {
        $data = [
            'igd' => $this->getModel('rawat')
                ->where('slug', 'igd')
                ->where('status', self::STATUS_ACTIVE)
                ->first(),
            'title' => 'IGD'
        ];
        
        return view('frontend/igd', $data);
    }

    /**
     * Display polyclinic page
     */
    public function poliklinik()
    {
        $data = [
            'poli' => $this->getModel('poli')
                ->where('status', self::STATUS_ACTIVE)
                ->findAll(),
            'title' => 'Poliklinik'
        ];
        
        return view('frontend/poliklinik', $data);
    }

    /**
     * Display polyclinic detail page
     */
    public function detailPoliklinik($namaPoli)
    {
        $data = [
            'jadwalpoli' => $this->getModel('jadwalpoli')->getJadwalPoliWithDokter($namaPoli),
            'title' => "Poliklinik $namaPoli"
        ];

        return view('frontend/detail-poliklinik', $data);
    }

    /**
     * Display inpatient care page
     */
    public function rawatInap($nama)
    {
        $data = [
            'rawatInap' => $this->getModel('rawat')->where('slug', $nama)->first(),
            'namaRawat' => $nama,
            'title' => "Rawat Inap $nama"
        ];
        
        return view('frontend/rawat-inap', $data);
    }

    /**
     * Display supporting services page
     */
    public function penunjang($nama)
    {
        $data = [
            'penunjang' => $this->getModel('penunjang')->where('nama', $nama)->first(),
            'namaPenunjang' => $nama,
            'title' => $nama
        ];

        return view('frontend/penunjang', $data);
    }

    /**
     * Display facilities page
     */
    public function fasilitas($nama)
    {
        $data = [
            'fasilitas' => $this->getModel('fasilitas')->where('slug', $nama)->first(),
            'namaFasilitas' => $nama,
            'title' => $nama
        ];

        return view('frontend/fasilitas', $data);
    }

    /**
     * Display PPID vision and mission page
     */
    public function visiMisiPPID()
    {
        $data = [
            'profilPpid' => $this->getModel('profilPPID')->first(),
            'title' => 'Visi Misi PPID'
        ];

        return view('frontend/visi-misi-ppid', $data);
    }

    /**
     * Display PPID duties and functions page
     */
    public function tugasFungsiPPID()
    {
        $data = [
            'profilPpid' => $this->getModel('profilPPID')->first(),
            'title' => 'Tugas Fungsi PPID'
        ];

        return view('frontend/tugas-fungsi-ppid', $data);
    }

    /**
     * Display PPID brief profile page
     */
    public function profilSingkat()
    {
        $data = [
            'title' => 'Profil Singkat PPID',
            'profilPpid' => $this->getModel('profilPPID')->first()
        ];
        
        return view('frontend/profil-singkat-ppid', $data);
    }

    /**
     * Display KIP regulations page
     */
    public function peraturanKIP()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/peraturan-kip', $data);
    }

    /**
     * Display bed information page
     */
    public function tempatTidur()
    {
        $data = [
            'informasiTempatTidur' => $this->getModel('tentangkami')->first()
        ];
        
        return view('frontend/tempat-tidur', $data);
    }

    /**
     * Display PPID announcement page
     */
    public function maklumat()
    {
        $data = [
            'profilPpid' => $this->getModel('profilPPID')->first()
        ];
        
        return view('frontend/maklumat-ppid', $data);
    }

    /**
     * Display information request flow page
     */
    public function alurPermohonanInformasi()
    {
        return view('frontend/alur-permohonan-informasi');
    }

    /**
     * Display objection submission flow page
     */
    public function alurPengajuanKeberatan()
    {
        return view('frontend/alur-pengajuan-keberatan');
    }

    /**
     * Display dispute resolution flow page
     */
    public function alurPenyelesaianSengketa()
    {
        return view('frontend/alur-penyelesaian-sengketa');
    }

    /**
     * Display PPID structure page
     */
    public function strukturPPID()
    {
        return view('frontend/struktur-ppid');
    }

    /**
     * Display financial report page with pagination
     */
    public function laporan($slug)
    {
        $page = $this->getPageParameter();
        $perPage = self::REFERENCE_PAGINATION_LIMIT;
        
        $laporanData = $this->getLaporanData($slug);
        $parsedContent = $this->parseLaporanContent($laporanData['konten']);
        
        $data = array_merge(
            $this->sidebarData('ppid'),
            [
                'laporan' => $this->prepareLaporanData($laporanData, $parsedContent, $page, $perPage),
                'pagination' => $this->createPagination($page, $perPage, count($parsedContent))
            ]
        );

        return view('frontend/laporan-keuangan', $data);
    }

    /**
     * Get page parameter from request
     */
    private function getPageParameter(): int
    {
        return isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }

    /**
     * Get laporan data by slug
     */
    private function getLaporanData(string $slug): array
    {
        $laporan = $this->getModel('pages')->where('slug', $slug)->first();
        
        if (!$laporan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Laporan Tidak Ditemukan');
        }
        
        return $laporan;
    }

    /**
     * Parse HTML content from laporan
     */
    private function parseLaporanContent(string $konten): array
    {
        if (empty($konten)) {
            return [];
        }

        $parsedContent = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($konten);

        // Parse <li> elements
        $lis = $dom->getElementsByTagName('li');
        foreach ($lis as $li) {
            $a = $li->getElementsByTagName('a')->item(0);
            if ($a) {
                $parsedContent[] = [
                    'href' => $a->getAttribute('href'),
                    'text' => $a->textContent
                ];
            } else {
                $parsedContent[] = ['text' => trim($li->textContent)];
            }
        }

        // Parse <p> elements
        $ps = $dom->getElementsByTagName('p');
        foreach ($ps as $p) {
            $textContent = trim($p->textContent);
            if (!empty($textContent)) {
                $parsedContent[] = ['text' => $textContent];
            }
        }

        return $parsedContent;
    }

    /**
     * Prepare laporan data for view
     */
    private function prepareLaporanData(array $laporanData, array $parsedContent, int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;
        $currentPageData = array_slice($parsedContent, $offset, $perPage);

        return [
            'title' => $laporanData['title'],
            'items' => $currentPageData,
            'gambar' => $laporanData['gambar']
        ];
    }

    /**
     * Create pagination links
     */
    private function createPagination(int $page, int $perPage, int $totalItems): string
    {
        $pager = \Config\Services::pager();
        return $pager->makeLinks($page, $perPage, $totalItems);
    }


    /**
     * Increment download count for PPID news
     */
    public function incrementDownloadCount()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak valid']);
        }

        $beritaModel = $this->getModel('beritappid');
        $berita = $beritaModel->find($id);

        if (!$berita) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Berita tidak ditemukan']);
        }

        $berita['download'] = ($berita['download'] ?? 0) + 1;
        $beritaModel->update($id, $berita);

        return $this->response->setJSON(['status' => 'success']);
    }

    /**
     * Display public information list (DIP)
     */
    public function daftarInformasiPublik()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            [
                'dip' => $this->getModel('beritappid')
                    ->getAllDataPPID(BeritaPPID::KATEGORI_PPID)
                    ->get()
                    ->getResultArray()
            ]
        );

        return view('frontend/dip', $data);
    }

    /**
     * Display PPID news by category
     */
    public function beritaKategoriPPID($kategoriPpid)
    {
        $beritaList = $this->getModel('beritappid')
            ->where('kategori_id', $kategoriPpid)
            ->where('status', self::STATUS_ACTIVE)
            ->where('nm_status', 'Publish')
            ->orderBy('tanggal', 'desc')
            ->findAll();

        $context = in_array($kategoriPpid, BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

        $data = array_merge(
            $this->sidebarData($context),
            [
                'beritappid' => $beritaList,
                'selectedKategori' => $kategoriPpid,
            ]
        );

        return view('frontend/berita-kategori-ppid', $data);
    }

    /**
     * Display PPID news detail page
     */
    public function beritaDetailPPID($idBeritaPpid)
    {
        $beritaModel = $this->getModel('beritappid');
        $beritaDetail = $beritaModel->where('idberita', $idBeritaPpid)->first();

        if (!$beritaDetail) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita Tidak Ditemukan');
        }

        // Increment view count
        $beritaModel->incrementViewCount($beritaDetail['idberita']);
        $detail = $beritaModel->getBeritaPpidById($idBeritaPpid);

        $context = in_array($detail['kategori_id'], BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

        $data = array_merge(
            $this->sidebarData($context),
            ['beritaDetailPpid' => $detail]
        );

        return view('frontend/berita-detail-ppid', $data);
    }
    /**
     * Display PKRS information list
     */
    public function daftarInformasiPkrs()
    {
        $data = array_merge(
            $this->sidebarData('pkrs'),
            [
                'dip' => $this->getModel('beritappid')
                    ->getAllDataPPID(BeritaPPID::KATEGORI_PKRS)
                    ->get()
                    ->getResultArray(),
            ]
        );

        return view('frontend/dip', $data);
    }

    /**
     * Display PKRS news by slug
     */
    public function pkrs($slug)
    {
        $beritaList = $this->getModel('beritappid')
            ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
            ->where('kategori_informasi_ppid.slug', $slug)
            ->where('kategori_informasi_ppid.status', self::STATUS_ACTIVE)
            ->where('nm_status', 'Publish')
            ->orderBy('tanggal', 'desc')
            ->findAll();

        $data = array_merge(
            $this->sidebarData('pkrs'),
            [
                'beritappid' => $beritaList,
                'selectedKategori' => $slug,
            ]
        );

        return view('frontend/pkrs', $data);
    }

    /**
     * Display hospital facilities page
     */
    public function saranaRumahSakit()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/sarana', $data);
    }

    /**
     * Display PPID SOP page
     */
    public function sopPPID()
    {
        $data = $this->sidebarData('ppid');
        return view('frontend/sop-ppid', $data);
    }

    /**
     * Display cost standards page
     */
    public function standarBiaya()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/standar-biaya', $data);
    }

    /**
     * Display elderly and disabled services page
     */
    public function layananLansiaDanDifabel()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/layanan-lansia', $data);
    }

    /**
     * Display complaint procedures page
     */
    public function tataCaraPengaduan()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/tata-cara-pengaduan', $data);
    }

    /**
     * Display evacuation procedures page
     */
    public function prosedurEvakuasi()
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            ['profilPpid' => $this->getModel('profilPPID')->first()]
        );

        return view('frontend/prosedur-evakuasi', $data);
    }

    /**
     * Display service flow page
     */
    public function alurPelayanan($slug)
    {
        $data = array_merge(
            $this->sidebarData('ppid'),
            [
                'alurPelayanan' => $this->getModel('pages')
                    ->where('status', self::STATUS_ACTIVE)
                    ->where('slug', $slug)
                    ->first()
            ]
        );
        
        return view('frontend/alur-pelayanan', $data);
    }

    /**
     * Display PPID online form page
     */
    public function formPPIDOnline()
    {
        $data = $this->sidebarData('ppid');
        return view('frontend/form-ppid-online', $data);
    }

    /**
     * Display PPID objection form page
     */
    public function formKeberatanInformasiPPIDOnline()
    {
        $data = $this->sidebarData('ppid');
        return view('frontend/form-keberatan-informasi-online', $data);
    }

    /**
     * Display information fulfillment time page
     */
    public function waktuPemenuhanInformasi()
    {
        $data = [
            'permohonaninformasi' => $this->getModel('permohononanInformasi')
                ->orderBy('tanggal', 'desc')
                ->findAll(),
            'keberataninformasi' => $this->getModel('keberatanInformasi')
                ->orderBy('tanggal', 'desc')
                ->findAll()
        ];

        return view('frontend/waktu-pemenuhan-informasi', $data);
    }

    /**
     * Display innovation list page
     */
    public function daftarInovasi()
    {
        $data = [
            'inovasi' => $this->getModel('daftarinovasi')
                ->orderBy('tahun', 'desc')
                ->findAll()
        ];
        
        return view('frontend/daftar-inovasi', $data);
    }

    /**
     * Display innovation detail page
     */
    public function detailInovasi($id)
    {
        $data = [
            'inovasi' => $this->getModel('daftarinovasi')->find($id)
        ];
        
        return view('frontend/detail-inovasi', $data);
    }



    /**
     * API endpoint for polyclinic schedule
     */
    public function apiJadwalPoli()
    {
        $jadwalPoli = $this->getModel('jadwalpoli')->getFormattedJadwalPoliWithDokter();
        
        return $this->response->setJSON([
            'data' => $jadwalPoli
        ]);
    }

    /**
     * Display bio link page
     */
    public function linkBio()
    {
        return view('frontend/bio');
    }

    /**
     * Generate signature for API authentication
     */
    private function generateSign(array $data, int $xtimestamp): string
    {
        $key = $data["X_ID"] . "&" . $xtimestamp;
        return base64_encode(
            hash_hmac("sha256", utf8_encode($key), utf8_encode($data["X_PASS"]), true)
        );
    }

    /**
     * Display room information (BPJS integration)
     * Note: This method contains commented code for BPJS API integration
     */
    public function kamar()
    {
        date_default_timezone_set('UTC');

        // BPJS API configuration
        $apiConfig = [
            'X_ID' => '21308',
            'X_PASS' => '5rVC94D0CF',
            'cons_id' => '21308',
            'secret_key' => '5rVC94D0CF',
            'user_key' => '8c2756b1374e314d69eb9fa93e3a0a99',
            'kode_rs' => '0302R001',
            'start' => 1,
            'limit' => 1
        ];

        $timestamp = time();
        $signature = $this->generateSign($apiConfig, $timestamp);

        // For debugging purposes
        print_r([$signature, $timestamp]);
        
        // TODO: Implement actual BPJS API call when needed
        // The commented code below shows how to make the API call
        /*
        $url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws/rest/bed/read/{$apiConfig['kode_rs']}/{$apiConfig['start']}/{$apiConfig['limit']}";
        
        $headers = [
            'X-cons-id' => $apiConfig['cons_id'],
            'X-timestamp' => $timestamp,
            'X-signature' => $signature,
            'user_key' => $apiConfig['user_key'],
            'Accept' => 'application/json',
        ];

        $curl = \Config\Services::curlrequest();
        try {
            $response = $curl->request('GET', $url, [
                'headers' => $headers,
                'verify' => false
            ]);

            $body = $response->getBody();
            $result = json_decode($body, true);
            return view('kamar', ['data' => $result]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        */
    }
}
