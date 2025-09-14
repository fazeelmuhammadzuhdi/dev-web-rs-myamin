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
        <h1>Media Informasi PKRS &raquo; <?= ucfirst($selectedKategori) ?> </h1>

    </div>


</section>


<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-9">

                    <div class="table-responsive">
                        <table id="datatable1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>DiPublikasikan</th>
                                    <th>Tahun</th>
                                    <th>Views</th>
                                    <th>Download</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($beritappid as $item) : ?>

                                    <tr>
                                        <td>
                                            <?php if ($item['kategori_id'] == "2") : ?>
                                                <a href="<?= site_url('form-ppid-online') ?>"><?= $item['judul'] ?></a>
                                            <?php else : ?>
                                                <a href="<?= site_url('beritappid-detail/' . $item['idberita']) ?>"><?= $item['judul'] ?></a>
                                            <?php endif; ?>


                                            <!-- <a href="<?= site_url('beritappid-detail/' . $item['idberita']) ?>"><?= $item['judul'] ?></a> -->
                                        </td>
                                        <td><?= tanggal_indonesia($item['tanggal'])   ?></td>
                                        <td><?= $item['tahun'] ?></td>
                                        <td><?= $item['viewberita'] ?></td>
                                        <td><?= $item['download'] ?></td>
                                    </tr>

                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



                </div>

                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section><!-- #content end -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#datatable1').DataTable({
            "order": [],
            "paging": true,
            "searching": true,
            "info": true
        });
    });
</script>


<?= $this->endsection() ?>