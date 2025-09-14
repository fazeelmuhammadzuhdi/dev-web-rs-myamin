<div class="sidebar col-lg-3">

    <!-- cari -->
    <div class="sidebar-widgets-wrap">

        <div class="widget widget_links clearfix">

            <h4 class="ls1 text-uppercase fw-bold"><?= $header ?? 'Kategori Informasi' ?></h4>



            <li class="d-flex align-items-center <?= (current_url(true)->getPath() == '/dip') ? 'active' : '' ?>  <?= (current_url(true)->getPath() == '/pkrs') ? 'active' : '' ?>">
                <a href="<?= site_url($url) ?>" class="flex-fill">
                    <?= $title ?>
                </a>

                <span class="badge text-light bg-success">
                    <?= (!empty($total)) ? $total : '0' ?>
                </span>
            </li>

            <?php if (!empty($countBeritaPPID)) : ?>
                <ul>
                    <?php foreach ($countBeritaPPID as $item) : ?>
                        <li class="d-flex align-items-center <?= (!empty($selectedKategori) && $item['idkategori'] == $selectedKategori) ? 'active' : '' ?>">
                            <a href="<?= site_url('beritakategori/' . $item['idkategori']) ?>" class="flex-fill"><?= $item['title'] ?></a>
                            <span class="badge text-light bg-success"><?= $item['total'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>


        </div>


    </div>

    <div class="widget clearfix">

        <div class="tabs mb-0 clearfix" id="sidebar-tabs">

            <ul class="tab-nav clearfix">
                <li><a href="#tabs-2">Terbaru</a></li>
                <li><a href="#tabs-1">Populer</a></li>
            </ul>

            <div class="tab-container">

                <div class="tab-content clearfix" id="tabs-1">
                    <div class="posts-sm row col-mb-30" id="popular-post-list-sidebar">
                        <?php if (!empty($popularBeritaPPID)) : ?>
                            <?php foreach ($popularBeritaPPID as $item) : ?>
                                <div class="entry col-12">
                                    <div class="grid-inner row g-0">

                                        <div class="col ps-3">
                                            <div class="entry-title">
                                                <h4><a href="<?= site_url('beritappid-detail/' . $item['idberita']) ?>"><?= $item['judul'] ?></a></h4>
                                            </div>
                                            <div class="entry-meta">
                                                <ul>
                                                    <li><i class="icon-eye"></i><?= $item['viewberita'] ?></li>
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
                        <?php if (!empty($beritaTerbaruPPID)) : ?>
                            <?php foreach ($beritaTerbaruPPID as $item) : ?>
                                <div class="entry col-12">
                                    <div class="grid-inner row g-0">

                                        <div class="col ps-3">
                                            <div class="entry-title">
                                                <h4><a href="<?= site_url('beritappid-detail/' . $item['idberita']) ?>"><?= $item['judul'] ?></a></h4>
                                            </div>
                                            <div class="entry-meta">
                                                <ul>
                                                    <li><i class="icon-calendar3"></i><?= tanggal_indonesia($item['tanggal'])  ?></li>
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



</div>