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

class FrontendController extends BaseController
{
    protected $tentangkami;
    protected $sejarah;
    protected $visimisi;
    protected $polling;
    protected $slider;
    protected $berita;
    protected $kategori;
    protected $video;
    protected $profil;
    protected $jadwalpoli;
    protected $banner;
    protected $albumlist;
    protected $album;
    protected $indikatormutulist;
    protected $indikatormutu;
    protected $spesialis;
    protected $dokter;
    protected $profilmanajemen;
    protected $referensi;
    protected $pesan;
    protected $poli;
    protected $rawat;
    protected $penunjang;
    protected $fasilitas;
    protected $beritappid;
    protected $pages;
    protected $pagevisit;
    protected $profilPPID;
    protected $permohononanInformasi;
    protected $keberatanInformasi;
    protected $daftarinovasi;

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



    public function __construct()
    {
        $this->daftarinovasi = new Inovasi();
        $this->tentangkami = new Tentang();
        $this->visimisi = new Profil();
        $this->polling = new Polling();
        $this->slider = new Slider();
        $this->berita = new Berita();
        $this->kategori = new Kategori();
        $this->video = new Video();
        $this->profil = new Profil();
        $this->jadwalpoli = new JadwalPoli();
        $this->banner = new Banner();
        $this->albumlist = new AlbumList();
        $this->album = new Album();
        $this->indikatormutulist = new IndikatorMutuList();
        $this->indikatormutu = new IndikatorMutu();
        $this->spesialis = new Spesialis();
        $this->dokter = new Dokter();
        $this->profilmanajemen = new ManajemenProfil();
        $this->referensi = new Referensi();
        $this->pesan = new Pesan();
        $this->poli = new Poli();
        $this->rawat = new Rawat();
        $this->penunjang = new Penunjang();
        $this->fasilitas = new Fasilitas();
        $this->pages = new PagesPPID();
        $this->beritappid = new BeritaPPID();
        $this->pagevisit = new PageVisit();
        $this->profilPPID = new ProfilPPID();
        $this->sejarah = new Sejarah();
        $this->permohononanInformasi = new FormulirPPID();
        $this->keberatanInformasi = new KeberatanInformasiPPID();

        helper('string');
    }

