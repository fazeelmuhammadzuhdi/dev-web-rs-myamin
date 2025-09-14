<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<style>
    .table-detail {
        width: 100%;
        border-collapse: collapse;
    }

    .table-detail th,
    .table-detail td {
        border: 1px solid #ccc;
        padding: 8px;
        vertical-align: top;
    }

    .table-detail th {
        background-color: #19827B;
        color: white;
        width: 200px;
        text-align: left;
    }

    .table-detail td.label {
        background-color: #19827B;
        color: white;
        width: 200px;
        font-weight: bold;
    }

    .table-detail td.separator {
        width: 10px;
        text-align: center;
    }

    .table-detail td {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
    }

    .table-detail td ol {
        margin-left: 1.5em;
        padding-left: 0;
    }
</style>



<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1><?= $inovasi['judul'] ?></h1>
    </div>


</section>


<section id="content">
    <div class="content-wrap">
        <div class="container clearfix">

            <div class="row gutter-40 col-mb-80">

                <div class="postcontent col-lg-12">
                    <table class="table-detail">
                        <tr>
                            <td class="label">Tahapan Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['tahapan'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Digital</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['digital'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Inisiator Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['inisiator'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Urusan Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['urusan'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Panduan Teknis Inovasi</td>
                            <td class="separator">:</td>
                            <td>
                                <?= $inovasi['panduan_teknis'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="label">Bentuk Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['jenis'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Tujuan Inovasi</td>
                            <td class="separator">:</td>
                            <td>
                                <?= $inovasi['tujuan'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Manfaat Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['manfaat'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Hasil Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['hasil'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Uji Coba Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['ujicoba'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Implementasi Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['implementasi'] ?></td>
                        </tr>
                        <tr>
                            <td class="label">Link Youtube</td>
                            <td class="separator">:</td>
                            <td>
                                <?= $inovasi['link_youtube'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Latar Belakang Inovasi</td>
                            <td class="separator">:</td>
                            <td><?= $inovasi['rancang'] ?></td>
                        </tr>
                    </table>
                </div>

            </div>

        </div>
    </div>
</section>




<?= $this->endsection() ?>