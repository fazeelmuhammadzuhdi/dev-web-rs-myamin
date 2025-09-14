<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .story-timeline {
        position: relative;
        padding-top: 60px;
    }

    .story-timeline>.story-timeline-line {
        position: absolute;
        display: block;
        top: 0;
        left: 10px;
        bottom: 0;
        width: 5px;
        border-radius: 0 0 6px 6px;
        background-image: linear-gradient(to bottom, #FFE640 0%, #fE9603 100%);
    }

    .story-timeline .story-timeline-dots {
        position: absolute;
        display: block;
        top: 0;
        left: 26px;
        z-index: 1;
        width: 26px;
        height: 36px;
        margin-left: -13px;
        background-image: linear-gradient(#FFE640 0%, #fE9603 100%);
        border: 6px solid #FFF;
        border-radius: 50%;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: border-color .3s ease;
    }

    .story-timeline>.row {
        position: relative;
        padding-left: 40px;
    }

    .story-timeline>.row:hover .story-timeline-dots {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        background: #555;
    }



    @media (min-width: 768px) {
        .story-timeline>.story-timeline-line {
            left: 50%;
            transform: translateX(-50%);
        }

        .story-timeline .story-timeline-dots {
            left: 50%;
            transform: translateX(-50%);
            margin-left: 0;
        }

        .story-timeline>.row {
            margin-right: -50px;
            margin-left: -50px;
            padding-left: 0;
        }

        .story-timeline>.row>.col,
        .story-timeline>.row>[class*="col-"] {
            padding-right: 50px;
            padding-left: 50px;
        }
    }

    [id^="particles-"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 50% 50%;
    }
</style>


<div class="section bg-transparent m-0 pt-5 pb-0">
    <div class="container mw-md">
        <div class="text-md-center">
            <h3 class="bg-color d-inline-block px-4 py-2 text-white mb-0 rounded-1" style="background-color: #1693a5 !important;"><?= $tentangKami['title_sejarah'] ?> </h3>
        </div>
        <div class="story-timeline">
            <div class="clear mt-4"></div>
            <div class="story-timeline-line"></div>

            <?php $index = 0; ?>
            <?php foreach ($sejarah as $item): ?>
                <div class="row justify-content-md-center my-5">
                    <div class="col-md-6 <?= $index % 2 == 0 ? 'text-md-end' : 'order-md-2' ?>">
                        <h4 class="bg-secondary d-inline-block px-3 py-1 text-white mb-0 rounded-1 mb-3"><?= esc($item['tahun']); ?></h4>
                    </div>
                    <div class="story-timeline-dots"></div>
                    <div class="col-md-6 <?= $index % 2 == 0 ? '' : 'text-md-end' ?>">
                        <h3 class="mb-3"><?= esc($item['judul']); ?></h3>
                        <p class="text-black fs-5"><?= esc($item['keterangan']); ?></p>
                    </div>
                </div>
                <?php $index++; ?>
            <?php endforeach; ?>

            <div class="clear mb-5"></div>
        </div>
    </div>
</div>

<?= $this->endsection() ?>