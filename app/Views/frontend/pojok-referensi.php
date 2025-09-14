<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<style>
    .card {
        border-radius: 10px;
        /* Tambahkan radius sudut jika diinginkan */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /* Tambahkan shadow untuk efek */
        margin-bottom: 20px;
        /* Beri jarak antar card dengan elemen di bawahnya */
    }

    .card-body {
        /* Sesuaikan padding untuk card body */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        /* Pastikan card-body mengambil seluruh tinggi */
    }

    .product-title {
        flex-grow: 1;

    }

    .product-title h3,
    .product-title p {
        margin: 0;
        /* Remove default margin */
    }

    .card-body .mt-auto {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: auto;
    }

    .button {
        flex: 1;
        text-align: center;
    }

    @media (max-width: 767px) {
        .card-body .mt-auto {
            flex-direction: column;
        }

        .button {
            margin-bottom: 10px;
        }
    }
</style>

<section id="content">

    <!-- <div class="heading-block topmargin text-center border-bottom-0">
        <h2 class="fw-normal ls0 nott">Pojok Referensi</h2>
    </div> -->

    <div class="container">
        <div class="card mb-5 mt-5">
            <div class="card-header"><strong class="text-danger"><i class="icon-book"></i> Data Pojok Referensi</strong></div>
            <div class="card-body mt-0 mb-0">
                <div class="col-lg-12 ps-lg-0">
                    <form class="input-group row" action="<?= site_url('pojok-referensi') ?>" method="get">
                        <div class="col-lg-3 col-md-12 col-12 mb-2">
                            <select class="form-control sm-form-control" name="nama_kategori">
                                <option value="">--Semua Kategori--</option>
                                <option value="ap" <?= !empty($namaKategori) && 'ap' == $namaKategori ? 'selected' : '' ?>>Asuhan Pasien</option>
                                <option value="mnj" <?= !empty($namaKategori) && 'mnj' == $namaKategori ? 'selected' : '' ?>>Manajemen</option>
                                <option value="pdk" <?= !empty($namaKategori) && 'pdk' == $namaKategori ? 'selected' : '' ?>>Pendidikan Klinis</option>
                                <option value="plk" <?= !empty($namaKategori) && 'plk' == $namaKategori ? 'selected' : '' ?>>Penelitian Klinis</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-12 col-12 mb-2">
                            <input type="search" value="<?= esc($namaJudul ?? '') ?>" name="nama_judul" class="form-control sm-form-control" placeholder="Masukkan Keyword.." />
                        </div>
                        <div class="col-lg-4 col-md-12 col-12 mb-2">
                            <button class="button button-3d button-green m-0"><i class="icon-book"></i> Cari</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <?php if (!empty($referensi)) : ?>
                <?php foreach ($referensi as $item) : ?>
                    <div class="col-md-3 mb-4"> <!-- Tambahkan mb-4 di sini -->
                        <div class="card h-100">
                            <a href="<?= base_url('referensi/' . $item['gambar']) ?>" target="_blank">
                                <img src="<?= base_url('referensi/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>" title="<?= $item['judul'] ?>" class="card-img-top">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <div class="product-title">
                                    <h3><a class="text-dark"><?= limit_words($item['pengarang'], 2) ?></a></h3>
                                    <p><small><?= limit_words(strtoupper($item['judul']), 10) ?></small></p>
                                </div>
                                <div class="mt-auto text-center">
                                    <a href="#block-modal-contact-<?= $item['idreferensi'] ?>" data-lightbox="inline" class="button button-circle button-blue button-3d button-rounded">Detail</a>
                                    <a href="<?= $item['link'] ?>" target="_blank" class="button button-circle button-green button-3d button-rounded">Download</a>
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

            <ul class="pagination mt-5 pagination-circle justify-content-center">
                <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
            </ul>
        </div>


        <?php
        /*      
        <div class="row mb-4" style="padding-bottom: 100px !important;">
            <?php if (!empty($referensi)) : ?>

                <?php foreach ($referensi as $item) : ?>


                    <div class="col-md-3">
                        <div class="card h-100">
                            <a href="<?= base_url('referensi/' . $item['gambar']) ?>" target="_blank">
                                <img src="<?= base_url('referensi/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>" title="<?= $item['judul'] ?>" class="card-img-top">
                            </a>
                            <div class="card-body d-flex flex-column">
                                <div class="product-title">
                                    <h3><a class="text-dark"><?= limit_words($item['pengarang'], 2) ?></a></h3>
                                    <p><small><?= limit_words(strtoupper($item['judul']), 10) ?></small></p>
                                </div>
                                <div class="mt-auto">
                                    <a href="#block-modal-contact-<?= $item['idreferensi'] ?>" data-lightbox="inline" class="button button-circle button-blue button-3d button-rounded">Detail</a>
                                    <a href="<?= $item['link'] ?>" target="_blank" class="button button-circle button-green button-3d button-rounded">Download</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <a href="<?= base_url('referensi/' . $item['gambar']) ?>" target="_blank">
                            <img src="<?= base_url('referensi/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>" title="<?= $item['judul'] ?>">
                        </a>

                        <div class="product-desc py-0">
                            <div class="product-title">
                                <h3><a class="text-dark"><?= limit_words($item['pengarang'], 2) ?></a></h3>
                                <p><small><?= limit_words(strtoupper($item['judul']), 10) ?></small></p>
                                <div class="mb-4">
                                    <a href="#block-modal-contact-<?= $item['idreferensi'] ?>" data-lightbox="inline" class="button button-circle button-blue button-3d button-rounded">Detail</a>
                                    <a href="<?= $item['link'] ?>" target="_blank" class="button button-circle button-green button-3d button-rounded">Download</a>
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


            <ul class="pagination mt-5 pagination-circle justify-content-center">
                <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
            </ul>

        </div>
        */
        ?>
    </div>
