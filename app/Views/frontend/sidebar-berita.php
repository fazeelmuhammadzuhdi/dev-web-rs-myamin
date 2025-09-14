<div class="widget clearfix">

    <div class="tabs mb-0 clearfix" id="sidebar-tabs">

        <ul class="tab-nav clearfix">
            <li><a href="#tabs-2">Terbaru</a></li>
            <li><a href="#tabs-1">Populer</a></li>
        </ul>

        <div class="tab-container">

            <div class="tab-content clearfix" id="tabs-1">
                <div class="posts-sm row col-mb-30" id="popular-post-list-sidebar">
                    <?php if (!empty($beritaMostView)) : ?>
                        <?php foreach ($beritaMostView as $item) : ?>
                            <div class="entry col-12">
                                <div class="grid-inner row g-0">
                                    <div class="col-auto">
                                        <div class="entry-image">
                                            <a href="<?= base_url('berita/' . $item['gambar']) ?>" target="_blank"><img src="<?= base_url('berita/' . $item['gambar']) ?>" alt="Image"></a>
                                        </div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="entry-title">
                                            <h4 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; text-align: justify;"><a href="<?= site_url('blog/' . $item['slug']) ?>"><?= ucwords($item['judul'])  ?></a></h4>
                                        </div>
                                        <div class="entry-meta">
                                            <ul>
                                                <li><span><i class="icon-eye"></i> <?= $item['viewberita']  ?> </span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-content clearfix" id="tabs-2">
                <div class="posts-sm row col-mb-30" id="popular-post-list-sidebar">
                    <?php if (!empty($beritaTerbaru)) : ?>
                        <?php foreach ($beritaTerbaru as $item) : ?>
                            <div class="entry col-12">
                                <div class="grid-inner row g-0">
                                    <div class="col-auto">
                                        <div class="entry-image">
                                            <a href="<?= base_url('berita/' . $item['gambar']) ?>" target="_blank"><img src="<?= base_url('berita/' . $item['gambar']) ?>" alt="Image"></a>
                                        </div>
                                    </div>
                                    <div class="col ps-3">
                                        <div class="entry-title">
                                            <h4 style="font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; text-align: justify;"><a href="<?= site_url('blog/' . $item['slug']) ?>"><?= ucwords($item['judul'])  ?></a></h4>
                                        </div>
                                        <div class="entry-meta">
                                            <ul>
                                                <li><i class="icon-calendar3 fw-bold"></i> <?= tanggal_indonesia($item['tanggal'])  ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>
                </div>
            </div>


        </div>

    </div>

</div>