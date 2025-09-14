<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah Pariaman, Sumatera Barat">
    <meta name="keywords" content="RSUD PROF. H. Muhammad Yamin, S.H, Rumah Sakit Umum Daerah Pariaman, Sumatera Barat, Kesehatan, PPID Pariaman, Dokter Pariaman, Rumah Sakit Pariaman, Klinik Pariaman, Medical Check Up, Vaksinasi, Laboratorium Kesehatan, Radiologi, Poliklinik, IGD Pariaman, UGD Pariaman, Instalasi Gawat Darurat, Medical Service, Health Care, Hospital Pariaman, Pariaman Health, Dokter Kami, Galeri Foto, Galeri Video, Fasilitas Umum, Mushalla, Pojok Referensi, Rawat Inap, Visi Misi, Struktur Organisasi, Profil Manajemen, Berita, Agenda, Promosi Kesehatan, Informasi Asuransi, PPID Pelaksana, Informasi Publi">
    <title>RSUD PROF. H. Muhammad Yamin, S.H - <?= isset($title) ? esc($title) : 'RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah Pariaman, Sumatera Barat ' ?></title>
    <meta name="author" content="Themesbox17" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <link rel="canonical" href="https://rsudmyamin.sumbarprov.go.id">
    <title>RSUD PROF. H. Muhammad Yamin, S.H - <?= isset($title) ? esc($title) : 'Home' ?></title>


    <!-- Fevicon -->
    <!-- style css -->
    <?= $this->include('include/style') ?>
    <!-- End css -->

    <!-- <script>
        const adminTabKey = 'admin-tab-opened';

        // Periksa apakah tab lain sudah membuka menu admin
        if (localStorage.getItem(adminTabKey)) {
            alert('Menu admin sudah terbuka di tab lain.');
            window.close(); // Redirect ke halaman utama atau halaman lain
        } else {
            // Set status bahwa menu admin sedang terbuka
            localStorage.setItem(adminTabKey, true);
        }

        // Hapus status ketika tab atau window ditutup
        window.addEventListener('beforeunload', function() {
            localStorage.removeItem(adminTabKey);
        });
    </script> -->

</head>

<body class="vertical-layout">
    <!-- Start Infobar Notifications Sidebar -->
    <?= $this->include('include/infobar') ?>
    <!-- End Infobar Setting Sidebar -->

    <!-- Start Containerbar -->
    <div id="containerbar">
        <!-- Start Leftbar -->
        <!-- menu -->
        <?= $this->include('include/menu') ?>

        <!-- End Leftbar -->
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Topbar Mobile -->
            <?= $this->include('include/header') ?>
            <!-- End Topbar -->

            <!-- Content -->
            <?= $this->renderSection('content') ?>
            <!-- End Content -->


            <!-- Start Footerbar -->
            <?= $this->include('include/footer') ?>

            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    <!-- Start js -->
    <!-- script -->
    <?= $this->include('include/script') ?>
    <!-- End js -->
</body>

</html>