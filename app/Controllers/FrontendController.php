THIS SHOULD BE A LINTER ERROR<?php

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
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * FrontendController handles all frontend-related requests
 * 
 * This controller manages the public-facing pages of the application,
 * including news, galleries, doctor information, and PPID (Public Information Disclosure) content.
 */
class FrontendController extends BaseController
{
    // Model instances - organized by functionality
    private Tentang $tentangModel;
    private Sejarah $sejarahModel;
    private Profil $profilModel;
    private Polling $pollingModel;
    private Slider $sliderModel;
    private Berita $beritaModel;
    private Kategori $kategoriModel;
    private Video $videoModel;
    private JadwalPoli $jadwalPoliModel;
    private Banner $bannerModel;
    private AlbumList $albumListModel;
    private Album $albumModel;
    private IndikatorMutuList $indikatorMutuListModel;
    private IndikatorMutu $indikatorMutuModel;
    private Spesialis $spesialisModel;
    private Dokter $dokterModel;
    private ManajemenProfil $manajemenProfilModel;
    private Referensi $referensiModel;
    private Pesan $pesanModel;
    private Poli $poliModel;
    private Rawat $rawatModel;
    private Penunjang $penunjangModel;
    private Fasilitas $fasilitasModel;
    private BeritaPPID $beritaPPIDModel;
    private PagesPPID $pagesModel;
    private PageVisit $pageVisitModel;
    private ProfilPPID $profilPPIDModel;
    private FormulirPPID $formulirPPIDModel;
    private KeberatanInformasiPPID $keberatanInformasiModel;
    private Inovasi $inovasiModel;


    /**
     * Get sidebar data for PPID pages
     * 
     * @param string $context The context (ppid or pkrs)
     * @return array Sidebar data
     */
    private function sidebarData(string $context): array
    {
        $config = $this->getSidebarConfig($context);
        
        return [
            'title'             => $config['title'],
            'url'               => $config['url'],
            'header'            => $config['header'],
            'countBeritaPPID'   => $this->beritaPPIDModel->countBeritaByKategori($config['kategori']),
            'popularBeritaPPID' => $this->beritaPPIDModel->getPopularBerita($config['kategori']),
            'beritaTerbaruPPID' => $this->beritaPPIDModel->getLatestBerita($config['kategori']),
            'total'             => $this->beritaPPIDModel
                ->where('nm_status', 'Publish')
                ->whereIn('kategori_id', $config['kategori'])
                ->countAllResults(),
        ];
    }
    
    /**
     * Get sidebar configuration based on context
     * 
     * @param string $context The context (ppid or pkrs)
     * @return array Sidebar configuration
     * @throws \InvalidArgumentException If context is invalid
     */
    private function getSidebarConfig(string $context): array
    {
        return match ($context) {
            'ppid' => [
                'kategori' => BeritaPPID::KATEGORI_PPID,
                'title'    => 'Daftar Informasi Publik',
                'url'      => 'dip',
                'header'   => 'Kategori Informasi PPID'
            ],
            'pkrs' => [
                'kategori' => BeritaPPID::KATEGORI_PKRS,
                'title'    => 'Media Informasi PKRS',
                'url'      => 'pkrs',
                'header'   => 'Kategori Informasi PKRS'
            ],
            default => throw new \InvalidArgumentException('Invalid context: ' . $context)
        };
    }



    /**
     * Constructor - Initialize models and helpers
     */
    public function __construct()
    {
        $this->initializeModels();
        helper(['string', 'url']);
    }
    
    /**
     * Initialize all model instances
     * 
     * @return void
     */
    private function initializeModels(): void
    {
        // Core content models
        $this->tentangModel = new Tentang();
        $this->sejarahModel = new Sejarah();
        $this->profilModel = new Profil();
        $this->pollingModel = new Polling();
        $this->sliderModel = new Slider();
        $this->bannerModel = new Banner();
        
        // News and content models
        $this->beritaModel = new Berita();
        $this->kategoriModel = new Kategori();
        $this->videoModel = new Video();
        $this->albumModel = new Album();
        $this->albumListModel = new AlbumList();
        
        // Medical services models
        $this->jadwalPoliModel = new JadwalPoli();
        $this->spesialisModel = new Spesialis();
        $this->dokterModel = new Dokter();
        $this->poliModel = new Poli();
        $this->rawatModel = new Rawat();
        $this->penunjangModel = new Penunjang();
        $this->fasilitasModel = new Fasilitas();
        
        // Quality and management models
        $this->indikatorMutuModel = new IndikatorMutu();
        $this->indikatorMutuListModel = new IndikatorMutuList();
        $this->manajemenProfilModel = new ManajemenProfil();
        
        // Reference and communication models
        $this->referensiModel = new Referensi();
        $this->pesanModel = new Pesan();
        
        // PPID models
        $this->beritaPPIDModel = new BeritaPPID();
        $this->pagesModel = new PagesPPID();
        $this->profilPPIDModel = new ProfilPPID();
        $this->formulirPPIDModel = new FormulirPPID();
        $this->keberatanInformasiModel = new KeberatanInformasiPPID();
        $this->inovasiModel = new Inovasi();
        
        // Analytics model
        $this->pageVisitModel = new PageVisit();
    }

