<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// frontend
$routes->get('/', 'FrontendController::index');
$routes->get('/bio', 'FrontendController::linkBio');
$routes->get('/api/jadwal-poli', 'FrontendController::apiJadwalPoli');

$routes->get('/struktur-organisasi', 'FrontendController::strukturOrganisasi');
$routes->get('/tentang-kami', 'FrontendController::tentangKami');
$routes->get('/sejarah', 'FrontendController::sejarah');
$routes->get('/visi-misi', 'FrontendController::visiMisi');
$routes->get('/dokter-kami', 'FrontendController::dokterKami');
$routes->get('/profil-manajemen', 'FrontendController::profilManajemen');
$routes->get('/indikator-mutu', 'FrontendController::indikatorMutu');
$routes->get('/dokter-kami', 'FrontendController::dokterKami');
$routes->get('/galeri-foto', 'FrontendController::galeriFoto');
$routes->get('/galeri-foto/(:any)', 'FrontendController::galeriFotoDetail/$1');
$routes->get('/galeri-video', 'FrontendController::galeriVideo');
$routes->get('/pojok-referensi', 'FrontendController::pojokReferensi');
$routes->get('/informasi-ketersediaan-tempattidur', 'FrontendController::tempatTidur');

$routes->get('/kontak', 'FrontendController::kontak');
$routes->get('/igd', 'FrontendController::igd');
$routes->get('/poliklinik', 'FrontendController::poliklinik');

$routes->post('/kontak/save', 'Backend\PesanController::save');

// ketersediaan tempat tidur
$routes->get('/ketersediaan-kamar', 'FrontendController::kamar');

// berita
$routes->get('/blog', 'FrontendController::berita');
$routes->get('/blog/(:any)', 'FrontendController::beritaDetail/$1');
$routes->get('/kategori/(:any)', 'FrontendController::beritaKategori/$1');

// rawat
$routes->get('/rawat-inap/(:any)', 'FrontendController::rawatInap/$1');

// penunjang
$routes->get('/penunjangs/(:any)', 'FrontendController::penunjang/$1');

// fasilitas
$routes->get('/fas-umum/(:any)', 'FrontendController::fasilitas/$1');

// poliklinik
$routes->get('/poliklinik/(:any)', 'FrontendController::detailPoliklinik/$1');
$routes->get('/pkrs', 'FrontendController::daftarInformasiPkrs');
$routes->get('/pkrs/(:any)', 'FrontendController::pkrs/$1');


// PPID
$routes->get('/visi-misi-ppid', 'FrontendController::visiMisiPPID');
$routes->get('/tugas-fungsi-ppid', 'FrontendController::tugasFungsiPPID');
$routes->get('/profil-singkat', 'FrontendController::profilSingkat');
$routes->get('/maklumat', 'FrontendController::maklumat');
$routes->get('/alur-permohonan-informasi', 'FrontendController::alurPermohonanInformasi');
$routes->get('/alur-pengajuan-keberatan', 'FrontendController::alurPengajuanKeberatan');
$routes->get('/alur-penyelesaian-sengketa', 'FrontendController::alurPenyelesaianSengketa');
$routes->get('/struktur-ppid', 'FrontendController::strukturPPID');
$routes->get('/ppid/(:any)', 'FrontendController::laporan/$1');
$routes->get('/pelayanan/(:any)', 'FrontendController::alurPelayanan/$1');
$routes->get('/beritakategori/(:any)', 'FrontendController::beritaKategoriPPID/$1');
$routes->get('/beritappid-detail/(:any)', 'FrontendController::beritaDetailPPID/$1');
$routes->get('/dip', 'FrontendController::daftarInformasiPublik');
$routes->get('/peraturan', 'FrontendController::peraturanKIP');
$routes->get('/sarana', 'FrontendController::saranaRumahSakit');
$routes->get('/sop', 'FrontendController::sopPPID');
$routes->get('/standar-biaya', 'FrontendController::standarBiaya');
$routes->get('/layanan-lansia-dan-difabel', 'FrontendController::layananLansiaDanDifabel');
$routes->get('/tata-cara-pengaduan', 'FrontendController::tataCaraPengaduan');
$routes->get('/prosedur-evakuasi', 'FrontendController::prosedurEvakuasi');
$routes->get('/form-ppid-online', 'FrontendController::formPPIDOnline');
$routes->get('/form-keberatan-informasi-ppid-online', 'FrontendController::formKeberatanInformasiPPIDOnline');
$routes->get('/waktu-pemenuhan-informasi', 'FrontendController::waktuPemenuhanInformasi');

