<?= $this->extend('frontend/main/layout') ?>

<?= $this->section('content') ?>

<style>
    .badge.badge-default {
        border: 1px solid var(--themecolor, #fE9603);
        color: var(--themecolor, #fE9603);
    }

    .text-warning {
        color: #fE9603 !important;
    }

    .badge {
        padding: 8px;
        font-size: 12px;
        border-radius: 2px;
        font-weight: 500;
        line-height: .8;
    }

    .rounded-pill {
        border-radius: var(--bs-border-radius-pill) !important;
    }

    .badge {
        --bs-badge-padding-x: 0.65em;
        --bs-badge-padding-y: 0.35em;
        --bs-badge-font-size: 0.75em;
        --bs-badge-font-weight: 700;
        --bs-badge-color: #fff;
        --bs-badge-border-radius: 0.375rem;
        display: inline-block;
        padding: var(--bs-badge-padding-y) var(--bs-badge-padding-x);
        font-size: var(--bs-badge-font-size);
        font-weight: var(--bs-badge-font-weight);
        line-height: 1;
        color: var(--bs-badge-color);
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: var(--bs-badge-border-radius);
    }
</style>

<section id="content">
    <div class="content-wrap">
        <div class="container">

            <?php if (!empty($jadwalpoli)) : ?>


                <div class="row col-mb-50 mb-0">
                    <div class="heading-block center">
                        <h3>Jadwal Dokter Poliklinik <?= $jadwalpoli[0]['nama_poli'] ?></h3>
                    </div>
                    <?php foreach ($jadwalpoli as $item) : ?>

                        <div class="col-12">

                            <div class="team team-list row align-items-center">
                                <div class="team-image col-md-5 col-lg-3">
                                    <img src="<?= base_url('dokter/' . $item['gambar']) ?>" alt="<?= $item['nama_dokter'] ?>">
                                </div>
                                <div class="team-desc col-md">
                                    <div class="team-title">
                                        <a href="javascript">
                                            <h4><?= $item['nama_dokter'] ?></h4>
                                        </a>
                                        <span class="fst-normal fw-bold text-warning"><?= $item['nama_spesialis'] ?></span>
                                        <!-- <p class="fw-bold"><?= $item['nama_poli'] ?></p> -->
                                    </div>

                                    <table class="table table-responsive table-hover mb-3 mt-3">
                                        <thead>
                                            <tr style="text-align:center">
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Senin</div>
                                                </th>
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Selasa</div>
                                                </th>
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Rabu</div>
                                                </th>
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Kamis</div>
                                                </th>
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Jumat</div>
                                                </th>
                                                <th>
                                                    <div class="badge rounded-pill badge-default">Sabtu</div>
                                                </th>

                                            </tr>


                                        </thead>
                                        <tbody>

                                            <tr class="text-center">

                                                <td><?= $item['senin'] == 1 ? $item['keterangan_senin'] : '-'; ?></td>
                                                <td><?= $item['selasa'] == 1 ? $item['keterangan_selasa'] : '-'; ?></td>
                                                <td><?= $item['rabu'] == 1 ? $item['keterangan_rabu'] : '-'; ?></td>
                                                <td><?= $item['kamis'] == 1 ? $item['keterangan_kamis'] : '-'; ?></td>
                                                <td><?= $item['jumat'] == 1 ? $item['keterangan_jumat'] : '-'; ?></td>
                                                <td><?= $item['sabtu'] == 1 ? $item['keterangan_sabtu'] : '-'; ?></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    <?php endforeach; ?>


                </div>
            <?php endif; ?>

        </div>



    </div>
</section>



<?= $this->endsection() ?>