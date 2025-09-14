 <div class="heading-block fancy-title border-bottom-0 title-bottom-border mt-4">
     <h3><span class="text-black">Galeri Foto</span></h3>
 </div>

 <div class="container topmargin">
     <div class="row clearfix ">
         <?php foreach ($galeri as $item) : ?>
             <div class="col-md-4 col-sm-2 mb-3">
                 <div class="img-hover-wrap">
                     <div class="img-hover-card">
                         <img src="<?= base_url('albumlist/' . $item['gambar']) ?>" alt="<?= $item['judul'] ?>">
                     </div>
                     <div class="entry-title">
                         <h4 class="nott ls0 h5"><a href="<?= site_url('galeri-foto/' . $item['slug']) ?>"><?= $item['judul'] ?></a></h4>
                     </div>

                 </div>

             </div>
         <?php endforeach; ?>
     </div>
 </div>