<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>


<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title"><?= $title ?></h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">Form Input <?= $title ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('tentangs/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="id" value="<?= $tentang['id'] ?? '' ?>">

                        <div class="form-group">
                            <label for="inputAddress">Title Tentang</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="title_tentang" autofocus value="<?= old('title_tentang', $tentang['title_tentang'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_title_tentang')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_title_tentang') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Konten Tentang</label>
                            <textarea class="editor form-control <?= (session()->getFlashdata('error_konten_tentang')) ? 'is-invalid' : '' ?>" name="konten_tentang" placeholder="Inputkan konten_tentang"><?= old('konten_tentang', htmlspecialchars($tentang['konten_tentang'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_konten_tentang')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_konten_tentang') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <label for="inputAddress">Title Sejarah</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_title_sejarah')) ? 'is-invalid' : '' ?>" name="title_sejarah" autofocus value="<?= old('title_sejarah', $tentang['title_sejarah'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_title_sejarah')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_title_sejarah') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Struktur Organisasi</label>
                            <textarea class="editor form-control <?= (session()->getFlashdata('error_konten_sejarah')) ? 'is-invalid' : '' ?>" name="konten_sejarah" placeholder="Inputkan Konten"><?= old('konten_sejarah', htmlspecialchars($tentang['konten_sejarah'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_konten_sejarah')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_konten_tentang') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Informasi Ketersedian Tempat Tidur</label>
                            <textarea class="editor form-control <?= (session()->getFlashdata('error_ketersediaan_tempat_tidur')) ? 'is-invalid' : '' ?>" name="ketersediaan_tempat_tidur" placeholder="Inputkan Konten"><?= old('ketersediaan_tempat_tidur', htmlspecialchars($tentang['ketersediaan_tempat_tidur'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_ketersediaan_tempat_tidur')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_konten_tentang') . "</div>" : ''; ?>
                        </div>






                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->


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

<?= $this->endsection() ?>