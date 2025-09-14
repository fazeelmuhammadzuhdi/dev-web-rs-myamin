<?= $this->extend('frontend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('frontend/include/home/style') ?>




<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-12">

                    <!-- about us -->
                    <?= $this->include('frontend/include/home/about-us') ?>

                    <!-- service -->
                    <?= $this->include('frontend/include/home/service') ?>


                    <!-- article -->
                    <?= $this->include('frontend/include/home/berita-terkini-1') ?>

                    <!-- jadwal Poliklinik -->
                    <?= $this->include('frontend/include/home/jadwal-poli-1') ?>

                    <!-- gallery -->
                    <?= $this->include('frontend/include/home/gallery') ?>

                    <!-- vidio -->
                    <?= $this->include('frontend/include/home/vidio') ?>

                </div>


                <div class="row">
                    <div class="col-lg-8">
                        <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
                            <button class="button button-xlarge button-circle  button-3d button-dirtygreen">Penghargaan</button>

                        </div>


                        <?= $this->include('frontend/include/home/penghargaan-1') ?>


                    </div>

                    <div class="col-lg-4 mt-5 mt-lg-0">
                        <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
                            <button class="button button-xlarge button-circle  button-3d button-dirtygreen">Hasil Polling</button>

                        </div>

                        <?= $this->include('frontend/include/polling') ?>

                    </div>
                </div>

                <!-- partners -->
                <?= $this->include('frontend/include/home/partners-2') ?>

                <?= $this->include('frontend/include/home/pesan-1') ?>


            </div>
        </div>
    </div>
</section>

<?= $this->include('frontend/include/home/script') ?>


<?= $this->endsection() ?>