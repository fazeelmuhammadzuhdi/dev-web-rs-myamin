<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Tempat Tidur</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tempat Tidur</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('tempattidur') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Tempat Tidur</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('tempattidur/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Nama Rawat</label>
                                <select id="inputState" class="form-control <?= (session()->getFlashdata('error_rawat_id')) ? 'is-invalid' : '' ?>" name="rawat_id">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($rawat as $item) {
                                        echo '<option value="' . $item['idrawat'] . '">' .  $item['nama'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_rawat_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_rawat_id') . "</div>" : ''; ?>
                            </div>


                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-3">
                                <label for="inputPassword4">VIP Isi</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_vip_isi')) ? 'is-invalid' : '' ?>" name="vip_isi" autofocus value="<?= old('vip_isi') ?>">
                                <?= (session()->getFlashdata('error_vip_isi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_vip_isi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">VIP Kosong</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_vip_kosong')) ? 'is-invalid' : '' ?>" name="vip_kosong" value="<?= old('vip_kosong') ?>">
                                <?= (session()->getFlashdata('error_vip_kosong')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_vip_kosong') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Utama Isi</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_utama_isi')) ? 'is-invalid' : '' ?>" name="utama_isi" value="<?= old('utama_isi') ?>">
                                <?= (session()->getFlashdata('error_utama_isi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_utama_isi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Utama Kosong</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_utama_kosong')) ? 'is-invalid' : '' ?>" name="utama_kosong" value="<?= old('utama_kosong') ?>">
                                <?= (session()->getFlashdata('error_utama_kosong')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_utama_kosong') . "</div>" : ''; ?>
                            </div>

                        </div>


                        <div class="form-row mb-3">

                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 1 Isi</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas1_isi')) ? 'is-invalid' : '' ?>" name="kelas1_isi" value="<?= old('kelas1_isi') ?>">
                                <?= (session()->getFlashdata('error_kelas1_isi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas1_isi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 1 Kosong</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas1_kosong')) ? 'is-invalid' : '' ?>" name="kelas1_kosong" value="<?= old('kelas1_kosong') ?>">
                                <?= (session()->getFlashdata('error_kelas1_kosong')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas1_kosong') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 2 Isi</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas2_isi')) ? 'is-invalid' : '' ?>" name="kelas2_isi" value="<?= old('kelas2_isi') ?>">
                                <?= (session()->getFlashdata('error_kelas2_isi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas2_isi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 2 Kosong</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas2_kosong')) ? 'is-invalid' : '' ?>" name="kelas2_kosong" value="<?= old('kelas2_kosong') ?>">
                                <?= (session()->getFlashdata('error_kelas2_kosong')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas2_kosong') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 3 Isi</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas3_isi')) ? 'is-invalid' : '' ?>" name="kelas3_isi" value="<?= old('kelas3_isi') ?>">
                                <?= (session()->getFlashdata('error_kelas3_isi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas3_isi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Kelas 3 Kosong</label>
                                <input type="number" class="form-control <?= (session()->getFlashdata('error_kelas3_kosong')) ? 'is-invalid' : '' ?>" name="kelas3_kosong" value="<?= old('kelas3_kosong') ?>">
                                <?= (session()->getFlashdata('error_kelas3_kosong')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kelas3_kosong') . "</div>" : ''; ?>
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