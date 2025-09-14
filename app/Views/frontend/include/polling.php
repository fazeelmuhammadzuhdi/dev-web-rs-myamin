<p class="pertanyaan" data-aos="zoom-in-up" data-aos-delay="500"><?= ucwords($polling['pertanyaan'] ?? '')  ?></p>

<style>
    .polling-option {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        font-family: Arial, sans-serif;
    }

    .polling-option span.label {
        width: 100px;
        /* Tentukan lebar tetap untuk label */
    }

    .progress {
        flex-grow: 1;
        margin: 0 10px;
        height: 20px;
        background-color: #dcdde1;
        border-radius: 5px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        background-color: #6e81dc;
        text-align: right;
        padding-right: 5px;
        color: #fff;
        line-height: 20px;
        /* align text vertically in the center */
        border-radius: 5px 0 0 5px;
    }

    .progress-percentage {
        width: 50px;
        /* Tentukan lebar tetap untuk persentase */
        text-align: right;
        white-space: nowrap;
    }

    @media (max-width: 768px) {
        .pertanyaan {
            padding: 7px !important;
        }

    }
</style>

<?php if (!empty($polling['options'])) : ?>
    <?php foreach ($polling['options'] as $option => $details) : ?>
        <div class="polling-option pertanyaan" data-aos="zoom-in-up" data-aos-delay="500">
            <span class="label"><?= $details['label'] ?></span>

            <div class="progress">
                <div class="progress-bar" role="progressbar"
                    aria-valuenow="<?= number_format($details['percentage'], 2) ?>"
                    aria-valuemin="0" aria-valuemax="100"
                    aria-label="Progress for <?= $details['label'] ?>: <?= number_format($details['percentage'], 2) ?>%"
                    style="width: <?= number_format($details['percentage'], 2) ?>%;">
                    &nbsp;
                </div>
            </div>
            <span class="progress-percentage"><?= number_format($details['percentage'], 2) ?> %</span>
        </div>
    <?php endforeach; ?>
<?php else : ?>
    <div class="no-data">
        Tidak Ada Data Polling Yang Tersedia.
    </div>
<?php endif; ?>



<p class="mt-3 pertanyaan" data-aos="zoom-in-up" data-aos-delay="500">
    <i class="icon-exclamation-circle"></i> <span>Ikuti Vote <a href="#block-modal-contact-<?= $vote[0]['idpolling'] ?? '' ?>" data-lightbox="inline">Disini</a></span>
</p>


<div data-target="#block-modal-contact"></div>

<?php if (!empty($vote)) : ?>

    <?php foreach ($vote as $item) : ?>
        <!-- Modal -->
        <div class="modal1 mfp-hide" id="block-modal-contact-<?= $item['idpolling'] ?>">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-white rounded">

                    <div class="row m-0">

                        <div class="col-lg-12 p-4">
                            <h2><?= $item['pertanyaan'] ?? '' ?></h2>
                            <form action="<?= base_url('pollings/voting'); ?>" method="post">
                                <?= csrf_field(); ?>
                                <input type="hidden" name="idpolling" value="<?= $item['idpolling'] ?>">
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $item['idpolling'] ?>-opa" value="opa">
                                    <label class="form-check-label" for="vote-<?= $item['idpolling'] ?>-opa">
                                        <?= $item['opa'] ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $item['idpolling'] ?>-opb" value="opb">
                                    <label class="form-check-label" for="vote-<?= $item['idpolling'] ?>-opb">
                                        <?= $item['opb'] ?>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $item['idpolling'] ?>-opc" value="opc">
                                    <label class="form-check-label" for="vote-<?= $item['idpolling'] ?>-opc">
                                        <?= $item['opc'] ?>
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input position-static" type="radio" name="vote" id="vote-<?= $item['idpolling'] ?>-opd" value="opd">
                                    <label class="form-check-label" for="vote-<?= $item['idpolling'] ?>-opd">
                                        <?= $item['opd'] ?>
                                    </label>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>


    <?php endforeach; ?>
<?php endif; ?>