// route daftar inovasi
$routes->get('/daftar-inovasi', 'FrontendController::daftarInovasi');
$routes->get('/daftar-inovasi/(:any)', 'FrontendController::detailInovasi/$1');


$routes->post('/pesan-ppid', 'PPID\FormulirPpidController::formulirPPIDSave');
$routes->post('/bunga-seroja/save', 'Backend\PengaduanController::save');

$routes->post('/keberataninformasi/save', 'PPID\KeberatanInformasiController::formulirKeberatanInformasiPPIDSave');

$routes->get('/form-whistleblowing', 'Backend\WbsController::create');
$routes->get('/form-pengaduan-bunga-seroja', 'Backend\PengaduanController::create');

$routes->post('/laporawbs/save', 'Backend\WbsController::save');


$routes->post('post/download', 'FrontendController::incrementDownloadCount');



$routes->get('/home', 'Backend\HomeController::index', ['filter' => 'auth']);

// Route Login
$routes->get('/auth', 'Backend\LoginController::index');
$routes->post('/auth/login', 'Backend\LoginController::login');
$routes->get('/logout', 'Backend\LoginController::logout');

// Route Album
$routes->group('album', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\AlbumController::index');
    $routes->get('create', 'Backend\AlbumController::create');
    $routes->get('getData', 'Backend\AlbumController::getData');
    $routes->post('save', 'Backend\AlbumController::save');
    $routes->get('edit/(:any)', 'Backend\AlbumController::edit/$1');
    $routes->put('update', 'Backend\AlbumController::update');
    $routes->delete('delete/(:any)', 'Backend\AlbumController::delete/$1');
});

// ? route faq
$routes->group('faqs', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\FaqController::index');
    $routes->get('create', 'Backend\FaqController::create');
    $routes->get('getData', 'Backend\FaqController::getData');
    $routes->post('save', 'Backend\FaqController::save');
    $routes->get('edit/(:any)', 'Backend\FaqController::edit/$1');
    $routes->put('update', 'Backend\FaqController::update');
    $routes->delete('delete/(:any)', 'Backend\FaqController::delete/$1');
});

// ? route permohonan keberatan informasi PPID
$routes->group('keberataninformasi', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\KeberatanInformasiController::index');
    $routes->get('create', 'PPID\KeberatanInformasiController::create');
    $routes->get('getData', 'PPID\KeberatanInformasiController::getData');
    $routes->get('edit/(:any)', 'PPID\KeberatanInformasiController::edit/$1');
    $routes->put('update', 'PPID\KeberatanInformasiController::update');
    $routes->delete('delete/(:any)', 'PPID\KeberatanInformasiController::delete/$1');
});

// ? route permohonan informasi ppid
$routes->group('pesanppid', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\FormulirPpidController::index');
    $routes->get('create', 'PPID\FormulirPpidController::create');
    $routes->get('getData', 'PPID\FormulirPpidController::getData');
    $routes->get('edit/(:any)', 'PPID\FormulirPpidController::edit/$1');
    $routes->put('update', 'PPID\FormulirPpidController::update');
    $routes->delete('delete/(:any)', 'PPID\FormulirPpidController::delete/$1');
});

// ? route pengaduan untuk ppt bunga seroja
$routes->group('bunga-seroja', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\PengaduanController::index');
    $routes->get('getData', 'Backend\PengaduanController::getData');
    $routes->get('edit/(:any)', 'Backend\PengaduanController::edit/$1');
    $routes->put('update', 'Backend\PengaduanController::update');
    $routes->delete('delete/(:any)', 'Backend\PengaduanController::delete/$1');
});

// ? route pengaduan whistleblowing
$routes->group('laporwbs', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\WbsController::index');
    $routes->get('getData', 'Backend\WbsController::getData');
    $routes->delete('delete/(:any)', 'Backend\WbsController::delete/$1');
});

