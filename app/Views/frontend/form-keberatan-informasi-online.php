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
        <h2>FORMULIR KEBERATAN ATAS PERMOHONAN INFORMASI PPID RSUD PROF. H. Muhammad Yamin, S.H</h2>
    </div>


</section>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-9">
                    <form class="mb-0" action="<?= base_url('keberataninformasi/save'); ?>" method="POST">
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
                                    <td>Pekerjaan</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" id="template-contactform-email" name="pekerjaan" value="<?= old('pekerjaan') ?>" class="sm-form-control <?= (session()->getFlashdata('error_pekerjaan')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_pekerjaan')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_pekerjaan') . "</div>" : ''; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Nomor Telepon / HP</td>
                                    <td>:</td>
                                    <td>
                                        <input type="number" id="template-contactform-name" value="<?= old('nomor_telepon_pemohon') ?>" name="nomor_telepon_pemohon" class="sm-form-control <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? 'is-invalid' : '' ?>" />
                                        <?= (session()->getFlashdata('error_nomor_telepon_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_nomor_telepon_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tujuan Penggunaan Informasi</td>
                                    <td>:</td>
                                    <td>
                                        <textarea class="sm-form-control <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="informasi_dibutuhkan_pemohon" rows="2" cols="30"><?= old('informasi_dibutuhkan_pemohon') ?></textarea>
                                        <?= (session()->getFlashdata('error_informasi_dibutuhkan_pemohon')) ? "<div class='invalid-feedback'>" .
                                            session()->getFlashdata('error_informasi_dibutuhkan_pemohon') . "</div>" : ''; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Alasan Pengajuan Keberatan</td>
                                    <td>:</td>
                                    <td>
                                        <select id="template-contactform-service" name="alasan_pengajuan" class="sm-form-control valid">
                                            <option value="Permohonan Informasi Ditolak">Permohonan Informasi Ditolak</option>
                                            <option value="Informasi Berkala Tidak Disediakan">Informasi Berkala Tidak Disediakan</option>
                                            <option value="Permintaan Informasi Tidak Ditanggapi">Permintaan Informasi Tidak Ditanggapi</option>
                                            <option value="Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta">Permintaan Informasi Ditanggapi Tidak Sebagaimana Yang Diminta</option>
                                            <option value="Permintaan Informasi Tidak Dipenuhi">Permintaan Informasi Tidak Dipenuhi</option>
                                            <option value="Biaya Yang Dikenakan Tidak Wajar">Biaya Yang Dikenakan Tidak Wajar</option>
                                            <option value="Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan">Informasi Disampaikan Melebihi Jangka Waktu Yang Ditentukan</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">&nbsp;</td>
                                    <td>
                                        <button class="button button-3d m-0 mb-2 mt-2" type="submit">KIRIM PERMINTAAN KEBERATAN INFORMASI</button>
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