<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    @media (max-width: 576px) {
        .fbox-effect.fbox-xl.flex-row-reverse {
            flex-direction: column-reverse;
            text-align: center;
        }

        .fbox-icon {
            margin: 0 auto 15px;
        }

        .fbox-content {
            text-align: center;
        }
    }
</style>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row d-flex justify-content-between align-items-center">
                <!-- <div class="row"> -->

                <div class="col-12">
                    <div class="border rounded p-4 mb-4 h-shadow all-ts bg-white h-translatey-sm">
                        <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                            <div class="fbox-icon me-3">
                                <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" alt="Maklumat Pelayanan PPID RSUD PROF. H. Muhammad Yamin, S.H" title="Maklumat Pelayanan PPID RSUD PROF. H. Muhammad Yamin, S.H" class="bg-transparent rounded-0"></a>
                            </div>
                            <div class="fbox-content">
                                <h1><span class="text-black">Maklumat Pelayanan PPID RSUD PROF. H. Muhammad Yamin, S.H</span></h1>
                                <div class="text-black">
                                    <?= $profilPpid['maklumat_input'] ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="border rounded p-4 mb-4 h-shadow all-ts bg-white h-translatey-sm">
                        <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                            <div class="fbox-icon ms-3">
                                <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/misi.png" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/misi.png" alt="Hakekat Pelayanan Informasi Publik" title="Hakekat Pelayanan Informasi Publik" class="bg-transparent rounded-0"></a>
                            </div>
                            <div class="fbox-content">
                                <h1><span class="text-black">HAKEKAT PELAYANAN INFORMASI PUBLIK</span></h1>
                                <div class="text-black">
                                    <?= $profilPpid['hakekat_input'] ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>


                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="border rounded p-4 mb-4 h-shadow all-ts bg-white h-translatey-sm">
                        <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                            <div class="fbox-icon ms-3">
                                <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/fungsi.png"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/fungsi.png" alt="Asas Pelayana Informasi Publik" title="Asas Pelayana Informasi Publik" class="bg-transparent rounded-0"></a>
                            </div>
                            <div class="fbox-content">
                                <h1><span class="text-black">ASAS PELAYANAN INFORMASI PUBLIK</span></h1>
                                <div class="ms-3 text-black">
                                    <?= $profilPpid['asas_input'] ?? '' ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


            </div>

        </div>
    </div>
</section <?= $this->endsection() ?>