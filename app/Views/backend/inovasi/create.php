<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Inovasi</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Inovasi</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('inovasis') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Inovasi</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('inovasis/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label for="inputPassword4">Judul Inovasi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul') ?>" placeholder="Masukkan Judul Inovasi">
                                <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_judul') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="inputPassword4">Tahun</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_tahun')) ? 'is-invalid' : '' ?>" name="tahun" value="<?= old('tahun') ?>" placeholder="2025">
                                <?= (session()->getFlashdata('error_tahun')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tahun') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputPassword4">Bentuk Inovasi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_jenis')) ? 'is-invalid' : '' ?>" name="jenis" value="<?= old('jenis') ?>" placeholder="Contoh : Inovasi Pelayanan Publik">
                                <?= (session()->getFlashdata('error_jenis')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_jenis') . "</div>" : ''; ?>
                            </div>
                        </div>


                        <div class="form-row mt-4 mb-3">

                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Tahapan Inovasi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_tahapan')) ? 'is-invalid' : '' ?>" name="tahapan" autofocus value="<?= old('tahapan') ?>" placeholder="Implementasi">
                                <?= (session()->getFlashdata('error_tahapan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tahapan') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Digital (Jenis Inovasi) </label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_digital')) ? 'is-invalid' : '' ?>" name="digital" value="<?= old('digital') ?>" placeholder="Digital / Non Digital">
                                <?= (session()->getFlashdata('error_digital')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_digital') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Inisiator Inovasi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_inisiator')) ? 'is-invalid' : '' ?>" name="inisiator" value="<?= old('inisiator') ?>" placeholder="Contoh : Inovasi Pelayanan Publik">
                                <?= (session()->getFlashdata('error_inisiator')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_inisiator') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Uji Cobba Inovasi</label>
                                <input type="date" class="form-control <?= (session()->getFlashdata('error_ujicoba')) ? 'is-invalid' : '' ?>" name="ujicoba" value="<?= old('ujicoba') ?>" placeholder="Contoh : Inovasi Pelayanan Publik">
                                <?= (session()->getFlashdata('error_ujicoba')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_ujicoba') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Implementasi Inovasi</label>
                                <input type="date" class="form-control <?= (session()->getFlashdata('error_implementasi')) ? 'is-invalid' : '' ?>" name="implementasi" value="<?= old('implementasi') ?>" placeholder="Contoh : Inovasi Pelayanan Publik">
                                <?= (session()->getFlashdata('error_implementasi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_implementasi') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Urusan Inovasi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_urusan')) ? 'is-invalid' : '' ?>" name="urusan" value="<?= old('urusan') ?>" placeholder="Contoh : Inovasi Pelayanan Publik">
                                <?= (session()->getFlashdata('error_urusan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_urusan') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Panduan Teknisi Inovasi</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_panduan_teknis')) ? 'is-invalid' : '' ?>" name="panduan_teknis" autofocus><?= old('panduan_teknis') ?></textarea>
                                <?= (session()->getFlashdata('error_panduan_teknis')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_panduan_teknis') . "</div>" : ''; ?>


                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputPassword4">Link Youtube</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_link_youtube')) ? 'is-invalid' : '' ?>" name="link_youtube" autofocus><?= old('link_youtube') ?></textarea>
                                <?= (session()->getFlashdata('error_link_youtube')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_link_youtube') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-12">
                                <label for="inputAddress">Tujuan Inovasi</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_tujuan')) ? 'is-invalid' : '' ?>" name="tujuan" autofocus><?= old('tujuan') ?></textarea>
                                <?= (session()->getFlashdata('error_tujuan')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_tujuan') . "</div>" : ''; ?>

                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputAddress">Manfaat Inovasi</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_manfaat')) ? 'is-invalid' : '' ?>" name="manfaat" autofocus><?= old('manfaat') ?></textarea>
                                <?= (session()->getFlashdata('error_manfaat')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_manfaat') . "</div>" : ''; ?>

                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputAddress">Rancang Bangun Inovasi</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_rancang')) ? 'is-invalid' : '' ?>" name="rancang" autofocus><?= old('rancang') ?></textarea>
                                <?= (session()->getFlashdata('error_rancang')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_rancang') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="inputAddress">Hasil Inovasi</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_hasil')) ? 'is-invalid' : '' ?>" name="hasil" autofocus><?= old('hasil') ?></textarea>
                                <?= (session()->getFlashdata('error_hasil')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_hasil') . "</div>" : ''; ?>
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

<!-- select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<script src="<?= base_url(); ?>/assets/tinymce/tiny_mce.js"></script>

<script>
    // Menginisialisasi TinyMCE pada semua elemen dengan class 'editor'
    tinymce.init({
        selector: 'textarea.editor',
        width: "100%",
        height: "100",
        theme: "advanced",
        relative_urls: false,
        remove_script_host: false,
        plugins: "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: false,
        toolbar: 'fontsizeselect',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt 60pt 72pt',
    });
</script>

<!-- End Contentbar -->
<?= $this->endsection() ?>