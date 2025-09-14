<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Indikator Mutu List</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Indikator Mutu List</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('indikatormutulists') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Indikatormutulist</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('indikatormutulists/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idindikatormutulist" value="<?= $indikatormutulists['idindikatormutulist'] ?>">


                        <div class="form-group">
                            <label for="inputEmail4">Nama Indikator</label>
                            <select id="inputState" class="form-control <?= (session()->getFlashdata('error_indikator_mutu_id')) ? 'is-invalid' : '' ?>" name="indikator_mutu_id">
                                <option selected="" value="" disabled>--Pilih--</option>
                                <?php foreach ($indikatormutu as $item) {
                                    $selected = ($item['idindikatormutu'] == $indikatormutulists['indikator_mutu_id']) ? 'selected' : '';
                                    echo '<option value="' . $item['idindikatormutu'] . '"' . $selected . '>' .  $item['nama'] . '</option>';
                                } ?>
                            </select>
                            <?= (session()->getFlashdata('error_indikator_mutu_id')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_indikator_mutu_id') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="summernote" name="keterangan" placeholder="Inputkan Keterangan"><?= old('keterangan', htmlspecialchars($indikatormutulists['keterangan'])) ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio1" value="Y" <?= ($indikatormutulists['status'] == 'Y') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">Y</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="inlineRadio2" value="T" <?= ($indikatormutulists['status'] == 'T') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">T</label>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Gambar Indikator Mutu List</label>
                            <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar">
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