<?php
$countPesanBelumDibaca = (new \App\Models\Pesan())->where('status_baca', 'UR')->where('status', 'UP')->countAllResults();
?>
<div class="topbar-mobile">
    <div class="row align-items-center">
        <div class="col-md-12">
            <div class="mobile-logobar">
                <a href="" class="mobile-logo"><img src="<?= base_url(); ?>/assets/images/logo.svg" class="img-fluid" alt="logo" /></a>
            </div>
            <div class="mobile-togglebar">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <div class="topbar-toggle-icon">
                            <a class="topbar-toggle-hamburger" href="javascript:void();">
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/horizontal.svg" class="img-fluid menu-hamburger-horizontal" alt="horizontal" />
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/verticle.svg" class="img-fluid menu-hamburger-vertical" alt="verticle" />
                            </a>
                        </div>
                    </li>
                    <li class="list-inline-item">
                        <div class="menubar">
                            <a class="menu-hamburger" href="javascript:void();">
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/collapse.svg" class="img-fluid menu-hamburger-collapse" alt="collapse" />
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close" alt="close" />
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Start Topbar -->
<div class="topbar">
    <!-- Start row -->
    <div class="row align-items-center">
        <!-- Start col -->
        <div class="col-md-12 align-self-center">
            <div class="togglebar">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <div class="menubar">
                            <a class="menu-hamburger" href="javascript:void();">
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/collapse.svg" class="img-fluid menu-hamburger-collapse" alt="collapse" />
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close" alt="close" />
                            </a>
                        </div>
                    </li>
                    <li class="list-inline-item">
                        <div class="searchbar">
                            <form>
                                <div class="input-group">
                                    <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2" />
                                    <div class="input-group-append">
                                        <button class="btn" type="submit" id="button-addon2">
                                            <img src="<?= base_url(); ?>/assets/images/svg-icon/search.svg" class="img-fluid" alt="search" />
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="infobar">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <div class="notifybar">
                            <a href="javascript:void(0)" id="infobar-notifications-open" class="infobar-icon">
                                <img src="<?= base_url(); ?>/assets/images/svg-icon/notifications.svg" class="img-fluid" alt="notifications" />
                                <span class="badge badge-danger pull-right"><?= $countPesanBelumDibaca ?></span>
                            </a>
                        </div>
                    </li>

                </ul>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>