    public function index()
    {
        // Dapatkan alamat IP dan user agent
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent();

        // Dapatkan tanggal hari ini
        $date = date('Y-m-d');

        // Tingkatkan jumlah kunjungan
        $this->pagevisit->incrementCount($date, $ipAddress, $userAgent);


        // get data berita ambil 3 data saja yang terbaru dan stauts publish
        // berita new
        $data['beritaNew'] = $this->berita->orderBy('tanggal', 'DESC')->orderBy('idberita', 'DESC')->where('status', 'PB')->first();

        if ($data['beritaNew']) {
            $data['beritaOne'] = $this->berita->orderBy('tanggal', 'DESC')
                ->where('status', 'PB')
                ->whereNotIn('idberita', [$data['beritaNew']['idberita']])
                ->limit(6)
                ->findAll();
        } else {
            $data['beritaOne'] = []; // Jika tidak ada berita, set array kosong.
        }

        // berita 3
        $data['berita'] = $this->berita->orderBy('tanggal', 'DESC')->orderBy('idberita', 'DESC')->where('status', 'PB')->limit(3)->findAll();

        // dd($data['berita']);
        // $data['video'] = $this->video->orderBy('tanggal', 'DESC')->where('status', 'PB')->limit(3)->findAll();
        $data['videoNew'] = $this->video->orderBy('tanggal', 'DESC')->where('status', 'PB')->limit(3)->findAll();
        $data['slider'] = $this->slider->findAll();
        $data['profil'] = $this->profil->first();

        $parsedMisi = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($data['profil']['misi']);
        $divs = $dom->getElementsByTagName('p');

        foreach ($divs as $div) {
            $textContent = trim($div->textContent);
            $cleanedText = preg_replace('/^\d+\.\s*/', '', $textContent);
            // Pisahkan berdasarkan titik atau tanda yang sesuai
            $items = preg_split('/\d+\.\s+/', $cleanedText, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($items as $item) {
                $parsedMisi[] = trim($item);
            }
        }

        $data['parsedMisi'] = $parsedMisi;
        $data['tentangKami'] = $this->tentangkami->first();
        $data['polling'] = $this->polling->getFormattedPollingData();
        $data['vote'] = $this->polling->getPollingData();
        $data['jadwalpoli'] = $this->jadwalpoli->getFormattedJadwalPoliWithDokter();
        $data['banner'] = $this->banner->orderBy('idbanner', 'ASC')->where('status', 'PB')->findAll();
        $data['galeri'] = $this->album->getGaleri();
        $data['pesanNew'] = $this->pesan->where('status', 'PB')->where('status_baca', 'RD')->orderBy('tanggal', 'DESC')->findAll();
        $data['title'] = 'Home';

        return view('frontend/main/home', $data);
    }

    // tentang kami
    public function tentangKami()
    {
        $data['tentangKami'] = $this->tentangkami->first();
        $data['profil'] = $this->profil->first();
        $data['vote'] = $this->polling->getPollingData();
        $data['slider'] = $this->slider->orderBy('idslider', 'ASC')->limit(5)->findAll();
        $data['polling'] = $this->polling->getFormattedPollingData();
        $data['title'] = 'Tentang Kami';

        return view('frontend/tentang-kami', $data);
    }

    public function strukturOrganisasi()
    {
        $data['title'] = 'Struktur Organisasi';
        $data['tentangKami'] = $this->tentangkami->first();
        return view('frontend/struktur-organisasi', $data);
    }

    public function sejarah()
    {
        $data['tentangKami'] = $this->tentangkami->first();
        $data['sejarah'] = $this->sejarah->orderBy('tahun', 'ASC')->findAll();
        $data['title'] = 'Sejarah & Perkembangan';
        return view('frontend/sejarah', $data);
    }

    public function visiMisi()
    {
        $data['visiMisi'] = $this->visimisi->first();
        $data['title'] = 'Visi & Misi';

        return view('frontend/visi-misi', $data);
    }

    public function indikatorMutu()
    {
        $data['indikatorMutu'] = $this->indikatormutu->getIndikatorMutu();
        $data['title'] = 'Indikator Mutu';
        return view('frontend/indikator-mutu', $data);
    }

    public function profilManajemen()
    {
        $data['profilManajemen'] = $this->profilmanajemen->getProfilManajemen();
        $data['title'] = 'Profil Manajemen';

        return view('frontend/profil-manajemen', $data);
    }



    public function dokterKami()
    {
        // Ambil parameter pencarian dari query string
        $namaSpesialis = $this->request->getGet('nama_spesialis');
        $namaDokter = $this->request->getGet('nama_dokter');

        // Buat query dasar untuk dokter
        $dokterQuery = $this->dokter->getDokterWithSpesialis();

        // Tambahkan kondisi berdasarkan spesialis jika ada
        if (!empty($namaSpesialis)) {
            $dokterQuery->where('spesialis_id', $namaSpesialis);
        }

        // Tambahkan kondisi berdasarkan nama dokter jika ada
        if (!empty($namaDokter)) {
            $dokterQuery->like('dokter.nama', $namaDokter);
        }

        // Dapatkan hasil pencarian dokter
        $dokterResults = $dokterQuery->orderBy('nama', 'asc')->get()->getResultArray();

        // Ambil semua spesialis yang aktif
        $spesialisList = $this->spesialis->where('status', 'Y')->orderBy('nama', 'asc')->findAll();

        // Jika ada pencarian, tampilkan hasil pencarian saja
        if (!empty($namaSpesialis) || !empty($namaDokter)) {
            return view('frontend/dokter', [
                'spesialisList' => $spesialisList,
                'dokterResults' => $dokterResults,
                'selectedSpesialis' => $namaSpesialis,
                'searchedNamaDokter' => $namaDokter,
            ]);
        }

        // Looping spesialis untuk mengambil dokter masing-masing spesialis
        $data = [];
        foreach ($spesialisList as $spesialis) {
            $dokter = $this->dokter->where('status', 'Y')
                ->where('spesialis_id', $spesialis['idspesialis'])
                ->orderBy('nama', 'asc')
                ->findAll();


            $data['spesialis'][$spesialis['idspesialis']] = [
                'nama' => $spesialis['nama'],
                'dokter' => $dokter,
            ];
        }



        // Kirim data ke view
        $data['spesialisList'] = $spesialisList;
        $data['selectedSpesialis'] = $namaSpesialis;
        $data['searchedNamaDokter'] = $namaDokter;
        $data['dokterResults'] = [];

        return view('frontend/dokter', $data);
    }


    public function galeriFoto()
    {
        $data['galeriFoto'] = $this->album->getGaleriFoto();
        $data['pager'] = $this->album->pager;
        $data['title'] = 'Galeri Foto';

        return view('frontend/galeri-foto', $data);
    }

    public function galeriFotoDetail($slug)
    {
        $data['galeriFotoDetail'] = $this->album->getGaleriFotoDetail($slug);
        $data['title'] = 'Galeri Foto Detail';

        return view('frontend/galeri-foto-detail', $data);
    }

    public function galeriVideo()
    {
        $data['galeriVideo'] = $this->video->where('status', 'PB')->orderBy('tanggal', 'DESC')->paginate(9);
        $data['pager'] = $this->video->pager;
        $data['title'] = 'Galeri Video';

        return view('frontend/galeri-video', $data);
    }

    public function berita()
    {
        $searchQuery = $this->request->getGet('q');


        // count berita berdasarkan kategori_id
        $data['kategori'] = $this->berita->countBeritaByKategori();
        // dd($data['kategori']);

        if ($searchQuery) {
            // If a search query is present, filter the news by title
            $data['berita'] = $this->berita
                ->like('judul', $searchQuery)
                ->where('status', 'PB')
                ->orderBy('tanggal', 'desc')
                ->orderBy('idberita', 'desc')
                ->paginate(8);
        } else {
            // If no search query, show all news
            $data['berita'] = $this->berita
                ->where('status', 'PB')
                ->orderBy('tanggal', 'desc')
                ->orderBy('idberita', 'desc')

                ->paginate(8);
        }


        $data['searchQuery'] = $searchQuery;
        $data['pager'] = $this->berita->pager;


        $data['beritaMostView'] = $this->berita->orderBy('viewberita', 'desc')->where('status', 'PB')->limit(8)->findAll();
        $data['beritaTerbaru'] = $this->berita->orderBy('tanggal', 'desc')->orderBy('idberita', 'desc')->where('status', 'PB')->limit(8)->findAll();
        $data['title'] = 'Berita';
        return view('frontend/berita', $data);
    }

    public function beritaDetail($slug)
    {
        // $data['kategori'] = $this->kategori->where('status', 'Y')->findAll();

        $data['kategori'] = $this->berita->countBeritaByKategori();
        $data['title'] = 'Detail Berita';

        $data['beritaDetail'] = $this->berita->where('slug', $slug)->first();

        if (!$data['beritaDetail']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita TIdak Ditemukan');
        }

        $this->berita->incrementViewCount($data['beritaDetail']['idberita']);
        $data['beritaDetail'] = $this->berita->where('slug', $slug)->first();


        // Ambil kategori_id dari berita yang sedang ditampilkan
        $kategoriId = $data['beritaDetail']['kategori_id'];

        // Query untuk mendapatkan berita lainnya
        $data['beritaLainnya'] = $this->berita
            ->where('kategori_id', $kategoriId) // Filter berdasarkan kategori
            ->where('slug !=', $slug) // Pastikan tidak termasuk berita yang sedang ditampilkan
            ->orderBy('tanggal', 'desc')
            ->limit(4)
            ->findAll();

        // dd($data['beritaLainnya']);
        $data['beritaMostView'] = $this->berita->orderBy('viewberita', 'desc')->where('status', 'PB')->limit(8)->findAll();
        $data['beritaTerbaru'] = $this->berita->orderBy('tanggal', 'desc')->orderBy('idberita', 'desc')->where('status', 'PB')->limit(8)->findAll();

        return view('frontend/berita-detail', $data);
    }

    public function beritaKategori($idkategori)
    {
        $kategori = $this->kategori->where('status', 'Y')->where('idkategori', $idkategori)->first();
        $data['berita'] = $this->berita->where('kategori_id', $kategori['idkategori'])->where('status', 'PB')->orderBy('tanggal', 'desc')->paginate(10);

        $data['pager'] = $this->berita->pager;
        $data['selectedKategori'] = $idkategori;

        $data['kategori'] = $this->berita->countBeritaByKategori();
        $data['title'] = "Berita Kategori";


        $data['beritaMostView'] = $this->berita->orderBy('viewberita', 'desc')->where('status', 'PB')->limit(8)->findAll();
        $data['beritaTerbaru'] = $this->berita->orderBy('tanggal', 'desc')->orderBy('idberita', 'desc')->where('status', 'PB')->limit(8)->findAll();


        return view('frontend/berita-kategori', $data);
    }

    // pojok referensi
    public function pojokReferensi()
    {
        $namaKategori = $this->request->getGet('nama_kategori');
        $namaJudul = $this->request->getGet('nama_judul');

        $referensiQuery = $this->referensi->orderBy('judul', 'asc');

        if (!empty($namaKategori)) {
            $referensiQuery->where('kategori', $namaKategori);
        }

        if (!empty($namaJudul)) {
            $referensiQuery->groupStart()
                ->like('judul', $namaJudul)
                ->orLike('pengarang', $namaJudul)
                ->orLike('penerbit', $namaJudul)
                ->groupEnd();
        }

        $data['referensi'] = $referensiQuery->paginate(12);
        $data['namaKategori'] = $namaKategori;
        $data['namaJudul'] = $namaJudul;
        $data['pager'] = $this->referensi->pager;
        $data['title'] = 'Pojok Referensi';

        return view('frontend/pojok-referensi', $data);
    }


    public function kontak()
    {
        $data['profil'] = $this->profil->first();
        $data['vote'] = $this->polling->getPollingData();
        $data['polling'] = $this->polling->getFormattedPollingData();
        $data['title'] = 'Kontak';

        return view('frontend/kontak', $data);
    }

    public function igd()
    {
        $data['igd'] = $this->rawat->where('slug', 'igd')->where('status', 'Y')->first();
        $data['title'] = 'IGD';
        return view('frontend/igd', $data);
    }

    public function poliklinik()
    {
        $data['poli'] = $this->poli->where('status', 'Y')->findAll();
        $data['title'] = 'Poliklinik';
        return view('frontend/poliklinik', $data);
    }

    public function detailPoliklinik($namaPoli)
    {

        $data['jadwalpoli'] = $this->jadwalpoli->getJadwalPoliWithDokter($namaPoli);
        $data['title'] = "Poliklinik $namaPoli";

        return view('frontend/detail-poliklinik', $data);
    }

    public function rawatInap($nama)
    {
        $data['rawatInap'] = $this->rawat->where('slug', $nama)->first();
        $data['namaRawat'] = $nama;
        $data['title'] = "Rawat Inap $nama";
        return view('frontend/rawat-inap', $data);
    }

    public function penunjang($nama)
    {
        $data['penunjang'] = $this->penunjang->where('nama', $nama)->first();
        $data['namaPenunjang'] = $nama;
        $data['title'] = $nama;

        return view('frontend/penunjang', $data);
    }

    public function fasilitas($nama)
    {
        $data['fasilitas'] = $this->fasilitas->where('slug', $nama)->first();
        $data['namaFasilitas'] = $nama;
        $data['title'] = $nama;

        return view('frontend/fasilitas', $data);
    }

    public function visiMisiPPID()
    {
        $data['profilPpid'] = $this->profilPPID->first();
        $data['title'] = 'Visi Misi PPID';

        return view('frontend/visi-misi-ppid', $data);
    }
    public function tugasFungsiPPID()
    {
        $data['profilPpid'] = $this->profilPPID->first();
        $data['title'] = 'Tugas Fungsi PPID';

        return view('frontend/tugas-fungsi-ppid', $data);
    }

    public function profilSingkat()
    {
        $data['title'] = 'Profil Singkat PPID';
        $data['profilPpid'] = $this->profilPPID->first();
        return view('frontend/profil-singkat-ppid', $data);
    }

    public function peraturanKIP()
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/peraturan-kip', $data);
    }

