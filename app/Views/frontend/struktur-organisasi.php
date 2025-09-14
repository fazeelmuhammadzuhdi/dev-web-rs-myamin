<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Strukur Organisasi RSUD PROF. H. Muhammad Yamin, S.H</h1>

    </div>

</section>

<section id="content">


    <div class="content-wrap">
        <div class="container clearfix">
            <div class="single-post mb-0">


                <div class="entry-image bottommargin">
                    <?= $tentangKami['konten_sejarah'] ?>
                </div>

            </div>

        </div>
    </div>
</section><!-- #content end -->


<?= $this->endsection() ?>