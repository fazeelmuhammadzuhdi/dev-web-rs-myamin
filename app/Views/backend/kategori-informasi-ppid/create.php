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
                <a href="<?= site_url('kategoris-ppid') ?>" class="btn btn-warning">Kembali</a>
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
                    <form action="<?= base_url('kategoris-ppid/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="inputAddress">Kategori</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_kategori')) ? 'is-invalid' : '' ?>" name="idkategori" placeholder="Contoh : AG" autofocus value="<?= old('idkategori') ?>">
                            <?= (session()->getFlashdata('error_kategori')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_kategori') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Title</label>
                            <input type="text" placeholder="Contoh : Agenda" class="form-control <?= (session()->getFlashdata('error_title')) ? 'is-invalid' : '' ?>" name="title" value="<?= old('title') ?>">
                            <?= (session()->getFlashdata('error_title')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_title') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="Y">
                                <label class="form-check-label" for="inlineRadio1">Y</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="T">
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