    public function tempatTidur()
    {

        $data['informasiTempatTidur'] = $this->tentangkami->first();
        return view('frontend/tempat-tidur', $data);
    }

    public function maklumat()
    {
        $data['profilPpid'] = $this->profilPPID->first();
        return view('frontend/maklumat-ppid', $data);
    }

    public function alurPermohonanInformasi()
    {
        return view('frontend/alur-permohonan-informasi');
    }

    public function alurPengajuanKeberatan()
    {

        return view('frontend/alur-pengajuan-keberatan');
    }
    public function alurPenyelesaianSengketa()
    {

        return view('frontend/alur-penyelesaian-sengketa');
    }

    public function strukturPPID()
    {
        return view('frontend/struktur-ppid');
    }

    public function laporan($slug)
    {
        // Ambil parameter halaman dari query string, jika tidak ada, set halaman pertama
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Jumlah data per halaman
        $perPage = 12;

        // Parsing data dari field 'konten'
        $data['laporan'] = $this->pages->where('slug', $slug)->first();

        // Parsing data dari field 'konten'
        $parsedKonten = [];
        $dom = new \DOMDocument();
        @$dom->loadHTML($data['laporan']['konten']);

        // Mengambil elemen <li>
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

        // Mengambil elemen <p>
        $ps = $dom->getElementsByTagName('p');
        foreach ($ps as $p) {
            $textContent = trim($p->textContent);
            $parsedKonten[] = ['text' => $textContent];
        }

        // Jumlah total data setelah parsing
        $totalItems = count($parsedKonten);

        // Hitung offset untuk data pada halaman ini
        $offset = ($page - 1) * $perPage;

        // Ambil data untuk halaman saat ini menggunakan array_slice dengan panjang $perPage
        $currentPageData = array_slice($parsedKonten, $offset, $perPage);

        // Menyiapkan data untuk view
        $data['laporan'] = [
            'title' => $data['laporan']['title'],
            'items' => $currentPageData, // Hanya data untuk halaman saat ini
            'gambar' => $data['laporan']['gambar']
        ];

        // Buat link pagination
        $pager = \Config\Services::pager();
        $data['pagination'] = $pager->makeLinks($page, $perPage, $totalItems);


        $data += $this->sidebarData('ppid');

        // $data['url'] = 'dip';
        // $data['title'] = 'Daftar Informasi Publik';
        // $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PPID);


        // Load view dengan data yang sudah disiapkan
        return view('frontend/laporan-keuangan', $data);
    }


