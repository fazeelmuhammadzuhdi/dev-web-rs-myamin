<style>
    /* Other Testimonials 1
------------------------------------*/
    /*Testimonials*/
    .testimonials {
        margin-bottom: 10px;
    }

    .testimonials .testimonial-info {
        color: #1693a5;
        font-size: 16px;
        padding: 0 15px;
        margin-top: 18px;
    }

    .testimonials .testimonial-info span {
        top: 3px;
        position: relative;
    }

    .testimonials .testimonial-info em {
        color: #777;
        display: block;
        font-size: 13px;
    }

    .testimonials .testimonial-info img {
        width: 60px;
        float: left;
        height: 60px;
        padding: 2px;
        margin-right: 15px;
        border: solid 1px #ccc;
    }

    .testimonials .testimonial-author {
        overflow: hidden;
        font-size: 22px !important;
    }

    .testimonials .carousel-arrow {
        top: -65px;
        position: relative;
    }

    .testimonials .carousel-arrow i {
        color: #777;
        padding: 2px;
        min-width: 25px;
        font-size: 20px;
        text-align: center;
        background: #f5f5f5;
    }

    .testimonials .carousel-arrow i:hover {
        color: #fff;
        background: #1693a5;
    }

    .testimonials .carousel-control {
        opacity: 1;
        width: 100%;
        text-align: right;
        text-shadow: none;
        position: absolute;
        filter: Alpha(opacity=100);
        /*For IE*/
    }

    .testimonials .carousel-control.left {
        right: 27px;
        left: auto;
    }

    .testimonials .carousel-control.right {
        right: 0px;
    }

    /*Testimonials v1*/
    .testimonials.testimonials-v1 .item p {
        position: relative;
    }

    .testimonials.testimonials-v1 .item p:after,
    .testimonials.testimonials-v1 .item p:before {
        left: 80px;
        bottom: -20px;
    }

    .testimonials.testimonials-v1 .item p:after {
        border-top: 22px solid;
        border-left: 0 solid transparent;
        border-right: 22px solid transparent;
    }

    /*Testimonials v2*/
    .testimonials.testimonials-v2 .testimonial-info {
        padding: 0 20px;
    }

    .testimonials.testimonials-v2 p {
        padding-bottom: 15px;
    }

    .testimonials.testimonials-v2 .carousel-arrow {
        top: -55px;
    }

    .testimonials.testimonials-v2 .item p:after,
    .testimonials.testimonials-v2 .item p:before {
        left: 8%;
        bottom: 45px;
    }

    .testimonials.testimonials-v2 .item p:after {
        border-top: 20px solid;
        border-left: 25px solid transparent;
        border-right: 0px solid transparent;
    }

    /*General Testimonials v1/v2*/
    .testimonials.testimonials-v1 p,
    .testimonials.testimonials-v2 p {
        padding: 15px;
        font-size: 14px;
        font-style: italic;
        background: #f5f5f5;
    }

    .testimonials.testimonials-v1 .item p:after,
    .testimonials.testimonials-v2 .item p:after {
        width: 0;
        height: 0;
        content: " ";
        display: block;
        position: absolute;
        border-top-color: #f5f5f5;
        border-left-style: inset;
        /*FF fixes*/
        border-right-style: inset;
        /*FF fixes*/
    }

    /*Testimonials Backgrounds*/
    .testimonials-bg-dark .item p,
    .testimonials-bg-default .item p {
        color: #fff;
        font-weight: 200;
    }

    .testimonials-bg-dark .carousel-arrow i,
    .testimonials-bg-default .carousel-arrow i {
        color: #fff;
    }

    /*Testimonials Default*/
    .testimonials-bg-default .item p {
        background: #1693a5;
    }

    .testimonials.testimonials-bg-default .item p:after,
    .testimonials.testimonials-bg-default .item p:after {
        border-top-color: #1693a5;
    }

    .testimonials-bg-default .carousel-arrow i {
        background: #1693a5;
    }

    .testimonials.testimonials-bg-default .carousel-arrow i:hover {
        background: #5fb611;
    }



    .rounded-2x {
        border-radius: 20px;
        padding: 10px;

    }
</style>

<?php
function cleanResponse($text)
{
    // Remove <p> and </p> tags
    $text = str_replace(array('<p>', '</p>'), '', $text);
    return $text;
}
?>

<div id="comments" class="clearfix border-0">

    <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up" data-aos-delay="400">
        <a href="<?= site_url('kontak') ?>" class="button button-xlarge button-circle  button-3d button-dirtygreen">PESAN</a>
    </div>



    <div id="oc-portfolio-sidebar" class="owl-carousel carousel-widget testimonials testimonials-v1 testimonials-bg-default" data-items="1" data-margin="10" data-loop="true" data-nav="false" data-autoplay="3000">
        <?php
        $no = 1;
        if ($pesanNew) :
            foreach ($pesanNew as $r) :
                $resp = ($r['respon'] == '') ? '' : '<br><b class="fw-bold">Jawab : ' . cleanResponse($r['respon']) . ' (oleh: ' . $r['admin'] . ')</b>';
        ?>
                <div class="oc-item" data-aos="zoom-in-up" data-aos-delay="600">
                    <div class="item <?= $no == 1 ? 'active' : '' ?>">
                        <p class="rounded-2x"><?= $r['pesan'] . $resp ?></p>
                        <div class="testimonial-info mb-5">
                            <span class="testimonial-author"><?= $r['nama'] ?><em><?= $r['judul'] ?> - Hari / Tanggal : <?= tanggal_indonesia($r['tanggal'])  ?> </em></span>
                        </div>
                    </div>
                </div>
            <?php
                $no++;
            endforeach;
            ?>

        <?php endif; ?>
    </div>





</div>