    /**
     * Display the home page
     * 
     * @return string Rendered home page view
     */
    public function index(): string
    {
        try {
            // Track page visit
            $this->trackPageVisit();

            // Get home page data
            $data = $this->getHomePageData();
            $data['title'] = 'Home';

            return view('frontend/main/home', $data);
        } catch (\Exception $e) {
            log_message('error', 'Home page error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load home page');
        }
    }

    /**
     * Track page visit for analytics
     * 
     * @return void
     */
    private function trackPageVisit(): void
    {
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();
        $date = date('Y-m-d');

        $this->pageVisitModel->incrementCount($date, $ipAddress, $userAgent);
    }

    /**
     * Get all data needed for home page
     * 
     * @return array Home page data
     */
    private function getHomePageData(): array
    {
        return [
            'beritaNew' => $this->getLatestNews(),
            'beritaOne' => $this->getAdditionalNews(),
            'berita' => $this->getRecentNews(3),
            'videoNew' => $this->getRecentVideos(3),
            'slider' => $this->sliderModel->findAll(),
            'profil' => $this->profilModel->first(),
            'parsedMisi' => $this->parseMisiContent(),
            'tentangKami' => $this->tentangModel->first(),
            'polling' => $this->pollingModel->getFormattedPollingData(),
            'vote' => $this->pollingModel->getPollingData(),
            'jadwalpoli' => $this->jadwalPoliModel->getFormattedJadwalPoliWithDokter(),
            'banner' => $this->bannerModel->orderBy('idbanner', 'ASC')->where('status', 'PB')->findAll(),
            'galeri' => $this->albumModel->getGaleri(),
            'pesanNew' => $this->pesanModel->where('status', 'PB')->where('status_baca', 'RD')->orderBy('tanggal', 'DESC')->findAll(),
        ];
    }

    /**
     * Get the latest published news
     * 
     * @return array|null Latest news or null if none found
     */
    private function getLatestNews(): ?array
    {
        return $this->beritaModel
            ->orderBy('tanggal', 'DESC')
            ->orderBy('idberita', 'DESC')
            ->where('status', 'PB')
            ->first();
    }

    /**
     * Get additional news excluding the latest one
     * 
     * @return array Additional news
     */
    private function getAdditionalNews(): array
    {
        $latestNews = $this->getLatestNews();
        
        if (!$latestNews) {
            return [];
        }

        return $this->beritaModel
            ->orderBy('tanggal', 'DESC')
            ->where('status', 'PB')
            ->whereNotIn('idberita', [$latestNews['idberita']])
            ->limit(6)
            ->findAll();
    }

