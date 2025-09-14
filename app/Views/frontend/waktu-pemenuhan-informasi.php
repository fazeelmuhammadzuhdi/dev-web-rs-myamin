<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    /* class active */
    .active {
        color: red;
    }

    .active a {
        font-weight: bold;
        color: red !important;
        /* Change to desired color */
    }



    .active {
        color: red;
        /* Change to desired color */
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>PPID &raquo; Estimasi Waktu Pemenuhan Informasi</h1>
    </div>


</section>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-12">
                    <h5>Estimasi Waktu Permohonan Informasi</h5>
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pemohon</th>
                                    <th>Alamat</th>
                                    <th>Informasi Dibutuhkan</th>
                                    <th>Alasan Permintaan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($permohonaninformasi as $item) : ?>

                                    <tr>

                                        <td><?= tanggal_indonesia($item['tanggal'])   ?></td>
                                        <td><?= $item['nama_pemohon_informasi'] ?></td>
                                        <td><?= $item['alamat_pemohon'] ?></td>
                                        <td><?= $item['informasi_dibutuhkan_pemohon'] ?></td>
                                        <td><?= $item['alasan_permintaan_pemohon'] ?></td>
                                        <td><?= $item['keterangan'] ?></td>
                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-12">
                    <h5>Estimasi Waktu Keberatan Informasi</h5>
                    <div class="table-responsive">
                        <table id="datatable2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pemohon</th>
                                    <th>Alamat</th>
                                    <th>Informasi Dibutuhkan</th>
                                    <th>Alasan Permintaan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($keberataninformasi as $item) : ?>
                                    <tr>
                                        <td><?= tanggal_indonesia($item['tanggal'])   ?></td>
                                        <td><?= $item['nama_pemohon_informasi'] ?></td>
                                        <td><?= $item['alamat_pemohon'] ?></td>
                                        <td><?= $item['informasi_dibutuhkan_pemohon'] ?></td>
                                        <td><?= $item['alasan_pengajuan'] ?></td>
                                        <td><?= $item['keterangan'] ?></td>
                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



                </div>




            </div>

        </div>
    </div>
</section><!-- #content end -->




<?= $this->endsection() ?>