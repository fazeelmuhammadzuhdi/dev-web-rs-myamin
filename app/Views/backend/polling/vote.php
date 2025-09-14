<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Vote Polling</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vote Polling</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('pollings') ?>" class="btn btn-warning">Kembali</a>
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
                    <h5 class="card-title">Default Radios</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($polling as $poll) : ?>
                        <h1><?= $poll['pertanyaan'] ?></h1>
                        <form action="<?= base_url('pollings/voting'); ?>" method="post">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="idpolling" value="<?= $poll['idpolling'] ?>">
                            <div class="form-check">
                                <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $poll['idpolling'] ?>-opa" value="opa">
                                <label class="form-check-label" for="vote-<?= $poll['idpolling'] ?>-opa">
                                    <?= $poll['opa'] ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $poll['idpolling'] ?>-opb" value="opb">
                                <label class="form-check-label" for="vote-<?= $poll['idpolling'] ?>-opb">
                                    <?= $poll['opb'] ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $poll['idpolling'] ?>-opc" value="opc">
                                <label class="form-check-label" for="vote-<?= $poll['idpolling'] ?>-opc">
                                    <?= $poll['opc'] ?>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $poll['idpolling'] ?>-opd" value="opd">
                                <label class="form-check-label" for="vote-<?= $poll['idpolling'] ?>-opd">
                                    <?= $poll['opd'] ?>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mb-5">Submit</button>

                        </form>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


    </div> <!-- End row -->
</div>


<!-- End Contentbar -->
<?= $this->endsection() ?>