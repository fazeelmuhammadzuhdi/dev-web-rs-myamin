<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .content-wrap {
        position: relative;
        padding: 50px 0;
        overflow: hidden;
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1><?= $namaPenunjang ?></h1>

    </div>

</section>



<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">



            <?= $this->include('frontend/include/penunjang/penunjang') ?>





            <p><?= $penunjang['keterangan'] ?? '-' ?></p>
        </div>
    </div>
</section>


<?= $this->endsection() ?>