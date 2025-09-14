<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Galeri Foto</h1>
    </div>


</section>
<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap pb-0">

        <div class="container clearfix">
            <div id="posts" class="post-grid row grid-container gutter-30 overflow-visible" data-layout="fitRows">

                <?php if (!empty($galeriFoto)) : ?>

                    <?php foreach ($galeriFoto as $item) : ?>
                        <div class="entry col-md-4 col-sm-6 col-12">
                            <div class="grid-inner h-translate-y-sm all-ts">
                                <div class="entry-image team-image imagescalein" data-animate="fadeIn">
                                    <a href="<?= base_url('albumlist/' . $item['gambar']) ?>" data-lightbox="image"><img src="<?= base_url('albumlist/' . $item['gambar']) ?>" alt="<?= $item['judul_album'] ?>" class="card-img-top img-fluid" style="height: 300px; object-fit: cover; object-position: center;"></a>
                                </div>
                                <div class="entry-title title-sm">
                                    <h3 class="nott mb-4 ls0"><a href="<?= site_url('galeri-foto/' . $item['slug']) ?>"><?= $item['judul_album'] ?></a></h3>
                                </div>
                                <div class="entry-meta">
                                    <ul>
                                        <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($item['tanggal']) ?></li>
                                        <li><a href="<?= site_url('galeri-foto/' . $item['slug']) ?>"><i class="icon-camera-retro"></i>Galeri Foto</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div><!-- #posts end -->

            <ul class="pagination mt-5 pagination-circle justify-content-center">
                <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
            </ul>


        </div>

    </div>
</section><!-- #content end -->
<?= $this->endsection() ?>