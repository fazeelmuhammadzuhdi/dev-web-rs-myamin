<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> Tahun <?= date('Y') ?> </title>
</head>


<style>
    /* General styling */
    body {
        font-family: 'Times New Roman', Times, serif;
        margin: 0;
        padding: 0;
    }

    .header-laporan {
        text-align: center;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .header-laporan h4,
    .header-laporan h2,
    .header-laporan h5 {
        margin: 4px 0;
        line-height: 1.2;
    }

    .data-laporan,
    .data-detail {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-top: 10px;
    }

    .data-laporan th,
    .data-laporan td,
    .data-detail th,
    .data-detail td {
        padding: 6px;
        border: 1px solid black;
        text-align: left;
    }

    .data-laporan th {
        text-align: center;
        background-color: #f2f2f2;
    }

    .data-detail td:first-child {
        font-weight: bold;
        width: 15%;
    }

    .data-detail td:nth-child(2) {
        width: 5%;
    }

    .data-detail td {
        padding: 4px;
        width: 30%;
    }

    .keterangan-kegiatan {
        text-align: justify;
    }

    .tanda-tangan {
        width: 100%;
        font-size: 14px;
        margin-top: 20px;
        text-align: center;
    }

    .tanda-tangan td {
        padding-top: 40px;
        font-weight: bold;
    }
</style>

<?php setlocale(LC_TIME, 'id_ID.utf8'); ?>



<body>
    <table class="header-laporan" style="width: 100%;">
        <thead>
            <tr>
                <th style="width: 10%; vertical-align: center !important;">
                    <img src="<?= $srcLogoPemrov ?>" alt="" width="70" style="margin-top: 20px;">
                </th>
                <th style="width: 80%; text-align: center; line-height: 5px;">
                    <h4>PEMERINTAH PROVINSI SUMATERA BARAT</h4>
                    <h2>RUMAH SAKIT UMUM DAERAH PROF. H. MUHAMMAD YAMIN, S.H</h2>
                    <h5>Jln. Prof. M. Yamin, SH No. 5 Telp. (0751) 91118 - 91428 (Fax-Direktur), Kode Pos 25514</h5>
                </th>
                <th style="width: 10%; text-align: left;">
                    <img src="<?= $srcLogoRsud ?>" alt="" width="90" style="margin-top: 20px;">
                </th>
            </tr>
        </thead>
    </table>

    <div style="border-bottom: 2px solid black; margin-bottom: 10px;"></div>

    <table style="width: 100%; margin-bottom: 18px;">
        <tr>
            <th style="font-size: 20px;"><?= $title ?> RSUD. Prof. H. Muhammad. Yamin, SH</th>
        </tr>
    </table>

    <!-- Tabel Kegiatan -->
    <table class="data-laporan" style="margin-top: 20px; margin-bottom: 30px;">
        <tr>
            <th scope="col">No.</th>
            <th scope="col">Nama</th>
            <th scope="col">Telepon</th>
            <th scope="col">Email</th>
            <th scope="col">Tanggal</th>
            <th scope="col">Informasi Dibutuhkan</th>
            <th scope="col">Alasan</th>
        </tr>
        <tbody>
            <?php if (empty($permintaaninformasi)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data tersedia</td>
                </tr>
            <?php else: ?>
                <?php foreach ($permintaaninformasi as $index => $item): ?>
                    <tr>
                        <td style="text-align: center;"><?= $index + 1 ?>.</td>
                        <td style="text-align: center;"><?= nl2br(strip_tags($item['nama_pemohon_informasi'])) ?></td>
                        <td><?= nl2br(strip_tags($item['nomor_telepon_pemohon'])) ?></td>
                        <td><?= nl2br(strip_tags($item['email_pemohon'] ?? '-')) ?></td>
                        <td><?= nl2br(strip_tags(tanggal_indonesia($item['tanggal']))) ?></td>
                        <td><?= nl2br(strip_tags($item['informasi_dibutuhkan_pemohon'] ?? '-')) ?></td>
                        <td><?= nl2br(strip_tags($item['alasan_permintaan_pemohon'] ?? '-')) ?></td>

                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="7" style="text-align: center; font-weight:bold;">Total Data <?= $title ?>: <?= $total ?></td>
                </tr>
            <?php endif; ?>
        </tbody>

    </table>



</body>


</html>

<script>
    window.onload = function() {
        window.print();
    }
</script>