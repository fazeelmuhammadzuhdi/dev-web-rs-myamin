<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Referensi</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Referensi</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('referensis') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input Referensi</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('referensis/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>


                        <div class="form-row mb-3">

                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Judul</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" name="judul" autofocus value="<?= old('judul') ?>">
                                <?= (session()->getFlashdata('error_judul')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_judul') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Pengarang</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_pengarang')) ? 'is-invalid' : '' ?>" name="pengarang" value="<?= old('pengarang') ?>">
                                <?= (session()->getFlashdata('error_pengarang')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_pengarang') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Bahasa</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_bahasa')) ? 'is-invalid' : '' ?>" name="bahasa" value="<?= old('bahasa') ?>">
                                <?= (session()->getFlashdata('error_bahasa')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_bahasa') . "</div>" : ''; ?>
                            </div>


                        </div>


                        <div class="form-row mb-3">

                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Kategori Referensi</label>
                                <select id="inputState" class="form-control <?= (session()->getFlashdata('error_kategori')) ? 'is-invalid' : '' ?>" name="kategori">
                                    <option selected="" value="" disabled>--Pilih--</option>
                                    <option value="ap">AP</option>
                                    <option value="pdk">PDK</option>
                                    <option value="plk">PLK</option>
                                    <option value="mnj">MNJ</option>

                                </select>
                                <?= (session()->getFlashdata('error_kategori')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kategori') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Penerbit</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_penerbit')) ? 'is-invalid' : '' ?>" name="penerbit" value="<?= old('penerbit') ?>">
                                <?= (session()->getFlashdata('error_penerbit')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_penerbit') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Tahun</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_tahun')) ? 'is-invalid' : '' ?>" name="tahun" value="<?= old('tahun') ?>">
                                <?= (session()->getFlashdata('error_tahun')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_tahun') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Foto Referensi</label>
                                <input type="file" class="form-control <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar" accept="image/*">
                                <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4">Link Referensi</label>
                                <input type="text" class="form-control <?= (session()->getFlashdata('error_link')) ? 'is-invalid' : '' ?>" name="link" value="<?= old('link') ?>">
                                <?= (session()->getFlashdata('error_link')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_link') . "</div>" : ''; ?>
                            </div>
                        </div>
                        <div class="form-row mb-3">

                            <div class="form-group col-md-12">
                                <label for="inputAddress">Deskripsi</label>
                                <textarea class="form-control <?= (session()->getFlashdata('error_deskripsi')) ? 'is-invalid' : '' ?>" id="summernote" name="deskripsi" placeholder="Inputkan Deskripsi Referensi"><?= old('deskripsi') ?></textarea>
                                <?= (session()->getFlashdata('error_deskripsi')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_deskripsi') . "</div>" : ''; ?>

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
<?= $this->endsection() ?>