<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Filedownload</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Filedownload</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('filedownloads') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Filedownload</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('filedownloads/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>


                        <div class="form-group">
                            <label for="inputAddress">Upload File</label>
                            <input type="file" class="form-control-file <?= (session()->getFlashdata('error_uploadfile')) ? 'is-invalid' : '' ?>" name="uploadfile">
                            <?= (session()->getFlashdata('error_uploadfile')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_uploadfile') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Keterangan File</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keteranganfile')) ? 'is-invalid' : '' ?>" id="summernote" name="keteranganfile" placeholder="Inputkan Keteranganfile Album"><?= old('keteranganfile') ?></textarea>
                            <?= (session()->getFlashdata('error_keteranganfile')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keteranganfile') . "</div>" : ''; ?>
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