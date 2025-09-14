<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<style>
    .content-wrap {
        position: relative;
        padding: 50px 0;
        overflow: hidden;
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h2>FORMULIR PERMINTAAN INFORMASI PPID RSUD PROF. H. Muhammad Yamin, S.H</h2>
    </div>


</section>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-9">
                    <form class="mb-0" action="<?= base_url('pesan-ppid'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td><b>Nama Pemohon Informasi</b></td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="template-contactform-name" name="nama_pemohon_informasi" value="<?= old('nama_pemohon_informasi') ?>" class="sm-form-control <?= (session()->getFlashdata('error_nama_pemohon_informasi')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_nama_pemohon_informasi')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_nama_pemohon_informasi') . "</div>" : ''; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Alamat Pemohon Informasi</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="template-contactform-name" name="alamat_pemohon" value="<?= old('alamat_pemohon') ?>" class="sm-form-control <?= (session()->getFlashdata('error_alamat_pemohon')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_alamat_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_alamat_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Nomor Telepon</td>
                                    <td>:</td>
                                    <td>
                                        <input type="number" id="template-contactform-name" value="<?= old('nomor_telepon_pemohon') ?>" name="nomor_telepon_pemohon" class="sm-form-control <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_nomor_telepon_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>:</td>
                                    <td>
                                        <input type="email" id="template-contactform-name" value="<?= old('email_pemohon') ?>" name="email_pemohon" class="sm-form-control <?= (session()->getFlashdata('error_email_pemohon')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_email_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_email_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rincian Informasi Yang dibutuhkan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea class="sm-form-control <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="informasi_dibutuhkan_pemohon" rows="2" cols="30"><?= old('informasi_dibutuhkan_pemohon') ?></textarea>
                                        <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_informasi_dibutuhkan_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Alasan Permintaan</td>
                                    <td>:</td>
                                    <td>
                                        <textarea class="sm-form-control <?= (session()->getFlashdata('error_alasan_permintaan_pemohon')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="alasan_permintaan_pemohon" rows="2" cols="30"><?= old('alasan_permintaan_pemohon') ?></textarea>
                                        <?= (session()->getFlashdata('error_alasan_permintaan_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_alasan_permintaan_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Upload KTP</td>
                                    <td>:</td>
                                    <td>
                                        <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_ktp')) ? 'is-invalid' : '' ?>" name="ktp" value="<?= old('ktp') ?>">
                                        <?= (session()->getFlashdata('error_ktp')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_ktp') . "</div>" : ''; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Cara Memperoleh Informasi</td>
                                    <td>:</td>
                                    <td>
                                        <select id="template-contactform-service" name="cara_memperoleh_informasi" class="sm-form-control valid">
                                            <option value="Langsung">Langsung</option>
                                            <option value="Website">Website</option>
                                            <option value="Email">Email</option>
                                            <option value="Fax">Fax</option>
                                            <option value="Via POS">Via POS</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Format Bahan Informasi</td>
                                    <td>:</td>
                                    <td>
                                        <select id="template-contactform-service" name="format_bahan_informasi" class="sm-form-control valid">
                                            <option value="Tercetak">Tercetak</option>
                                            <option value="Terekam">Terekam</option>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Cara Mengirim Bahan Informasi</td>
                                    <td>:</td>
                                    <td>
                                        <select id="template-contactform-service" name="cara_mengirim_bahan_informasi" class="sm-form-control valid">
                                            <option value="Langsung">Langsung</option>
                                            <option value="Via POS">Via POS</option>
                                            <option value="Email">Email</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td>
                                        <button class="button button-3d m-0 mb-2 mt-2" type="submit">KIRIM PERMINTAAN PERMOHONAN INFORMASI PUBLIK</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>

                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section>

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flash Data Message -->
<script>
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            title: "Terima Kasih!",
            text: "<?= session()->getFlashdata('success'); ?>",
            icon: "success",
            timer: 1500,
        });
    <?php elseif (session()->getFlashdata('error')) : ?>
        Swal.fire({
            title: "Oops...",
            text: "<?= session()->getFlashdata('error'); ?>",
            icon: "error",
            timer: 1500,
        });
    <?php endif; ?>
</script>

<?= $this->endsection() ?>