    /**
     * Get recent news with limit
     * 
     * @param int $limit Number of news to retrieve
     * @return array Recent news
     */
    private function getRecentNews(int $limit = 3): array
    {
        return $this->beritaModel
            ->orderBy('tanggal', 'DESC')
            ->orderBy('idberita', 'DESC')
            ->where('status', 'PB')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Get recent videos with limit
     * 
     * @param int $limit Number of videos to retrieve
     * @return array Recent videos
     */
    private function getRecentVideos(int $limit = 3): array
    {
        return $this->videoModel
            ->orderBy('tanggal', 'DESC')
            ->where('status', 'PB')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Parse misi content from HTML
     * 
     * @return array Parsed misi items
     */
    private function parseMisiContent(): array
    {
        $profil = $this->profilModel->first();
        if (!$profil || empty($profil['misi'])) {
            return [];
        }

        $parsedMisi = [];
        $dom = new \DOMDocument();
        
        // Suppress HTML parsing errors
        libxml_use_internal_errors(true);
        $dom->loadHTML($profil['misi']);
        libxml_clear_errors();

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
     * 
     * @return string Rendered about us view
     */
    public function tentangKami(): string
    {
        $data = [
            'tentangKami' => $this->tentangModel->first(),
            'profil' => $this->profilModel->first(),
            'vote' => $this->pollingModel->getPollingData(),
            'slider' => $this->sliderModel->orderBy('idslider', 'ASC')->limit(5)->findAll(),
            'polling' => $this->pollingModel->getFormattedPollingData(),
            'title' => 'Tentang Kami'
        ];

        return view('frontend/tentang-kami', $data);
    }

    /**
     * Display organizational structure page
     * 
     * @return string Rendered organizational structure view
     */
    public function strukturOrganisasi(): string
    {
        $data = [
            'title' => 'Struktur Organisasi',
            'tentangKami' => $this->tentangModel->first()
        ];
        
        return view('frontend/struktur-organisasi', $data);
    }

    /**
     * Display history page
     * 
     * @return string Rendered history view
     */
    public function sejarah(): string
    {
        $data = [
            'tentangKami' => $this->tentangModel->first(),
            'sejarah' => $this->sejarahModel->orderBy('tahun', 'ASC')->findAll(),
            'title' => 'Sejarah & Perkembangan'
        ];
        
        return view('frontend/sejarah', $data);
    }

    /**
     * Display vision and mission page
     * 
     * @return string Rendered vision and mission view
     */
    public function visiMisi(): string
    {
        $data = [
            'visiMisi' => $this->profilModel->first(),
            'title' => 'Visi & Misi'
        ];

        return view('frontend/visi-misi', $data);
    }

    /**
     * Display quality indicators page
     * 
     * @return string Rendered quality indicators view
     */
    public function indikatorMutu(): string
    {
        $data = [
            'indikatorMutu' => $this->indikatorMutuModel->getIndikatorMutu(),
            'title' => 'Indikator Mutu'
        ];
        
        return view('frontend/indikator-mutu', $data);
    }

    /**
     * Display management profile page
     * 
     * @return string Rendered management profile view
     */
    public function profilManajemen(): string
    {
        $data = [
            'profilManajemen' => $this->manajemenProfilModel->getProfilManajemen(),
            'title' => 'Profil Manajemen'
        ];

        return view('frontend/profil-manajemen', $data);
    }



    /**
     * Display doctors page with search functionality
     * 
     * @return string Rendered doctors view
     */
    public function dokterKami(): string
    {
        try {
            // Get search parameters
            $searchParams = $this->getDoctorSearchParams();
            
            // Get all active specialties
            $spesialisList = $this->getActiveSpecialties();
            
            // Check if there's a search query
            if ($this->hasSearchQuery($searchParams)) {
                return $this->displaySearchResults($searchParams, $spesialisList);
            }
            
            // Display all doctors grouped by specialty
            return $this->displayAllDoctors($spesialisList, $searchParams);
            
        } catch (\Exception $e) {
            log_message('error', 'Doctors page error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load doctors page');
        }
    }

    /**
     * Get doctor search parameters from request
     * 
     * @return array Search parameters
     */
    private function getDoctorSearchParams(): array
    {
        return [
            'nama_spesialis' => $this->request->getGet('nama_spesialis'),
            'nama_dokter' => $this->request->getGet('nama_dokter')
        ];
    }

    /**
     * Get all active specialties
     * 
     * @return array Active specialties
     */
    private function getActiveSpecialties(): array
    {
        return $this->spesialisModel
            ->where('status', 'Y')
            ->orderBy('nama', 'asc')
            ->findAll();
    }

    /**
     * Check if there's a search query
     * 
     * @param array $searchParams Search parameters
     * @return bool True if search query exists
     */
    private function hasSearchQuery(array $searchParams): bool
    {
        return !empty($searchParams['nama_spesialis']) || !empty($searchParams['nama_dokter']);
    }

    /**
     * Display search results
     * 
     * @param array $searchParams Search parameters
     * @param array $spesialisList List of specialties
     * @return string Rendered search results view
     */
    private function displaySearchResults(array $searchParams, array $spesialisList): string
    {
        $dokterResults = $this->searchDoctors($searchParams);
        
        $data = [
            'spesialisList' => $spesialisList,
            'dokterResults' => $dokterResults,
            'selectedSpesialis' => $searchParams['nama_spesialis'],
            'searchedNamaDokter' => $searchParams['nama_dokter'],
            'title' => 'Dokter Kami'
        ];

        return view('frontend/dokter', $data);
    }

    /**
     * Search doctors based on parameters
     * 
     * @param array $searchParams Search parameters
     * @return array Search results
     */
    private function searchDoctors(array $searchParams): array
    {
        $dokterQuery = $this->dokterModel->getDokterWithSpesialis();

        // Add specialty filter if specified
        if (!empty($searchParams['nama_spesialis'])) {
            $dokterQuery->where('spesialis_id', $searchParams['nama_spesialis']);
        }

        // Add name filter if specified
        if (!empty($searchParams['nama_dokter'])) {
            $dokterQuery->like('dokter.nama', $searchParams['nama_dokter']);
        }

        return $dokterQuery->orderBy('nama', 'asc')->get()->getResultArray();
    }

    /**
     * Display all doctors grouped by specialty
     * 
     * @param array $spesialisList List of specialties
     * @param array $searchParams Search parameters
     * @return string Rendered all doctors view
     */
    private function displayAllDoctors(array $spesialisList, array $searchParams): string
    {
        $data = [
            'spesialis' => $this->getDoctorsBySpecialty($spesialisList),
            'spesialisList' => $spesialisList,
            'selectedSpesialis' => $searchParams['nama_spesialis'],
            'searchedNamaDokter' => $searchParams['nama_dokter'],
            'dokterResults' => [],
            'title' => 'Dokter Kami'
        ];

        return view('frontend/dokter', $data);
    }

    /**
     * Get doctors grouped by specialty
     * 
     * @param array $spesialisList List of specialties
     * @return array Doctors grouped by specialty
     */
    private function getDoctorsBySpecialty(array $spesialisList): array
    {
        $doctorsBySpecialty = [];
        
        foreach ($spesialisList as $spesialis) {
            $dokter = $this->dokterModel
                ->where('status', 'Y')
                ->where('spesialis_id', $spesialis['idspesialis'])
                ->orderBy('nama', 'asc')
                ->findAll();

            $doctorsBySpecialty[$spesialis['idspesialis']] = [
                'nama' => $spesialis['nama'],
                'dokter' => $dokter,
            ];
        }

        return $doctorsBySpecialty;
    }


    /**
     * Display photo gallery page
     * 
     * @return string Rendered photo gallery view
     */
    public function galeriFoto(): string
    {
        $data = [
            'galeriFoto' => $this->albumModel->getGaleriFoto(),
            'pager' => $this->albumModel->pager,
            'title' => 'Galeri Foto'
        ];

        return view('frontend/galeri-foto', $data);
    }

    /**
     * Display photo gallery detail page
     * 
     * @param string $slug Gallery slug
     * @return string Rendered photo gallery detail view
     * @throws PageNotFoundException If gallery not found
     */
    public function galeriFotoDetail(string $slug): string
    {
        if (empty($slug)) {
            throw new PageNotFoundException('Gallery slug is required');
        }

        $galeriDetail = $this->albumModel->getGaleriFotoDetail($slug);
        
        if (!$galeriDetail) {
            throw new PageNotFoundException('Gallery not found');
        }

        $data = [
            'galeriFotoDetail' => $galeriDetail,
            'title' => 'Galeri Foto Detail'
        ];

        return view('frontend/galeri-foto-detail', $data);
    }

    /**
     * Display video gallery page
     * 
     * @return string Rendered video gallery view
     */
    public function galeriVideo(): string
    {
        $data = [
            'galeriVideo' => $this->videoModel
                ->where('status', 'PB')
                ->orderBy('tanggal', 'DESC')
                ->paginate(9),
            'pager' => $this->videoModel->pager,
            'title' => 'Galeri Video'
        ];

        return view('frontend/galeri-video', $data);
    }

    /**
     * Display news page with search functionality
     * 
     * @return string Rendered news view
     */
    public function berita(): string
    {
        try {
            $searchQuery = $this->request->getGet('q');
            
            $data = [
                'kategori' => $this->beritaModel->countBeritaByKategori(),
                'berita' => $this->getNewsWithPagination($searchQuery),
                'searchQuery' => $searchQuery,
                'pager' => $this->beritaModel->pager,
                'beritaMostView' => $this->getMostViewedNews(),
                'beritaTerbaru' => $this->getLatestNews(),
                'title' => 'Berita'
            ];

            return view('frontend/berita', $data);
        } catch (\Exception $e) {
            log_message('error', 'News page error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load news page');
        }
    }

    /**
     * Get news with pagination and optional search
     * 
     * @param string|null $searchQuery Search query
     * @return array Paginated news
     */
    private function getNewsWithPagination(?string $searchQuery): array
    {
        $query = $this->beritaModel
            ->where('status', 'PB')
            ->orderBy('tanggal', 'desc')
            ->orderBy('idberita', 'desc');

        if (!empty($searchQuery)) {
            $query->like('judul', $searchQuery);
        }

        return $query->paginate(8);
    }

    /**
     * Display news detail page
     * 
     * @param string $slug News slug
     * @return string Rendered news detail view
     * @throws PageNotFoundException If news not found
     */
    public function beritaDetail(string $slug): string
    {
        if (empty($slug)) {
            throw new PageNotFoundException('News slug is required');
        }

        try {
            $beritaDetail = $this->beritaModel->where('slug', $slug)->first();

            if (!$beritaDetail) {
                throw new PageNotFoundException('News not found');
            }

            // Increment view count
            $this->beritaModel->incrementViewCount($beritaDetail['idberita']);
            
            // Refresh data after increment
            $beritaDetail = $this->beritaModel->where('slug', $slug)->first();

            $data = [
                'kategori' => $this->beritaModel->countBeritaByKategori(),
                'title' => 'Detail Berita',
                'beritaDetail' => $beritaDetail,
                'beritaLainnya' => $this->getRelatedNews($beritaDetail['kategori_id'], $slug),
                'beritaMostView' => $this->getMostViewedNews(),
                'beritaTerbaru' => $this->getLatestNews()
            ];

            return view('frontend/berita-detail', $data);
        } catch (\Exception $e) {
            log_message('error', 'News detail error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load news detail');
        }
    }
    
    /**
     * Get related news by category
     * 
     * @param int $kategoriId Category ID
     * @param string $excludeSlug Slug to exclude
     * @return array Related news
     */
    private function getRelatedNews(int $kategoriId, string $excludeSlug): array
    {
        return $this->beritaModel
            ->where('kategori_id', $kategoriId)
            ->where('slug !=', $excludeSlug)
            ->where('status', 'PB')
            ->orderBy('tanggal', 'desc')
            ->limit(4)
            ->findAll();
    }
    
    /**
     * Get most viewed news
     * 
     * @return array Most viewed news
     */
    private function getMostViewedNews(): array
    {
        return $this->beritaModel
            ->orderBy('viewberita', 'desc')
            ->where('status', 'PB')
            ->limit(8)
            ->findAll();
    }
    
    /**
     * Get latest news
     * 
     * @return array Latest news
     */
    private function getLatestNews(): array
    {
        return $this->beritaModel
            ->orderBy('tanggal', 'desc')
            ->orderBy('idberita', 'desc')
            ->where('status', 'PB')
            ->limit(8)
            ->findAll();
    }

    /**
     * Display news by category
     * 
     * @param int $idkategori Category ID
     * @return string Rendered news category view
     * @throws PageNotFoundException If category not found
     */
    public function beritaKategori(int $idkategori): string
    {
        if ($idkategori <= 0) {
            throw new PageNotFoundException('Invalid category ID');
        }

        try {
            $kategori = $this->kategoriModel
                ->where('status', 'Y')
                ->where('idkategori', $idkategori)
                ->first();
            
            if (!$kategori) {
                throw new PageNotFoundException('Category not found');
            }

            $data = [
                'berita' => $this->beritaModel
                    ->where('kategori_id', $kategori['idkategori'])
                    ->where('status', 'PB')
                    ->orderBy('tanggal', 'desc')
                    ->paginate(10),
                'pager' => $this->beritaModel->pager,
                'selectedKategori' => $idkategori,
                'kategori' => $this->beritaModel->countBeritaByKategori(),
                'title' => "Berita Kategori",
                'beritaMostView' => $this->getMostViewedNews(),
                'beritaTerbaru' => $this->getLatestNews()
            ];

            return view('frontend/berita-kategori', $data);
        } catch (\Exception $e) {
            log_message('error', 'News category error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load news category');
        }
    }

    /**
     * Display reference corner page with search functionality
     * 
     * @return string Rendered reference corner view
     */
    public function pojokReferensi(): string
    {
        try {
            $searchParams = $this->getReferenceSearchParams();
            $referensiQuery = $this->buildReferenceQuery($searchParams);

            $data = [
                'referensi' => $referensiQuery->paginate(12),
                'namaKategori' => $searchParams['nama_kategori'],
                'namaJudul' => $searchParams['nama_judul'],
                'pager' => $this->referensiModel->pager,
                'title' => 'Pojok Referensi'
            ];

            return view('frontend/pojok-referensi', $data);
        } catch (\Exception $e) {
            log_message('error', 'Reference corner error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load reference corner');
        }
    }

    /**
     * Get reference search parameters
     * 
     * @return array Search parameters
     */
    private function getReferenceSearchParams(): array
    {
        return [
            'nama_kategori' => $this->request->getGet('nama_kategori'),
            'nama_judul' => $this->request->getGet('nama_judul')
        ];
    }

    /**
     * Build reference query with filters
     * 
     * @param array $searchParams Search parameters
     * @return object Query builder
     */
    private function buildReferenceQuery(array $searchParams): object
    {
        $referensiQuery = $this->referensiModel->orderBy('judul', 'asc');

        // Add category filter
        if (!empty($searchParams['nama_kategori'])) {
            $referensiQuery->where('kategori', $searchParams['nama_kategori']);
        }

        // Add title/author/publisher filter
        if (!empty($searchParams['nama_judul'])) {
            $referensiQuery->groupStart()
                ->like('judul', $searchParams['nama_judul'])
                ->orLike('pengarang', $searchParams['nama_judul'])
                ->orLike('penerbit', $searchParams['nama_judul'])
                ->groupEnd();
        }

        return $referensiQuery;
    }


    /**
     * Display contact page
     * 
     * @return string Rendered contact view
     */
    public function kontak(): string
    {
        $data = [
            'profil' => $this->profilModel->first(),
            'vote' => $this->pollingModel->getPollingData(),
            'polling' => $this->pollingModel->getFormattedPollingData(),
            'title' => 'Kontak'
        ];

        return view('frontend/kontak', $data);
    }

    /**
     * Display emergency department page
     * 
     * @return string Rendered emergency department view
     */
    public function igd(): string
    {
        $data = [
            'igd' => $this->rawatModel->where('slug', 'igd')->where('status', 'Y')->first(),
            'title' => 'IGD'
        ];
        
        return view('frontend/igd', $data);
    }

    /**
     * Display polyclinic page
     * 
     * @return string Rendered polyclinic view
     */
    public function poliklinik(): string
    {
        $data = [
            'poli' => $this->poliModel->where('status', 'Y')->findAll(),
            'title' => 'Poliklinik'
        ];
        
        return view('frontend/poliklinik', $data);
    }

    /**
     * Display polyclinic detail page
     * 
     * @param string $namaPoli Polyclinic name
     * @return string Rendered polyclinic detail view
     */
    public function detailPoliklinik(string $namaPoli): string
    {
        $data = [
            'jadwalpoli' => $this->jadwalPoliModel->getJadwalPoliWithDokter($namaPoli),
            'title' => "Poliklinik $namaPoli"
        ];

        return view('frontend/detail-poliklinik', $data);
    }

    /**
     * Display inpatient care page
     * 
     * @param string $nama Inpatient care name
     * @return string Rendered inpatient care view
     */
    public function rawatInap(string $nama): string
    {
        $data = [
            'rawatInap' => $this->rawatModel->where('slug', $nama)->first(),
            'namaRawat' => $nama,
            'title' => "Rawat Inap $nama"
        ];
        
        return view('frontend/rawat-inap', $data);
    }

    /**
     * Display support services page
     * 
     * @param string $nama Support service name
     * @return string Rendered support services view
     */
    public function penunjang(string $nama): string
    {
        $data = [
            'penunjang' => $this->penunjangModel->where('nama', $nama)->first(),
            'namaPenunjang' => $nama,
            'title' => $nama
        ];

        return view('frontend/penunjang', $data);
    }

    /**
     * Display facilities page
     * 
     * @param string $nama Facility name
     * @return string Rendered facilities view
     */
    public function fasilitas(string $nama): string
    {
        $data = [
            'fasilitas' => $this->fasilitasModel->where('slug', $nama)->first(),
            'namaFasilitas' => $nama,
            'title' => $nama
        ];

        return view('frontend/fasilitas', $data);
    }

    /**
     * Display PPID vision and mission page
     * 
     * @return string Rendered PPID vision and mission view
     */
    public function visiMisiPPID(): string
    {
        $data = [
            'profilPpid' => $this->profilPPIDModel->first(),
            'title' => 'Visi Misi PPID'
        ];

        return view('frontend/visi-misi-ppid', $data);
    }

    /**
     * Display PPID duties and functions page
     * 
     * @return string Rendered PPID duties and functions view
     */
    public function tugasFungsiPPID(): string
    {
        $data = [
            'profilPpid' => $this->profilPPIDModel->first(),
            'title' => 'Tugas Fungsi PPID'
        ];

        return view('frontend/tugas-fungsi-ppid', $data);
    }

    /**
     * Display PPID brief profile page
     * 
     * @return string Rendered PPID brief profile view
     */
    public function profilSingkat(): string
    {
        $data = [
            'title' => 'Profil Singkat PPID',
            'profilPpid' => $this->profilPPIDModel->first()
        ];
        
        return view('frontend/profil-singkat-ppid', $data);
    }

    /**
     * Display KIP regulations page
     * 
     * @return string Rendered KIP regulations view
     */
    public function peraturanKIP(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();

        return view('frontend/peraturan-kip', $data);
    }

    /**
     * Display bed information page
     * 
     * @return string Rendered bed information view
     */
    public function tempatTidur(): string
    {
        $data = [
            'informasiTempatTidur' => $this->tentangModel->first(),
            'title' => 'Informasi Tempat Tidur'
        ];
        
        return view('frontend/tempat-tidur', $data);
    }

    /**
     * Display PPID announcement page
     * 
     * @return string Rendered PPID announcement view
     */
    public function maklumat(): string
    {
        $data = [
            'profilPpid' => $this->profilPPIDModel->first(),
            'title' => 'Maklumat PPID'
        ];
        
        return view('frontend/maklumat-ppid', $data);
    }

    /**
     * Display information request flow page
     * 
     * @return string Rendered information request flow view
     */
    public function alurPermohonanInformasi(): string
    {
        return view('frontend/alur-permohonan-informasi', ['title' => 'Alur Permohonan Informasi']);
    }

    /**
     * Display objection submission flow page
     * 
     * @return string Rendered objection submission flow view
     */
    public function alurPengajuanKeberatan(): string
    {
        return view('frontend/alur-pengajuan-keberatan', ['title' => 'Alur Pengajuan Keberatan']);
    }

    /**
     * Display dispute resolution flow page
     * 
     * @return string Rendered dispute resolution flow view
     */
    public function alurPenyelesaianSengketa(): string
    {
        return view('frontend/alur-penyelesaian-sengketa', ['title' => 'Alur Penyelesaian Sengketa']);
    }

    /**
     * Display PPID structure page
     * 
     * @return string Rendered PPID structure view
     */
    public function strukturPPID(): string
    {
        return view('frontend/struktur-ppid', ['title' => 'Struktur PPID']);
    }

    /**
     * Display financial report page with pagination
     * 
     * @param string $slug Report slug
     * @return string Rendered financial report view
     * @throws PageNotFoundException If report not found
     */
    public function laporan(string $slug): string
    {
        if (empty($slug)) {
            throw new PageNotFoundException('Report slug is required');
        }

        try {
            // Get page parameter from query string
            $page = $this->request->getGet('page') ? (int)$this->request->getGet('page') : 1;
            $perPage = 12;

            // Get report data
            $laporanData = $this->pagesModel->where('slug', $slug)->first();
            
            if (!$laporanData) {
                throw new PageNotFoundException('Report not found');
            }

            // Parse content from HTML
            $parsedKonten = $this->parseReportContent($laporanData['konten']);

            // Calculate pagination
            $totalItems = count($parsedKonten);
            $offset = ($page - 1) * $perPage;
            $currentPageData = array_slice($parsedKonten, $offset, $perPage);

            // Prepare data for view
            $data = [
                'laporan' => [
                    'title' => $laporanData['title'],
                    'items' => $currentPageData,
                    'gambar' => $laporanData['gambar']
                ],
                'pagination' => $this->createPagination($page, $perPage, $totalItems),
                'title' => $laporanData['title']
            ];

            // Add sidebar data
            $data += $this->sidebarData('ppid');

            return view('frontend/laporan-keuangan', $data);
        } catch (\Exception $e) {
            log_message('error', 'Report page error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load report');
        }
    }

    /**
     * Parse report content from HTML
     * 
     * @param string $konten HTML content
     * @return array Parsed content
     */
    private function parseReportContent(string $konten): array
    {
        $parsedKonten = [];
        $dom = new \DOMDocument();
        
        // Suppress HTML parsing errors
        libxml_use_internal_errors(true);
        $dom->loadHTML($konten);
        libxml_clear_errors();

        // Extract <li> elements
        $lis = $dom->getElementsByTagName('li');
        foreach ($lis as $li) {
            $a = $li->getElementsByTagName('a')->item(0);
            if ($a) {
                $href = $a->getAttribute('href');
                $text = $a->textContent;
                $parsedKonten[] = ['href' => $href, 'text' => $text];
            } else {
                $textContent = trim($li->textContent);
                $parsedKonten[] = ['text' => $textContent];
            }
        }

        // Extract <p> elements
        $ps = $dom->getElementsByTagName('p');
        foreach ($ps as $p) {
            $textContent = trim($p->textContent);
            $parsedKonten[] = ['text' => $textContent];
        }

        return $parsedKonten;
    }

    /**
     * Create pagination links
     * 
     * @param int $page Current page
     * @param int $perPage Items per page
     * @param int $totalItems Total items
     * @return string Pagination HTML
     */
    private function createPagination(int $page, int $perPage, int $totalItems): string
    {
        $pager = \Config\Services::pager();
        return $pager->makeLinks($page, $perPage, $totalItems);
    }


    /**
     * Increment download count for PPID news
     * 
     * @return ResponseInterface JSON response
     */
    public function incrementDownloadCount(): ResponseInterface
    {
        try {
            $id = $this->request->getPost('id');

            if (!$id) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'ID is required']);
            }

            $berita = $this->beritaPPIDModel->find($id);

            if (!$berita) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'News not found']);
            }

            $berita['download'] += 1;
            $this->beritaPPIDModel->update($id, $berita);

            return $this->response->setJSON(['status' => 'success']);
        } catch (\Exception $e) {
            log_message('error', 'Download count increment error: ' . $e->getMessage());
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unable to increment download count']);
        }
    }

    public function daftarInformasiPublik(): string
    {
        $data = $this->sidebarData('ppid');
        $data['dip'] = $this->beritaPPIDModel->getAllDataPPID(BeritaPPID::KATEGORI_PPID)->get()->getResultArray();

        return view('frontend/dip', $data);
    }

    public function beritaKategoriPPID($kategoriPpid)
    {

        $list = $this->beritaPPIDModel->where('kategori_id', $kategoriPpid)->where('status', 'Y')->where('nm_status', 'Publish')->orderBy('tanggal', 'desc')->findAll();
        $data['selectedKategori'] = $kategoriPpid;


        $context = in_array($kategoriPpid, BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

        $data = array_merge(
            $this->sidebarData($context),
            [
                'beritappid'       => $list,
                'selectedKategori' => $kategoriPpid,
            ]
        );




        // Load sidebar data untuk kategori PPID
        return view('frontend/berita-kategori-ppid', $data);
    }

    /**
     * Display PPID news detail page
     * 
     * @param int $idBeritaPpid PPID news ID
     * @return string Rendered PPID news detail view
     * @throws PageNotFoundException If news not found
     */
    public function beritaDetailPPID(int $idBeritaPpid): string
    {
        try {
            $beritaDetail = $this->beritaPPIDModel->where('idberita', $idBeritaPpid)->first();

            if (!$beritaDetail) {
                throw new PageNotFoundException('PPID news not found');
            }

            $this->beritaPPIDModel->incrementViewCount($beritaDetail['idberita']);
            $detail = $this->beritaPPIDModel->getBeritaPpidById($idBeritaPpid);

            $context = in_array($detail['kategori_id'], BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

            $data = array_merge(
                $this->sidebarData($context),
                [
                    'beritaDetailPpid' => $detail,
                    'title' => 'Detail Berita PPID'
                ]
            );

            return view('frontend/berita-detail-ppid', $data);
        } catch (\Exception $e) {
            log_message('error', 'PPID news detail error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load PPID news detail');
        }
    }
    /**
     * Display PKRS information list page
     * 
     * @return string Rendered PKRS information list view
     */
    public function daftarInformasiPkrs(): string
    {
        $data = array_merge(
            $this->sidebarData('pkrs'),
            [
                'dip' => $this->beritaPPIDModel
                    ->getAllDataPPID(BeritaPPID::KATEGORI_PKRS)
                    ->get()
                    ->getResultArray(),
                'title' => 'Daftar Informasi PKRS'
            ]
        );

        return view('frontend/dip', $data);
    }



    /**
     * Display PKRS page by slug
     * 
     * @param string $slug PKRS slug
     * @return string Rendered PKRS view
     */
    public function pkrs(string $slug): string
    {
        try {
            $list = $this->beritaPPIDModel
                ->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')
                ->where('kategori_informasi_ppid.slug', $slug)
                ->where('kategori_informasi_ppid.status', 'Y')
                ->where('nm_status', 'Publish')
                ->orderBy('tanggal', 'desc')
                ->findAll();

            $data = array_merge(
                $this->sidebarData('pkrs'),
                [
                    'beritappid' => $list,
                    'selectedKategori' => $slug,
                    'title' => 'PKRS'
                ]
            );

            return view('frontend/pkrs', $data);
        } catch (\Exception $e) {
            log_message('error', 'PKRS page error: ' . $e->getMessage());
            throw new PageNotFoundException('Unable to load PKRS page');
        }
    }

    /**
     * Display hospital facilities page
     * 
     * @return string Rendered hospital facilities view
     */
    public function saranaRumahSakit(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();
        $data['title'] = 'Sarana Rumah Sakit';

        return view('frontend/sarana', $data);
    }

    /**
     * Display PPID SOP page
     * 
     * @return string Rendered PPID SOP view
     */
    public function sopPPID(): string
    {
        $data = $this->sidebarData('ppid');
        $data['title'] = 'SOP PPID';
        
        return view('frontend/sop-ppid', $data);
    }

    /**
     * Display cost standards page
     * 
     * @return string Rendered cost standards view
     */
    public function standarBiaya(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();
        $data['title'] = 'Standar Biaya';

        return view('frontend/standar-biaya', $data);
    }

    /**
     * Display elderly and disabled services page
     * 
     * @return string Rendered elderly and disabled services view
     */
    public function layananLansiaDanDifabel(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();
        $data['title'] = 'Layanan Lansia dan Difabel';

        return view('frontend/layanan-lansia', $data);
    }

    /**
     * Display complaint procedures page
     * 
     * @return string Rendered complaint procedures view
     */
    public function tataCaraPengaduan(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();
        $data['title'] = 'Tata Cara Pengaduan';

        return view('frontend/tata-cara-pengaduan', $data);
    }

    /**
     * Display evacuation procedures page
     * 
     * @return string Rendered evacuation procedures view
     */
    public function prosedurEvakuasi(): string
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPIDModel->first();
        $data['title'] = 'Prosedur Evakuasi';

        return view('frontend/prosedur-evakuasi', $data);
    }

    /**
     * Display service flow page
     * 
     * @param string $slug Service flow slug
     * @return string Rendered service flow view
     */
    public function alurPelayanan(string $slug): string
    {
        $data = $this->sidebarData('ppid');
        $data['alurPelayanan'] = $this->pagesModel->where('status', 'Y')->where('slug', $slug)->first();
        $data['title'] = 'Alur Pelayanan';
        
        return view('frontend/alur-pelayanan', $data);
    }

    /**
     * Display PPID online form page
     * 
     * @return string Rendered PPID online form view
     */
    public function formPPIDOnline(): string
    {
        $data = $this->sidebarData('ppid');
        $data['title'] = 'Form PPID Online';

        return view('frontend/form-ppid-online', $data);
    }

    /**
     * Display PPID objection information online form page
     * 
     * @return string Rendered PPID objection form view
     */
    public function formKeberatanInformasiPPIDOnline(): string
    {
        $data = $this->sidebarData('ppid');
        $data['title'] = 'Form Keberatan Informasi PPID Online';

        return view('frontend/form-keberatan-informasi-online', $data);
    }

    /**
     * Display information fulfillment time page
     * 
     * @return string Rendered information fulfillment time view
     */
    public function waktuPemenuhanInformasi(): string
    {
        $data = [
            'permohonaninformasi' => $this->formulirPPIDModel->orderBy('tanggal', 'desc')->findAll(),
            'keberataninformasi' => $this->keberatanInformasiModel->orderBy('tanggal', 'desc')->findAll(),
            'title' => 'Waktu Pemenuhan Informasi'
        ];

        return view('frontend/waktu-pemenuhan-informasi', $data);
    }

    /**
     * Display innovation list page
     * 
     * @return string Rendered innovation list view
     */
    public function daftarInovasi(): string
    {
        $data = [
            'inovasi' => $this->inovasiModel->orderBy('tahun', 'desc')->findAll(),
            'title' => 'Daftar Inovasi'
        ];
        
        return view('frontend/daftar-inovasi', $data);
    }

    /**
     * Display innovation detail page
     * 
     * @param int $id Innovation ID
     * @return string Rendered innovation detail view
     * @throws PageNotFoundException If innovation not found
     */
    public function detailInovasi(int $id): string
    {
        $inovasi = $this->inovasiModel->find($id);
        
        if (!$inovasi) {
            throw new PageNotFoundException('Innovation not found');
        }

        $data = [
            'inovasi' => $inovasi,
            'title' => 'Detail Inovasi'
        ];
        
        return view('frontend/detail-inovasi', $data);
    }

    /**
     * Get polyclinic schedule API
     * 
     * @return ResponseInterface JSON response with polyclinic schedule
     */
    public function apiJadwalPoli(): ResponseInterface
    {
        try {
            $jadwalPoli = $this->jadwalPoliModel->getFormattedJadwalPoliWithDokter();
            
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $jadwalPoli
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Polyclinic schedule API error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unable to retrieve polyclinic schedule'
            ]);
        }
    }

    /**
     * Display bio link page
     * 
     * @return string Rendered bio link view
     */
    public function linkBio(): string
    {
        return view('frontend/bio', ['title' => 'Bio Link']);
    }


    /**
     * Get bed availability information
     * 
     * @return ResponseInterface JSON response with bed availability data
     */
    public function kamar(): ResponseInterface
    {
        try {
            // TODO: Implement proper bed availability service
            // This should connect to a secure API or database service
            // with proper authentication and error handling
            
            return $this->response->setJSON([
                'status' => 'maintenance',
                'message' => 'Bed availability service is currently under maintenance',
                'data' => null
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Kamar service error: ' . $e->getMessage());
            
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Unable to retrieve bed availability information',
                'data' => null
            ]);
        }
    }
}