// Route Kategoris
$routes->group('kategoris', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\KategoriController::index');
    $routes->get('create', 'Backend\KategoriController::create');
    $routes->get('getData', 'Backend\KategoriController::getData');
    $routes->post('save', 'Backend\KategoriController::save');
    $routes->get('edit/(:any)', 'Backend\KategoriController::edit/$1');
    $routes->put('update', 'Backend\KategoriController::update');
    $routes->delete('delete/(:any)', 'Backend\KategoriController::delete/$1');
});


// Route Spesialis
$routes->group('spesialis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\SpesialisController::index');
    $routes->get('create', 'Backend\SpesialisController::create');
    $routes->get('getData', 'Backend\SpesialisController::getData');
    $routes->post('save', 'Backend\SpesialisController::save');
    $routes->get('edit/(:any)', 'Backend\SpesialisController::edit/$1');
    $routes->put('update', 'Backend\SpesialisController::update');
    $routes->delete('delete/(:any)', 'Backend\SpesialisController::delete/$1');
});

// Route Indikator Mutu
$routes->group('indikatormutu', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\IndikatorMutuController::index');
    $routes->get('create', 'Backend\IndikatorMutuController::create');
    $routes->get('getData', 'Backend\IndikatorMutuController::getData');
    $routes->post('save', 'Backend\IndikatorMutuController::save');
    $routes->get('edit/(:any)', 'Backend\IndikatorMutuController::edit/$1');
    $routes->put('update', 'Backend\IndikatorMutuController::update');
    $routes->delete('delete/(:any)', 'Backend\IndikatorMutuController::delete/$1');
});

// Route Rawat

$routes->group('rawat', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\RawatController::index');
    $routes->get('create', 'Backend\RawatController::create');
    $routes->get('getData', 'Backend\RawatController::getData');
    $routes->post('save', 'Backend\RawatController::save');
    $routes->get('edit/(:any)', 'Backend\RawatController::edit/$1');
    $routes->put('update', 'Backend\RawatController::update');
    $routes->delete('delete/(:any)', 'Backend\RawatController::delete/$1');
});


// Route Penunjang
$routes->group('penunjang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\PenunjangController::index');
    $routes->get('create', 'Backend\PenunjangController::create');
    $routes->get('getData', 'Backend\PenunjangController::getData');
    $routes->post('save', 'Backend\PenunjangController::save');
    $routes->get('edit/(:any)', 'Backend\PenunjangController::edit/$1');
    $routes->put('update', 'Backend\PenunjangController::update');
    $routes->delete('delete/(:any)', 'Backend\PenunjangController::delete/$1');
});

// Route Manajemen
$routes->group('manajemen', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\ManajemenController::index');
    $routes->get('create', 'Backend\ManajemenController::create');
    $routes->get('getData', 'Backend\ManajemenController::getData');
    $routes->post('save', 'Backend\ManajemenController::save');
    $routes->get('edit/(:any)', 'Backend\ManajemenController::edit/$1');
    $routes->put('update', 'Backend\ManajemenController::update');
    $routes->delete('delete/(:any)', 'Backend\ManajemenController::delete/$1');
});


// Route Tempat Tidur
$routes->group('tempattidur', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\TempatTidurController::index');
    $routes->get('create', 'Backend\TempatTidurController::create');
    $routes->get('getData', 'Backend\TempatTidurController::getData');
    $routes->post('save', 'Backend\TempatTidurController::save');
    $routes->get('edit/(:any)', 'Backend\TempatTidurController::edit/$1');
    $routes->put('update', 'Backend\TempatTidurController::update');
    $routes->delete('delete/(:any)', 'Backend\TempatTidurController::delete/$1');
});


// Route Banners
$routes->group('banners', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\BannerController::index');
    $routes->get('create', 'Backend\BannerController::create');
    $routes->get('getData', 'Backend\BannerController::getData');
    $routes->post('save', 'Backend\BannerController::save');
    $routes->get('edit/(:any)', 'Backend\BannerController::edit/$1');
    $routes->put('update', 'Backend\BannerController::update');
    $routes->delete('delete/(:any)', 'Backend\BannerController::delete/$1');
});


// Route referensi
$routes->group('referensis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\ReferensiController::index');
    $routes->get('create', 'Backend\ReferensiController::create');
    $routes->get('getData', 'Backend\ReferensiController::getData');
    $routes->post('save', 'Backend\ReferensiController::save');
    $routes->get('edit/(:any)', 'Backend\ReferensiController::edit/$1');
    $routes->put('update', 'Backend\ReferensiController::update');
    $routes->delete('delete/(:any)', 'Backend\ReferensiController::delete/$1');
});


