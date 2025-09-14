<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Album List</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Album List</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('albumlists') ?>" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">

        <!-- Start col -->
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">Form Input Album List</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('albumlists/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="form-group">
                            <label for="inputEmail4">Judul Album</label>
                            <select id="inputState" class="select2 form-control <?= (session()->getFlashdata('error_album_id')) ? 'is-invalid' : '' ?>" name="album_id">
                                <option selected="" value="">--Pilih--</option>

                                <?php foreach ($album as $item) {
                                    $selected = (old('album_id') == $item['idalbum']) ? 'selected' : '';
                                    echo '<option value="' . $item['idalbum'] . '"' . $selected . '>' .  $item['judul'] . '</option>';
                                } ?>
                            </select>
                            <?= (session()->getFlashdata('error_album_id')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_album_id') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="summernote" name="keterangan" placeholder="Inputkan Keterangan Album"><?= old('keterangan') ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <label for="inputAddress">Gambar</label>
                            <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar[]" value="<?= old('gambar') ?>" multiple>
                            <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                </div>
            </div>
        </div>
        <!-- End col -->
    </div> <!-- End row -->
</div>

<!-- select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<!-- End Contentbar -->
<?= $this->endsection() ?>