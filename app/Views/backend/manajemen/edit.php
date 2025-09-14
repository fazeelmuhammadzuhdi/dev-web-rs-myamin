<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Manajemen</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('manajemen') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Manajemen</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('manajemen/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idmanajemen" value="<?= $manajemen['idmanajemen'] ?>">

                        <div class="form-group">
                            <label for="inputAddress">Nama</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" autofocus value="<?= old('nama', $manajemen['nama']) ?>">
                            <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="Y" <?= ($manajemen['status'] == 'Y') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">Y</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="T" <?= ($manajemen['status'] == 'T') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">T</label>
                            </div>
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