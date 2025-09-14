<style>
    .posts-sm .entry-image {
        width: 74px;
    }

    /* perangkat mobile */
    @media (max-width: 575.98px) {
        .view-all-berita {
            text-align: center !important;
        }
    }
</style>

<div class="container">
    <div class="row col-mb-30 mt-5 align-content-stretch">
        <div class="container py-4" data-aos="fade-up">
            <div class="heading-block border-bottom-0 center mx-auto mb-0" style="max-width: 550px;">
                <a href="<?= site_url('blog') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen mb-5">BERITA TERBARU</a>
            </div>

        </div>

        <div id="posts" class="post-grid row grid-container gutter-40 clearfix" data-layout="fitRows">
            <div class="row col-mb-30 mb-0" data-aos="fade-up">
                <div class="col-lg-5" data-aos="fade-right" data-aos-duration="800">
                    <?php if (!empty($beritaNew)) : ?>
                        <div class="posts-md">
                            <div class="entry">
                                <div class="entry-image imagescalein" data-aos="zoom-in" data-aos-delay="100">
                                    <a href="<?= site_url('blog/' . $beritaNew['slug']) ?>" target="_blank" aria-label="Gambar Berita: <?= htmlspecialchars($beritaNew['judul'], ENT_QUOTES, 'UTF-8') ?>">
                                        <img src="<?= base_url('berita/' . $beritaNew['gambar']); ?>" alt="<?= ucwords($beritaNew['judul']) ?>">
                                    </a>
                                </div>
                                <div class="entry-title nott" data-aos="fade-left" data-aos-delay="200">
                                    <h3 class="mb-2">
                                        <a href="<?= site_url('blog/' . $beritaNew['slug']) ?>" aria-label="Baca lebih lanjut tentang <?= htmlspecialchars($beritaNew['judul'], ENT_QUOTES, 'UTF-8') ?>"><?= limit_words(ucwords($beritaNew['judul'])) ?></a>
                                    </h3>
                                </div>
                                <div class="entry-meta" data-aos="fade-up" data-aos-delay="300">
                                    <ul>
                                        <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($beritaNew['tanggal']) ?></li>
                                        <li><i class="icon-eye"></i><?= $beritaNew['viewberita'] ?></li>
                                        <li><i class="icon-user"></i>Website Administrator</li>
                                    </ul>
                                </div>
                                <div class="entry-content" data-aos="fade-up" data-aos-delay="400">
                                    <a href="<?= site_url('blog/' . $beritaNew['slug']) ?>" class="btn btn-danger" aria-label="Baca lebih lanjut tentang <?= htmlspecialchars($beritaNew['judul'], ENT_QUOTES, 'UTF-8') ?>">Baca Selengkapnya</a>
                                </div>
                            </div>
                        </div>
                    <?php else : ?>
                        <p data-aos="fade-up">Tidak ada berita terbaru.</p>
                    <?php endif; ?>

                </div>

                <div class="col-lg-7" data-aos="fade-left" data-aos-duration="800">
                    <div class="posts-sm row col-mb-30">
                        <?php foreach ($beritaOne as $index => $item) : ?>
                            <div class="entry col-lg-6" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                                <div class="grid-inner row align-items-center g-0">
                                    <div class="col-auto imagescalein" data-aos="zoom-in" data-aos-delay="<?= $index * 100 + 100 ?>">
                                        <div class="entry-image">
                                            <a href="<?= base_url('berita/' . $item['thumbnail']); ?>" target="_blank">
                                                <img src="<?= base_url('berita/' . $item['thumbnail']); ?>" alt="<?= $item['judul'] ?>" loading="lazy">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="entry-title" data-aos="fade-left" data-aos-delay="<?= $index * 100 + 200 ?>">
                                            <h4><a href="<?= site_url('blog/' . $item['slug']) ?>"><?= limit_words(ucwords($item['judul'])) ?></a></h4>
                                        </div>
                                        <div class="entry-meta" data-aos="fade-up" data-aos-delay="<?= $index * 100 + 300 ?>">
                                            <ul>
                                                <li><i class="icon-calendar3"></i> <?= format_indo($item['tanggal']) ?></li>
                                                <li><i class="icon-eye"></i><?= $item['viewberita'] ?></li>
                                                <li><i class="icon-user"></i>Website Administrator</li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="text-end view-all-berita" data-aos="fade-up" data-aos-delay="500">
                        <a href="<?= site_url('blog') ?>" class="button button-large button-circle button-3d button-dirtygreen button-border">View All Berita</a>
                    </div>
                </div>
            </div>

        </div>




    </div>
</div>