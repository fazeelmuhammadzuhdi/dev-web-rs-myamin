<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    p {
        color: black !important;
        font-size: 19px;
    }
</style>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row  g-0">
                <!-- <div class="row"> -->
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl">
                        <div class="fbox-icon me-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">VISI</span></h1>
                            <div class="text-black fs-2"><?= $visiMisi['visi'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon ms-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/misi.png" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">MISI</span></h1>
                            <div class="text-black fs-2"><?= nl2br($visiMisi['misi']) ?></div>
                        </div>
                    </div>
                </div>

                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl">
                        <div class="fbox-icon me-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/motto.png" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">MOTTO</span></h1>
                            <div class="text-black fs-2"><?= $visiMisi['motto'] ?></div>

                        </div>
                    </div>
                </div>


                <div class="line my-5"></div>
                <div class="col-12">
                    <div class="feature-box fbox-effect fbox-xl flex-row-reverse">
                        <div class="fbox-icon ms-3">
                            <a href="<?= base_url(); ?>/frontend/demos/seo/images/icons/experience.svg" target="_blank"><img src="<?= base_url(); ?>/frontend/demos/seo/images/icons/tugas.png" alt="Feature Icon" class="bg-transparent rounded-0"></a>
                        </div>
                        <div class="fbox-content">
                            <h1><span class="text-black">Tugas Dan Fungsi</span></h1>
                            <div class="text-black fs-3"><?= nl2br($visiMisi['tugas']) ?></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section
<?= $this->endsection() ?>