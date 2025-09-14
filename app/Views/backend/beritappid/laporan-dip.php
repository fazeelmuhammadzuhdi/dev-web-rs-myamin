<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Daftar Informasi Publik Tahun <?= date('Y') ?> </title>
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
        text-align: center;
        margin-bottom: 50px;
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
            <th style="font-size: 20px;"><?= $title ?></th>
        </tr>
    </table>

    <?php
    $totalSertaMerta = 0;
    $totalSetiapSaat = 0;
    $totalBerkala = 0;
    $totalTidakDiketahui = 0;

    foreach ($beritaPpid as $item) {
        if ($item['kategori_id'] == "1") {
            $totalSertaMerta++;
        } elseif ($item['kategori_id'] == "2") {
            $totalSetiapSaat++;
        } elseif ($item['kategori_id'] == "3") {
            $totalBerkala++;
        } else {
            $totalTidakDiketahui++;
        }
    }
    ?>


    <!-- Tabel Kegiatan -->
    <table class="data-laporan" style="margin-top: 20px; margin-bottom: 30px;">
        <tr>
            <th rowspan="2">NO</th>
            <th rowspan="2">JENIS INFORMASI</th>
            <th rowspan="2">RINGKASAN ISI INFORMASI</th>
            <th rowspan="2">OPD YANG MENGUASAI</th>
            <th rowspan="2">PENANGGUNG JAWAB</th>
            <th rowspan="2">WAKTU DAN TEMPAT PEMBUATAN</th>
            <th colspan="2">BENTUK INFORMASI</th>
            <th rowspan="2">RETENSI ARSIP</th>
            <th rowspan="2">CARA PEROLEHAN INFORMASI</th>
        </tr>
        <tr>
            <th>SOFT</th>
            <th>HARD</th>
        </tr>


        <tbody>
            <?php if (empty($beritaPpid)): ?>
                <tr>
                    <td colspan="10" style="text-align: center;">Tidak ada data tersedia</td>
                </tr>
            <?php else: ?>
                <?php foreach ($beritaPpid as $index => $item): ?>
                    <tr>
                        <td style="text-align: center;"><?= $index + 1 ?>.</td>
                        <td style="text-align: center;">
                            <?php
                            if ($item['kategori_id'] == "1") {
                                echo "Informasi Serta Merta";
                            } elseif ($item['kategori_id'] == "2") {
                                echo "Informasi Setiap Saat";
                            } elseif ($item['kategori_id'] == "3") {
                                echo "Informasi Berkala";
                            } else {
                                echo "Tidak Diketahui";
                            }
                            ?>
                        </td>
                        <td style="text-align: center;"><?= nl2br(strip_tags($item['judul'])) ?></td>
                        <td style="text-align: center;">
                            <?php
                            if ($item['tahun'] == "2024") {
                                echo "RSUD Pariaman";
                            } else {
                                echo "Rsud M Yamin";
                            }
                            ?>
                        </td>
                        <td style="text-align: center;"><?= nl2br(strip_tags($item['penanggung_jawab'])) ?></td>
                        <td style="text-align: center;"><?= nl2br(strip_tags($item['tempat'] . ', ' . $item['tahun'])) ?></td>
                        <!-- Tambahan kolom untuk "BENTUK INFORMASI" -->
                        <td style="text-align: center;">
                            v
                        </td>
                        <td style="text-align: center;">
                            -
                        </td>
                        <td style="text-align: center;"><?= nl2br(strip_tags($item['jangka'])) ?></td>
                        <td style="text-align: center;">
                            Melihat dan mengetahui melalui website
                            <a href="https://rsudmyamin.sumbarprov.go.id/dip" style="text-decoration: underline;" target="_blank">https://rsudmyamin.sumbarprov.go.id/dip</a>
                        </td>


                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="10" style="text-align: start; font-weight: bold;">
                        Jumlah Data <?= isset($startYear) ? "Berdasarkan Kategori Pada Tahun $startYear" : '' ?> :<br>
                        - Total Data DIP: <?= $total ?><br>
                        - Informasi Serta Merta: <?= $totalSertaMerta ?><br>
                        - Informasi Setiap Saat: <?= $totalSetiapSaat ?><br>
                        - Informasi Berkala: <?= $totalBerkala ?><br>
                        - Tidak Diketahui: <?= $totalTidakDiketahui ?><br>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if (!empty($beritaPpid)) : ?>
        <tfoot>
            <tr>
                <td colspan="10" style="text-align: left; font-weight: bold; padding-top: 10px;">

                </td>
            </tr>
        </tfoot>
    <?php endif; ?>


    <table class="tanda-tangan">
        <tbody>
            <tr>
                <td style="text-align: center; width: 30%"><br><br>

                </td>
                <td style="text-align: center; width: 30%">
                    <?php
                    // Mendapatkan tanggal saat ini
                    $tanggalSekarang = date('d');
                    $bulanSekarang = date('n'); // Bulan dalam angka (1-12)
                    $tahunSekarang = date('Y');

                    // Array bulan dalam Bahasa Indonesia
                    $namaBulan = [
                        1 => 'Januari',
                        2 => 'Februari',
                        3 => 'Maret',
                        4 => 'April',
                        5 => 'Mei',
                        6 => 'Juni',
                        7 => 'Juli',
                        8 => 'Agustus',
                        9 => 'September',
                        10 => 'Oktober',
                        11 => 'November',
                        12 => 'Desember'
                    ];

                    // Menyusun tanggal dalam format Indonesia
                    $tanggalIndonesia = $tanggalSekarang . ' ' . $namaBulan[$bulanSekarang] . ' ' . $tahunSekarang;
                    ?>
                    Pariaman, <?= $tanggalIndonesia ?> <br><br>
                    Diketahui Oleh, <br>
                    Direktur RSUD Prof H Muhammad Yamin SH <br><br><br><br><br><br>
                    <br><br><br><br><br><br><br>
                    <span style="border-bottom: 1px solid black;">
                        <b>dr. Herlina Nasution, M.Kes</b>
                    </span> <br>

                    NIP. 197306052002122003
                </td>

            </tr>

        </tbody>
    </table>



</body>


</html>

<script>
    window.onload = function() {
        window.print();
    }
</script>