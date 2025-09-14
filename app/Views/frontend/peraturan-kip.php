<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .content-wrap {
        position: relative;
        padding: 40px 0;
        overflow: hidden;
    }
</style>

<!-- Page Title
		============================================= -->
<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Peraturan Keterbukaan Informasi Publik</h1>
    </div>

</section>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-9">
                    <?= $profilPpid['regulasi_kip'] ?>
                </div>

                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>






        </div>
    </div>
</section>




<?= $this->endsection() ?>