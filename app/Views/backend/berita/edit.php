<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Berita</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Berita</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('beritas') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Albumlist</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('beritas/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idberita" value="<?= $beritas['idberita'] ?>">


                        <div class="form-group">
                            <label for="inputEmail4">Kategori Berita</label>
                            <select id="inputState"
                                class="select2 form-control <?= (session()->getFlashdata('error_kategori_id')) ? 'is-invalid' : '' ?>"
                                name="kategori_id">
                                <option selected="" value="">--Pilih--</option>
                                <?php foreach ($kategori as $item) {
                                    $selected = ($item['idkategori'] == $beritas['kategori_id']) ? 'selected' : '';
                                    echo '<option value="' . $item['idkategori'] . '"' . $selected . '>' .  $item['title'] . '</option>';
                                } ?>
                            </select>
                            <?= (session()->getFlashdata('error_kategori_id')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_kategori_id') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Judul Berita</label>
                            <input type="text"
                                class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>"
                                name="judul" autofocus value="<?= old('judul', $beritas['judul']) ?>">
                            <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_judul') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tanggal Berita</label>
                            <input type="date"
                                class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>"
                                name="tanggal" id="tanggal" value="<?= old('tanggal', $beritas['tanggal']) ?>">
                            <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Status Berita</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>"
                                        type="radio" name="status" id="inlineRadio1" value="PB"
                                        <?= ($beritas['status'] == 'PB') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio1">PB</label>
                                </div>
                                <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_status') . "</div>" : ''; ?>

                                <div class="form-check form-check-inline">
                                    <input
                                        class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>"
                                        type="radio" name="status" id="inlineRadio2" value="UP"
                                        <?= ($beritas['status'] == 'UP') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">UP</label>

                                </div>
                                <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_status') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Konten Berita</label>

                            <textarea
                                class="form-control <?= (session()->getFlashdata('error_konten')) ? 'is-invalid' : '' ?>"
                                id="konten" name="konten" placeholder="Inputkan Konten Album">
                            <?= old('konten', $beritas['konten']) ?>
                            
                                <?php
                                /*
                                if (!empty($beritas['konten'])) {
                                    $dom = new DOMDocument();
                                    @$dom->loadHTML($beritas['konten']);
                                    $images = $dom->getElementsByTagName('img');
                                    foreach ($images as $image) {
                                        $src = $image->getAttribute('src');
                                        $new_src = base_url('uploadGaleri/' . basename($src));
                                        $image->setAttribute('src', $new_src);
                                    }
                                    echo $dom->saveHTML();
                                } else {
                                    echo htmlspecialchars(old('konten'));
                                }
                                */
                                ?>
                            </textarea>
                            <?= (session()->getFlashdata('error_konten')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_konten') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <label for="inputAddress">Gambar</label>
                            <input type="file" accept="image/*"
                                class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>"
                                name="gambar">
                            <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
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
    document.getElementById('uploadGambar').addEventListener('change', function(event) {
        var formData = new FormData();
        formData.append('gambar', this.files[0]);

        fetch('<?= base_url('beritas/uploadimage'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('imagePath').value = data.filePath;
                    // tinymce.activeEditor.execCommand('mceInsertContent', false, '<img src="' + data.filePath + '" />');


                    alert('Gambar berhasil diupload. Path telah disalin.');
                } else {
                    alert('Gagal mengupload gambar. ' + data.error);
                }
            })
            .catch(error => console.error('Error:', error));
    });

    function copyToClipboard() {
        var copyText = document.getElementById("imagePath");
        copyText.select();
        document.execCommand("copy");
        alert("Path telah disalin ke clipboard: " + copyText.value);
    }
</script>



<script>
    tinymce.init({
        mode: "exact",
        elements: "konten",
        width: "100%",
        height: "450",
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


<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<?= $this->endsection() ?>