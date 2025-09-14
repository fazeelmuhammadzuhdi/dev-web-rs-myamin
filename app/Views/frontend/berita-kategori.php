<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>


<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <?php if (!empty($selectedKategori == 'AG')) : ?>
        <div class="container clearfix ">
            <h1>Post &raquo; Agenda</h1>
        </div>
    <?php endif; ?>

    <?php if (!empty($selectedKategori == 'BR')) : ?>
        <div class="container clearfix ">
            <h1>Post &raquo; Berita</h1>
        </div>
    <?php endif; ?>

    <?php if (!empty($selectedKategori == 'IA')) : ?>
        <div class="container clearfix ">
            <h1>Post &raquo; Informasi Asuransi</h1>
        </div>
    <?php endif; ?>

    <?php if (!empty($selectedKategori == 'PK')) : ?>
        <div class="container clearfix ">
            <h1>Post &raquo; Promosi Kesehatan Rumah Sakit</h1>
        </div>
    <?php endif; ?>

</section>



<style>
    .active a {
        font-weight: bold;
        color: red;
        /* Change to desired color */
    }



    .berita-kategori .grid-inner {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
    }

    .berita-kategori .entry-image {
        flex-shrink: 0;
        height: 200px;
        /* Sesuaikan tinggi gambar */
        overflow: hidden;
    }

    .berita-kategori .entry-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .berita-kategori .entry-title {
        margin-top: 10px;
    }

    .berita-kategori .entry-meta {
        margin-bottom: 10px;
        /* Tambahkan margin bawah */
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .berita-kategori .entry-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding-bottom: 10px;
        margin-top: 0 !important;
    }

    .berita-kategori .entry-content .btn {
        align-self: flex-start;
        margin-top: auto;
    }
</style>

<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <!-- Post Content
						============================================= -->
                <div class="postcontent col-lg-9">

                    <!-- Posts
							============================================= -->
                    <div id="posts" class="post-grid berita-kategori row grid-container gutter-30" data-layout="fitRows">

                        <?php if (!empty($berita)) : ?>

                            <?php foreach ($berita as $item) : ?>
                                <div class="entry col-sm-6 col-12">
                                    <div class="grid-inner">
                                        <div class="entry-image">
                                            <a href="<?= base_url('berita/' . $item['gambar']) ?>" data-lightbox="image"><img src="<?= base_url('berita/' . $item['gambar']) ?>" alt="Standard Post with Image"></a>
                                        </div>
                                        <div class="entry-title">
                                            <h4><a class="menu-suara" href="<?= site_url('blog/' . $item['slug']) ?>"><?= limit_words($item['judul'], 5) ?></a></h4>
                                        </div>
                                        <div class="entry-meta">
                                            <ul>
                                                <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($item['tanggal'])  ?></li>
                                                <li><a><i class="icon-eye"></i> <?= $item['viewberita'] ?></a></li>
                                                <li><a><i class="icon-user"></i>Admin</a></li>
                                            </ul>
                                        </div>
                                        <div class="entry-content">
                                            <p>
                                                <!-- <?= strip_empty_p_tags($item['konten']) ?> -->
                                            </p>

                                            <a href="<?= site_url('blog/' . $item['slug']) ?>" class="btn btn-danger"> Read More</a>


                                        </div>
                                    </div>
                                </div>


                            <?php endforeach; ?>
                        <?php endif; ?>

                    </div>
                    <!-- #posts end -->

                    <!-- Pagination
							============================================= -->
                    <ul class="pagination mt-5 pagination-circle justify-content-center">
                        <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
                    </ul>
                    <!-- .pager end -->

                </div>
                <!-- .postcontent end -->
                <div class="sidebar col-lg-3">

                    <!-- cari -->

                    <div class="widget widget-search">
                        <form class="input-group" action="<?= site_url('blog') ?>" method="get">
                            <input class="form-control" name="q" type="search" placeholder="Search" aria-label="Search" value="<?= esc($searchQuery ?? '') ?>">
                            <button class="btn btn-outline-secondary icon-line-search" type="submit"></button>
                        </form>
                    </div>

                    <div class="sidebar-widgets-wrap">

                        <div class="widget widget_links clearfix">

                            <h4>Kategori Berita</h4>

                            <?php if (!empty($kategori)) : ?>
                                <ul>
                                    <?php foreach ($kategori as $item) : ?>
                                        <li class="d-flex align-items-center <?= ($item['idkategori'] == $selectedKategori) ? 'active' : '' ?>">
                                            <a href="<?= site_url('kategori/' . $item['idkategori']) ?>" class="flex-fill <?= ($item['idkategori'] == $selectedKategori) ? 'text-danger' : '' ?>">
                                                <?= $item['title'] ?>
                                            </a>
                                            <span class="badge text-light bg-success"><?= $item['total'] ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                            <?php endif; ?>
                        </div>

                        <?= $this->include('frontend/sidebar-berita')  ?>


                        <div class="widget clearfix">
                            <h4>Tags</h4>
                            <?php if (!empty($kategori)) : ?>
                                <div class="tagcloud">
                                    <?php foreach ($kategori as $item) : ?>
                                        <a href="<?= site_url('kategori/' . $item['idkategori']) ?>" class="<?= ($item['idkategori'] == $selectedKategori) ? 'text-primary' : '' ?>">
                                            <?= $item['title'] ?>
                                        </a>
                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</section><!-- #content end -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuItems = document.querySelectorAll('.menu-suara');
        let currentSpeech = null;

        // Cari suara yang menggunakan bahasa Indonesia
        let indonesianVoice = null;
        window.speechSynthesis.onvoiceschanged = function() {
            const voices = window.speechSynthesis.getVoices();
            indonesianVoice = voices.find(voice => voice.lang === 'id-ID');
        };

        menuItems.forEach(item => {
            item.addEventListener('mouseover', function() {
                // Hentikan pembacaan sebelumnya jika ada
                if (currentSpeech) {
                    window.speechSynthesis.cancel();
                }
                const text = item.textContent.trim();
                const msg = new SpeechSynthesisUtterance(text);

                // Tentukan bahasa yang digunakan
                msg.lang = 'id-ID';

                // Jika suara Bahasa Indonesia ditemukan, gunakan suara tersebut
                if (indonesianVoice) {
                    msg.voice = indonesianVoice;
                }

                currentSpeech = msg;
                window.speechSynthesis.speak(msg);
            });

            item.addEventListener('mouseleave', function() {
                // Hentikan pembacaan saat mouse keluar dari elemen
                if (currentSpeech) {
                    window.speechSynthesis.cancel();
                    currentSpeech = null;
                }
            });
        });
    });
</script>


<?= $this->endsection() ?>