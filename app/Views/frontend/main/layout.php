<!DOCTYPE html>
<html dir="ltr" lang="en-US">


<head>
    <meta name="google-site-verification" content="IxS7-w6bbVaOnsrA-dgHsaZm7ysXYCuVTr3hxnK029g" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb">

    <meta name="description" content="RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat yang menyediakan berbagai layanan kesehatan berkualitas dan terpercaya.">
    <meta name="keywords" content="RSUD PROF. H. Muhammad Yamin, S.H, Rumah Sakit Umum Daerah, Sumatera Barat, Kesehatan, PPID RSUD Prof. H. Muhammad Yamin, S.H, Dokter RSUD Prof. H. Muhammad Yamin, Rumah Sakit Prof. H. Muhammad Yamin Pariaman, Klinik Di Pariaman, Medical Check Up, Vaksinasi, Laboratorium Kesehatan, Radiologi, Poliklinik, IGD RSUD Prof. H. Muhammad Yamin, UGD RSUD Prof. H. Muhammad Yamin, Instalasi Gawat Darurat Prof. H. Muhammad Yamin, Medical Service, Health Care, Hospital RSUD Prof. H. Muhammad Yamin, RSUD Prof. H. Muhammad Yamin Health, Dokter Prof. H. Muhammad Yamin, Galeri Foto, Galeri Video, Fasilitas Umum, Mushalla, Pojok Referensi, Rawat Inap, Visi Misi, Struktur Organisasi, Profil Manajemen, Berita, Agenda, Promosi Kesehatan, Informasi Asuransi, PPID Pelaksana, Informasi Publik">
    <title>RSUD PROF. H. Muhammad Yamin, S.H - <?= isset($title) ? esc($title) : 'RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah, Sumatera Barat ' ?></title>
    <meta name="robots" content="index, follow">

    <link rel="canonical" href="https://rsudmyamin.sumbarprov.go.id">
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <link rel="shortcut icon" href="<?= base_url(); ?>logo.png" />
    <link rel="icon" href="<?= base_url(); ?>logo.png?v=2">

    <?= $this->include('frontend/include/style') ?>
    <?= $this->renderSection('extra-css') ?>

    <!-- Section untuk CSS tambahan -->
    <!-- <script src="https://cdn.userway.org/widget.js" data-account="DJOJxuhN9F"></script> -->



</head>

<style>
    /* Floating Button Style */
    .help-button {
        position: fixed;
        bottom: 20px;
        left: 20px;
        background-color: #188079;
        color: white;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease-in-out;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .help-button:hover {
        background-color: #145c57;
        transform: scale(1.05);
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
    }

    .help-button:active {
        transform: scale(0.95);
    }

    /* Optional: Add an icon */
    .help-button::after {
        content: "❓";
        font-size: 16px;
    }
</style>

<?php
$faq = (new \App\Models\FAQ())->findAll(10);
?>

<body class="stretched">

    <!-- Document Wrapper
	============================================= -->
    <div id="wrapper" class="clearfix">

        <!-- Header / Menu Navigation Bar -->
        <?= $this->include('frontend/include/header') ?>

        <!-- end Menu Header -->

        <!-- content -->
        <?= $this->renderSection('content') ?>
        <!-- end Content -->



        <!-- Footer -->
        <?= $this->include('frontend/include/footer') ?>
        <!-- end Footer -->

    </div><!-- #wrapper end -->

    <!-- Large modal -->
    <button id="helpButton" class="help-button" style="display: none;" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg">Butuh Bantuan</button>

    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">FAQ (Frequently Ask Question)</h4>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <?php foreach ($faq as $item): ?>
                        <div class="toggle faq pb-3 mb-3 faq-marketplace faq-authors">
                            <div class="toggle-header">
                                <div class="toggle-icon">
                                    <i class="toggle-closed icon-question-sign"></i>
                                    <i class="toggle-open icon-question-sign"></i>
                                </div>
                                <div class="toggle-title ps-1">
                                    <?= $item['pertanyaan'] ?>
                                </div>
                                <div class="toggle-icon">
                                    <i class="toggle-closed icon-line-chevron-down"></i>
                                    <i class="toggle-open icon-line-chevron-up"></i>
                                </div>
                            </div>
                            <div class="toggle-content ps-4">
                                <?= $item['jawaban'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Go To Top
	============================================= -->
    <div id="gotoTop" class="icon-angle-up"></div>



    <!-- script -->
    <?= $this->include('frontend/include/script') ?>
    <!-- end script -->

    <?= $this->renderSection('extra-script') ?>


    <script>
        // Show the help button when the user scrolls down 100px from the top
        window.onscroll = function() {
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                document.getElementById("helpButton").style.display = "block";
            } else {
                document.getElementById("helpButton").style.display = "none";
            }
        };
    </script>


</body>

</html>