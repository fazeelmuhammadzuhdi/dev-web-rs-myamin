<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    /* class active */
    .active {
        color: red;
    }

    a {
        text-decoration: none !important;
        color: #000;
    }

    a:hover {
        color: #1abc9c;
        text-decoration: underline;
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>SOP &raquo; PPID</h1>
    </div>


</section>

<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">


                <!-- Post Content
						============================================= -->


                <div class="postcontent col-lg-9">

                    <div class="row clearfix">

                        <div class="col-lg-4 center bottommargin">

                            <img class="i-plain i-large inline-block" src="<?= base_url(); ?>/frontend/images/ppid/sop-pelayanan.png" alt="" style="margin-bottom: 15px; width: 90px !important; height: 90px !important;">

                            <div class="heading-block border-bottom-0">
                                <a href="<?= site_url('/ppid/sop-penunjang') ?>">SOP PENUNJANG</a>

                            </div>

                        </div>
                        <div class="col-lg-4 center bottommargin">

                            <img class="i-plain i-large inline-block" src="<?= base_url(); ?>/frontend/images/ppid/sop-pelayanan.png" alt="" style="margin-bottom: 15px; width: 90px !important; height: 90px !important;">

                            <div class="heading-block border-bottom-0">
                                <a href="<?= site_url('/ppid/sop-pelayanan') ?>">SOP PELAYANAN</a>

                            </div>

                        </div>
                        <!-- <div class="col-lg-4 center bottommargin">

                            <img class="i-plain i-large inline-block" src="<?= base_url(); ?>/frontend/images/ppid/sop-pelayanan.png" alt="" style="margin-bottom: 15px; width: 90px !important; height: 90px !important;">

                            <div class="heading-block border-bottom-0">
                                <a href="https://ppid.sumbarprov.go.id/images/2023/09/file/Alur_Pengaduan1.pdf" target="_blank">SOP PENGADUAN MASYARAKAT</a>

                            </div>

                        </div> -->
                    </div>
                </div>





                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section><!-- #content end -->


<?= $this->endsection() ?>