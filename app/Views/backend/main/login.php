<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb">

    <meta name="description" content="RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat yang menyediakan berbagai layanan kesehatan berkualitas dan terpercaya.">
    <meta name="keywords" content="RSUD PROF. H. Muhammad Yamin, S.H, Rumah Sakit Umum Daerah Pariaman, Sumatera Barat, Kesehatan, PPID Pariaman, Dokter Pariaman, Rumah Sakit Pariaman, Klinik Pariaman, Medical Check Up, Vaksinasi, Laboratorium Kesehatan, Radiologi, Poliklinik, IGD Pariaman, UGD Pariaman, Instalasi Gawat Darurat, Medical Service, Health Care, Hospital Pariaman, Pariaman Health, Dokter Kami, Galeri Foto, Galeri Video, Fasilitas Umum, Mushalla, Pojok Referensi, Rawat Inap, Visi Misi, Struktur Organisasi, Profil Manajemen, Berita, Agenda, Promosi Kesehatan, Informasi Asuransi, PPID Pelaksana, Informasi Publik">
    <title>RSUD PROF. H. Muhammad Yamin, S.H - <?= isset($title) ? esc($title) : 'RSUD PROF. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah Pariaman, Sumatera Barat ' ?></title>
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://rsudmyamin.sumbarprov.go.id">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <?= $this->include('include/style') ?>
    <!-- End css -->
</head>



