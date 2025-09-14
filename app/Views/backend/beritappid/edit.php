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
                <a href="<?= site_url('beritappid/' . $tipe) ?>" class="btn btn-warning">Kembali</a>
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
                    <form action="<?= base_url('beritappid/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idberita" value="<?= $beritappid['idberita'] ?>">
                        <input type="hidden" name="tipe" value="<?= $tipe ?>">



                        <div class="form-row mb-3">
                            <div class="form-group col-md-3">
                                <label for="inputEmail4">Kategori Berita</label>
                                <select id="inputState" class="select2 form-control <?= (session()->getFlashdata('error_kategori_id')) ? 'is-invalid' : '' ?>" name="kategori_id">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($kategori as $item) {
                                        $selected = ($item['idkategori'] == $beritappid['kategori_id']) ? 'selected' : '';
                                        echo '<option value="' . $item['idkategori'] . '"' . $selected . '>' .  $item['title'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_kategori_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kategori_id') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-9">
                                <label for="inputAddress">Judul Berita</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul', $beritappid['judul']) ?>">
                                <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_judul') . "</div>" : ''; ?>
                            </div>


                        </div>

                        <div class="form-row mb-3">
                            <div class="form-group col-md-2">
                                <label for="inputAddress">Tanggal Berita</label>
                                <input type="datetime-local" class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>" name="tanggal" value="<?= old('tanggal', $beritappid['tanggal']) ?>">
                                <?= (session()->getFlashdata('error_tanggal')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tanggal') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="inputAddress">Tahun</label>
                                <input placeholder="2014" type="text" class="form-control <?= (session()->getFlashdata('error_tahun')) ? 'is-invalid' : '' ?>" name="tahun" autofocus value="<?= old('tahun', $beritappid['tahun']) ?>">
                                <?= (session()->getFlashdata('error_tahun')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tahun') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress">Jangka</label>
                                <input placeholder="Selama Dibutuhkan" type="text" class="form-control <?= (session()->getFlashdata('error_jangka')) ? 'is-invalid' : '' ?>" name="jangka" autofocus value="<?= old('jangka', $beritappid['jangka']) ?>">
                                <?= (session()->getFlashdata('error_jangka')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_jangka') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress">Penanggung Jawab</label>
                                <input placeholder="Direktur RSUD PROF. H. Muhammad Yamin, S.H" type="text" class="form-control <?= (session()->getFlashdata('error_penanggung_jawab')) ? 'is-invalid' : '' ?>" name="penanggung_jawab" autofocus value="<?= old('penanggung_jawab', $beritappid['penanggung_jawab']) ?>">
                                <?= (session()->getFlashdata('error_penanggung_jawab')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_penanggung_jawab') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="inputAddress">Tempat</label>
                                <input placeholder="RSUD PROF. H. Muhammad Yamin, S.H" type="text" class="form-control <?= (session()->getFlashdata('error_tempat')) ? 'is-invalid' : '' ?>" name="tempat" autofocus value="<?= old('tempat', $beritappid['tempat']) ?>">
                                <?= (session()->getFlashdata('error_tempat')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tempat') . "</div>" : ''; ?>
                            </div>


                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-8">
                                <label for="inputAddress">Link Berita</label>
                                <input placeholder="Masukkan Link" type="text" class="form-control <?= (session()->getFlashdata('error_link')) ? 'is-invalid' : '' ?>" name="link" autofocus value="<?= old('link', $beritappid['link']) ?>">
                                <?= (session()->getFlashdata('error_link')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_link') . "</div>" : ''; ?>

                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputAddress">Filetype</label>
                                <select id="inputState" class="form-control <?= (session()->getFlashdata('error_filetype')) ? 'is-invalid' : '' ?>" name="filetype">
                                    <option selected="" value="">--Pilih Type File--</option>
                                    <option value="archive.png" <?= ($beritappid['filetype'] == 'archive.png') ? 'selected' : '' ?>>Archive</option>
                                    <option value="docs.png" <?= ($beritappid['filetype'] == 'docs.png') ? 'selected' : '' ?>>Docs</option>
                                    <option value="excel.png" <?= ($beritappid['filetype'] == 'excel.png') ? 'selected' : '' ?>>Excel</option>
                                    <option value="image.png" <?= ($beritappid['filetype'] == 'image.png') ? 'selected' : '' ?>>Image</option>
                                    <option value="pdf.png" <?= ($beritappid['filetype'] == 'pdf.png') ? 'selected' : '' ?>>PDF</option>
                                    <option value="ppt.png" <?= ($beritappid['filetype'] == 'ppt.png') ? 'selected' : '' ?>>PPT</option>
                                    <option value="word.png" <?= ($beritappid['filetype'] == 'word.png') ? 'selected' : '' ?>>Word</option>

                                </select>
                                <?= (session()->getFlashdata('error_filetype')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_filetype') . "</div>" : ''; ?>
                            </div>

                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Status Berita</label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="inlineRadio1" value="Y" <?= ($beritappid['status'] == 'Y') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio1">Y</label>
                                </div>
                                <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_status') . "</div>" : ''; ?>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" type="radio" name="status" id="inlineRadio2" value="T" <?= ($beritappid['status'] == 'T') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="inlineRadio2">T</label>

                                </div>
                                <?= (session()->getFlashdata('error_status')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_status') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Konten Berita</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_konten')) ? 'is-invalid' : '' ?>" id="summernote" name="konten" placeholder="Inputkan Konten Album"><?= old('konten', $beritappid['konten']) ?></textarea>
                            <?= (session()->getFlashdata('error_konten')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_konten') . "</div>" : ''; ?>
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

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

<?= $this->endsection() ?>