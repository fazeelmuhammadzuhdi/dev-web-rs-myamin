<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Manajemen Profil</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Profil</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('manajemenprofils') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Manajemen Profil</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('manajemenprofils/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idmanajemenprofil" value="<?= $manajemenprofil['idmanajemenprofil'] ?>">


                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Nama Bagian Manajemen </label>
                                <select id="inputState" class="select2 form-control <?= (session()->getFlashdata('error_manajemen_id')) ? 'is-invalid' : '' ?>" name="manajemen_id">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($manajemen as $item) {
                                        $selected = ($item['idmanajemen'] == $manajemenprofil['manajemen_id']) ? 'selected' : '';
                                        echo '<option value="' . $item['idmanajemen'] . '"' . $selected . '>' .  $item['nama'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_manajemen_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_manajemen_id') . "</div>" : ''; ?>
                            </div>


                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Nama Manajemen</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" autofocus value="<?= old('nama', $manajemenprofil['nama']) ?>">
                                <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nama') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Jabatan</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_jabatan')) ? 'is-invalid' : '' ?>" name="jabatan" value="<?= old('jabatan', $manajemenprofil['jabatan']) ?>">
                                <?= (session()->getFlashdata('error_jabatan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_jabatan') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">NIP</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_nip')) ? 'is-invalid' : '' ?>" name="nip" autofocus value="<?= old('nip', $manajemenprofil['nip']) ?>">
                                <?= (session()->getFlashdata('error_nip')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_nip') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Tempat Lahir</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_tempatlahir')) ? 'is-invalid' : '' ?>" name="tempatlahir" autofocus value="<?= old('tempatlahir', $manajemenprofil['tempatlahir']) ?>">
                                <?= (session()->getFlashdata('error_tempatlahir')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tempatlahir') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Tanggal Lahir</label>
                                <input type="date" class="form-control <?= (session()->getFlashdata('error_tanggallahir')) ? 'is-invalid' : '' ?>" name="tanggallahir" value="<?= old('tanggallahir', $manajemenprofil['tanggallahir']) ?>">
                                <?= (session()->getFlashdata('error_tanggallahir')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tanggallahir') . "</div>" : ''; ?>
                            </div>
                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Pendidikan</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_pendidikan')) ? 'is-invalid' : '' ?>" name="pendidikan" autofocus value="<?= old('pendidikan', $manajemenprofil['pendidikan']) ?>">
                                <?= (session()->getFlashdata('error_pendidikan')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_pendidikan') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Pangkat</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_pangkat')) ? 'is-invalid' : '' ?>" name="pangkat" autofocus value="<?= old('pangkat', $manajemenprofil['pangkat']) ?>">
                                <?= (session()->getFlashdata('error_pangkat')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_pangkat') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputAddress">Foto</label>
                                <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar">
                                <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputAddress">Riwayat Pendidikan</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_profil_singkat')) ? 'is-invalid' : '' ?>" name="profil_singkat" autofocus><?= old('profil_singkat', $manajemenprofil['profil_singkat']) ?></textarea>
                                <?= (session()->getFlashdata('error_profil_singkat')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_profil_singkat') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputAddress">Riwayat Pendidikan</label>
                                <textarea class="form-control editor <?= (session()->getFlashdata('error_riwayat_pendidikan')) ? 'is-invalid' : '' ?>" name="riwayat_pendidikan" autofocus><?= old('riwayat_pendidikan', $manajemenprofil['riwayat_pendidikan']) ?></textarea>
                                <?= (session()->getFlashdata('error_riwayat_pendidikan')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_riwayat_pendidikan') . "</div>" : ''; ?>
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
<!-- End Contentbar -->
<!-- select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url(); ?>/assets/tinymce/tiny_mce.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>


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
    });
</script>

<?= $this->endsection() ?>