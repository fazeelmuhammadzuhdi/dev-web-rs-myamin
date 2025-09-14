<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<style>
    /* class active */
    .active {
        color: red;
    }

    .meta-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .meta-list li {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 10px;
    }

    .meta-list .label {
        width: 230px;
        /* Adjust this width as necessary */
        font-weight: bold;
        /* color: black; */
    }

    .meta-list .value {
        flex-grow: 1;
    }

    /* buatkan tampilan untuk mobile */
    @media (max-width: 768px) {
        .meta-list li {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-bottom: 10px;
            flex-wrap: wrap;
            /* Membuat konten bisa membungkus jika terlalu panjang */
        }

        .meta-list .label {
            width: 150px;
            /* Lebar label, bisa disesuaikan */
            font-weight: bold;
            font-size: 13px;
        }

        .meta-list .value {
            flex: 1;
            /* Agar konten value menempati sisa ruang yang tersedia */
            font-size: 13px;
            /* Sesuaikan ukuran font value */
        }

        .widget {
            padding: 15px !important;
        }

        /* Khusus untuk Penanggung Jawab */
        .meta-list .label i.icon-user {
            margin-right: 5px;
            /* Memberi jarak antara ikon dan teks */
        }
    }
</style>

<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40">
                <div class="postcontent col-lg-9">

                    <div class="entry event col-md-12 imagescalein">
                        <div class="grid-inner row g-0 p-4 border rounded">
                            <div class="col-lg-5 mb-lg-0">
                                <a href="<?= base_url('filetype/' . $beritaDetailPpid['filetype']); ?>" target="_blank" class="entry-image overflow-hidden">
                                    <img src="<?= base_url('filetype/' . $beritaDetailPpid['filetype']); ?>" alt="<?= $beritaDetailPpid['title'] ?>">
                                </a>
                            </div>

                            <div class="col-lg-7 ps-lg-4">
                                <div class="entry-title title-sm">
                                    <h3 class="card-title"><?= $beritaDetailPpid['judul'] ?></h3>
                                </div>
                                <div class="entry-meta">
                                    <ul class="meta-list">
                                        <li>
                                            <span class="label"><i class="icon-info-circle"></i> Klasifikasi</span>
                                            <span class="value badge bg-warning text-dark py-2 px-2" style="font-size: 90%;"><?= $beritaDetailPpid['title'] ?></span>
                                        </li>
                                        <li>
                                            <span class="label"><i class="icon-time"></i> Jangka Waktu</span>
                                            <span class="value text-black fw-semibold"><?= $beritaDetailPpid['jangka'] ?></span>
                                        </li>
                                        <li>
                                            <span class="label"><i class="icon-user"></i> Penanggung Jawab</span>
                                            <span class="value text-black fw-semibold"><?= $beritaDetailPpid['penanggung_jawab'] ?></span>
                                        </li>
                                        <li>
                                            <span class="label"><i class="icon-calendar3"></i> Dipublikasikan</span>
                                            <span class="value text-black fw-semibold"><?= tanggal_indonesia($beritaDetailPpid['tanggal'])   ?></span>
                                        </li>
                                        <li>
                                            <span class="label"><i class="icon-time"></i> Tahun</span>
                                            <span class="value text-black fw-semibold"><?= $beritaDetailPpid['tahun'] ?></span>
                                        </li>

                                        <li>
                                            <span class="label"><i class="icon-eye"></i> Dilihat</span>
                                            <span class="value text-black fw-semibold"><?= $beritaDetailPpid['viewberita'] ?></span>
                                        </li>
                                        <li>
                                            <span class="label"><i class="icon-line-download"></i> Didownload</span>
                                            <span class="value text-black fw-semibold" id="downloadCount"><?= $beritaDetailPpid['download'] ?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="entry-content">
                                    <button id="downloadButton" class="button button-large button-circle button-blue" data-link="<?= $beritaDetailPpid['link']; ?>" data-id="<?= $beritaDetailPpid['idberita']; ?>"><i class="icon-download"></i> Download</button>
                                </div>
                            </div>




                        </div>
                    </div>






                </div>

                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section><!-- #content end -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#downloadButton').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var link = $(this).data('link');
            var button = $(this);

            $.ajax({
                url: '<?= site_url('post/download'); ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                beforeSend: function() {
                    button.attr('disabled', 'disabled');
                },
                success: function(data) {
                    button.removeAttr('disabled');
                    if (data.status === 'success') {
                        var currentCount = parseInt($('#downloadCount').text());
                        $('#downloadCount').text(currentCount + 1);
                        window.open(link, '_blank');
                    } else {
                        alert(data.statusText);
                    }
                },
                error: function(data) {
                    button.removeAttr('disabled');
                    alert('Terjadi kesalahan.');
                }
            });
        });
    });
</script>


<!-- <script type="text/javascript">
    $(document).ready(function() {
        $('#downloadButton').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var link = $(this).data('link');
            var button = $(this);

            $.ajax({
                url: '<?= site_url('post/download'); ?>',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'json',
                beforeSend: function() {
                    button.attr('disabled', 'disabled');
                },
                success: function(data) {
                    button.removeAttr('disabled');
                    if (data.status === 'success') {
                        window.open(link, '_blank');
                    } else {
                        alert(data.statusText);
                    }
                },
                error: function(data) {
                    button.removeAttr('disabled');
                    alert('Terjadi kesalahan.');
                }
            });
        });
    });
</script> -->


<script>
    $(document).ready(function() {
        $('#datatable1').dataTable();
    });
</script>


<?= $this->endsection() ?>