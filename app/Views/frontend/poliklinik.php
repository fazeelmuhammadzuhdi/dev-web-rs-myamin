<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<!-- Page Title	============================================= -->
<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Poliklinik</h1>
    </div>

</section>

<!-- Content ============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row align-items-stretch grid-border clearfix">
                <?php if (!empty($poli)) : ?>

                    <?php foreach ($poli as $item) : ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="feature-box fbox-center fbox-plain border-bottom-0 mb-5">
                                <div class="fbox-icon">
                                    <a href="<?= site_url('poliklinik/' . $item['slug']) ?>"><img src="<?= base_url('polikliniks/' . $item['gambar']); ?>" alt="<?= $item['nama'] ?>"></a>
                                </div>
                                <div class="fbox-content">
                                    <div class="product-title">
                                        <h3><a href="<?= site_url('poliklinik/' . $item['slug']) ?>"><?= $item['nama'] ?></a></h3>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php endforeach; ?>
                <?php endif; ?>



            </div>

        </div>
    </div>

    <div class="line"></div>


    </div>
</section><!-- #content end -->


<?= $this->endsection() ?>