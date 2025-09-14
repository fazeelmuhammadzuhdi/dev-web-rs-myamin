<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Fasilitas</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fasilitas</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('fasilitasumum') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Fasilitas</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('fasilitasumum/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        <div class="form-group">
                            <label for="inputAddress">Nama</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" autofocus value="<?= old('nama') ?>">
                            <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Keterangan</label>
                            <textarea class="editor form-control <?= (session()->getFlashdata('error_keterangan')) ? 'is-invalid' : '' ?>" name=" keterangan" placeholder="Inputkan keterangan Referensi"><?= old('keterangan') ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_keterangan') . "</div>" : ''; ?>

                        </div>


                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="Y" checked>
                                <label class="form-check-label" for="inlineRadio1">Y</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="T">
                                <label class="form-check-label" for="inlineRadio2">T</label>

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

<script src="<?= base_url(); ?>/assets/tinymce/tiny_mce.js"></script>

<script>
    // Menginisialisasi TinyMCE pada semua elemen dengan class 'editor'
    tinymce.init({
        selector: 'textarea.editor',
        width: "100%",
        height: "400",
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
    });
</script>
<!-- End Contentbar -->
<?= $this->endsection() ?>