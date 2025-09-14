<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Kategori</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('videos') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Kategori</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('videos/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idvideo" value="<?= $video['idvideo'] ?>">

                        <div class="form-group">
                            <label for="inputAddress">Judul Video</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul', $video['judul']) ?>">
                            <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_judul') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Link Video</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_link')) ? 'is-invalid' : '' ?>" name="link" value="<?= old('link', $video['link']) ?>">
                            <?= (session()->getFlashdata('error_link')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_link') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tanggal Video</label>
                            <input type="date" class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" value="<?= old('tanggal', $video['tanggal']) ?>">
                            <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="PB" <?= ($video['status'] == 'PB') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">PB</label>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="UP" <?= ($video['status'] == 'UP') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">UP</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Thumbnail Video</label>
                            <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_thumbnail')) ? 'is-invalid' : '' ?>" name="thumbnail">
                            <?= (session()->getFlashdata('error_thumbnail')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_thumbnail') . "</div>" : ''; ?>
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