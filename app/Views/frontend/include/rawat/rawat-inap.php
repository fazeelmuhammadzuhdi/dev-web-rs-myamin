<?php
if ($namaRawat == "anak") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "bedah") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "icu") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "indera") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "jantung") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "jiwa") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "kebidanan") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "neonatologi") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "paru") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "penyakit-dalam") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "syaraf-neurologi") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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
if ($namaRawat == "vip-nan-tongga") :
    // Ambil konten deskripsi
    $deskripsi = $rawatInap['keterangan'];

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