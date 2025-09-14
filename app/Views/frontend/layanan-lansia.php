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
        <h1>PPID &raquo; Layanan Lansia & Difabel</h1>
    </div>


</section>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-9">
                    <div id="posts" class="post-grid row grid-container gutter-30" data-layout="fitRows">

                        <div class="entry col-sm-12 col-12">
                            <?= $profilPpid['layanan_lansia_difabel'] ?? '-' ?>
                        </div>
                    </div>
                </div>

                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section>

<?= $this->endsection() ?>