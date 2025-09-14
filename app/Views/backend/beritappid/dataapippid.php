<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>

<!-- <style>
    #tableBanner th,
    #tableBanner td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
</style> -->

<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title"><?= $title ?></h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </div>
        </div>

    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">List Data <?= $title ?></h5>
                    <div class="widgetbar">

                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="<?= site_url('apiberitappid/saveSelected'); ?>" method="post" id="formUpdate">
                            <?= csrf_field(); ?>
                            <button type="submit" class="btn btn-primary mb-3">Update</button>

                            <table class="table" id="tableBanner">
                                <thead>
                                    <tr>
                                        <th scope="col">Id </th>
                                        <th scope="col">Judul</th>
                                        <th scope="col">Kategori</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Db</th>
                                        <th scope="col">Pilih</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($beritaPpid)) : ?>
                                        <?php foreach ($beritaPpid as $berita) : ?>
                                            <tr>
                                                <td><?= $berita['id_content']; ?></td>
                                                <td>
                                                    <a href="<?= $berita['downloads']; ?>" target="_blank">
                                                        <?= $berita['title_content']; ?>
                                                    </a>
                                                </td>
                                                <td><?= $berita['title_category']; ?></td>
                                                <td><span class="badge badge-success"><?= $berita['nm_status']; ?></span></td>
                                                <td><?= $berita['created']; ?></td>
                                                <td>
                                                    <?php if (in_array($berita['id_content'], $existingIds)) : ?>
                                                        <span class="badge badge-success">Sudah Update</span>
                                                    <?php else : ?>
                                                        <span class="badge badge-danger">Belum Update</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><input  type="checkbox" name="pilih[]" value="<?= htmlspecialchars(json_encode($berita)); ?>"></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr>
                                            <td colspan="7">Tidak ada data tersedia.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->
<script>
    // Inisialisasi DataTable pada tabel


    $(document).ready(function() {
        $('#tableBanner').dataTable({
            "order": [
                [4, "desc"]
            ]
        });
    });
</script>


<?= $this->endsection() ?>