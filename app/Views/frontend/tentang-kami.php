<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    @media (max-width: 768px) {
        .konten-tentang {
            padding: 40px;
        }

        .product-desc {
            width: 100%;
        }

        .masonry-thumbs {
            width: 100%;
        }
    }
</style>

<section id="content">
    <div class="content-wrap" style="overflow: visible;">
        <div class="container clearfix">
            <div class="single-product">
                <div class="product">
                    <div class="row gutter-40">
                        <div class="col-md-8 product-desc position-lg-sticky h-100">
                            <div class="heading-block fancy-title border-bottom-0 title-bottom-border">
                                <h3><span class="text-black"><?= $tentangKami['title_tentang'] ?> </span></h3>
                            </div>
                            <div class="konten-tentang">

                                <img src="<?= base_url('profil/' . $profil['gambar']) ?>" alt="<?= $profil['nama'] ?>" title="<?= $profil['nama'] ?>">
                                <p><?= $tentangKami['konten_tentang'] ?></p>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="masonry-thumbs grid-container grid-2 has-init-isotope mb-5" data-lightbox="gallery" style="position: relative; height: 145.312px;">
                                <?php foreach ($slider as $item): ?>
                                    <a class="grid-item" href="<?= base_url('slider/' . $item['gambar']); ?>" data-lightbox="gallery-item" style="position: absolute; left: 50.0001%; top: 0px;"><img src="<?= base_url('slider/' . $item['gambar']); ?>" alt="<?= $item['keterangan'] ?>"></a>
                                <?php endforeach; ?>
                            </div>

                            <!-- include profil -->
                            <?= $this->include('frontend/include/profil') ?>

                            <div class="heading-block fancy-title border-bottom-0 title-bottom-border mt-5 text-black">
                                <h4><span class="text-black">Hasil Polling</span></h4>
                            </div>
                            <!-- include polling -->
                            <?= $this->include('frontend/include/polling') ?>




                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endsection() ?>