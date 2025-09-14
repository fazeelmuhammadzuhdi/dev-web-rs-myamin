<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Tambahkan CSS untuk styling -->
<style>
    .card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #chartjs-pie-chart {
        width: 100% !important;
        height: auto !important;
    }

    .card-statistic {
        border: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
        transition: transform 0.3s ease-in-out;
        background-color: #fff;
        /* Add white background */
        border: 1px solid #ddd;
        /* Add light gray border */
    }

    .card-statistic:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        /* Add deeper shadow on hover */
    }

    .statistic-icon {
        margin-right: 20px;

    }

    .statistic-info {
        flex: 1;
        padding-left: 15px;
        /* Add padding to info container */
    }

    .statistic-value {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
    }

    .card-title {
        font-size: 18px;
        margin-bottom: 10px;
        color: #666;
    }
</style>



<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Dashboard</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>

                </ol>
            </div>
        </div>

    </div>
</div>
<!-- End Breadcrumbbar -->

<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">

        <div class="col-md-12 col-lg-12 col-xl-8">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="col-12">
                        <h5 class="card-title mb-0">Jumlah Berita Berdasarkan Kategori</h5>

                    </div>
                </div>
                <div class="card-body">

                    <div class="col-md-12">
                        <canvas id="kategori-chart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class=" col-md-12 col-lg-12 col-xl-6">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">

                            <h5 class="card-title mb-0">Jumlah Berita Berdasarkan Kategori</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <?php $colors = ['rgba(220, 12, 36, 0.8)', 'rgba(13, 240, 13, 0.8)', 'rgba(212, 255, 0, 1)', 'rgba(15, 150, 227, 1)']; ?>
                            <?php foreach ($kategoriBerita as $index => $kategori) : ?>
                                <p style="background-color: <?= $colors[$index % count($colors)] ?>; padding: 5px 10px; display: inline-block; margin-bottom: 5px; color: white; font-weight: bold;">
                                    <?= $kategori['jumlah'] ?> - <?= $kategori['kategori_title'] ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                        <div class="col-md-5">
                            <canvas height="200" id="chartjs-pie-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="col-md-12 col-lg-12 col-xl-4">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h5 class="card-title mb-0">Jumlah Pengunjung Hari Ini : <?= $dailyVisitsToday ?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <canvas id="daily-visits-chart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="col-12">
                        <h5 class="card-title mb-0">Jumlah Pengunjung Per Bulan Pada Tahun <?= date('Y') ?> </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <canvas id="pengunjung-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="col-12">
                        <h5 class="card-title mb-0">Grafik Postingan Berita Pada Tahun <?= date('Y') ?> </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <canvas id="berita-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12 col-xl-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <div class="col-12">
                        <h5 class="card-title mb-0">Jumlah Orang Yang Melakukan Voting Per Bulan Pada Tahun <?= date('Y') ?> </h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <canvas id="voting-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="card ecommerce-widget m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <p class="dash-analytic-icon">
                                <i class="feather icon-users primary-rgba text-primary"></i>
                            </p>
                        </div>
                        <div class="col-8 text-right">
                            <h5 class="font-16 mb-0">Dokter</h5>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="text-center mb-0"><?= $dokter ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="card ecommerce-widget m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <p class="dash-analytic-icon">
                                <i class="feather icon-users warning-rgba text-warning"></i>
                            </p>
                        </div>
                        <div class="col-8 text-right">
                            <h5 class="font-16 mb-0">Spesialis</h5>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="text-center mb-0"><?= $spesialis ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="card ecommerce-widget m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <p class="dash-analytic-icon">
                                <i class="feather icon-image success-rgba text-success"></i>
                            </p>
                        </div>
                        <div class="col-8 text-right">
                            <h5 class="font-16 mb-0">Poliklinik</h5>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="text-center mb-0"><?= $poliklinik ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-6 col-xl-3">
            <div class="card ecommerce-widget m-b-30">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <p class="dash-analytic-icon">
                                <i class="
                            feather
                            icon-dollar-sign
                            danger-rgba
                            text-danger
                          "></i>
                            </p>
                        </div>
                        <div class="col-8 text-right">
                            <h5 class="font-16 mb-0">Video</h5>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h3 class="text-center mb-0"><?= $video ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> -->

    <div class="row">
        <!-- Start col -->
        <div class="col-md-6 col-lg-3 col-xl-3">
            <div class="card card-statistic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="statistic-icon">
                            <img src="<?= base_url('dashboard/dokter.png') ?>" alt="" width="80px">
                        </div>
                        <div class="statistic-info">
                            <h5 class="card-title">Dokter</h5>
                            <h2 class="statistic-value"><?= $dokter ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
        <!-- Start col -->
        <div class="col-md-6 col-lg-3 col-xl-3">
            <div class="card card-statistic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="statistic-icon">
                            <img src="<?= base_url('dashboard/spesialis.png') ?>" alt="" width="80px">
                        </div>
                        <div class="statistic-info">
                            <h5 class="card-title">Spesialis</h5>
                            <h2 class="statistic-value"><?= $spesialis ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
        <!-- Start col -->
        <div class="col-md-6 col-lg-3 col-xl-3">
            <div class="card card-statistic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="statistic-icon">
                            <img src="<?= base_url('dashboard/poliklinik.png') ?>" alt="" width="80px">
                        </div>
                        <div class="statistic-info">
                            <h5 class="card-title">Poliklinik</h5>
                            <h2 class="statistic-value"><?= $poliklinik ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
        <!-- Start col -->
        <div class="col-md-6 col-lg-3 col-xl-3">
            <div class="card card-statistic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="statistic-icon">
                            <img src="<?= base_url('dashboard/video.png') ?>" alt="" width="80px">
                        </div>
                        <div class="statistic-info">
                            <h5 class="card-title">Video</h5>
                            <h2 class="statistic-value"><?= $video ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>


