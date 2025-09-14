<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input User</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('users') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Form Input User</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('users/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="iduser" value="<?= $user['iduser'] ?>">


                        <div class="form-group">
                            <label for="inputAddress">Username</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_username')) ? 'is-invalid' : '' ?>" name="username" autofocus value="<?= old('username', $user['username']) ?>">
                            <?= (session()->getFlashdata('error_username')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_username') . "</div>" : ''; ?>

                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Nama</label>
                            <input type="text" class="form-control <?= (session()->getFlashdata('error_nama')) ? 'is-invalid' : '' ?>" name="nama" value="<?= old('nama', $user['nama']) ?>">
                            <?= (session()->getFlashdata('error_nama')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_nama') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Password Baru</label>
                            <input type="password" class="form-control <?= (session()->getFlashdata('error_password')) ? 'is-invalid' : '' ?>" name="password">
                            <?= (session()->getFlashdata('error_password')) ? "<div class='invalid-feedback'>" .
                                session()->getFlashdata('error_password') . "</div>" : ''; ?>

                        </div>


                        <div class="form-group">
                            <div>
                                <label for="inputAddress">Role</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_role')) ? 'is-invalid' : '' ?>" required type="radio" name="role" id="inlineRadio1" value="US" <?= ($user['role'] == 'US') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">US</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_role')) ? 'is-invalid' : '' ?>" required type="radio" name="role" id="inlineRadio2" value="SU" <?= ($user['role'] == 'SU') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">SU</label>

                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <label for="inputAddress">Status</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_role')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio1" value="A" <?= ($user['status'] == 'A') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio1">Aktif</label> <br>

                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input <?= (session()->getFlashdata('error_role')) ? 'is-invalid' : '' ?>" required type="radio" name="status" id="inlineRadio2" value="B" <?= ($user['status'] == 'B') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="inlineRadio2">Tidak Aktif</label>

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