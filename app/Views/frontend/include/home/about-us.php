<style>
    .card {
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .badge {
        padding: 6px 10px;
        border-radius: 6px;
    }

    .badge.bg-custom {
        background-color: #1F827C;
        /* background-color: #1693A5; */
    }
</style>

<div class="row clearfix">
    <!-- <div class="col-lg-6">
        <div class="heading-block border-bottom-0 bottommargin-sm" data-aos="fade-right" data-aos-duration="800">
            <a href="<?= site_url('tentang-kami') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen">Tentang Kami</a>
        </div>
        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-duration="800">
            <h3 class="mb-2 fw-semibold">Visi</h3>
            <h2><?= $profil['visi'] ?></h2>
        </div>

        <ul class="iconlist iconlist-large iconlist-color">
            <h3 class="mb-2 fw-semibold">Misi</h3>
            <?php foreach ($parsedMisi as $index => $item) : ?>
                <li data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-duration="800">
                    <i class="icon-ok"></i> <?= nl2br($item) ?>
                </li>
            <?php endforeach; ?>

            <li data-aos="fade-up" data-aos-delay="<?= (count($parsedMisi) * 100) ?>" data-aos-duration="800">
                <i class="icon-ok"></i> <?= $profil['motto'] ?>
            </li>
        </ul>

        <a href="<?= site_url('tentang-kami') ?>" class="button button-3d ms-0 bottommargin-sm button-border button-border button-dirtygreen" data-aos="fade-up">Selengkapnya</a>
    </div> -->


    <!-- <div class="col-lg-6">
        <div class="heading-block border-bottom-0 bottommargin-sm" data-aos="fade-right" data-aos-duration="800">
            <a href="<?= site_url('tentang-kami') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen">Tentang Kami</a>
        </div>

        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-duration="800">
            <div class="d-flex align-items-center">
                <div class="badge bg-custom me-2" style="font-size: 14px;">Visi</div>
            </div>
            <p class="fs-2 fw-semibold"><?= $profil['visi'] ?></p>
        </div>


        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-duration="800">
            <div class="d-flex align-items-center">
                <div class="badge bg-custom me-2" style="font-size: 14px;">Misi</div>
            </div>
            <ul class="iconlist iconlist-large iconlist-color">
                <?php foreach ($parsedMisi as $index => $item) : ?>
                    <li data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-duration="800">
                        <i class="icon-ok text-success me-2"></i> <?= nl2br($item) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>


        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-duration="800">

            <div class="d-flex align-items-center mb-2">
                <div class="badge bg-custom me-2" style="font-size: 14px;">MOTTO Pelayanan</div>
            </div>
            <p class="fs-5"><i class="icon-quote-left me-2 text-muted"></i> <?= $profil['motto'] ?></p>
        </div>

        <a href="<?= site_url('tentang-kami') ?>" class="button button-3d ms-0 bottommargin-sm button-border button-border button-dirtygreen" data-aos="fade-up">Selengkapnya</a>
    </div> -->

    <!-- <div class="col-lg-6">
        <div class="mb-4" data-aos="fade-right">
            <a href="<?= site_url('tentang-kami') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen">Tentang Kami</a>
        </div>

        <div class="card border-0 shadow-sm mb-2" data-aos="fade-up">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge bg-custom me-2" style="font-size: 14px;">VISI</div>
                </div>
                <h2><?= $profil['visi'] ?></h2>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-2" data-aos="fade-up" data-aos-delay="100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge bg-custom me-2" style="font-size: 14px;">MISI</div>
                </div>
                <ul class="iconlist iconlist-large iconlist-color">
                    <?php foreach ($parsedMisi as $index => $item) : ?>
                        <li data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>" data-aos-duration="800">
                            <i class="icon-ok text-custom me-2"></i> <?= nl2br($item) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-2" data-aos="fade-up" data-aos-delay="200">
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div class="badge bg-custom me-2" style="font-size: 14px;">MOTTO Pelayanan Kami</div>
                </div>
                <p class="fs-5"><i class="icon-quote-left me-2 text-muted"></i> <?= $profil['motto'] ?></p>
            </div>
        </div>

        <a href="<?= site_url('tentang-kami') ?>" class="button button-3d mt-2 button-border button-dirtygreen" data-aos="fade-up" data-aos-delay="300">Selengkapnya</a>
    </div> -->


    <div class="col-lg-6">
        <div class="heading-block border-bottom-0 bottommargin-sm" data-aos="fade-right" data-aos-delay="0" data-aos-duration="800">
            <a href="<?= site_url('tentang-kami') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen">Tentang Kami</a>
        </div>

        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-delay="100" data-aos-duration="800">
            <div class="d-flex align-items-center">
                <div class="badge bg-custom me-2" style="font-size: 14px;">Visi</div>
            </div>
            <h3 class="mt-2"><?= $profil['visi'] ?></h3>
        </div>

        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-delay="200" data-aos-duration="800">
            <div class="d-flex align-items-center">
                <div class="badge bg-custom me-2" style="font-size: 14px;">Misi, Tujuan & Sasaran</div>
            </div>
            <ul class="iconlist iconlist-large iconlist-color">
                <?php foreach ($parsedMisi as $index => $item) : ?>
                    <li data-aos="fade-up" data-aos-delay="<?= 300 + ($index * 100) ?>" data-aos-duration="800">
                        <i class="icon-ok text-success me-2"></i> <?= nl2br($item) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="heading-block border-bottom-0" data-aos="fade-right" data-aos-delay="<?= 300 + count($parsedMisi) * 100 ?>" data-aos-duration="800">
            <div class="d-flex align-items-center mb-2">
                <div class="badge bg-custom me-2" style="font-size: 14px;">MOTTO Pelayanan</div>
            </div>
            <p class="fs-5"><i class="icon-quote-left me-2 text-muted"></i> <?= $profil['motto'] ?></p>
        </div>

        <a href="<?= site_url('tentang-kami') ?>" class="button button-3d ms-0 bottommargin-sm button-border button-dirtygreen" data-aos="fade-up" data-aos-delay="<?= 400 + count($parsedMisi) * 100 ?>">Selengkapnya</a>
    </div>


    <div class="col-lg-6">
        <div class="fslider flex-thumb-grid grid-6" data-pagi="false" data-arrows="false" data-thumbs="true">
            <div class="flexslider">
                <div class="slider-wrap">

                    <?php foreach ($slider as $index => $item) : ?>
                        <?php
                        $img_src = base_url('slider/' . $item['gambar']);
                        ?>

                        <?php if ($index === 0): ?>
                            <link rel="preload" as="image" href="<?= $img_src; ?>">
                        <?php endif; ?>

                        <div class="slide" data-thumb="<?= $img_src; ?>">
                            <img loading="lazy" src="<?= $img_src; ?>" alt="<?= htmlentities($item['judul'], ENT_QUOTES, 'UTF-8'); ?>" title="<?= htmlspecialchars($item['judul'], ENT_QUOTES, 'UTF-8'); ?>" style="display: block; width: 100%; height: auto;">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>