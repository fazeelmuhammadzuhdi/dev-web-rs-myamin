<style>
    /* .video-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        justify-content: center;
    }

    .showcase-target {
        width: 100%;
        max-width: 500px;
    } */

    .video-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .showcase-target {
        flex: 1 1 calc(50% - 20px);
        /* Bagi 2 kolom */
        max-width: 500px;
        /* Sesuaikan ukuran maksimal */
    }
</style>

<div class="container topmargin-lg clearfix">
    <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
        <a href="<?= site_url('galeri-video') ?>" class="button button-xlarge button-circle  button-3d button-dirtygreen">VIDEO</a>

    </div>


    <div id="portfolio" class="portfolio row grid-container gutter-20" data-layout="fitRows">
        <!-- <?php foreach ($videoNew as $index => $item) : ?>
            <article class="portfolio-item col-12 col-sm-6 pf-media pf-icons" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="grid-inner">
                    <div class="portfolio-image">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item lazy" width="560" height="315" data-src="<?= $item['link'] ?>" allowfullscreen title="<?= $item['judul'] ?>"></iframe>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?> -->

        <!-- <?php foreach ($videoNew as $item) : ?>
            <div class="showcase-target showcase-target-active mb-4">
                <a href="<?= $item['link'] ?>" data-fancybox="gallery" data-type="iframe">
                    <img src="<?= base_url('video/' . $item['thumbnail']) ?>" alt="<?= $item['judul'] ?>">
                </a>
            </div>
        <?php endforeach; ?> -->

        <div class="video-container">
            <?php foreach ($videoNew as $item) : ?>
                <div class="showcase-target">
                    <a href="<?= $item['link'] ?>" data-fancybox="gallery" data-type="iframe">
                        <img src="<?= base_url('video/' . $item['thumbnail']) ?>" alt="<?= $item['judul'] ?>">
                    </a>
                </div>
            <?php endforeach; ?>
        </div>


        <div class="center mt-3" data-aos="fade-up" data-aos-delay="<?= count($videoNew) * 100 ?>">
            <a href="<?= site_url('galeri-video') ?>" class="button button-large button-circle button-3d button-dirtygreen button-border">View All Video</a>
        </div>
    </div>


</div>