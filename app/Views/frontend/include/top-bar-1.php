<style>
    .top-links-item {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    #page-title.page-title-mini {
        padding: 15px 0;
    }

    #page-title {
        position: relative;
        padding: 4rem 0;
        background-color: #fff;
        border-bottom: 1px solid #FFF;
    }
</style>

<?php
$days = [
    'Sunday' => 'Minggu',
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => "Jum'at",
    'Saturday' => 'Sabtu'
];

$months = [
    'January' => 'Januari',
    'February' => 'Februari',
    'March' => 'Maret',
    'April' => 'April',
    'May' => 'Mei',
    'June' => 'Juni',
    'July' => 'Juli',
    'August' => 'Agustus',
    'September' => 'September',
    'October' => 'Oktober',
    'November' => 'November',
    'December' => 'Desember'
];

// Mendapatkan hari ini dalam bahasa Inggris
$dayEnglish = date('l');

// Menerjemahkan hari ini ke bahasa Indonesia
$day = $days[$dayEnglish];

// Mendapatkan tanggal hari ini
$date = date('d');

// Mendapatkan bulan ini dalam bahasa Inggris
$monthEnglish = date('F');

// Menerjemahkan bulan ini ke bahasa Indonesia
$month = $months[$monthEnglish];

// Mendapatkan tahun ini
$year = date('Y');

// Menggabungkan hari, tanggal, bulan, dan tahun
$currentDate = "$day, $date $month $year";

// panggil model profile
$profil = (new \App\Models\Profil())->first();
?>

<div id="top-bar">
    <div class="container clearfix">

        <div class="row justify-content-between">
            <div class="col-12 col-md-auto d-none d-md-flex">

                <!-- Top Links
						============================================= -->
                <!-- <div class="top-links">
                    <ul class="top-links-container">
                        <li class="top-links-item"><a href="javascript:void(0);"><i class="icon-calendar3"></i> <?= $currentDate ?> </strong></a></li>
                        <li class="top-links-item"><a href="javascript:void(0);"><i class="icon-clock"></i> <span id="currentTime"></span></a></li>

                        <li class="top-links-item">
                            <a href="tel:<?= $profil['telepon'] ?? '' ?>">
                                <i class="icon-phone3"></i> <?= $profil['telepon'] ?? '' ?>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="mailto:<?= $profil['email'] ?? '' ?>" class="nott">
                                <i class="icon-envelope2"></i> <?= $profil['email'] ?? '' ?>
                            </a>
                        </li>


                        <li class="top-links-item"><a href="javascript:void(0);" class="nott"><i class="icon-location"></i> <?= $profil['alamat'] ?? '' ?></a></li>
                        <li class="top-links-item">
                            <a href="javascript:void(0);" aria-label="Aksesibilitas untuk pengguna kursi roda">
                                <i class="icon-wheelchair"></i>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="javascript:void(0);" aria-label="Aksesibilitas untuk pengguna tuli">
                                <i class="icon-deaf"></i>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="javascript:void(0);" aria-label="Aksesibilitas untuk pengguna tunanetra">
                                <i class="icon-blind"></i>
                            </a>
                        </li>

                        <li class="top-links-item"><a href="javascript:void(0);" class="nott"><i class="icon-accessible-icon"></i> </a></li>
                    </ul>
                </div> -->

                <div class="top-links">
                    <ul class="top-links-container">
                        <li class="top-links-item">
                            <a href="#"><i class="icon-calendar3"></i> <?= $currentDate ?> </a>
                        </li>
                        <li class="top-links-item">
                            <a href="#"><i class="icon-clock"></i> <span id="currentTime"></span></a>
                        </li>

                        <li class="top-links-item">
                            <a href="tel:<?= $profil['telepon'] ?? '' ?>">
                                <i class="icon-phone3"></i> <?= $profil['telepon'] ?? '' ?>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="mailto:<?= $profil['email'] ?? '' ?>" class="nott">
                                <i class="icon-envelope2"></i> <?= $profil['email'] ?? '' ?>
                            </a>
                        </li>

                        <li class="top-links-item">
                            <a href="#" class="nott"><i class="icon-location"></i> <?= $profil['alamat'] ?? '' ?></a>
                        </li>

                        <li class="top-links-item">
                            <a href="#" aria-label="Aksesibilitas untuk pengguna kursi roda">
                                <i class="icon-wheelchair"></i>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="#" aria-label="Aksesibilitas untuk pengguna tuli">
                                <i class="icon-deaf"></i>
                            </a>
                        </li>
                        <li class="top-links-item">
                            <a href="#" aria-label="Aksesibilitas untuk pengguna tunanetra">
                                <i class="icon-blind"></i>
                            </a>
                        </li>

                        <li class="top-links-item">
                            <a href="#" aria-label="Aksesibilitas untuk pengguna">
                                <i class="icon-accessible-icon"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- .top-links end -->

            </div>


        </div>

    </div>
</div>


<script>
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const currentTime = `${hours}:${minutes}:${seconds}`;
        document.getElementById('currentTime').innerText = currentTime;
    }

    updateTime();
    setInterval(updateTime, 1000);
</script>