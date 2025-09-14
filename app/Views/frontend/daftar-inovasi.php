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
        <h1>PPID &raquo; Inovasi RSUD. Prof. H. Muhammad Yamin, SH</h1>
    </div>


</section>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-12">
                    <div class="table-responsive">
                        <table id="datatable1" class="table table-hover table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Judul</th>
                                    <th>Tahun</th>
                                    <th>Tahapan Inovasi</th>
                                    <th>Bentuk Inovasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $no = 1;
                                foreach ($inovasi as $item) : ?>

                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item['judul'] ?></td>
                                        <td><?= $item['tahun'] ?></td>
                                        <td><?= $item['tahapan'] ?></td>
                                        <td><?= $item['jenis'] ?></td>
                                        <td>
                                            <a href="<?= base_url('daftar-inovasi/' . $item['idinovasi']) ?>" class="btn btn-info text-white rounded">
                                                Info
                                            </a>
                                        </td>
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