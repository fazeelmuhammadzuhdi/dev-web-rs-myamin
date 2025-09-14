<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .content-wrap {
        position: relative;
        padding: 50px 0;
        overflow: hidden;
    }
</style>

<!-- Page Title
		============================================= -->
<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Rawat Inap <?= $namaRawat ?></h1>
    </div>

</section>
<!-- #page-title end -->

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <?= $this->include('frontend/include/rawat/rawat-inap',) ?>
            <p><?= $rawatInap['keterangan'] ?></p>
            <!-- <p><?= preg_replace(['/<img[^>]+\>/i', '/<p><\/p>/i', '/<h3><br><\/h3>/i'], '', $rawatInap['keterangan']) ?? '-' ?></p> -->

        </div>
    </div>
</section>


<?= $this->endsection() ?>