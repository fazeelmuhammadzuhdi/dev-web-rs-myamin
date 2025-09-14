<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<section id="content">
    <div class="content-wrap pb-0">

        <div class="container clearfix">

            <div class="row justify-content-center text-center mb-5">
                <div class="entry-title">
                    <h2><?= $galeriFotoDetail[0]['judul_album'] ?></h2>
                </div><!-- .entry-title end -->
            </div>


            <div id="posts" class="post-grid row grid-container gutter-30 overflow-visible" data-layout="fitRows" data-lightbox="gallery">

                <?php if (!empty($galeriFotoDetail)) : ?>

                    <?php foreach ($galeriFotoDetail as $item) : ?>
                        <div class="entry col-md-4 col-sm-6 col-12">
                            <div class="grid-inner h-translate-y-sm all-ts">
                                <div class="entry-image team-image imagescalein" data-animate="fadeIn">
                                    <a href="<?= base_url('albumlist/' . $item['gambar']) ?>" data-lightbox="gallery-item"><img src="<?= base_url('albumlist/' . $item['gambar']) ?>" alt="<?= $item['list_keterangan'] ?>"></a>
                                </div>
                                <div class="entry-title title-sm">
                                    <h3 class="nott mb-4 ls0"><a href="<?= site_url('galeri-foto/' . $item['slug']) ?>"><?= $item['list_keterangan'] ?></a></h3>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>




        </div>

    </div>
</section><!-- #content end -->
<?= $this->endsection() ?>