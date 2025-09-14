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
                    <form action="<?= base_url('album/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="inputAddress">Judul</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul') ?>">
                            <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_judul') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tanggal Album</label>
                            <input type="date" id="tanggal" class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" value="<?= old('tanggal') ?>">
                            <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="PB">
                                <label class="form-check-label" for="inlineRadio1">PB</label> <br>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="UP" checked>
                                <label class="form-check-label" for="inlineRadio2">UP</label>
                                <!-- <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_status') . "</div>" : ''; ?> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="summernote" name="keterangan" placeholder="Inputkan Keterangan Album"><?= old('keterangan') ?></textarea>
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

<script>
    // JavaScript untuk mengatur tanggal saat ini pada input date
    document.addEventListener("DOMContentLoaded", function() {
        // Fungsi untuk memformat tanggal dalam format YYYY-MM-DD
        function formatDate(date) {
            var day = ("0" + date.getDate()).slice(-2);
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            return year + "-" + month + "-" + day;
        }

        // Mengatur tanggal pinjam menjadi tanggal saat ini
        var today = new Date();
        var todayString = formatDate(today);
        document.getElementById("tanggal").value = todayString;

    });
</script>

<!-- End Contentbar -->
<?= $this->endsection() ?>