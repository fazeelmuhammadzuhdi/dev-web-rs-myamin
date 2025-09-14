<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Slider</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Slider</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('sliders') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Slider</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('sliders/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="inputAddress">Judul Slider</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul') ?>">
                            <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_judul') . "</div>" : ''; ?>

                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="summernote" name="keterangan" placeholder="Inputkan Keterangan Album"><?= old('keterangan') ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <label for="inputAddress">Gambar Slider</label>
                            <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar" value="<?= old('gambar') ?>">
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


<!-- End Contentbar -->
<?= $this->endsection() ?>