<div class="container">
    <div class="row col-mb-30 mt-5 align-content-stretch">
        <div class="container py-4">
            <div class="heading-block border-bottom-0 center mx-auto mb-0" style="max-width: 550px">
                <a href="<?= site_url('blog') ?>" class="button button-xlarge button-circle  button-3d button-dirtygreen">BERITA TERBARU</a>

            </div>

        </div>

        <?php foreach ($berita as $item) : ?>

            <div class="col-lg-4 col-md-6 d-flex">
                <div class="card-body p-4 card h-shadow h-translate-y-sm all-ts">
                    <div class="feature-box flex-column">
                        <div class="fbox-image mb-3 text-center imagescalein">
                            <img src="<?= base_url('berita/' . $item['gambar']); ?>" alt="<?= ucwords($item['judul'])  ?>">
                        </div>
                        <div class="fbox-content entry-title mb-2">
                            <h4 class="nott ls0 text-larger ">
                                <a href="<?= site_url('blog/' . $item['slug']) ?>"><?= $item['judul']  ?></a>
                            </h4>
                        </div>

                        <ul class="iconlist ms-3 mt-1 mb-0">
                            <li class="mb-2 text-muted"><i class="icon-line2-user text-smaller color"></i> Website
                                Administrator</li>
                            <li class="mb-2 text-muted"><i class="icon-calendar-times1 text-smaller color"></i>
                                <?= tanggal_indonesia($item['tanggal']) ?></li>
                            <li class="mb-2 text-muted"><i class="icon-eye text-smaller color"></i>
                                <?= $item['viewberita'] ?></li>
                            <a href="<?= site_url('blog/' . $item['slug']) ?>" class="btn btn-danger mt-3">Read More</a>
                        </ul>


                    </div>

                </div>

            </div>
        <?php endforeach; ?>

        <div class="text-end">
            <a href="<?= site_url('blog') ?>" class="button button-large button-circle  button-3d button-dirtygreen button-border">View All
                Berita</a>
        </div>


    </div>
</div>