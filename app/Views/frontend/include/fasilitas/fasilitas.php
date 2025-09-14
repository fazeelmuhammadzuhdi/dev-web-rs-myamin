<?php
if ($namaFasilitas == "ruang-pertemuan") :
    // Ambil konten deskripsi
    $deskripsi = $fasilitas['keterangan'];

    // Ambil semua elemen <img> dari deskripsi
    preg_match_all('/<img[^>]+>/i', $deskripsi, $matches);

    // Jika ada gambar ditemukan, tampilkan dalam loop
    if (!empty($matches[0])): ?>
        <div class="real-estate owl-carousel image-carousel carousel-widget bottommargin-lg" data-speed="100" data-margin="10" data-nav="true" data-loop="true" data-pagi="false" data-items-xs="1" data-items-sm="1" data-items-md="2" data-items-lg="3" data-items-xl="3">

            <?php foreach ($matches[0] as $imgTag): ?>
                <div class="oc-item">
                    <div class="real-estate-item">
                        <div class="real-estate-item-image">
                            <?= $imgTag; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
<?php endif;
endif;
?>


<?php
if ($namaFasilitas == "mushalla") :
    // Ambil konten deskripsi
    $deskripsi = $fasilitas['keterangan'];

    // Ambil semua elemen <img> dari deskripsi
    preg_match_all('/<img[^>]+>/i', $deskripsi, $matches);

    // Jika ada gambar ditemukan, tampilkan dalam loop
    if (!empty($matches[0])): ?>
        <div class="real-estate owl-carousel image-carousel carousel-widget bottommargin-lg" data-speed="100" data-margin="10" data-nav="true" data-loop="true" data-pagi="false" data-items-xs="1" data-items-sm="1" data-items-md="2" data-items-lg="3" data-items-xl="3">

            <?php foreach ($matches[0] as $imgTag): ?>
                <div class="oc-item">
                    <div class="real-estate-item">
                        <div class="real-estate-item-image">
                            <?= $imgTag; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
<?php endif;
endif;
?>