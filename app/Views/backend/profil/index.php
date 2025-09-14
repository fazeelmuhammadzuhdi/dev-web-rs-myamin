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
                    <form action="<?= base_url('profils/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="idprofil" value="<?= $profil['idprofil'] ?? '' ?>">

                        <div class="form-group">
                            <label for="inputAddress">Nama</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" autofocus value="<?= old('nama', $profil['nama'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nama') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Visi</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_visi')) ? 'is-invalid' : '' ?>" name="visi" placeholder="Inputkan visi"><?= old('visi', $profil['visi']) ?></textarea>
                            <?= (session()->getFlashdata('error_visi')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_visi') . "</div>" : ''; ?>
                        </div>



                        <div class="form-group">
                            <label for="inputAddress">Misi</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_misi')) ? 'is-invalid' : '' ?>" name="misi" placeholder="Inputkan misi"><?= old('misi', htmlspecialchars($profil['misi'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_misi')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_misi') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Motto</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_motto')) ? 'is-invalid' : '' ?>" name="motto" value="<?= old('motto', $profil['motto'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_motto')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_motto') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tugas Pokok Dan Fungsi</label>
                            <textarea class="form-control <?= (session()->getFlashdata('error_misi')) ? 'is-invalid' : '' ?>" name="tugas" placeholder="Inputkan tugas" rows="15"><?= old('tugas', htmlspecialchars($profil['tugas'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_tugas')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_tugas') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Alamat</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_alamat')) ? 'is-invalid' : '' ?>" name="alamat" value="<?= old('alamat', $profil['alamat'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_alamat')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_alamat') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">No Telepon</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_telepon')) ? 'is-invalid' : '' ?>" name="telepon" value="<?= old('telepon', $profil['telepon'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_telepon')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_telepon') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">No Fax</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_fax')) ? 'is-invalid' : '' ?>" name="fax" value="<?= old('fax', $profil['fax'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_fax')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_fax') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Email</label>
                            <input type="email" class="form-control <?= (session()->getFlashdata('error_email')) ? 'is-invalid' : '' ?>" name="email" value="<?= old('email', $profil['email'] ?? '') ?>">

                            <?= (session()->getFlashdata('error_email')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_email') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Gambar</label>
                            <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar">
                            <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
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


<script>
    $(document).ready(function() {
        $('.summernote').summernote();
    });
</script>


<?= $this->endsection() ?>