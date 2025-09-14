<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Pesan</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pesan</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('pesans') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Pesan</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pesans/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idpesan" value="<?= $pesan['idpesan'] ?>">

                        <div class="form-group">
                            <label for="inputAddress">Tanggal Pesan Masuk</label>
                            <input type="datetime" class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" autofocus value="<?= old('tanggal',  $pesan['tanggal']) ?>" readonly>
                            <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Nama</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" autofocus value="<?= old('nama', $pesan['nama']) ?>" readonly>
                            <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Pesan / Pertanyaan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_pesan')) ? 'is-invalid' : '' ?>" rows="4" name="pesan" readonly><?= old('pesan', $pesan['pesan']) ?></textarea>
                            <?= (session()->getFlashdata('error_pesan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_pesan') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">

                            <div>
                                <label for="inputAddress">Status Pesan</label>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="PB" <?= ($pesan['status'] == 'PB') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">PB</label>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="UP" <?= ($pesan['status'] == 'UP') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">UP</label>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Respon / Jawaban</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_pesan')) ? 'is-invalid' : '' ?>" id="summernote" name="respon" readonly><?= old('respon', $pesan['respon']) ?></textarea>
                            <?= (session()->getFlashdata('error_pesan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_pesan') . "</div>" : ''; ?>
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