// Route fasilitasi
$routes->group('fasilitasumum', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\FasilitasController::index');
    $routes->get('create', 'Backend\FasilitasController::create');
    $routes->get('getData', 'Backend\FasilitasController::getData');
    $routes->post('save', 'Backend\FasilitasController::save');
    $routes->get('edit/(:any)', 'Backend\FasilitasController::edit/$1');
    $routes->put('update', 'Backend\FasilitasController::update');
    $routes->delete('delete/(:any)', 'Backend\FasilitasController::delete/$1');
});

// Route slider
$routes->group('sliders', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\SliderController::index');
    $routes->get('create', 'Backend\SliderController::create');
    $routes->get('getData', 'Backend\SliderController::getData');
    $routes->post('save', 'Backend\SliderController::save');
    $routes->get('edit/(:any)', 'Backend\SliderController::edit/$1');
    $routes->put('update', 'Backend\SliderController::update');
    $routes->delete('delete/(:any)', 'Backend\SliderController::delete/$1');
});

$routes->group('sejarahs', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\SejarahController::index');
    $routes->get('create', 'Backend\SejarahController::create');
    $routes->get('getData', 'Backend\SejarahController::getData');
    $routes->post('save', 'Backend\SejarahController::save');
    $routes->get('edit/(:any)', 'Backend\SejarahController::edit/$1');
    $routes->put('update', 'Backend\SejarahController::update');
    $routes->delete('delete/(:any)', 'Backend\SejarahController::delete/$1');
});

// route inovasi
$routes->group('inovasis', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\InovasiController::index');
    $routes->get('create', 'PPID\InovasiController::create');
    $routes->get('getData', 'PPID\InovasiController::getData');
    $routes->post('save', 'PPID\InovasiController::save');
    $routes->get('edit/(:any)', 'PPID\InovasiController::edit/$1');
    $routes->put('update', 'PPID\InovasiController::update');
    $routes->delete('delete/(:any)', 'PPID\InovasiController::delete/$1');
});


// Route profil
$routes->group('profils', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\ProfilController::index');
    $routes->get('create', 'Backend\ProfilController::create');
    $routes->post('save', 'Backend\ProfilController::save');
});


// Route Indikator Mutu List
$routes->group('indikatormutulists', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\IndikatorMutuListController::index');
    $routes->get('create', 'Backend\IndikatorMutuListController::create');
    $routes->get('getData', 'Backend\IndikatorMutuListController::getData');
    $routes->post('save', 'Backend\IndikatorMutuListController::save');
    $routes->get('edit/(:any)', 'Backend\IndikatorMutuListController::edit/$1');
    $routes->put('update', 'Backend\IndikatorMutuListController::update');
    $routes->delete('delete/(:any)', 'Backend\IndikatorMutuListController::delete/$1');
});


// Route Dokter
$routes->group('dokters', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\DokterController::index');
    $routes->get('create', 'Backend\DokterController::create');
    $routes->get('getData', 'Backend\DokterController::getData');
    $routes->post('save', 'Backend\DokterController::save');
    $routes->get('edit/(:any)', 'Backend\DokterController::edit/$1');
    $routes->put('update', 'Backend\DokterController::update');
    $routes->delete('delete/(:any)', 'Backend\DokterController::delete/$1');
});


// Route video
$routes->group('videos', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\VideoController::index');
    $routes->get('create', 'Backend\VideoController::create');
    $routes->get('getData', 'Backend\VideoController::getData');
    $routes->post('save', 'Backend\VideoController::save');
    $routes->get('edit/(:any)', 'Backend\VideoController::edit/$1');
    $routes->put('update', 'Backend\VideoController::update');
    $routes->delete('delete/(:any)', 'Backend\VideoController::delete/$1');
});

// Route poli
$routes->group('poly', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\PoliController::index');
    $routes->get('create', 'Backend\PoliController::create');
    $routes->get('getData', 'Backend\PoliController::getData');
    $routes->post('save', 'Backend\PoliController::save');
    $routes->get('edit/(:any)', 'Backend\PoliController::edit/$1');
    $routes->put('update', 'Backend\PoliController::update');
    $routes->delete('delete/(:any)', 'Backend\PoliController::delete/$1');
});


