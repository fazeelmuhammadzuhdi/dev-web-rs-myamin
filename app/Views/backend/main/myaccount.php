<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>

<!-- Start Breadcrumbbar -->
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
    <!-- <div class="card m-b-30">
        <div class="card-header">
            <h5 class="card-title mb-0">My Profile</h5>
        </div>
        <div class="card-body">
            <div class="profilebox pt-4 text-center">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="#" class="btn btn-success-rgba font-18"><i class="feather icon-edit"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <img src="assets/images/users/profile.svg" class="img-fluid" alt="profile">
                    </li>
                    <li class="list-inline-item">
                        <a href="#" class="btn btn-danger-rgba font-18"><i class="feather icon-trash"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </div> -->
    <div class="card m-b-30">
        <div class="card-header">
            <h5 class="card-title mb-0">Edit Profile Informations</h5>
        </div>
        <div class="card-body">
            <form action="<?= base_url('save'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>

                <input type="hidden" name="id" value="<?= $user['iduser'] ?? '' ?>">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $user['username'] ?>" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="useremail">Nama</label>
                        <input type="text" class="form-control" id="useremail" name="nama" value="<?= $user['nama'] ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="usermobile">Role</label>
                        <input type="text" class="form-control" id="usermobile" name="role"
                            value="<?= ($user['role'] == 'US') ? 'User' : (($user['role'] == 'SU') ? 'Super Admin' : 'Unknown') ?>"
                            disabled>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="userbirthdate">Last Login</label>
                        <input type="datetime" class="form-control" id="userlastlogin" name="userlastlogin" value="<?= $user['userlastlogin'] ?>" disabled>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="userpassword">Password</label>
                        <input type="password" class="form-control <?= (session()->getFlashdata('error_password')) ? 'is-invalid' : '' ?>" id="userpassword" name="password" autofocus>
                        <?= (session()->getFlashdata('error_password')) ? "<div class='invalid-feedback'>" .
                            session()->getFlashdata('error_password') . "</div>" : ''; ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="userconfirmedpassword">Confirmed Password</label>
                        <input type="password" class="form-control <?= (session()->getFlashdata('error_confirmpassword')) ? 'is-invalid' : '' ?>" id="userconfirmedpassword" name="confirmpassword">
                        <?= (session()->getFlashdata('error_confirmpassword')) ? "<div class='invalid-feedback'>" .
                            session()->getFlashdata('error_confirmpassword') . "</div>" : ''; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary-rgba font-16"><i class="feather icon-save mr-2"></i>Update</button>
            </form>
        </div>
    </div>
</div>


<?= $this->endsection() ?>