</div>

<!-- Script untuk menampilkan Chart -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('chartjs-pie-chart').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?= json_encode(array_column($kategoriBerita, 'kategori_title')) ?>,
                datasets: [{
                    data: <?= json_encode(array_column($kategoriBerita, 'jumlah')) ?>,
                    backgroundColor: [
                        'rgba(220, 12, 36, 0.8)', // Warna untuk 'Agenda'
                        'rgba(13, 240, 13, 0.8)', // Warna untuk 'Berita'
                        'rgba(212, 255, 0, 1)', // Warna untuk 'Informasi Asuransi'
                        'rgba(15, 150, 227, 1)' // Warna untuk 'Promosi Kesehatan'
                    ],
                    borderColor: [
                        'rgba(220, 12, 36, 0.8)',
                        'rgba(13, 240, 13, 0.8)',
                        'rgba(212, 255, 0, 1)',
                        'rgba(15, 150, 227, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Sembunyikan legend untuk menghemat ruang
                    }
                }
            }
        });
    });
</script>



<!-- pengunjung -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari controller
        const labels = <?= json_encode($labelsVisit) ?>;
        const counts = <?= json_encode($countVisit) ?>;

        // Konfigurasi chart
        const ctx = document.getElementById('pengunjung-chart').getContext('2d');
        const pengunjungChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pengunjung ',
                    data: counts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {

                scales: {
                    x: {
                        border: {
                            color: 'rgba(54, 162, 235, 1)'
                        }
                    },
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]

                }
            }
        });
    });
</script>


<!-- voting -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari controller
        const labels = <?= json_encode($labelsVoting) ?>;
        const counts = <?= json_encode($countsVoting) ?>;

        // Warna untuk setiap bar
        const backgroundColors = [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(201, 203, 207, 0.2)',
            'rgba(255, 105, 180, 0.2)',
            'rgba(139, 69, 19, 0.2)',
            'rgba(0, 255, 0, 0.2)',
            'rgba(255, 0, 0, 0.2)',
            'rgba(0, 0, 255, 0.2)'
        ];

        const borderColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(201, 203, 207, 1)',
            'rgba(255, 105, 180, 1)',
            'rgba(139, 69, 19, 1)',
            'rgba(0, 255, 0, 1)',
            'rgba(255, 0, 0, 1)',
            'rgba(0, 0, 255, 1)'
        ];

        // Konfigurasi chart
        const ctx = document.getElementById('voting-chart').getContext('2d');
        const votingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Voting',
                    data: counts,
                    backgroundColor: backgroundColors.slice(0, labels.length),
                    borderColor: borderColors.slice(0, labels.length),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari controller
        const labels = <?= json_encode($labelsBerita) ?>;
        const datasets = <?= json_encode($datasetsBerita) ?>;

        // Konfigurasi chart
        const ctx = document.getElementById('berita-chart').getContext('2d');
        const beritaChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                scales: {


                }
            }
        });
    });
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari controller
        const dailyVisitsToday = <?= json_encode($dailyVisitsToday) ?>;

        // Konfigurasi chart Donut untuk pengunjung hari ini
        const ctxDaily = document.getElementById('daily-visits-chart').getContext('2d');
        const dailyVisitsChart = new Chart(ctxDaily, {
            type: 'doughnut',
            data: {
                labels: ['Hari Ini'],
                datasets: [{
                    label: 'Jumlah Pengunjung Hari Ini',
                    data: [dailyVisitsToday],
                    backgroundColor: ['rgba(15, 108, 124, 0.8)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                }
            }
        });

        const monthlyLabels = <?= json_encode(array_column($kategoriBerita, 'kategori_title')) ?>;
        const monthlyCounts = <?= json_encode(array_column($kategoriBerita, 'jumlah')) ?>;

        // Konfigurasi chart Pie untuk pengunjung per bulan
        const ctxMonthly = document.getElementById('kategori-chart').getContext('2d');
        const monthlyVisitsChart = new Chart(ctxMonthly, {
            type: 'pie',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    data: monthlyCounts,
                    backgroundColor: [
                        'rgba(220, 12, 36, 0.8)',
                        'rgba(13, 240, 13, 0.8)',
                        'rgba(212, 255, 0, 1)',
                        'rgba(15, 150, 227, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: 'bottom',
                }
            }
        });
    });
</script>
<?= $this->endsection() ?>