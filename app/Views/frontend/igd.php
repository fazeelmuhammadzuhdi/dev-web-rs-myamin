<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<!-- Page Title
		============================================= -->
<!-- Page Title
		============================================= -->
<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>INSTALASI GAWAT DARURAT (IGD)</h1>
    </div>

</section>
<!-- #page-title end -->

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <?= $igd['keterangan'] ?>


        </div>
    </div>
</section>


<?= $this->endsection() ?>