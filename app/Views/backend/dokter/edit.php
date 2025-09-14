<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Dokter</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dokter</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('dokters') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Dokter</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('dokters/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="iddokter" value="<?= $dokters['iddokter'] ?>">

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Nip Dokter</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_nip')) ? 'is-invalid' : '' ?>" name="nip" autofocus value="<?= old('nip', $dokters['nip']) ?>">
                                <?= (session()->getFlashdata('error_nip')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nip') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Nama Dokter</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" value="<?= old('nama', $dokters['nama']) ?>">
                                <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Spesialis</label>
                                <select id="inputState" class="select2 form-control <?= (session()->getFlashdata('error_spesialis_id')) ? 'is-invalid' : '' ?>" name="spesialis_id">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($spesialis as $item) {
                                        $selected = ($item['idspesialis'] == $dokters['spesialis_id']) ? 'selected' : '';
                                        echo '<option value="' . $item['idspesialis'] . '"' . $selected . '>' .  $item['nama'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_spesialis_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_spesialis_id') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">


                            <div class="form-group col-md-4">
                                <label for="inputAddress">Keterangan</label>
                                <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" name="keterangan" placeholder="Inputkan Keterangan Dokter"><?= old('keterangan', $dokters['keterangan']) ?></textarea>
                                <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Status Dokter</label><br>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="Y" <?= ($dokters['status'] == 'Y') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio1">Y</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="T" <?= ($dokters['status'] == 'T') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">T</label>
                                </div>
                            </div>


                            <div class="form-group col-md-4">
                                <label for="inputAddress">Foto Dokter</label>
                                <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar">
                                <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
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

<!-- select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<?= $this->endsection() ?>