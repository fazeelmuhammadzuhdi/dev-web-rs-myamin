<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    /* class active */
    .active {
        color: red;
    }

    @media (max-width: 768px) {
        .sidebar {
            display: flex;
            flex-direction: column;
        }

        .widget-search {
            order: -1;
        }

        .postcontent {
            order: 1;
        }
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Post</h1>

    </div>


</section>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">


            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-9">
                    <!-- Posts
							============================================= -->
                    <div id="posts" class="post-grid row grid-container gutter-30" data-layout="fitRows">

                        <?php if (!empty($berita)) : ?>

                            <?php foreach ($berita as $item) : ?>


                                <div class="entry col-sm-6 col-12">
                                    <div class="grid-inner">
                                        <div class="entry-image">
                                            <a href="<?= base_url('berita/' . ($item['gambar'] ? $item['gambar'] : 'no-thumbnail.png')) ?>" data-lightbox="image">
                                                <img src="<?= base_url('berita/' . ($item['gambar'] ? $item['gambar'] : 'no-thumbnail.png')) ?>" alt="<?= $item['judul'] ?>">
                                            </a>
                                        </div>
                                        <div class="entry-title">
                                            <style>
                                                .capitalize-first-letter {
                                                    text-transform: capitalize;
                                                }
                                            </style>

                                            <h3>
                                                <a class="menu-suara capitalize-first-letter" href="<?= site_url('blog/' . $item['slug']) ?>">
                                                    <?= $item['judul'] ?>
                                                </a>
                                            </h3>


                                        </div>
                                        <div class="entry-meta">
                                            <ul>
                                                <li><i class="icon-calendar3"></i> <?= tanggal_indonesia($item['tanggal'])  ?></li>
                                                <li><i class="icon-eye"></i> <?= $item['viewberita'] ?></li>
                                                <li><i class="icon-user"></i>Admin</li>
                                            </ul>
                                        </div>
                                        <div class="entry-content">
                                            <a href="<?= site_url('blog/' . $item['slug']) ?>" class="btn btn-danger">Baca Selengkapnya</a>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; ?>

                        <?php else : ?>
                            <div class="product center">
                                <a href="<?= base_url(); ?>/frontend/404.jpg"><img src="<?= base_url(); ?>/frontend/404.jpg" alt="Image" width="500px"></a>
                            </div>

                        <?php endif; ?>

                    </div>
                    <!-- #posts end -->

                    <!-- Pagination
							============================================= -->

                    <ul class="pagination mt-5 pagination-circle justify-content-center">
                        <li class="page-item"> <?= $pager->links('default', 'pagination') ?></li>
                    </ul>
                </div>





                <div class="sidebar col-lg-3 ">
                    <div class="widget widget-search">
                        <form class="input-group" action="<?= site_url('blog') ?>" method="get">
                            <input class="form-control" name="q" type="search" placeholder="Search..." aria-label="Search" value="<?= esc($searchQuery ?? '') ?>">
                            <button class="btn btn-outline-secondary icon-line-search" type="submit" aria-label="Search"></button>
                        </form>
                    </div>



                    <div class="sidebar-widgets-wrap">
                        <div class="widget widget_links clearfix">
                            <h4 class="ls1 text-uppercase fw-bold">Kategori Berita</h4>

                            <?php if (!empty($kategori)) : ?>
                                <ul>
                                    <?php foreach ($kategori as $item) : ?>

                                        <li class="d-flex align-items-center active">
                                            <a href="<?= site_url('kategori/' . $item['idkategori']) ?>" class="flex-fill"><?= $item['title'] ?></a>
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
                                        <a href="<?= site_url('kategori/' . $item['idkategori']) ?>"><?= $item['title'] ?></a>

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