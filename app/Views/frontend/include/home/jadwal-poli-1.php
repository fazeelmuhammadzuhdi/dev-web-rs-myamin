<style>
    .badge.badge-default {
        border: 1px solid var(--themecolor, #1693a5);
        color: var(--themecolor, #1693a5);
    }

    .text-warning {
        color: #1693a5 !important;
    }

    .badge {
        padding: 8px !important;
        font-size: 16px !important;
        border-radius: 2px;
        font-weight: 500;
        line-height: .8;
        margin-bottom: 10px;
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

    .text-small {
        display: none;
    }

    @media (max-width: 768px) {
        .text-poli {
            font-size: 16px !important;
            color: #fff !important;
            font-weight: 600 !important;
        }

        .text-large {
            display: none;
        }

        .text-small {
            display: inline;
            font-size: 16px !important;
            color: #fff !important;
            font-weight: 600 !important;
        }

        .table-responsive {
            overflow-x: auto;
            display: block;
            white-space: nowrap;
        }

        .badge {
            font-size: 12px !important;
            padding: 5px !important;
        }

        td:first-child {
            white-space: normal;
            word-wrap: break-word;
        }

        .table {
            font-size: 14px;
        }
    }
</style>

<div class="container topmargin-lg clearfix">
    <div class="heading-block center border-bottom-0 mx-auto" data-aos="fade-up">
        <a href="<?= site_url('poliklinik') ?>" class="button button-xlarge button-circle button-3d button-dirtygreen text-poli">
            <span class="text-large">Jadwal Layanan Poliklinik</span>
            <span class="text-small">Jadwal Poliklinik</span>
        </a>
    </div>

    <div id="oc-portfolio-sidebar" class="owl-carousel carousel-widget" data-items="1" data-margin="10" data-loop="true" data-nav="false" data-autoplay="4000">
        <?php foreach ($jadwalpoli as $nama_poli => $jadwal) : ?>
            <div class="oc-item" data-aos="zoom-in" data-aos-delay="500">
                <div class="card">
                    <h3 class="card-header text-center text-white fw-bold" style="background-color: #1693a5 !important;">
                        <?= $nama_poli ?></h3>
                    <div class="card-body table-responsive">
                        <table class="table table-sm table-hover mb-3" style="width: 100%;">
                            <thead class="thead-light">
                                <tr style="text-align:center">
                                    <th>
                                        <div class="badge rounded-pill badge-default">Dokter</div>
                                    </th>
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
                                <?php foreach ($jadwal as $item) : ?>
                                    <tr class="text-center">
                                        <td><?= $item['nama_dokter'] ?></td>
                                        <td><?= $item['senin'] == 1 ? 'Ada' : '-'; ?></td>
                                        <td><?= $item['selasa'] == 1 ? 'Ada' : '-'; ?></td>
                                        <td><?= $item['rabu'] == 1 ? 'Ada' : '-'; ?></td>
                                        <td><?= $item['kamis'] == 1 ? 'Ada' : '-'; ?></td>
                                        <td><?= $item['jumat'] == 1 ? 'Ada' : '-'; ?></td>
                                        <td><?= $item['sabtu'] == 1 ? 'Ada' : '-'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>


                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>



</div>