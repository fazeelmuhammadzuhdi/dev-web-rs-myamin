<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title"><?= $title ?></h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Permohonan Informasi</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('pesanppid') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Permohonan Informasi PPID</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('/pesan-ppid'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>


                        <div class="form-row mb-3">

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Nama Pemohon Informasi</label>
                                <input type="text" autofocus id="template-contactform-name" name="nama_pemohon_informasi" value="<?= old('nama_pemohon_informasi') ?>" class="form-control <?= (session()->getFlashdata('error_nama_pemohon_informasi')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_nama_pemohon_informasi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama_pemohon_informasi') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Alamat Pemohon Informasi</label>
                                <input type="text" id="template-contactform-name" name="alamat_pemohon" value="<?= old('alamat_pemohon') ?>" class="form-control <?= (session()->getFlashdata('error_alamat_pemohon')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_alamat_pemohon')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_alamat_pemohon') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Nomor Telepon / HP</label>
                                <input type="number" id="template-contactform-name" value="<?= old('nomor_telepon_pemohon') ?>" name="nomor_telepon_pemohon" class="form-control <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nomor_telepon_pemohon') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Email</label>
                                <input type="email" id="template-contactform-name" value="<?= old('email_pemohon') ?>" name="email_pemohon" class="form-control <?= (session()->getFlashdata('error_email_pemohon')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_email_pemohon')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_email_pemohon') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Rincian Informasi Yang Dibutuhkan</label>
                                <textarea class="form-control <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="informasi_dibutuhkan_pemohon" rows="2" cols="30"><?= old('informasi_dibutuhkan_pemohon') ?></textarea>
                                <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_informasi_dibutuhkan_pemohon') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Alasan Permintaan</label>
                                <textarea class="form-control <?= (session()->getFlashdata('error_alasan_permintaan_pemohon')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="alasan_permintaan_pemohon" rows="2" cols="30"><?= old('alasan_permintaan_pemohon') ?></textarea>
                                <?= (session()->getFlashdata('error_alasan_permintaan_pemohon')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_alasan_permintaan_pemohon') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Cara Memperoleh Informasi</label>
                                <select id="template-contactform-service" name="cara_memperoleh_informasi" class="form-control valid">
                                    <option value="Langsung">Langsung</option>
                                    <option value="Website">Website</option>
                                    <option value="Email">Email</option>
                                    <option value="Fax">Fax</option>
                                    <option value="Via POS">Via POS</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Format Bahan Informasi</label>
                                <select id="template-contactform-service" name="format_bahan_informasi" class="form-control valid">
                                    <option value="Tercetak">Tercetak</option>
                                    <option value="Terekam">Terekam</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Cara Mengirim Bahan Informasi</label>
                                <select id="template-contactform-service" name="cara_mengirim_bahan_informasi" class="form-control valid">
                                    <option value="Langsung">Langsung</option>
                                    <option value="Via POS">Via POS</option>
                                    <option value="Email">Email</option>
                                </select>
                            </div>



                        </div>

                        <div class="form-group mb-3">
                            <label for="inputAddress">Keterangan</label>
                            <textarea placeholder="Masukkan Keterangan" class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="keterangan" rows="2" cols="30"><?= old('keterangan') ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                        </div>



                        <!-- <div class="form-group">
                            <label for="inputAddress">Nomor KTP (Sesuai KTP)</label>
                            <input type="number" id="template-contactform-email" name="nomor_ktp_pemohon" value="<?= old('nomor_ktp_pemohon') ?>" class="form-control <?= (session()->getFlashdata('error_nomor_ktp_pemohon')) ? 'is-invalid' : '' ?>" />
                            <?= (session()->getFlashdata('error_nomor_ktp_pemohon')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nomor_ktp_pemohon') . "</div>" : ''; ?>
                        </div> -->

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div> <!-- End row -->
</div>
<!-- End Contentbar -->
<?= $this->endsection() ?>