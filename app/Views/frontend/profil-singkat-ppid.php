<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Profil Singkat PPID RSUD PROF. H. Muhammad Yamin, S.H</h1>
    </div>

</section>
<!-- #page-title end -->

<section id="content">
    <div class="container clearfix" style="text-align: justify;">
        <p class="mt-2">
            <?= $profilPpid['keterangan_profil_ppid'] ?>
        </p>
        <p class="fw-bold fs-5">Sekretariat PPID RSUD PROF. H. Muhammad Yamin, S.H</p>
        <div class="masonry-thumbs grid-container grid-3 mb-5 " data-big="2" data-lightbox="gallery">
            <a class="grid-item" href="<?= base_url(); ?>/frontend/images/ppid/2.jpg" data-lightbox="gallery-item"><img src="<?= base_url(); ?>/frontend/images/ppid/2.jpg" alt="Gallery Thumb 1"></a>
            <a class="grid-item" href="<?= base_url(); ?>/frontend/images/ppid/1.jpg" data-lightbox="gallery-item"><img src="<?= base_url(); ?>/frontend/images/ppid/1.jpg" alt="Gallery Thumb 3"></a>
            <a class="grid-item" href="<?= base_url(); ?>/frontend/images/ppid/3.jpg" data-lightbox="gallery-item"><img src="<?= base_url(); ?>/frontend/images/ppid/3.jpg" alt="Gallery Thumb 2"></a>
        </div>
    </div>
</section>




<?= $this->endsection() ?>