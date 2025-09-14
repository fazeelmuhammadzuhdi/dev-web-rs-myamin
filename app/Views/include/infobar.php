<?php
$pesan = (new \App\Models\Pesan())->where('status_baca', 'UR')->where('status', 'UP')->orderBy('tanggal', 'desc')->findAll();
?>

<div id="infobar-notifications-sidebar" class="infobar-notifications-sidebar">
    <div class="
          infobar-notifications-sidebar-head
          d-flex
          w-100
          justify-content-between
        ">
        <h4>Pesan</h4>
        <a href="javascript:void(0)" id="infobar-notifications-close" class="infobar-notifications-close"><img src="<?= base_url(); ?>/assets/images/svg-icon/close.svg" class="img-fluid menu-hamburger-close" alt="close" /></a>
    </div>
    <div class="infobar-notifications-sidebar-body">
        <ul class="nav nav-pills nav-justified" id="infobar-pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-messages-tab" data-toggle="pill" href="#pills-messages" role="tab" aria-controls="pills-messages" aria-selected="true">Messages</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" id="pills-emails-tab" data-toggle="pill" href="#pills-emails" role="tab" aria-controls="pills-emails" aria-selected="false">Emails</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-actions-tab" data-toggle="pill" href="#pills-actions" role="tab" aria-controls="pills-actions" aria-selected="false">Actions</a>
            </li> -->
        </ul>
        <div class="tab-content" id="infobar-pills-tabContent">
            <div class="tab-pane fade show active" id="pills-messages" role="tabpanel" aria-labelledby="pills-messages-tab">
                <ul class="list-unstyled">
                    <?php foreach ($pesan as $item) : ?>
                        <li class="media">
                            <img class="mr-3 align-self-center rounded-circle" src="<?= base_url(); ?>/assets/images/users/girl.svg" alt="Generic placeholder image" />
                            <div class="media-body">
                                <h5>
                                    <?= $item['nama'] ?><span class="badge badge-success">1</span><span class="timing"><?= tanggal_indonesia($item['tanggal']) ?></span>
                                </h5>
                                <p>
                                    <a href="<?= site_url('pesans/edit/' . $item['idpesan']) ?>"><?= limit_words($item['pesan'], 10)  ?></a>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>
    </div>
</div>
<div class="infobar-notifications-sidebar-overlay"></div>

<div class="infobar-settings-sidebar-overlay"></div>