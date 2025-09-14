 <div class="leftbar">
     <!-- Start Sidebar -->
     <div class="sidebar">
         <!-- Start Logobar -->
         <div class="logobar">
             <a href="<?= base_url('/home') ?>" class="logo logo-large"><img src="<?= base_url(); ?>/assets/images/rsud.png" class="img-fluid" alt="logo" /></a>
             <a href="<?= base_url('/home') ?>" class="logo logo-small"><img src="<?= base_url(); ?>/assets/images/rsud.png" class="img-fluid" alt="logo" /></a>
         </div>
         <!-- End Logobar -->
         <!-- Start Profilebar -->
         <div class="profilebar text-center">
             <img src="<?= base_url(); ?>/assets/images/users/profile.svg" class="img-fluid" alt="profile" />
             <div class="profilename">
                 <h5> <?= ucwords(session('usernameUser'))  ?></h5>
                 <p><?= session('namaUser') ?></p>
             </div>
             <div class="userbox">
                 <ul class="list-inline mb-0">
                     <li class="list-inline-item">
                         <a href="<?= site_url('akun') ?>" class="profile-icon"><img src="<?= base_url(); ?>/assets/images/svg-icon/user.svg" class="img-fluid" title="Akun" alt="user" /></a>
                     </li>

                     <li class="list-inline-item">
                         <a href="<?= base_url('logout') ?>" class="profile-icon"><img src="<?= base_url(); ?>/assets/images/svg-icon/logout.svg" class="img-fluid" title="Logout" alt="logout" /></a>
                     </li>
                 </ul>
             </div>
         </div>
         <!-- End Profilebar -->
         <!-- Start Navigationbar -->
         <div class="navigationbar">
             <ul class="vertical-menu">
                 <li class="vertical-header">Main</li>
                 <li>
                     <a href="<?= base_url('/home') ?>">
                         <img src="<?= base_url(); ?>/assets/images/svg-icon/chart.svg" class="img-fluid" alt="dashboard" /><span>Dashboard</span>
                     </a>

                 </li>
                 <?php if (session()->get('roleUser') == 'SU') { ?>
                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/advanced.svg" class="img-fluid" alt="layouts"><span>Profil</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li><a href="<?= site_url('tentangs') ?>"><i class="mdi mdi-circle"></i>Tentang</a></li>
                             <li><a href="<?= site_url('sejarahs') ?>"><i class="mdi mdi-circle"></i>Sejarah</a></li>
                             <li><a href="<?= site_url('profils') ?>"><i class="mdi mdi-circle"></i>Profil RSUD</a></li>
                             <li><a href="<?= site_url('manajemen') ?>"><i class="mdi mdi-circle"></i>Manajemen</a></li>
                             <li><a href="<?= site_url('manajemenprofils') ?>"><i class="mdi mdi-circle"></i>Profil Manajemen</a></li>
                             <li><a href="<?= site_url('indikatormutu') ?>"><i class="mdi mdi-circle"></i>Indikator Mutu</a></li>
                             <li><a href="<?= site_url('indikatormutulists') ?>"><i class="mdi mdi-circle"></i>Indikator Mutu List</a></li>


                         </ul>

                     </li>

                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/layouts.svg" class="img-fluid" alt="layouts"><span>Fasilitas &amp; Layanan</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li><a href="<?= site_url('poly') ?>"><i class=" mdi mdi-circle"></i>Poliklinik</a></li>
                             <li><a href="<?= site_url('rawat') ?>"><i class=" mdi mdi-circle"></i>Rawatan</a></li>
                             <li><a href="<?= site_url('penunjang') ?>"><i class=" mdi mdi-circle"></i>Penunjang</a></li>
                             <li><a href="<?= site_url('fasilitasumum') ?>"><i class=" mdi mdi-circle"></i>Fasilitas Umum</a></li>
                             <li><a href="<?= site_url('spesialis') ?>"><i class=" mdi mdi-circle"></i>Spesialis</a></li>
                             <li><a href="<?= site_url('dokters') ?>"><i class=" mdi mdi-circle"></i>Dokter</a></li>
                             <li><a href="<?= site_url('tempattidur') ?>"><i class=" mdi mdi-circle"></i>Tempat Tidur</a></li>
                             <li><a href="<?= site_url('jadwalpoli') ?>"><i class=" mdi mdi-circle"></i>Jadwal Layanan Poli</a></li>
                             <li><a href="<?= site_url('referensis') ?>"><i class=" mdi mdi-circle"></i>Pojok Referensi</a></li>
                         </ul>

                     </li>


                     <li>
                         <a href="<?= site_url('kategoris') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/basic.svg" class="img-fluid" alt="layouts"><span>Kategori</span>
                         </a>
                     </li>

                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/close.svg" class="img-fluid" alt="layouts"><span>Post</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li><a href="<?= site_url('beritas') ?>"><i class=" mdi mdi-circle"></i>Berita</a></li>
                         </ul>

                     </li>

                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/pages.svg" class="img-fluid" alt="layouts"><span>Gallery</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li><a href="<?= site_url('album') ?>"><i class="mdi mdi-circle"></i>Album</a></li>
                             <li><a href="<?= site_url('albumlists') ?>"><i class=" mdi mdi-circle"></i>Galeri Foto</a></li>
                             <li><a href="<?= site_url('videos') ?>"><i class=" mdi mdi-circle"></i>Galeri Video</a></li>
                         </ul>

                     </li>

                     <li>
                         <a href="<?= site_url('pollings') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/components.svg" class="img-fluid" alt="layouts"><span>Polling</span>
                         </a>
                     </li>
                     <li>
                         <a href="<?= site_url('faqs') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/components.svg" class="img-fluid" alt="layouts"><span>Pertanyaan</span>
                         </a>
                     </li>

                     <li>
                         <a href="<?= site_url('sliders') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/horizontal.svg" class="img-fluid" alt="layouts"><span>Slider</span>
                         </a>
                     </li>
                     <li>
                         <a href="<?= site_url('banners') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/widgets.svg" class="img-fluid" alt="layouts"><span>Banner</span>
                         </a>
                     </li>
                     <li>

                         <a href="<?= site_url('users') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/user.svg" class="img-fluid" alt="layouts"><span>User</span>
                         </a>
                     </li>

                     <li>

                         <a href="<?= site_url('pesans') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/email.svg" class="img-fluid" alt="layouts"><span>Pesan</span>
                         </a>
                     </li>
                     <li>

                         <a href="<?= site_url('bunga-seroja') ?>">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/email.svg" class="img-fluid" alt="layouts"><span>Bunga Seroja</span>
                         </a>
                     </li>

                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/apps.svg" class="img-fluid" alt="apps" /><span>PPID</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li>
                                 <a href="<?= site_url('profilppid') ?>"><i class="mdi mdi-circle"></i>Profil PPID</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('pesanppid') ?>"><i class="mdi mdi-circle"></i>Permintaan Informasi PPID Online</a>
                             </li>

                             <li>
                                 <a href="<?= site_url('keberataninformasi') ?>"><i class="mdi mdi-circle"></i>Permintaan Keberatan Informasi PPID Online</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('page') ?>"><i class="mdi mdi-circle"></i>Pages</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('kategoris-ppid') ?>"><i class="mdi mdi-circle"></i>Kategori Informasi</a>
                             </li>

                             <li>
                                 <a href="<?= site_url('beritappid/ppid') ?>"><i class="mdi mdi-circle"></i>DIP</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('beritappid/pkrs') ?>"><i class="mdi mdi-circle"></i>Informasi PKRS</a>
                             </li>


                             <li>
                                 <a href="<?= site_url('apiberitappid') ?>"><i class="mdi mdi-circle"></i>DIP PPID API</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('inovasis') ?>"><i class="mdi mdi-circle"></i>Inovasi</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('laporwbs') ?>"><i class="mdi mdi-circle"></i>Whistleblowing</a>
                             </li>
                         </ul>
                     </li>
                 <?php } else { ?>
                     <li>
                         <a href="javaScript:void();">
                             <img src="<?= base_url(); ?>/assets/images/svg-icon/apps.svg" class="img-fluid" alt="apps" /><span>PPID</span><i class="feather icon-chevron-right pull-right"></i>
                         </a>
                         <ul class="vertical-submenu">
                             <li>
                                 <a href="<?= site_url('profilppid') ?>"><i class="mdi mdi-circle"></i>Profil PPID</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('pesanppid') ?>"><i class="mdi mdi-circle"></i>Permintaan Informasi PPID Online</a>
                             </li>

                             <li>
                                 <a href="<?= site_url('keberataninformasi') ?>"><i class="mdi mdi-circle"></i>Permintaan Keberatan Informasi PPID Online</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('page') ?>"><i class="mdi mdi-circle"></i>Pages</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('kategoris-ppid') ?>"><i class="mdi mdi-circle"></i>Kategori Informasi</a>
                             </li>

                             <li>
                                 <a href="<?= site_url('beritappid') ?>"><i class="mdi mdi-circle"></i>Post PPID</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('apiberitappid') ?>"><i class="mdi mdi-circle"></i>Post PPID API</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('laporwbs') ?>"><i class="mdi mdi-circle"></i>Whistleblowing</a>
                             </li>
                             <li>
                                 <a href="<?= site_url('inovasis') ?>"><i class="mdi mdi-circle"></i>Inovasi</a>
                             </li>
                         </ul>
                     </li>

                 <?php } ?>







             </ul>
         </div>
         <!-- End Navigationbar -->
     </div>
     <!-- End Sidebar -->
 </div>