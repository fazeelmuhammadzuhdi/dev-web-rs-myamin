<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon ms-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/fungsi.png" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/fungsi.png" alt="Tugas PPID RSUD PROF. H. Muhammad Yamin, S.H" title="Tugas PPID RSUD PROF. H. Muhammad Yamin, S.H" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">Tugas PPID RSUD PROF. H. Muhammad Yamin, S.H</span></h1>
                            <div class="ms-3 text-black">
                                <?= $profilPpid['tugas_input'] ?? '' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon ms-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/tugas.png" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/tugas.png" alt="Fungsi PPID RSUD PROF. H. Muhammad Yamin, S.H" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">Fungsi PPID RSUD PROF. H. Muhammad Yamin, S.H</span></h1>
                            <div class="ms-3 text-black">
                                <?= $profilPpid['fungsi_input'] ?? '' ?>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</section <?= $this->endsection() ?>