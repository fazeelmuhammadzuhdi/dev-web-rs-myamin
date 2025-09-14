<!-- Looping semua spesialis -->
<?php if (!empty($spesialis)) : ?>
    <?php foreach ($spesialis as $idSpesialis => $infoSpesialis) : ?>
        <!-- Section untuk setiap spesialis -->
        <h3><?= $infoSpesialis['nama'] ?></h3>

        <div class="container clearfix">
            <div id="section-wheel" class="page-section">
                <?php if (!empty($infoSpesialis['dokter'])) : ?>
                    <div id="oc-wheel" class="owl-carousel shop-carousel carousel-widget" data-margin="30" data-nav="true" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="3" data-autoplay="4000">
                        <!-- Looping dokter berdasarkan spesialis -->
                        <?php foreach ($infoSpesialis['dokter'] as $dokter) : ?>
                            <div class="product center">
                                <div class="product-image px-4 py-1">
                                    <a href="<?= base_url('dokter/' . $dokter['gambar']) ?>" target="_blank"><img src="<?= base_url('dokter/' . $dokter['gambar']) ?>" alt="Image"></a>
                                </div>

                                <div class="product-desc">
                                    <div class="product-title">
                                        <h3><?= $dokter['nama'] ?></h3>
                                    </div>
                                    <div class="product-price"><ins><?= $dokter['nip'] ?></ins></div>
                                    <p><?= $infoSpesialis['nama'] ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="hr-xs">
                <?php else : ?>
                    <p>Dokter belum tersedia untuk spesialis ini.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>