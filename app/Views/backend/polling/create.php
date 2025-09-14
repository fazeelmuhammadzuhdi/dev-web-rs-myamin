<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Polling</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Polling</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('pollings') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Polling</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pollings/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="inputAddress">Pertanyaan</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_pertanyaan')) ? 'is-invalid' : '' ?>" name="pertanyaan" autofocus value="<?= old('pertanyaan') ?>">
                            <?= (session()->getFlashdata('error_pertanyaan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_pertanyaan') . "</div>" : ''; ?>

                        </div>
                        <div class="form-group">
                            <label for="inputAddress">OPA</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_opa')) ? 'is-invalid' : '' ?>" name="opa" autofocus value="<?= old('opa') ?>">
                            <?= (session()->getFlashdata('error_opa')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_opa') . "</div>" : ''; ?>

                        </div>
                        <div class="form-group">
                            <label for="inputAddress">OPB</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_opb')) ? 'is-invalid' : '' ?>" name="opb" autofocus value="<?= old('opb') ?>">
                            <?= (session()->getFlashdata('error_opb')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_opb') . "</div>" : ''; ?>

                        </div>
                        <div class="form-group">
                            <label for="inputAddress">OPC</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_opc')) ? 'is-invalid' : '' ?>" name="opc" autofocus value="<?= old('opc') ?>">
                            <?= (session()->getFlashdata('error_opc')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_opc') . "</div>" : ''; ?>

                        </div>
                        <div class="form-group">
                            <label for="inputAddress">OPD</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_opd')) ? 'is-invalid' : '' ?>" name="opd" autofocus value="<?= old('opd') ?>">
                            <?= (session()->getFlashdata('error_opd')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_opd') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <div>
                                <label for="inputAddress">Status Polling</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="PB">
                                <label class="form-check-label" for="inlineRadio1">PB</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="UP">
                                <label class="form-check-label" for="inlineRadio2">UP</label>

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