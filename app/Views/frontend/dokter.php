<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    @media (min-width: 1200px) {
        .dokter-kami {
            margin: 0.5rem !important;

        }
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Dokter Kami</h1>

    </div>

</section>



<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="card mb-5">

                <div class="card-header"><strong class="text-danger"><i class="icon-user-md"></i> Filter Data Dokter</strong></div>
                <div class="card-body mt-0 mb-0">
                    <div class="col-lg-auto ps-lg-0">
                        <form class="input-group" action="<?= site_url('dokter-kami') ?>" method="get">
                            <div class="col-md-3 dokter-kami">

                                <select class="selectpicker" name="nama_spesialis" data-live-search="true" tabindex="null">
                                    <option value="" disabled <?= empty($selectedSpesialis) ? 'selected' : '' ?>>--Pilih Spesialis--</option>
                                    <?php foreach ($spesialisList as $spesialisItem) : ?>
                                        <option value="<?= esc($spesialisItem['idspesialis']) ?>" <?= !empty($selectedSpesialis) && $spesialisItem['idspesialis'] == $selectedSpesialis ? 'selected' : '' ?>>
                                            <?= esc($spesialisItem['nama']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                            <div class="col-md-3 me-4" style="margin-top: 7px;">
                                <input type="search" value="<?= esc($searchedNamaDokter ?? '') ?>" name="nama_dokter" class="sm-form-control text-center text-md-start" placeholder="Nama Dokter.." autofocus />
                            </div>

                            <div class="col-md-4">
                                <button class="button button-3d button-green m-0 mt-2"><i class="icon-user-md"></i> Cari</button>
                                <a href="<?= site_url('dokter-kami') ?>" class="button button-3d button-red m-0"><i class="icon-refresh"></i> Reset</a>
                            </div>
                        </form>

                    </div>

                </div>
            </div>

            <!-- pencarian dokter -->
            <h3><?= $dokterResults[0]['nama_spesialis'] ?? '' ?></h3>
            <div class="container clearfix">

                <div id="section-wheel" class="page-section">

                    <?php

                    if (!empty($dokterResults)) : ?>
                        <div id="oc-wheel" class="owl-carousel shop-carousel carousel-widget" data-margin="30" data-nav="true" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="3" data-autoplay="4000">

                            <?php foreach ($dokterResults as $item) : ?>
                                <div class="product center">
                                    <div class="product-image px-4 py-1">
                                        <a href="<?= base_url('dokter/' . $item['gambar']) ?>" target="_blank"><img src="<?= base_url('dokter/' . $item['gambar']) ?>" alt="<?= $item['nama'] ?>"></a>
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
                    <?php endif; ?>


                    <?php if (!empty($searchedNamaDokter) && empty($dokterResults[0]['nama'])) : ?>
                        <div class="product center">
                            <a href="<?= base_url('frontend/404.jpg'); ?>" target="_blank"><img src="<?= base_url('frontend/404.jpg'); ?>" alt="Image" width="400px"></a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>


            <!-- anastesi -->
            <?= $this->include('frontend/include/dokter/index'); ?>


        </div>


    </div>
</section><!-- #content end -->

<?= $this->endsection() ?>