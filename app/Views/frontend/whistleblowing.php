<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1><?= $title ?></h1>
        <p><span style="color: #ff0000">* Wajib Diisi</span></p>
    </div>


</section>

<section id="content">
    <div class="content-wrap" style="padding: 20px 0 !important;">
        <div class="container">

            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-12">
                    <form class="mb-0" action="<?= base_url('laporawbs/save'); ?>" method="POST">
                        <?= csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="template-contactform-name">Nama Pelapor<small class="text-danger">*</small></label>
                                <input type="text" id="template-contactform-name" name="nama_pelapor" class="sm-form-control <?= (session()->getFlashdata('error_nama_pelapor')) ? 'is-invalid' : '' ?>" autofocus value="<?= old('nama_pelapor') ?>" />
                                <?= (session()->getFlashdata('error_nama_pelapor')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama_pelapor') . "</div>" : ''; ?>

                            </div>

                            <div class="col-md-4 form-group">
                                <label for="template-contactform-email">Email Pelapor<small class="text-danger">*</small></label>
                                <input type="email" id="template-contactform-email" name="email_pelapor" class="email sm-form-control <?= (session()->getFlashdata('error_email_pelapor')) ? 'is-invalid' : '' ?>" value="<?= old('email_pelapor') ?>" />
                                <?= (session()->getFlashdata('error_email_pelapor')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_email_pelapor') . "</div>" : ''; ?>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="template-contactform-email">Telepon Pelapor <small class="text-danger">*</small></label>
                                <input type="number" id="template-contactform-email" name="telepon_pelapor" class="sm-form-control <?= (session()->getFlashdata('error_telepon_pelapor')) ? 'is-invalid' : '' ?>" value="<?= old('telepon_pelapor') ?>" />
                                <?= (session()->getFlashdata('error_telepon_pelapor')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_telepon_pelapor') . "</div>" : ''; ?>
                            </div>



                            <div class="col-md-12 form-group">
                                <label for="template-contactform-subject">Tindakan / Perbuatan Yang Dilaporkan <small class="text-danger">*</small></label><br>
                                <?php foreach ($tindakans as $index => $tindakan): ?>
                                    <div class="form-check">
                                        <input class="form-check-input <?= (session()->getFlashdata('error_tindakan')) ? 'is-invalid' : '' ?>"
                                            type="checkbox" name="tindakan[]" id="tindakan<?= $index ?>" value="<?= $tindakan ?>">
                                        <label class="form-check-label text-danger" for="tindakan<?= $index ?>">
                                            <?= $tindakan ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                                <?= (session()->getFlashdata('error_tindakan')) ? "<div class='invalid-feedback d-block'>" .
                                    session()->getFlashdata('error_tindakan') . "</div>" : ''; ?>
                            </div>


                            <div class="col-md-4 form-group">
                                <label for="template-contactform-name">Nama Terlapor<small class="text-danger">*</small></label>
                                <input type="text" id="template-contactform-name" name="nama_terlapor" class="sm-form-control <?= (session()->getFlashdata('error_nama_terlapor')) ? 'is-invalid' : '' ?>" value="<?= old('nama_terlapor') ?>" />
                                <?= (session()->getFlashdata('error_nama_terlapor')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama_terlapor') . "</div>" : ''; ?>

                            </div>

                            <div class="col-md-4 form-group">
                                <label for="template-contactform-email">Waktu Kejadian<small class="text-danger">*</small></label>
                                <input type="date" id="template-contactform-email" name="waktu_kejadian" class="sm-form-control <?= (session()->getFlashdata('error_waktu_kejadian')) ? 'is-invalid' : '' ?>" value="<?= old('waktu_kejadian') ?>" />
                                <?= (session()->getFlashdata('error_waktu_kejadian')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_waktu_kejadian') . "</div>" : ''; ?>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="template-contactform-email">Lokasi Kejadian <small class="text-danger">*</small></label>
                                <input type="text" id="template-contactform-email" name="lokasi_kejadian" class="sm-form-control <?= (session()->getFlashdata('error_lokasi_kejadian')) ? 'is-invalid' : '' ?>" value="<?= old('lokasi_kejadian') ?>" />
                                <?= (session()->getFlashdata('error_lokasi_kejadian')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_lokasi_kejadian') . "</div>" : ''; ?>
                            </div>



                            <div class="col-12 form-group">
                                <label for="template-contactform-message">Kronologis Kejadian <small class="text-danger">*</small></label>
                                <textarea class=" sm-form-control <?= (session()->getFlashdata('error_kronologis')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="kronologis" rows="4" cols="30"><?= old('kronologis') ?></textarea>
                                <?= (session()->getFlashdata('error_kronologis')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kronologis') . "</div>" : ''; ?>
                            </div>

                            <div class="col-12 form-group">
                                <button class="button button-3d m-0" type="submit">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>
                </div>
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