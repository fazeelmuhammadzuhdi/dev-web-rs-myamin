<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>



<section id="page-title" style="padding: 2rem 0 !important; " class="bg-transparent">

    <div class="container clearfix ">
        <h1>Profil Manajemen RSUD PROF. H. Muhammad Yamin, S.H</h1>
    </div>

</section>


<!-- Content
		============================================= -->
<section id="content">
    <div class="content-wrap">
        <div class="container">

            <div class="row col-mb-50 mb-0">
                <?php if (!empty($profilManajemen)) : ?>

                    <?php foreach ($profilManajemen as $item) : ?>
                        <div class="col-12">
                            <div class="team team-list row align-items-start">
                                <div class="team-image col-md-5 col-lg-3">
                                    <a href="<?= base_url('manajemenprofil/' . $item['gambar']) ?>" target="_blank">
                                        <img src="<?= base_url('manajemenprofil/' . $item['gambar']) ?>" alt="<?= $item['nama'] ?>" title="<?= $item['nama'] ?>" height="400px">
                                    </a>
                                </div>
                                <div class="team-desc col-md">
                                    <div class="team-title">
                                        <h4 style="text-transform: none;"><?= $item['nama'] ?></h4>
                                        <a class="text-black"><?= preg_replace('/\s+/', '', $item['nip']) ?></a>

                                        <p class="fw-bold text-danger"><?= $item['jabatan'] ?></p>

                                    </div>
                                    <div class="col-md-12">
                                        <table style="width: 100%;" border="0">
                                            <tbody>
                                                <tr valign="top">
                                                    <td style="width: 30%;"><strong>Tempat, Tanggal Lahir</strong></td>
                                                    <td>:&nbsp;</td>
                                                    <td><?= $item['tempatlahir'] ?>, <?= format_indo($item['tanggallahir']) ?></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td style="width: 30%;"><strong>Pendidikan Terakhir</strong></td>
                                                    <td>:&nbsp;</td>
                                                    <td><?= $item['pendidikan'] ?></td>
                                                </tr>
                                                <tr valign="top">
                                                    <td style="width: 30%;"><strong>Pangkat / Golongan</strong></td>
                                                    <td>:&nbsp;</td>
                                                    <td><?= $item['pangkat'] ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <?php if (!empty($item['profil_singkat']) || !empty($item['riwayat_pendidikan'])) : ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="team-title">
                                                            <h4 style="text-transform: none;">Riwayat Pekerjaan</h4>
                                                        </div>
                                                        <div class="card-text">
                                                            <?= $item['profil_singkat'] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="team-title">
                                                            <h4 style="text-transform: none;">Riwayat Pendidikan</h4>
                                                        </div>
                                                        <div class="card-text">
                                                            <?= $item['riwayat_pendidikan'] ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    <?php endif; ?>


                                </div>
                            </div>

                        </div>


                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>


    </div>
</section><!-- #content end -->



<?= $this->endsection() ?>