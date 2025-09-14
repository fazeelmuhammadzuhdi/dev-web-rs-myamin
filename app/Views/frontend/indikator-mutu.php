<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .grid-filter.flex-column {
        border: 1px solid rgba(0, 0, 0, 0.07);
        border-radius: 4px;
    }

    .grid-filter.flex-column,
    .grid-filter.flex-column li {
        width: 100%;
    }

    .grid-filter.flex-column li a {
        padding: 14px 20px;
        font-size: 0.9375rem;
        text-align: left;
        border-left: 0;
        border-radius: 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.07);
    }

    .grid-filter.flex-column li:first-child a {
        border-radius: 4px 4px 0 0;
    }

    .grid-filter.flex-column li:last-child a {
        border-bottom: 0;
        border-radius: 0 0 4px 4px;
    }
</style>

<div id="wrapper" class="clearfix">



    <section id="content">
        <div class="content-wrap">
            <div class="container clearfix">

                <div class="tabs tabs-bb clearfix" id="tab-9">

                    <ul class="tab-nav clearfix">
                        <?php
                        foreach ($indikatorMutu as $index => $kategori) { ?>
                            <li><a href="#<?= $kategori['idindikatormutu'] ?>"><?= $kategori['nama'] ?></a></li>
                        <?php    }
                        ?>
                    </ul>

                    <div class="tab-container">

                        <?php
                        foreach ($indikatorMutu as $kategori) {
                            $cur_id = $kategori['idindikatormutu'];
                            $list = (new \App\Models\IndikatorMutuList())->getIndikatorMutuListById($kategori['idindikatormutu']);

                            echo '<div class="tab-content clearfix" id="' . $cur_id . '">';
                            echo '<div class="accordion" data-collapsible="true">';

                            foreach ($list as $indikator) {
                                $img = ($indikator['gambar'] != '' && file_exists('indikatormutulist/' . $indikator['gambar'])) ? $indikator['gambar'] : 'noimg.jpg';
                                echo '
            <div class="accordion-header">
                <div class="accordion-icon">
                    <i class="accordion-closed icon-ok-circle"></i>
                    <i class="accordion-open icon-remove-circle"></i>
                </div>
                <div class="accordion-title">
                    ' . ($indikator['keterangan']) . '
                </div>
            </div>
            <div class="accordion-content">
                <img src="' . base_url('indikatormutulist/' . $img) . '" alt="imut" width="100%" />
            </div>';
                            }

                            echo '</div>'; // Close accordion
                            echo '</div>'; // Close tab-content
                        }
                        ?>









                    </div>

                </div>



            </div>
        </div>
    </section>





</div><!-- #wrapper end -->
<?= $this->endsection() ?>