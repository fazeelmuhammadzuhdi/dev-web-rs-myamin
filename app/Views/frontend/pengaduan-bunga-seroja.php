<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1><?= $title ?></h1>
    </div>


</section>

<section id="content">
    <div class="content-wrap" style="padding: 0;">
        <div class="container">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-12">

                    <form class="mb-0" action="<?= base_url('bunga-seroja/save'); ?>" method="POST">
                        <?= csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="template-contactform-name">Nama <small>*</small></label>
                                <input type="text" id="template-contactform-name" name="nama" class="sm-form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                            </div>

                            <div class="col-md-6 form-group">
                                <label for="template-contactform-email">Nomor Handphone <small>*</small></label>
                                <input type="number" id="template-contactform-email" name="nomor_hp" class="sm-form-control <?= (session()->getFlashdata('error_nomor_hp')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_nomor_hp')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nomor_hp') . "</div>" : ''; ?>
                            </div>


                            <div class="w-100"></div>

                            <div class="col-md-12 form-group">
                                <label for="template-contactform-subject">Alamat Rumah <small>*</small></label>
                                <input type="text" id="template-contactform-subject" name="alamat" class=" sm-form-control <?= (session()->getFlashdata('error_alamat')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_alamat')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_alamat') . "</div>" : ''; ?>
                            </div>



                            <div class="w-100"></div>

                            <div class="col-12 form-group">
                                <label for="template-contactform-message">Kronologi Kejadian <small>*</small></label>
                                <textarea class="sm-form-control <?= (session()->getFlashdata('error_kronologi')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="kronologi" rows="10" cols="30"></textarea>
                                <?= (session()->getFlashdata('error_kronologi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kronologi') . "</div>" : ''; ?>
                            </div>

                            <div class="col-12 form-group">
                                <button class="button button-3d mb-5 m-0" type="submit">Kirim</button>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- .postcontent end -->

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
            timer: 2500,
        });
    <?php elseif (session()->getFlashdata('error')) : ?>
        Swal.fire({
            title: "Oops...",
            text: "<?= session()->getFlashdata('error'); ?>",
            icon: "error",
            timer: 2500,
        });
    <?php endif; ?>
</script>

<?= $this->endsection() ?>