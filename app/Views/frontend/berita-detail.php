<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<style>
    .konten-berita p {
        margin: 0 0 1em;
        /* Atur jarak antar paragraf */
        padding: 0;
    }

    .konten-berita ul,
    .konten-berita ol {
        margin: 0 0 1em;
        padding-left: 20px;
        /* Memberi indentasi untuk list */
    }

    .konten-berita li {
        margin: 0 0 0.5em;
    }
</style>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <!-- detail -->
                <div class="postcontent col-lg-9">

                    <div class="single-post mb-0">

                        <!-- Single Post
								============================================= -->
                        <div class="entry clearfix">

                            <!-- Entry Title
									============================================= -->
                            <div class="entry-title">
                                <h2><?= $beritaDetail['judul'] ?></h2>
                            </div><!-- .entry-title end -->

                            <!-- Entry Meta
									============================================= -->
                            <div class="entry-meta">
                                <ul>
                                    <li><a href="javascript:void(0);"><i class="icon-user"></i> Website Administrator</a></li>
                                    <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($beritaDetail['tanggal'])  ?></li>
                                    <li><a href="javascript:void(0);"><i class="icon-eye"></i><?= $beritaDetail['viewberita'] ?></a></li>
                                </ul>
                            </div><!-- .entry-meta end -->

                            <div class="konten-berita">
                                <?= htmlspecialchars_decode($beritaDetail['konten']); ?>
                            </div>


                            <!-- <?= $beritaDetail['konten']; ?> -->
                        </div>

                        <h4>Berita Lainnya :</h4>
                        <div class="related-posts row posts-md col-mb-30">
                            <?php if (!empty($beritaLainnya)) : ?>
                                <?php foreach ($beritaLainnya as $item) : ?>
                                    <div class="entry col-12 col-md-6">
                                        <div class="grid-inner row align-items-start gutter-20">
                                            <div class="col-4">
                                                <div class="entry-image">
                                                    <a href="<?= base_url('berita/' . $item['gambar']) ?>" target="_blank"><img src="<?= base_url('berita/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>"></a>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="entry-title title-xs">
                                                    <h4><a href="<?= site_url('blog/' . $item['slug']) ?>"><?= ucwords($item['judul'])  ?></a>
                                                    </h4>
                                                </div>
                                                <div class="entry-meta">
                                                    <ul>
                                                        <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($item['tanggal'])  ?>
                                                        </li>
                                                        <li><a><i class="icon-eye"></i> <?= $item['viewberita'] ?></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <div class="product center">
                                    <a href="<?= base_url(); ?>/frontend/404.jpg" target="_blank"><img src="<?= base_url(); ?>/frontend/404.jpg" alt="Image" width="400px"></a>
                                </div>
                            <?php endif; ?>
                        </div>

                    </div>

                </div>



                <div class="sidebar col-lg-3">


                    <div class="widget widget-search">
                        <form class="input-group" action="<?= site_url('blog') ?>" method="get">
                            <input class="form-control" name="q" type="search" placeholder="Search" aria-label="Search" value="<?= esc($searchQuery ?? '') ?>">
                            <button class="btn btn-outline-secondary icon-line-search" type="submit"></button>
                        </form>
                    </div>

                    <div class="sidebar-widgets-wrap">
                        <div class="widget widget_links clearfix">

                            <h4>Kategori Berita</h4>

                            <?php if (!empty($kategori)) : ?>
                                <ul>
                                    <?php foreach ($kategori as $item) : ?>

                                        <li class="d-flex align-items-center active"><a href="<?= site_url('kategori/' . $item['idkategori']) ?>" class="flex-fill"><?= $item['title'] ?></a><span class="badge text-light bg-success"><?= $item['total'] ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </div>
                        <?= $this->include('frontend/sidebar-berita')  ?>


                        <div class="widget clearfix">
                            <h4>Tags</h4>
                            <?php if (!empty($kategori)) : ?>
                                <div class="tagcloud">
                                    <?php foreach ($kategori as $item) : ?>
                                        <a href="<?= site_url('kategori/' . $item['idkategori']) ?>"><?= $item['title'] ?></a>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section>



<?= $this->endsection() ?>