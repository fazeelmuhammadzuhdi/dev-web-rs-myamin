<div class="container clearfix">
    <div class="heading-block center border-bottom-0 mx-auto" style="max-width: 640px" data-aos="fade-up">
        <a href="<?= site_url('galeri-foto') ?>" class="button button-xlarge button-circle  button-3d button-dirtygreen">GALERI</a>

    </div>

    <div class="post-grid row">
        <?php foreach ($galeri as $index => $item) : ?>
            <div class="entry col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="<?= $index * 500 ?>">
                <div class="grid-inner shadow-sm card rounded-5 img-hover-wrap">
                    <a href="<?= base_url('albumlist/' . $item['gambar']) ?>" data-lightbox="image">
                        <img src="<?= base_url('albumlist/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>" class="card-img-top img-fluid" style="height: 250px; object-fit: cover; object-position: center;">
                    </a>
                    <div class="p-4">
                        <div class="entry-title">
                            <h4 class="nott ls0 h5"><a href="<?= site_url('galeri-foto/' . $item['slug']) ?>"><?= $item['judul'] ?></a></h4>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
   
</div>