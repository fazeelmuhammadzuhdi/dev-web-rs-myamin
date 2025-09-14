<style>
    .clients-grid .grid-item:hover img {
        transform: scale(1.05);
    }
</style>

<div class="container topmargin-lg clearfix">
    <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
        <button class="button button-xlarge button-circle button-3d button-dirtygreen">Partner Kami</button>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <ul class="clients-grid grid-2 grid-sm-3 grid-md-4 mb-0" data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                <?php foreach ($banner as $index => $item) : ?>
                    <li class="grid-item" data-aos="zoom-in" data-aos-duration="600" data-aos-delay="<?= $index * 200 ?>">
                        <a class="op-08" href="<?= $item['link'] ?>" target="_blank">
                            <img src="<?= base_url('banner/' . $item['gambar']); ?>" alt="<?= $item['judul'] ?>">
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>