    public function incrementDownloadCount()
    {
        $id = $this->request->getPost('id');

        if ($id) {
            $berita = $this->beritappid->find($id);

            if ($berita) {
                $berita['download'] += 1;
                $this->beritappid->update($id, $berita);

                return $this->response->setJSON(['status' => 'success']);
            }
        }

        return $this->response->setJSON(['status' => 'error']);
    }

    public function daftarInformasiPublik()
    {
        // $data['dip'] = $this->beritappid->getAllDataPPID(BeritaPPID::KATEGORI_PPID)->get()->getResultArray();
        // $data['title'] = 'Daftar Informasi Publik';
        // $data['countBeritaPPID'] = $this->beritappid->countBeritaByKategori(BeritaPPID::KATEGORI_PPID);
        // $data['popularBeritaPPID'] = $this->beritappid->getPopularBerita(BeritaPPID::KATEGORI_PPID);
        // $data['beritaTerbaruPPID'] = $this->beritappid->getLatestBerita(BeritaPPID::KATEGORI_PPID);
        // $data['total'] = $this->beritappid->where('nm_status', 'Publish')->whereIn('kategori_id', BeritaPPID::KATEGORI_PPID)->countAllResults();


        $data = $this->sidebarData('ppid');
        $data['dip'] = $this->beritappid->getAllDataPPID(BeritaPPID::KATEGORI_PPID)->get()->getResultArray();

        // dd($data['dip']);
        return view('frontend/dip', $data);
    }

