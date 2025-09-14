<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row d-flex justify-content-between align-items-center">
                <!-- <div class="row"> -->

                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon me-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">Visi PPID RSUD PROF. H. Muhammad Yamin, S.H</span></h1>
                            <p class="ms-3">
                                <?= $profilPpid['visi_input'] ?? '' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon ms-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/misi.png" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/misi.png" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">Misi PPID RSUD PROF. H. Muhammad Yamin, S.H</span></h1>
                            <div class="ms-3">
                                <?= $profilPpid['misi_input'] ?? '' ?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section <?= $this->endsection() ?>