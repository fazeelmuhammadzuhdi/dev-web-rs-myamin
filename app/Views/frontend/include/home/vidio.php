<div class="container topmargin-lg clearfix">
    <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
        <a href="<?= site_url('galeri-video') ?>" class="button button-xlarge button-circle  button-3d button-dirtygreen">VIDEO</a>

    </div>


    <div id="portfolio" class="portfolio row grid-container gutter-20" data-layout="fitRows">
        <?php foreach ($videoNew as $index => $item) : ?>
            <article class="portfolio-item col-12 col-md-4 pf-media pf-icons" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                <div class="grid-inner">
                    <div class="portfolio-image">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item lazy" width="560" height="250" data-src="<?= $item['link'] ?>" allowfullscreen title="<?= $item['judul'] ?>"></iframe>
                        </div>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>

        <div class="center mt-3" data-aos="fade-up" data-aos-delay="<?= count($videoNew) * 100 ?>">
            <a href="<?= site_url('galeri-video') ?>" class="button button-large button-circle button-3d button-dirtygreen button-border">View All Video</a>
        </div>
    </div>


</div>