    public function beritaKategoriPPID($kategoriPpid)
    {

        $list = $this->beritappid->where('kategori_id', $kategoriPpid)->where('status', 'Y')->where('nm_status', 'Publish')->orderBy('tanggal', 'desc')->findAll();
        $data['selectedKategori'] = $kategoriPpid;


        $context = in_array($kategoriPpid, BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

        $data = array_merge(
            $this->sidebarData($context),
            [
                'beritappid'       => $list,
                'selectedKategori' => $kategoriPpid,
            ]
        );

        // // cek apakah kategori termasuk KATEGORI_PPID atau KATEGORI_PKRS
        // if (in_array($kategoriPpid, BeritaPPID::KATEGORI_PPID)) {
        //     $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PPID);
        //     $data['url'] = 'dip';
        //     $data['title'] = 'Daftar Informasi Publik';
        // } else {
        //     $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PKRS);
        //     $data['url'] = 'pkrs';
        //     $data['title'] = 'Media Informasi PKRS';
        // }



        // Load sidebar data untuk kategori PPID
        return view('frontend/berita-kategori-ppid', $data);
    }

    public function beritaDetailPPID($idBeritaPpid)
    {
        $data['beritaDetailPpid'] = $this->beritappid->where('idberita', $idBeritaPpid)->first();

        if (!$data['beritaDetailPpid']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Berita Tidak Ditemukan');
        }

        $this->beritappid->incrementViewCount($data['beritaDetailPpid']['idberita']);
        $detail = $this->beritappid->getBeritaPpidById($idBeritaPpid);

        $context = in_array($detail['kategori_id'], BeritaPPID::KATEGORI_PPID) ? 'ppid' : 'pkrs';

        $data = array_merge(
            $this->sidebarData($context),
            ['beritaDetailPpid' => $detail]
        );
        // if (in_array($data['beritaDetailPpid']['kategori_id'], BeritaPPID::KATEGORI_PPID)) {
        //     $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PPID);
        //     $data['url'] = 'dip';
        //     $data['title'] = 'Daftar Informasi Publik';
        // } else {
        //     $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PKRS);
        //     $data['title'] = 'Media Informasi PKRS';
        //     $data['url'] = 'pkrs';
        // }


        return view('frontend/berita-detail-ppid', $data);
    }
    public function daftarInformasiPkrs()
    {
        $data = array_merge(
            $this->sidebarData('pkrs'),
            [
                'dip' => $this->beritappid
                    ->getAllDataPPID(BeritaPPID::KATEGORI_PKRS)
                    ->get()
                    ->getResultArray(),
            ]
        );

        return view('frontend/dip', $data);
    }



    public function pkrs($slug)
    {


        $list = $this->beritappid->join('kategori_informasi_ppid', 'berita_ppid.kategori_id = kategori_informasi_ppid.idkategori')->where('kategori_informasi_ppid.slug', $slug)->where('kategori_informasi_ppid.status', 'Y')->where('nm_status', 'Publish')->orderBy('tanggal', 'desc')->findAll();
        $data['selectedKategori'] = $slug;


        $data = array_merge(
            $this->sidebarData('pkrs'),
            [
                'beritappid'       => $list,
                'selectedKategori' => $slug,
            ]
        );
        // $data += $this->loadBeritaKategori(BeritaPPID::KATEGORI_PKRS);
        // $data['url'] = 'pkrs';
        // $data['title'] = 'Media Informasi PKRS';


        return view('frontend/pkrs', $data);
    }

    public function saranaRumahSakit()
    {

        $data = $this->sidebarData('ppid');

        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/sarana', $data);
    }

    public function sopPPID()
    {
        $data = $this->sidebarData('ppid');
        return view('frontend/sop-ppid', $data);
    }
    public function standarBiaya()
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/standar-biaya', $data);
    }
    public function layananLansiaDanDifabel()
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/layanan-lansia', $data);
    }

    public function tataCaraPengaduan()
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/tata-cara-pengaduan', $data);
    }
    public function prosedurEvakuasi()
    {
        $data = $this->sidebarData('ppid');
        $data['profilPpid'] = $this->profilPPID->first();

        return view('frontend/prosedur-evakuasi', $data);
    }

    public function alurPelayanan($slug)
    {
        $data = $this->sidebarData('ppid');
        $data['alurPelayanan'] = $this->pages->where('status', 'Y')->where('slug', $slug)->first();
        return view('frontend/alur-pelayanan', $data);
    }

    public function formPPIDOnline()
    {
        $data = $this->sidebarData('ppid');

        return view('frontend/form-ppid-online', $data);
    }
    public function formKeberatanInformasiPPIDOnline()
    {
        $data = $this->sidebarData('ppid');

        return view('frontend/form-keberatan-informasi-online', $data);
    }
    public function waktuPemenuhanInformasi()
    {
        $data['permohonaninformasi'] = $this->permohononanInformasi->orderBy('tanggal', 'desc')->findAll();
        $data['keberataninformasi'] = $this->keberatanInformasi->orderBy('tanggal', 'desc')->findAll();

        return view('frontend/waktu-pemenuhan-informasi', $data);
    }
    public function daftarInovasi()
    {
        $data['inovasi'] = $this->daftarinovasi->orderBy('tahun', 'desc')->findAll();
        return view('frontend/daftar-inovasi', $data);
    }
    public function detailInovasi($id)
    {
        $data['inovasi'] = $this->daftarinovasi->find($id);
        return view('frontend/detail-inovasi', $data);
    }



    public function apiJadwalPoli()
    {
        $jadwalPoli = $this->jadwalpoli->getFormattedJadwalPoliWithDokter();
        return $this->response->setJSON([
            'data' => $jadwalPoli
        ]);
    }

    public function linkBio()
    {
        return view('frontend/bio');
    }

    public function generateSign($data, $xtimestamp)
    {
        $key = $data["X_ID"] . "&" . $xtimestamp;
        return base64_encode(
            hash_hmac("sha256", utf8_encode($key), utf8_encode($data["X_PASS"]), true)
        );
    }

    public function kamar()
    {
        date_default_timezone_set('UTC');

        //     'aplicares' => [
        //     'url' => 'https://new-api.bpjs-kesehatan.go.id/aplicaresws/rest',
        //     'id' => '21308',
        //     'key' => '5rVC94D0CF',
        //             'koders' => '0302R001',
        //             'timezone' => 'UTC',
        //     'addTime' => 'PT0M',
        //             'writeLog' => false,
        //   ],

        $data = [
            'X_ID'   => '21308',
            'X_PASS' => '5rVC94D0CF'
        ];

        $cons_id    = "21308";
        $secret_key = "5rVC94D0CF";
        $user_key   = "8c2756b1374e314d69eb9fa93e3a0a99";
        $kode_rs    = "0302R001";
        $start      = 1;
        $limit      = 1;

        $timestamp  = time();
        $signature  = $this->generateSign($data, $timestamp);

        print_r([$signature, $timestamp]);
        // return $signature;

        // $signature  = base64_encode(
        //     hash_hmac('sha256', $cons_id . "&" . $timestamp, $secret_key, true)
        // );


        // $url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws/rest/bed/read/{$kode_rs}/{$start}/{$limit}";

        // // {Base URL}/aplicaresws/rest/bed/delete/{kodeppk}

        // // $url = "https://dvlp.bpjs-kesehatan.go.id:8888/aplicaresws/rest/bed/delete/{$kode_rs}";

        // $headers = [
        //     'X-cons-id'   => $cons_id,
        //     'X-timestamp' => $timestamp,
        //     'X-signature' => $signature,
        //     'user_key'    => $user_key,
        //     'Accept'      => 'application/json',
        // ];


        // $curl = \Config\Services::curlrequest();
        // try {
        //     $response = $curl->request('GET', $url, [
        //         'headers' => $headers,
        //         'verify' => false // untuk development SSL
        //     ]);

        //     $body = $response->getBody();
        //     $result = json_decode($body, true);

        //     // Tampilkan hasilnya
        //     return view('kamar', ['data' => $result]);
        // } catch (\Exception $e) {
        //     return $e->getMessage();
        // }
    }
}
