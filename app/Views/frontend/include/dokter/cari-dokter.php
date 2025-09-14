<div class="container clearfix">

    <div id="section-wheel" class="page-section">

        <?php if (!empty($dokterResults)) : ?>
            <div id="oc-wheel" class="owl-carousel shop-carousel carousel-widget" data-margin="30" data-nav="true" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="3" data-autoplay="4000">

                <?php foreach ($dokterResults as $item) : ?>
                    <div class="product center">
                        <div class="product-image px-4 py-1">
                            <a href="<?= base_url('dokter/' . $item['gambar']) ?>" target="_blank"><img src="<?= base_url('dokter/' . $item['gambar']) ?>" alt="Dokter <?= $item['nama'] . "Spesialis" . $item['nama_spesialis'] ?>"></a>
                        </div>

                        <div class="product-desc">
                            <div class="product-title">
                                <h3><?= $item['nama'] ?></h3>
                            </div>
                            <div class="product-price"><ins><?= $item['nip'] ?></ins></div>
                            <p><?= $item['nama_spesialis'] ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
            <hr class="hr-xs">
        <?php endif; ?>

    </div>

</div>