// Route Jadwal Poli

$routes->group('jadwalpoli', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\JadwalPoliController::index');
    $routes->get('create', 'Backend\JadwalPoliController::create');
    $routes->get('getData', 'Backend\JadwalPoliController::getData');
    $routes->post('save', 'Backend\JadwalPoliController::save');
    $routes->get('edit/(:any)', 'Backend\JadwalPoliController::edit/$1');
    $routes->put('update', 'Backend\JadwalPoliController::update');
    $routes->delete('delete/(:any)', 'Backend\JadwalPoliController::delete/$1');

    // get Dokter with spesialis
    $routes->post('getDokter', 'Backend\JadwalPoliController::getDokterSpesialis');
});


// Route Filedownload
$routes->group('filedownloads', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\FileDownloadController::index');
    $routes->get('create', 'Backend\FileDownloadController::create');
    $routes->get('getData', 'Backend\FileDownloadController::getData');
    $routes->post('save', 'Backend\FileDownloadController::save');
    $routes->get('edit/(:any)', 'Backend\FileDownloadController::edit/$1');
    $routes->put('update', 'Backend\FileDownloadController::update');
    $routes->delete('delete/(:any)', 'Backend\FileDownloadController::delete/$1');
});


// Route manajemenprofil
$routes->group('manajemenprofils', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\ManajemenProfilController::index');
    $routes->get('create', 'Backend\ManajemenProfilController::create');
    $routes->get('getData', 'Backend\ManajemenProfilController::getData');
    $routes->post('save', 'Backend\ManajemenProfilController::save');
    $routes->get('edit/(:any)', 'Backend\ManajemenProfilController::edit/$1');
    $routes->put('update', 'Backend\ManajemenProfilController::update');
    $routes->delete('delete/(:any)', 'Backend\ManajemenProfilController::delete/$1');
});

// Route album list
$routes->group('albumlists', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\AlbumListController::index');
    $routes->get('create', 'Backend\AlbumListController::create');
    $routes->get('getData', 'Backend\AlbumListController::getData');
    $routes->post('save', 'Backend\AlbumListController::save');
    $routes->get('edit/(:any)', 'Backend\AlbumListController::edit/$1');
    $routes->get('detail/(:any)', 'Backend\AlbumListController::detail/$1');
    $routes->put('update', 'Backend\AlbumListController::update');
    $routes->delete('delete/(:any)', 'Backend\AlbumListController::delete/$1');
    $routes->post('uploadimage', 'Backend\AlbumListController::uploadImage');
    $routes->post('tambahgambar', 'Backend\AlbumListController::tambahGambar');
    $routes->post('hapusgambar', 'Backend\AlbumListController::hapusgambar');
});

// Route berita
$routes->group('beritas', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\BeritaController::index');
    $routes->get('create', 'Backend\BeritaController::create');
    $routes->get('getData', 'Backend\BeritaController::getData');
    $routes->post('save', 'Backend\BeritaController::save');
    $routes->get('edit/(:any)', 'Backend\BeritaController::edit/$1');
    $routes->put('update', 'Backend\BeritaController::update');
    $routes->delete('delete/(:any)', 'Backend\BeritaController::delete/$1');
});


// Route polling
$routes->group('pollings', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\PollingController::index');
    $routes->get('create', 'Backend\PollingController::create');
    $routes->get('getData', 'Backend\PollingController::getData');
    $routes->post('save', 'Backend\PollingController::save');
    $routes->get('edit/(:any)', 'Backend\PollingController::edit/$1');
    $routes->put('update', 'Backend\PollingController::update');
    $routes->delete('delete/(:any)', 'Backend\PollingController::delete/$1');
});


$routes->get('/pollings/vote', 'Backend\PollingController::vote');
$routes->post('/pollings/voting', 'Backend\PollingController::voting');



// Route pesan
$routes->group('pesans', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\PesanController::index');
    $routes->get('getData', 'Backend\PesanController::getData');
    $routes->get('edit/(:any)', 'Backend\PesanController::edit/$1');
    $routes->put('update', 'Backend\PesanController::update');
    $routes->delete('delete/(:any)', 'Backend\PesanController::delete/$1');
});



