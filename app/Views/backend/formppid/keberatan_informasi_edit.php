<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Edit Keberatan Informasi PPID</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keberatan Informasi</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('keberataninformasi') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Keberatan Informasi</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('keberataninformasi/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idkeberataninformasippid" value="<?= $keberataninformasippid['idkeberataninformasippid'] ?>">

                        <div class="form-group">
                            <label for="inputAddress">Nama Pemohon Informasi</label>
                            <input type="text" placeholder="Masukkan Nama Pemohon Informasi" class="form-control <?= (session()->getFlashdata('error_nama_pemohon_informasi')) ? 'is-invalid' : '' ?>" name="nama_pemohon_informasi" value="<?= old('nama_pemohon_informasi', $keberataninformasippid['nama_pemohon_informasi']) ?>">
                            <?= (session()->getFlashdata('error_title')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_title') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Alamat Pemohon Informasi</label>
                            <input type="text" id="template-contactform-name" name="alamat_pemohon" value="<?= old('alamat_pemohon', $keberataninformasippid['alamat_pemohon']) ?>" class="form-control <?= (session()->getFlashdata('error_alamat_pemohon')) ? 'is-invalid' : '' ?>" />
                            <?= (session()->getFlashdata('error_alamat_pemohon')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_alamat_pemohon') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Pekerjaan Informasi</label>
                            <input type="text" id="template-contactform-email" name="pekerjaan" value="<?= old('pekerjaan', $keberataninformasippid['pekerjaan']) ?>" class="form-control <?= (session()->getFlashdata('error_pekerjaan')) ? 'is-invalid' : '' ?>" />
                            <?= (session()->getFlashdata('error_pekerjaan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_pekerjaan') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Nomor Telepon / HP</label>
                            <input type="number" id="template-contactform-name" value="<?= old('nomor_telepon_pemohon', $keberataninformasippid['nomor_telepon_pemohon']) ?>" name="nomor_telepon_pemohon" class="form-control <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? 'is-invalid' : '' ?>" />
                            <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nomor_telepon_pemohon') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tujuan Penggunaan Informasi</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="informasi_dibutuhkan_pemohon" rows="2" cols="30"><?= old('informasi_dibutuhkan_pemohon', $keberataninformasippid['informasi_dibutuhkan_pemohon']) ?></textarea>
                            <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_informasi_dibutuhkan_pemohon') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Alasan Pengajuan Keberatan</label>
                            <select id="template-contactform-service" name="alasan_pengajuan" class="form-control valid">
                                <option value="Permohonan Informasi Ditolak" <?= ($keberataninformasippid['alasan_pengajuan'] == "Permohonan Informasi Ditolak") ? 'selected' : '' ?>>Permohonan Informasi Ditolak</option>
                                <option value="Informasi Berkala Tidak Disediakan" <?= ($keberataninformasippid['alasan_pengajuan'] == "Informasi Berkala Tidak Disediakan") ? 'selected' : '' ?>>Informasi Berkala Tidak Disediakan</option>
                                <option value="Permintaan Informasi Tidak Ditanggapi" <?= ($keberataninformasippid['alasan_pengajuan'] == "Permintaan Informasi Tidak Ditanggapi") ? 'selected' : '' ?>>Permintaan Informasi Tidak Ditanggapi</option>
                                <option value="Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta" <?= ($keberataninformasippid['alasan_pengajuan'] == "Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta") ? 'selected' : '' ?>>Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta</option>
                                <option value="Permintaan Informasi Tidak Dipenuhi" <?= ($keberataninformasippid['alasan_pengajuan'] == "Permintaan Informasi Tidak Dipenuhi") ? 'selected' : '' ?>>Permintaan Informasi Tidak Dipenuhi</option>
                                <option value="Biaya Yang Dikenakan Tidak Wajar" <?= ($keberataninformasippid['alasan_pengajuan'] == "Biaya Yang Dikenakan Tidak Wajar") ? 'selected' : '' ?>>Biaya Yang Dikenakan Tidak Wajar</option>
                                <option value="Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan" <?= ($keberataninformasippid['alasan_pengajuan'] == "Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan") ? 'selected' : '' ?>>Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="keterangan" rows="2" cols="30"><?= old('keterangan', $keberataninformasippid['keterangan']) ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>
                        </div>


                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div> <!-- End row -->
</div>
<!-- End Contentbar -->
<?= $this->endsection() ?>