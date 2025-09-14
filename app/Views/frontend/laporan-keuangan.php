<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    .i-large.i-plain {
        width: 88px !important;
        height: 88px !important;
        font-size: 42px;
        line-height: 48px !important;
    }

    .heading-block {
        margin-bottom: 20px;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
        list-style-type: none;
    }

    .pagination li {
        margin-right: 5px;
        border-radius: 50%;
    }

    .pagination li a {
        display: inline-block;
        color: #333;
        text-decoration: none;
        padding: 8px 16px;
        border: 1px solid #ddd;
        background-color: #fff;
        border-radius: 20px;
    }

    .pagination li.active a {
        background-color: #1abc9c;
        color: #fff;
        border-color: #1abc9c;
    }

    .pagination li a:hover {
        background-color: #1abc9c;
    }

    .pagination .disabled a {
        pointer-events: none;
        color: #999;
        background-color: #fff;
        border-color: #ddd;
    }
</style>

<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1><?= $laporan['title'] ?></h1>
    </div>


</section>

<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">


                <!-- Post Content
						============================================= -->

                <div class="postcontent col-lg-9">

                    <div class="row clearfix">

                        <?php if (!empty($laporan['items'])) : ?>

                            <?php foreach ($laporan['items'] as $item) : ?>

                                <div class="col-lg-4 center bottommargin">

                                    <img class="i-plain  i-large inline-block" src="<?= $laporan['gambar'] ? base_url('frontend/images/ppid/' . $laporan['gambar']) : base_url('frontend/images/ppid/laporan_keuangan.png'); ?>" alt="<?= $item['text'] ?>" style="margin-bottom: 15px;">

                                    <div class="heading-block border-bottom-0">
                                        <?php if (isset($item['href']) && !empty($item['href'])) : ?>
                                            <a href="<?= $item['href'] ?>"><?= $item['text'] ?></a>
                                        <?php else : ?>
                                            <p><?= $item['text'] ?></p>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>




                    <nav aria-label="Page navigation example">
                        <ul class="pagination justify-content-center mt-4">
                            <?= $pagination ?>
                        </ul>
                    </nav>

                </div>
                <!-- .postcontent end -->





                <?= $this->include('frontend/sidebar-ppid') ?>

            </div>

        </div>
    </div>
</section><!-- #content end -->


<?= $this->endsection() ?>