</section>

<!-- <div class="modal-on-load" data-target="#block-modal-contact"></div> -->

<?php if (!empty($referensi)) : ?>

    <?php foreach ($referensi as $item) : ?>
        <!-- Modal -->
        <div class="modal1 mfp-hide" id="block-modal-contact-<?= $item['idreferensi'] ?>">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-white rounded">

                    <div class="row m-0">
                        <div class="col-lg-6 d-flex align-items-center bg-light">
                            <img src="<?= base_url('referensi/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>">
                        </div>
                        <div class="col-lg-6 p-4">
                            <div class="form-widget">
                                <form class="mb-0" id="block-contactform" name="block-contactform">
                                    <div class="row">
                                        <div class="col-12 form-group">
                                            <label class="nott ls0 fw-bold" for="block-contactform-name">Judul <small>*</small></label>
                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;"><?= $item['judul'] ?></p>
                                            <label class="nott ls0 fw-bold" for="block-contactform-name">Pengarang <small>*</small></label>
                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;"><?= $item['pengarang'] ?></p>
                                            <label class="nott ls0 fw-bold" for="block-contactform-name">Bahasa <small>*</small></label>
                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;"><?= $item['bahasa'] ?></p>

                                            <label class="nott ls0 fw-bold" for="block-contactform-name"> Kategori <small>*</small></label>


                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;">

                                                <?php
                                                switch ($item['kategori']) {
                                                    case 'ap':
                                                        echo 'Asuhan Pasien';
                                                        break;
                                                    case 'pdk':
                                                        echo 'Pendidikan Klinis';
                                                        break;
                                                    case 'plk':
                                                        echo 'Penelitian Klinis';
                                                        break;
                                                    case 'mnj':
                                                        echo 'Manajemen';
                                                        break;
                                                    default:
                                                        echo 'Kategori Tidak Dikenal'; // Optional: for unexpected values
                                                }
                                                ?>
                                            </p>


                                            <label class="nott ls0 fw-bold" for="block-contactform-name">Penerbit <small>*</small></label>
                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;"><?= $item['penerbit'] ?></p>
                                            <label class="nott ls0 fw-bold" for="block-contactform-name">Tahun <small>*</small></label>
                                            <p style="margin-bottom: 10px !important; margin-top: 0px !important;"><?= $item['tahun'] ?></p>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn w-100 button button-purple button-3d py-2 mx-0" type="button" onclick="window.open('<?= $item['link'] ?>', '_blank')">Download</button>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    <?php endforeach; ?>
<?php endif; ?>


<?= $this->endsection() ?>