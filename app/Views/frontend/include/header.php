<!-- topbar 1 -->
<?= $this->include('frontend/include/top-bar-1') ?>


<style>
    /* Aturan untuk layar kecil (di bawah 992px) */
    @media (max-width: 992px) {
        #logo img {
            max-height: 30px;
            height: auto;
            display: block;
        }
    }


    @media (min-width: 992px) {
        #logo {
            flex: 0 0 auto;
        }

        #logo img {
            max-height: 80px;
            height: auto;
            display: block;
        }
    }
</style>

<?php

use App\Models\BeritaPPID;

$kategoriInformasi  = (new \App\Models\KategoriInformasiPPID())->whereIn('idkategori', BeritaPPID::KATEGORI_PPID)->where('status', 'Y')->orderBy('title', 'asc')->findAll();
?>

<header id="header">
    <div id="header-wrap">
        <div class="container">
            <div class="header-row">

                <div id="logo">
                    <a href="<?= site_url('/') ?>" class="standard-logo" data-dark-logo="<?= base_url(); ?>/assets/images/rsud.png"><img src="<?= base_url(); ?>/assets/images/rsud.png" alt="Logo"></a>
                    <a href="<?= site_url('/') ?>" class="retina-logo" data-dark-logo="<?= base_url(); ?>/assets/images/rsud.png"><img src="<?= base_url(); ?>/assets/images/rsud.png" alt="Logo"></a>
                </div>


                <div id="primary-menu-trigger">
                    <svg class="svg-trigger" viewBox="0 0 100 100">
                        <path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20">
                        </path>
                        <path d="m 30,50 h 40"></path>
                        <path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20">
                        </path>
                    </svg>
                </div>

                <!-- Primary Navigation
						============================================= -->
                <nav class="primary-menu">

                    <ul class="menu-container">
                        <li class="menu-item <?= current_url(true)->getPath() == '/' ? 'current' : '' ?>"><a class="menu-link" href="<?= site_url('/') ?>">
                                <div>Home</div>
                            </a>
                        </li>

                        <li class="menu-item <?= current_url(true)->getPath() == '/struktur-organisasi' ||
                                                    current_url(true)->getPath() == '/tentang-kami' ||
                                                    current_url(true)->getPath() == '/sejarah' ||
                                                    current_url(true)->getPath() == '/visi-misi' ||
                                                    current_url(true)->getPath() == '/profil-manajemen' ||
                                                    current_url(true)->getPath() == '/indikator-mutu' ? 'current' : '' ?>">
                            <a class="menu-link" href="javascript:void(0);">
                                <div>Profil</div>
                            </a>
                            <ul class="sub-menu-container">

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('struktur-organisasi') ?>">
                                        <div>Struktur Organisasi</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('tentang-kami') ?>">
                                        <div>Tentang Kami</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('sejarah') ?>">
                                        <div>Sejarah & Perkembangan</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('visi-misi') ?>">
                                        <div>Visi Misi</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('profil-manajemen') ?>">
                                        <div>Profil Manajemen</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('indikator-mutu') ?>">
                                        <div>Indikator Mutu RS</div>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="menu-item <?= strpos(current_url(true)->getPath(), '/blog') === 0 || strpos(current_url(true)->getPath(), 'kategori/AG') || strpos(current_url(true)->getPath(), 'kategori/IA') || strpos(current_url(true)->getPath(), 'kategori/BR') || strpos(current_url(true)->getPath(), 'kategori/PK') ? 'current' : '' ?>">
                            <a class="menu-link" href="<?= site_url('blog') ?>">
                                <div>Berita</div>
                            </a>


                            <ul class="sub-menu-container">
                                <?php $kategori = get_kategori(); ?>
                                <?php foreach ($kategori as $item) : ?>
                                    <li class="menu-item">
                                        <a class="menu-link" href="<?= site_url('kategori/' . $item['idkategori']) ?>">
                                            <div><?= $item['title'] ?></div>
                                        </a>
                                    </li>


                                <?php endforeach; ?>

                            </ul>

                        </li>


                        <?php
                        $currentPath = current_url(true)->getPath();
                        $isFasilitasActive = in_array($currentPath, [
                            '/rawat-inap',
                            '/igd',
                            '/penunjangs',
                            '/fas-umum',
                            '/poliklinik',
                            '/pojok-referensi',
                            '/informasi-ketersediaan-tempattidur',
                            '/pkrs',

                        ]);

                        // Check sub-menu paths
                        $rawatList = (new \App\Models\Rawat())->where('status', 'Y')->orderBy('nama', 'asc')->findAll();
                        foreach ($rawatList as $item) {
                            if (strpos($currentPath, '/rawat-inap/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) === 0) {
                                $isFasilitasActive = true;
                                break;
                            }
                        }

                        $polikliniks = (new \App\Models\Poli())->where('status', 'Y')->orderBy('nama', 'asc')->findAll();
                        foreach ($polikliniks as $item) {
                            if (strpos($currentPath, '/poliklinik/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) === 0) {
                                $isFasilitasActive = true;
                                break;
                            }
                        }

                        $penunjangList = (new \App\Models\Penunjang())->where('status', 'Y')->orderBy('nama', 'asc')->findAll();
                        foreach ($penunjangList as $item) {
                            if (strpos($currentPath, '/penunjangs/' . htmlspecialchars(url_title($item['nama']), ENT_QUOTES, 'UTF-8')) === 0) {
                                $isFasilitasActive = true;
                                break;
                            }
                        }

                        $pkrsList = (new \App\Models\KategoriInformasiPPID())->whereIn('idkategori', BeritaPPID::KATEGORI_PKRS)->where('status', 'Y')->orderBy('title', 'asc')->findAll();
                        foreach ($pkrsList as $item) {
                            if (strpos($currentPath, '/pkrs/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) === 0) {
                                $isFasilitasActive = true;
                                break;
                            }
                        }

                        $fasilitasList = (new \App\Models\Fasilitas())->where('status', 'Y')->orderBy('nama', 'asc')->findAll();
                        foreach ($fasilitasList as $item) {
                            if (strpos($currentPath, '/fas-umum/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) === 0) {
                                $isFasilitasActive = true;
                                break;
                            }
                        }
                        ?>

                        <li class="menu-item <?= $isFasilitasActive ? 'current' : '' ?>"><a class="menu-link" href="javascript:void(0);">
                                <div>Fasilitas & Layanan</div>
                            </a>
                            <ul class="sub-menu-container">
                                <li class="menu-item">
                                    <a class="menu-link" href="javascript:void(0);">
                                        <div>Rawat Inap</div>
                                    </a>
                                    <ul class="sub-menu-container">
                                        <?php foreach ($rawatList as $item) : ?>
                                            <li class="menu-item <?= $currentPath == '/rawat-inap/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8') ? 'current' : '' ?>">
                                                <a class="menu-link" href="<?= site_url('rawat-inap/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) ?>">
                                                    <div><?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?></div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/pkrs') ?>">
                                        <div>PKRS</div>
                                    </a>
                                    <ul class="sub-menu-container">
                                        <?php foreach ($pkrsList as $item) : ?>
                                            <li class="menu-item <?= $currentPath == '/pkrs/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8') ? 'current' : '' ?>">
                                                <a class="menu-link" href="<?= site_url('pkrs/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) ?>">
                                                    <div><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="javascript:void(0);">
                                        <div>Penunjang</div>
                                    </a>
                                    <ul class="sub-menu-container">
                                        <?php foreach ($penunjangList as $item) : ?>
                                            <li class="menu-item <?= $currentPath == '/penunjangs/' . htmlspecialchars(url_title($item['nama']), ENT_QUOTES, 'UTF-8') ? 'current' : '' ?>">
                                                <a class="menu-link" href="<?= site_url('penunjangs/' . htmlspecialchars(url_title($item['nama']), ENT_QUOTES, 'UTF-8')) ?>">
                                                    <div><?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?></div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="javascript:void(0);">
                                        <div>Fasilitas Umum</div>
                                    </a>
                                    <ul class="sub-menu-container">
                                        <?php foreach ($fasilitasList as $item) : ?>
                                            <li class="menu-item <?= $currentPath == '/fas-umum/' . htmlspecialchars(url_title($item['nama']), ENT_QUOTES, 'UTF-8') ? 'current' : '' ?>">
                                                <a class="menu-link" href="<?= site_url('fas-umum/' . htmlspecialchars(url_title($item['slug']), ENT_QUOTES, 'UTF-8')) ?>">
                                                    <div><?= htmlspecialchars($item['nama'], ENT_QUOTES, 'UTF-8') ?></div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>

                                <li class="menu-item <?= $currentPath == '/igd' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('igd') ?>">
                                        <div>Instalasi Gawat Darurat</div>
                                    </a>
                                </li>

                                <li class="menu-item <?= $currentPath == '/poliklinik' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('poliklinik') ?>">
                                        <div>Poliklinik</div>
                                    </a>
                                </li>

                                <li class="menu-item <?= $currentPath == '/pojok-referensi' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('pojok-referensi') ?>">
                                        <div>Pojok Referensi</div>
                                    </a>
                                </li>
                                <li class="menu-item <?= $currentPath == '/informasi-ketersediaan-tempattidur' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('informasi-ketersediaan-tempattidur') ?>">
                                        <div>Ketersedian Tempat Tidur</div>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <?php
                        $currentPath = current_url(true)->getPath();
                        $ppidPaths  =  [
                            '/profil-singkat',
                            '/visi-misi-ppid',
                            '/tugas-fungsi-ppid',
                            '/maklumat',
                            '/struktur-organisasi-ppid',
                            '/dip',
                            '/pelayanan/alur-permohonan-informasi',
                            '/pelayanan/alur-pengajuan-keberatan',
                            '/pelayanan/alur-penyelesaian-sengketa',
                            '/pelayanan/maklumat-layanan',
                            '/pelayanan/struktur-ppid',
                            '/ppid/sop-pelayanan',
                            '/ppid/sop-penunjang',
                            '/ppid/lhkpn',
                            '/ppid/laporan-keuangan-rsud-pariaman',
                            '/ppid/undang-undang-tentang-rumah-sakit',
                            '/ppid/mou',
                            '/peraturan',
                            '/sarana',
                            '/standar-biaya',
                            '/sop',
                            '/form-ppid-online',
                            '/form-keberatan-informasi-ppid-online',
                            '/tata-cara-pengaduan',
                            '/prosedur-evakuasi',
                            '/waktu-pemenuhan-informasi',
                            '/form-whistleblowing',
                            '/layanan-lansia-dan-difabel',
                        ];

                        $isPpidActive = false;
                        foreach ($ppidPaths as $path) {
                            if (preg_match('#^' . preg_quote($path, '#') . '(/.*)?$#', $currentPath)) {
                                $isPpidActive = true;
                                break;
                            }
                        }

                        // Check sub-menu paths

                        foreach ($kategoriInformasi as $item) {
                            if (strpos($currentPath, '/beritakategori/' . $item['idkategori']) === 0) {
                                $isPpidActive = true;
                                break;
                            }
                        }

                        ?>


                        <li class="menu-item <?= $isPpidActive ? 'current' : '' ?>"><a class="menu-link" href="javascript:void(0);">
                                <div>PPID</div>
                            </a>
                            <ul class="sub-menu-container">
                                <li class="menu-item">
                                    <a class="menu-link" href="javascript:void(0);">
                                        <div>Profil</div>
                                    </a>
                                    <ul class="sub-menu-container">

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('profil-singkat') ?>">
                                                <div>Profil Singkat PPID</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('visi-misi-ppid') ?>">
                                                <div>Visi & Misi PPID</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('tugas-fungsi-ppid') ?>">
                                                <div>Tugas & Fungsi PPID</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('maklumat') ?>">
                                                <div>Maklumat Hakekat & Asas</div>
                                            </a>
                                        </li>

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/pelayanan/struktur-ppid') ?>">
                                                <div>Struktur Organisasi PPID</div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('dip') ?>">
                                        <div>Informasi Publik</div>
                                    </a>
                                    <ul class="sub-menu-container">
                                        <?php foreach ($kategoriInformasi as $item) : ?>
                                            <li class="menu-item">
                                                <a class="menu-link" href="<?= site_url('beritakategori/' . $item['idkategori']) ?>">
                                                    <div><?= esc($item['title']) ?></div>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('waktu-pemenuhan-informasi') ?>">
                                                <div>Waktu Pemenuhan Informasi</div>
                                            </a>
                                        </li>



                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="javascript:void(0);">
                                        <div>Prosedur & Peraturan</div>
                                    </a>
                                    <ul class="sub-menu-container">

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/pelayanan/alur-permohonan-informasi') ?>">
                                                <div>Alur Memperoleh Informasi</div>
                                            </a>
                                        </li>

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/pelayanan/alur-pengajuan-keberatan') ?>">
                                                <div>Alur Pengajuan Keberatan</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/pelayanan/alur-penyelesaian-sengketa') ?>">
                                                <div>Alur Penyelesaian Sengketa Informasi</div>
                                            </a>
                                        </li>
                                        <!-- <li class="menu-item">
                                            <a class="menu-link" href="https://drive.google.com/file/d/1yE-qpqxuUniq6iZdXLX1BEwEiDhHyvJY/view" target="_blank">
                                                <div>Formulir Permohonan Informasi</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="https://drive.google.com/file/d/19zMsLeJiTvcN6eFjn4bJVztijZBoA5fk/view" target="_blank">
                                                <div>Formulir Keberatan Informasi</div>
                                            </a>
                                        </li> -->
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('form-ppid-online') ?>">
                                                <div>Form Permohonan Informasi Online</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('form-keberatan-informasi-ppid-online') ?>">
                                                <div>Form Keberatan Informasi Online</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('tata-cara-pengaduan') ?>">
                                                <div>Tata Cara Pengaduan</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('prosedur-evakuasi') ?>">
                                                <div>Prosedur Evakuasi</div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/dip') ?>">
                                        <div>DIP</div>
                                    </a>
                                    <ul class="sub-menu-container">

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/dip') ?>">
                                                <div>DIP</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/ppid/mou') ?>">
                                                <div>MOU</div>
                                            </a>
                                        </li>

                                        <li class="menu-item">
                                            <a class="menu-link" href="<?= site_url('/sop') ?>">
                                                <div>SOP</div>
                                            </a>
                                        </li>
                                        <li class="menu-item">
                                            <a class="menu-link" href="https://spse.inaproc.id/sumbarprov/pengumuman" target="_blank">
                                                <div>Pengadaan Barang dan Jasa</div>
                                            </a>
                                        </li>

                                    </ul>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/ppid/lhkpn') ?>">
                                        <div>LHKPN</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/ppid/laporan-keuangan-rsud-pariaman') ?>">
                                        <div>Laporan Keuangan</div>
                                    </a>
                                </li>


                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/peraturan') ?>">
                                        <div>Regulasi KIP</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="https://www.lapor.go.id" target="_blank">
                                        <div>SP4N LAPOR</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/ppid/undang-undang-tentang-rumah-sakit') ?>">
                                        <div>UU Tentang RS</div>
                                    </a>
                                </li>

                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('layanan-lansia-dan-difabel') ?>">
                                        <div>Layanan Lansia & Difabel</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('/pelayanan/maklumat-layanan') ?>">
                                        <div>Maklumat Layanan</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('standar-biaya') ?>">
                                        <div>Standar Biaya</div>
                                    </a>
                                </li>
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('sarana') ?>">
                                        <div>Sarana & Prasarana</div>
                                    </a>
                                </li>
                                <!-- <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('daftar-inovasi') ?>">
                                        <div>Inovasi</div>
                                    </a>
                                </li> -->
                                <li class="menu-item">
                                    <a class="menu-link" href="<?= site_url('form-whistleblowing') ?>">
                                        <div>Whistleblowing</div>
                                    </a>
                                </li>

                            </ul>

                        </li>




                        <?php
                        $currentPath = current_url(true)->getPath();
                        $isGaleriActive = in_array($currentPath, [
                            '/galeri-foto',
                            '/galeri-video',
                        ]);
                        ?>

                        <li class="menu-item <?= $isGaleriActive ? 'current' : '' ?>">
                            <a class="menu-link" href="javascript:void(0);">
                                <div>Galeri</div>
                            </a>
                            <ul class="sub-menu-container">
                                <li class="menu-item <?= $currentPath == '/galeri-foto' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('galeri-foto') ?>">
                                        <div>Galeri Foto</div>
                                    </a>
                                </li>
                                <li class="menu-item <?= $currentPath == '/galeri-video' ? 'current' : '' ?>">
                                    <a class="menu-link" href="<?= site_url('galeri-video') ?>">
                                        <div>Galeri Video</div>
                                    </a>
                                </li>
                            </ul>
                        </li>




                        <li class="menu-item <?= current_url(true)->getPath() == '/dokter-kami' ? 'current' : '' ?>"><a class="menu-link" href="<?= site_url('dokter-kami') ?>">
                                <div>Dokter Kami</div>
                            </a>
                        </li>

                        <li class="menu-item <?= current_url(true)->getPath() == '/kontak' ? 'current' : '' ?>"><a class="menu-link" href="<?= site_url('kontak') ?>">
                                <div>Pengaduan</div>
                            </a>
                        </li>
                        <li class="menu-item <?= current_url(true)->getPath() == '/daftar-inovasi' ? 'current' : '' ?>"><a class="menu-link" href="<?= site_url('daftar-inovasi') ?>">
                                <div>Inovasi</div>
                            </a>
                        </li>


                    </ul>

                </nav><!-- #primary-menu end -->


            </div>
        </div>
    </div>
    <div class="header-wrap-clone"></div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-link');
        let currentSpeech = null;

        // Cari suara yang menggunakan bahasa Indonesia
        let indonesianVoice = null;
        window.speechSynthesis.onvoiceschanged = function() {
            const voices = window.speechSynthesis.getVoices();
            indonesianVoice = voices.find(voice => voice.lang === 'id-ID');
        };

        menuItems.forEach(item => {
            item.addEventListener('mouseover', function() {
                // Hentikan pembacaan sebelumnya jika ada
                if (currentSpeech) {
                    window.speechSynthesis.cancel();
                }
                const text = item.textContent.trim();
                const msg = new SpeechSynthesisUtterance(text);

                // Tentukan bahasa yang digunakan
                msg.lang = 'id-ID';

                // Jika suara Bahasa Indonesia ditemukan, gunakan suara tersebut
                if (indonesianVoice) {
                    msg.voice = indonesianVoice;
                }

                currentSpeech = msg;
                window.speechSynthesis.speak(msg);
            });

            item.addEventListener('mouseleave', function() {
                // Hentikan pembacaan saat mouse keluar dari elemen
                if (currentSpeech) {
                    window.speechSynthesis.cancel();
                    currentSpeech = null;
                }
            });
        });
    });
</script>