// Route users
$routes->group('users', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\UserController::index');
    $routes->get('create', 'Backend\UserController::create');
    $routes->get('getData', 'Backend\UserController::getData');
    $routes->post('save', 'Backend\UserController::save');
    $routes->get('edit/(:any)', 'Backend\UserController::edit/$1');
    $routes->put('update', 'Backend\UserController::update');
    $routes->delete('delete/(:any)', 'Backend\UserController::delete/$1');
});


//  Route Tentang
$routes->group('tentangs', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Backend\TentangController::index');
    $routes->get('create', 'Backend\TentangController::create');
    $routes->post('save', 'Backend\TentangController::save');
});



// Route Pages
$routes->group('page', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\PagesController::index');
    $routes->get('create', 'PPID\PagesController::create');
    $routes->get('getData', 'PPID\PagesController::getData');
    $routes->post('save', 'PPID\PagesController::save');
    $routes->get('edit/(:any)', 'PPID\PagesController::edit/$1');
    $routes->put('update', 'PPID\PagesController::update');
    $routes->delete('delete/(:any)', 'PPID\PagesController::delete/$1');
});



// Route Kategoripppid
$routes->group('kategoris-ppid', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\KategoriInformasiController::index');
    $routes->get('create', 'PPID\KategoriInformasiController::create');
    $routes->get('getData', 'PPID\KategoriInformasiController::getData');
    $routes->post('save', 'PPID\KategoriInformasiController::save');
    $routes->get('edit/(:any)', 'PPID\KategoriInformasiController::edit/$1');
    $routes->put('update', 'PPID\KategoriInformasiController::update');
    $routes->delete('delete/(:any)', 'PPID\KategoriInformasiController::delete/$1');
});


// Route beritappid
$routes->group('beritappid', ['filter' => 'auth'], function ($routes) {
    $routes->get('ppid', 'PPID\BeritaPpidController::index/ppid');
    $routes->get('pkrs', 'PPID\BeritaPpidController::index/pkrs');

    $routes->get('create/(:any)', 'PPID\BeritaPpidController::create/$1');
    $routes->get('getData/(:any)', 'PPID\BeritaPpidController::getData/$1');
    $routes->post('save/(:any)', 'PPID\BeritaPpidController::save/$1');
    $routes->get('edit/(:any)/(:any)', 'PPID\BeritaPpidController::edit/$1/$2');
    $routes->put('update', 'PPID\BeritaPpidController::update');
    $routes->delete('delete/(:any)', 'PPID\BeritaPpidController::delete/$1');
});

$routes->group('profilppid', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'PPID\ProfilPpidController::index');
    $routes->get('create', 'PPID\ProfilPpidController::create');
    $routes->post('save', 'PPID\ProfilPpidController::save');
    $routes->post('uploadGambarVisimisi', 'PPID\ProfilPpidController::uploadGambarVisimisi');
    $routes->post('uploadGambarTugas', 'PPID\ProfilPpidController::uploadGambarTugas');
    $routes->post('uploadGambarFungsi', 'PPID\ProfilPpidController::uploadGambarFungsi');
});


// Api PPID
$routes->get('/apiberitappid', 'PPID\BeritaPpidController::apiIndex', ['filter' => 'auth']);
$routes->get('/apiberitappid/getDataFromAPI', 'PPID\BeritaPpidController::getDataApi', ['filter' => 'auth']);
$routes->post('/apiberitappid/saveSelected', 'PPID\BeritaPpidController::saveSelectedData', ['filter' => 'auth']);

$routes->get('/akun', 'Backend\HomeController::myAccount', ['filter' => 'auth']);
$routes->post('/save', 'Backend\HomeController::saveAccount', ['filter' => 'auth']);

// Route Laporan Daftar Informasi Publik
$routes->get('/laporan/daftar-informasi-publik', 'PPID\BeritaPpidController::cetakLaporanInformasiPublik', ['filter' => 'auth']);

$routes->get('/laporan/whistleblowing', 'Backend\WbsController::cetakLaporanWbs', ['filter' => 'auth']);

$routes->get('/laporan/permintaan-informasi-ppid', 'PPID\FormulirPpidController::cetakLaporanPermintaanInformasiPPID', ['filter' => 'auth']);
$routes->get('/laporan/keberatan-informasi-ppid', 'PPID\KeberatanInformasiController::cetakLaporanKeberatanInformasiPPID', ['filter' => 'auth']);