<body class="vertical-layout">
    <!-- Start Containerbar -->

    <div id="containerbar" class="containerbar authenticate-bg">
        <!-- Start Container -->

        <div class="container">
            <?= $this->include('include/pesan_login') ?>


            <div class="auth-box login-box">
                <!-- Start row -->
                <div class="row no-gutters align-items-center justify-content-center">
                    <!-- Start col -->
                    <!-- <div class="col-md-6 col-lg-5">
                        <div class="auth-box-left">
                            <div class="card">
                                <div class="card-body">
                                    <h4>RSUD PROF. H. Muhammad Yamin, S.H</h4>
                                    <div class="auth-box-icon">
                                        <img src="<?= base_url(); ?>/assets/images/authentication/auth-box-icon.svg" class="img-fluid" alt="auth-box-icon" />
                                    </div>
                                    <div class="auth-box-logo">
                                        <img src="<?= base_url(); ?>/assets/images/rsud.png" class="img-fluid" alt="logo" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Start end -->
                    <!-- Start col -->
                    <div class="col-md-6 col-lg-5">
                        <!-- Start Auth Box -->
                        <div class="auth-box-right">

                            <div class="card">
                                <div class="card-body">
                                    <form action="<?= base_url('auth/login'); ?>" method="post">
                                        <div class="auth-box-logo mb-4">
                                            <img src="<?= base_url(); ?>/assets/images/rsud.png" class="img-fluid" alt="logo" />
                                        </div>
                                        <!-- <h4 class="text-primary mb-4 text-center">Log in !</h4> -->
                                        <!-- <div class="form-group">
                                            <input type="text" name="username" id="username" class="form-control <?= (session()->getFlashdata('error_username')) ? 'is-invalid' : '' ?>" name="username" placeholder="Masukkan Username" value="<?= old('username') ?>" autofocus>
                                            <?= (session()->getFlashdata('error_username')) ? "<div class='invalid-feedback'>" .
                                                session()->getFlashdata('error_username') . "</div>" : ''; ?>
                                        </div>



                                        <div class="input-group mb-3">
                                            <input type="password" class="form-control <?= (session()->getFlashdata('error_password')) ? 'is-invalid' : '' ?>" placeholder="Masukkan password" aria-label="Password" aria-describedby="password-addon" id="password" name="password">
                                            <div class="input-group-append">
                                                <span class="input-group-text password-toggle">
                                                    <i class="mdi mdi-eye" id="password-toggle"></i>
                                                </span>

                                            </div>
                                            <?= (session()->getFlashdata('error_password')) ? "<div class='invalid-feedback'>" .
                                                session()->getFlashdata('error_password') . "</div>" : ''; ?>
                                        </div> -->


                                        <!-- Input Username -->
                                        <div class="form-group">
                                            <input type="text"
                                                name="username"
                                                id="username"
                                                class="form-control <?= (session()->getFlashdata('error_username')) ? 'is-invalid' : '' ?>"
                                                placeholder="Masukkan Username"
                                                value="<?= old('username') ?>"
                                                autofocus
                                                <?= session()->getFlashdata('remainingTime') ? 'disabled' : '' ?>>
                                            <?= (session()->getFlashdata('error_username')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_username') . "</div>" : ''; ?>
                                        </div>

                                        <!-- Input Password -->
                                        <div class="input-group mb-3">
                                            <input type="password"
                                                class="form-control <?= (session()->getFlashdata('error_password')) ? 'is-invalid' : '' ?>"
                                                placeholder="Masukkan password"
                                                aria-label="Password"
                                                aria-describedby="password-addon"
                                                id="password"
                                                name="password"
                                                <?= session()->getFlashdata('remainingTime') ? 'disabled' : '' ?>>
                                            <div class="input-group-append">
                                                <span class="input-group-text password-toggle">
                                                    <i class="mdi mdi-eye" id="password-toggle"></i>
                                                </span>
                                            </div>
                                            <?= (session()->getFlashdata('error_password')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_password') . "</div>" : ''; ?>
                                        </div>



                                        <button type="submit"
                                            class="btn btn-success btn-lg btn-block font-18"
                                            <?= session()->getFlashdata('remainingTime') ? 'disabled' : '' ?>>
                                            <?= session()->getFlashdata('remainingTime') ? 'Login terkunci selama: ' . session()->getFlashdata('remainingTime') . ' detik' : 'Log in Now' ?>
                                        </button>

                                        <!-- <button type="submit" class="btn btn-success btn-lg btn-block font-18">
                                            Log in Now
                                        </button> -->
                                    </form>
                                    <!-- <div class="login-or hidden">
                                        <h6 class="text-muted">OR</h6>
                                    </div>
                                    <div class="social-login text-center hidden">
                                        <button type="submit" class="btn btn-primary-rgba btn-lg btn-block font-18">
                                            <i class="mdi mdi-facebook mr-2"></i>Log in with
                                            Facebook
                                        </button>
                                        <button type="submit" class="btn btn-danger-rgba btn-lg btn-block font-18">
                                            <i class="mdi mdi-google mr-2"></i>Log in with Google
                                        </button>
                                    </div>
                                    <p class="mb-0 mt-3">
                                        Don't have a account?
                                        <a href="user-register.html">Sign up</a>
                                    </p> -->
                                </div>
                            </div>
                        </div>
                        <!-- End Auth Box -->
                    </div>
                    <!-- End col -->
                </div>
                <!-- End row -->
            </div>
        </div>
        <!-- End Container -->
    </div>
    <!-- End Containerbar -->

    <?php if (session()->getFlashdata('remainingTime')): ?>
        <script>
            let remainingTime = <?= session()->getFlashdata('remainingTime') ?>;
            const btn = document.querySelector('button[type="submit"]');
            const interval = setInterval(() => {
                if (remainingTime > 0) {
                    btn.textContent = 'Login terkunci selama: ' + remainingTime + ' detik';
                    remainingTime--;
                } else {
                    clearInterval(interval);
                    location.reload();
                }
            }, 1000);
        </script>
    <?php endif; ?>


    <script>
        const passwordToggle = document.getElementById('password-toggle');
        const passwordInput = document.getElementById('password');

        passwordToggle.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggle.classList.add('mdi-eye-off');
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('mdi-eye-off');
            }
        });
    </script>


    <!-- Start js -->
    <?= $this->include('include/script') ?>
    <!-- End js -->



</body>

</html>