<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Kontak</h1>
    </div>


</section>

<section id="content">
    <div class="content-wrap">
        <div class="container">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-9">

                    <div class="heading-block fancy-title border-bottom-0 title-bottom-border">
                        <h3><span class="text-black">Form Pesan</span></h3>
                    </div>

                    <form class="mb-0" action="<?= base_url('kontak/save'); ?>" method="POST">
                        <?= csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="template-contactform-name">Nama <small>*</small></label>
                                <input type="text" id="template-contactform-name" name="nama" class="sm-form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                            </div>

                            <div class="col-md-6 form-group">
                                <label for="template-contactform-email">Email <small>*</small></label>
                                <input type="email" id="template-contactform-email" name="email" class=" email sm-form-control <?= (session()->getFlashdata('error_email')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_email')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_email') . "</div>" : ''; ?>
                            </div>


                            <div class="w-100"></div>

                            <div class="col-md-12 form-group">
                                <label for="template-contactform-subject">Judul <small>*</small></label>
                                <input type="text" id="template-contactform-subject" name="judul" class=" sm-form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" />
                                <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_judul') . "</div>" : ''; ?>
                            </div>



                            <div class="w-100"></div>

                            <div class="col-12 form-group">
                                <label for="template-contactform-message">Pesan <small>*</small></label>
                                <textarea class=" sm-form-control <?= (session()->getFlashdata('error_pesan')) ? 'is-invalid' : '' ?>" id="template-contactform-message" name="pesan" rows="4" cols="30"></textarea>
                                <?= (session()->getFlashdata('error_pesan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_pesan') . "</div>" : ''; ?>
                            </div>

                            <div class="col-12 form-group">
                                <button class="button button-3d m-0" type="submit">Kirim Pesan</button>
                            </div>
                        </div>
                    </form>

                    <!-- Post Content
						============================================= -->
                    <div class="postcontent bothsidebar col-lg-12">

                        <div class="col-lg-12 min-vh-50">
                            <div class="gmap h-100" data-address="Pariaman, Indonesia" data-markers="[{address: &quot;Pariaman, Indonesia&quot;, html: &quot;<div class=\&quot;p-2\&quot; style=\&quot;width: 300px;\&quot;><h4 class=\&quot;mb-2\&quot;>Hi! We are <span>Envato!</span></h4><p class=\&quot;mb-0\&quot; style=\&quot;font-size:1rem;\&quot;>Our mission is to help people to <strong>earn</strong> and to <strong>learn</strong> online. We operate <strong>marketplaces</strong> where hundreds of thousands of people buy and sell digital goods every day.</p></div>&quot;, icon:{ image: &quot;images/icons/map-icon-red.png&quot;, iconsize: [32, 39], iconanchor: [32,39] } }]">
                                <div style="height: 100%; width: 100%;">
                                    <div class="gm-err-container">
                                        <div class="gm-err-content">
                                            <iframe frame src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.57644518253!2d100.12328807358139!3d-0.6308976352580986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2fd4e300098c868b%3A0xf30c6cd74d6ad812!2sRSUD%20Pariaman!5e0!3m2!1sid!2sid!4v1719453234304!5m2!1sid!2sid" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d3989.2669504011174!2d100.38690847368555!3d-0.9525496353497478!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1700390114638!5m2!1sid!2sid" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                            </iframe> -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- Google Map End -->

                    </div><!-- .postcontent end -->

                </div>
                <!-- .postcontent end -->



                <!-- Sidebar
						============================================= -->
                <div class="sidebar col-lg-3">

                    <!-- include profil -->


                    <?= $this->include('frontend/include/profil') ?>

                    <!-- include polling -->

                    <div class="heading-block fancy-title border-bottom-0 title-bottom-border mt-4">
                        <h4><span class="text-black">Hasil Polling</span></h4>
                    </div>
                    <?= $this->include('frontend/include/polling') ?>



                    <div class="widget border-0 pt-0">

                        <a href="https://www.facebook.com/rsudprofmyamin.sh" target="_blank" class="social-icon si-small si-dark si-facebook">
                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a>

                        <a href="https://www.youtube.com/@rsudpariaman1818" target="_blank" class="social-icon si-small si-dark si-youtube">
                            <i class="icon-youtube"></i>
                            <i class="icon-youtube"></i>
                        </a>

                        <a href="https://www.instagram.com/rsudprofmyamin.sh/" target="_blank" class="social-icon si-small si-dark si-instagram">
                            <i class="icon-instagram"></i>
                            <i class="icon-instagram"></i>
                        </a>

                        <a href="https://www.tiktok.com/@rsud_profmyaminsh" target="_blank" class="social-icon si-small si-dark si-tiktok">
                            <i class="icon-tiktok"></i>
                            <i class="icon-tiktok"></i>
                        </a>



                    </div>

                </div><!-- .sidebar end -->
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