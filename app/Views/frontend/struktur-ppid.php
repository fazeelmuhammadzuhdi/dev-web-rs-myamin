<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    /* class active */
    .active {
        color: red;
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Struktur Organisasi PPID</h1>
    
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

                    <!-- Posts
							============================================= -->
                    <div id="posts" class="post-grid row grid-container gutter-30" data-layout="fitRows">

                        <div class="entry col-sm-12 col-12">
                            <div class="grid-inner">
                                <div class="entry-image">
                                    <a href="<?= base_url(); ?>/frontend/images/ppid/struktur-ppid.jpg" data-lightbox="image"><img src="<?= base_url(); ?>/frontend/images/ppid/struktur-ppid.jpg" alt="Standard Post with Image"></a>
                                </div>

                            </div>
                        </div>

                    </div><!-- #posts end -->


                </div>
                <!-- .postcontent end -->





                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section><!-- #content end -->


<?= $this->endsection() ?>