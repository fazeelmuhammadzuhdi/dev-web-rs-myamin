<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Album</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Album</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('album') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Album</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('album/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idalbum" value="<?= $album['idalbum'] ?>">

                        <div class="form-group">
                            <label for="inputAddress">Judul</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul', $album['judul']) ?>">
                            <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_judul') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tanggal Album</label>
                            <input type="date" class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" value="<?= old('tanggal', $album['tanggal']) ?>">
                            <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="PB" <?= ($album['status'] == 'PB') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">PB</label>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="UP" <?= ($album['status'] == 'UP') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">UP</label>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="summernote" name="keterangan" placeholder="Inputkan Keterangan Album"><?= old('keterangan', $album['keterangan']) ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
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