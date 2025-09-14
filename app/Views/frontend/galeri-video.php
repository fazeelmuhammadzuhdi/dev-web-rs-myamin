<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Galeri Video</h1>
    </div>


</section>

<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap pb-0">

        <div class="container clearfix">
            <div id="posts" class="post-grid row grid-container gutter-30 overflow-visible" data-layout="fitRows">






                <?php if (!empty($galeriVideo)) : ?>

                    <?php foreach ($galeriVideo as $item) : ?>
                        <div class="entry col-md-4 col-sm-6 col-12">
                            <div class="grid-inner">
                                <div class="entry-image">
                                    <iframe width="560" height="315" src="<?= $item['link'] ?>" allowfullscreen></iframe>
                                </div>
                                <div class="entry-title">
                                    <h2><a href="<?= $item['link'] ?>" target="_blank"><?= $item['judul'] ?></a></h2>
                                </div>
                                <div class="entry-meta">
                                    <ul>
                                        <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($item['tanggal'])  ?></li>
                                        <li><a href="blog-single-full.html#comments"><i class="icon-user"></i> Admin</a></li>
                                    </ul>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
            <!-- #posts end -->

            <ul class="pagination mt-5 pagination-circle justify-content-center">
                <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
            </ul>


        </div>

    </div>
</section><!-- #content end -->
<?= $this->endsection() ?>