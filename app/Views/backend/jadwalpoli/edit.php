<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>
<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title">Form Input Jadwalpoli</h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Jadwalpoli</li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('jadwalpoli') ?>" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">

        <!-- Start col -->
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">Form Input Tempat Tidur</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('jadwalpoli/update'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="idjadwalpoli" value="<?= $jadwalpoli['idjadwalpoli'] ?>">


                        <div class="form-row mb-3">
                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Nama Poli</label>
                                <select id="inputState1" class="form-control select2 <?= (session()->getFlashdata('error_poli_id')) ? 'is-invalid' : '' ?>" name="poli_id">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($poli as $item) {
                                        $selected = ($item['idpoli'] == $jadwalpoli['poli_id']) ? 'selected' : '';
                                        echo '<option value="' . $item['idpoli'] . '"' . $selected . '>' .  $item['nama'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_poli_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_poli_id') . "</div>" : ''; ?>
                            </div>

                            <?php
                            // Misalkan $dokterData berisi data dokter yang diambil berdasarkan $jadwalpoli['dokter_id']
                            $dokterData = null;
                            foreach ($dokter as $doc) {
                                if ($doc['iddokter'] == $jadwalpoli['dokter_id']) {
                                    $dokterData = $doc;
                                    break;
                                }
                            }

                            $selectedSpesialisId = $dokterData ? $dokterData['spesialis_id'] : null;
                            ?>

                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Spesialis</label>
                                <select id="spesialis_id" class="select2 form-control <?= (session()->getFlashdata('error_spesialis')) ? 'is-invalid' : '' ?>" name="spesialis">
                                    <option selected="" value="">--Pilih--</option>
                                    <?php foreach ($spesialis as $item) {
                                        $selected = ($item['idspesialis'] == $selectedSpesialisId) ? 'selected' : '';
                                        echo '<option value="' . $item['idspesialis'] . '" ' . $selected . '>' .  $item['nama'] . '</option>';
                                    } ?>
                                </select>
                                <?= (session()->getFlashdata('error_spesialis')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_spesialis') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputEmail4">Nama Dokter</label>
                                <select id="dokter_id" class="form-control select2 <?= (session()->getFlashdata('error_dokter_id')) ? 'is-invalid' : '' ?>" name="dokter_id">
                                    <option value="">--Pilih--</option>
                                    <?php
                                    foreach ($dokter as $item) {
                                        if ($item['iddokter'] == $jadwalpoli['dokter_id']) {
                                            echo '<option value="' . $item['iddokter'] . '" selected>' . $item['nama'] . '</option>';
                                            break; // Keluar dari loop setelah menemukan dokter yang sesuai
                                        }
                                    }
                                    ?>
                                </select>

                                <?= (session()->getFlashdata('error_dokter_id')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_dokter_id') . "</div>" : ''; ?>
                            </div>
                        </div>


                        <div class="form-row mb-3">

                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Senin</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_senin')) ? 'is-invalid' : '' ?>" name="senin" value="<?= old('senin', $jadwalpoli['senin']) ?>" min="0" max="1">
                                <?= (session()->getFlashdata('error_senin')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_senin') . "</div>" : ''; ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Senin</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_senin')) ? 'is-invalid' : '' ?>" name="keterangan_senin" value="<?= old('keterangan_senin', $jadwalpoli['keterangan_senin']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_senin')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_senin') . "</div>" : ''; ?>
                            </div>


                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Selasa</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_selasa')) ? 'is-invalid' : '' ?>" name="selasa" value="<?= old('selasa', $jadwalpoli['selasa']) ?>">
                                <?= (session()->getFlashdata('error_selasa')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_selasa') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Selasa</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_selasa')) ? 'is-invalid' : '' ?>" name="keterangan_selasa" value="<?= old('keterangan_selasa', $jadwalpoli['keterangan_selasa']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_selasa')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_selasa') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Rabu</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_rabu')) ? 'is-invalid' : '' ?>" name="rabu" value="<?= old('rabu', $jadwalpoli['rabu']) ?>">
                                <?= (session()->getFlashdata('error_rabu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_rabu') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Rabu</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_rabu')) ? 'is-invalid' : '' ?>" name="keterangan_rabu" value="<?= old('keterangan_rabu', $jadwalpoli['keterangan_rabu']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_rabu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_rabu') . "</div>" : ''; ?>
                            </div>


                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Kamis</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_kamis')) ? 'is-invalid' : '' ?>" name="kamis" value="<?= old('kamis', $jadwalpoli['kamis']) ?>">
                                <?= (session()->getFlashdata('error_kamis')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_kamis') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Kamis</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_kamis')) ? 'is-invalid' : '' ?>" name="keterangan_kamis" value="<?= old('keterangan_kamis', $jadwalpoli['keterangan_kamis']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_kamis')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_kamis') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Jumat</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_jumat')) ? 'is-invalid' : '' ?>" name="jumat" value="<?= old('jumat', $jadwalpoli['jumat']) ?>">
                                <?= (session()->getFlashdata('error_jumat')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_jumat') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Jumat</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_jumat')) ? 'is-invalid' : '' ?>" name="keterangan_jumat" value="<?= old('keterangan_jumat', $jadwalpoli['keterangan_jumat']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_jumat')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_jumat') . "</div>" : ''; ?>
                            </div>


                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Sabtu</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_sabtu')) ? 'is-invalid' : '' ?>" name="sabtu" value="<?= old('sabtu', $jadwalpoli['sabtu']) ?>">
                                <?= (session()->getFlashdata('error_sabtu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_sabtu') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan Sabtu</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_sabtu')) ? 'is-invalid' : '' ?>" name="keterangan_sabtu" value="<?= old('keterangan_sabtu', $jadwalpoli['keterangan_sabtu']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_sabtu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_sabtu') . "</div>" : ''; ?>
                            </div>

                        </div>

                        <div class="form-row mb-3">

                            <div class="form-group col-md-2">
                                <label for="inputPassword4">Minggu</label>
                                <input min="0" max="1" type="number" class="form-control <?= (session()->getFlashdata('error_minggu')) ? 'is-invalid' : '' ?>" name="minggu" value="<?= old('minggu', $jadwalpoli['minggu']) ?>">
                                <?= (session()->getFlashdata('error_minggu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_minggu') . "</div>" : ''; ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="inputPassword4">Keterangan minggu</label>
                                <input type="text" placeholder="Masukkan Jam Kerja. Contoh : 08.00 - 12.00" class="form-control <?= (session()->getFlashdata('error_keterangan_minggu')) ? 'is-invalid' : '' ?>" name="keterangan_minggu" value="<?= old('keterangan_minggu', $jadwalpoli['keterangan_minggu']) ?>">
                                <?= (session()->getFlashdata('error_keterangan_minggu')) ? "<div class='invalid-feedback'>" .
                                    session()->getFlashdata('error_keterangan_minggu') . "</div>" : ''; ?>
                            </div>
                        </div>






                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div> <!-- End row -->
</div>
<!-- End Contentbar -->

<!-- select 2-->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<script>
    $(document).ready(function() {
        $('.select1').select2();
    });
</script>

<script>
    $(document).ready(function() {
        $('#spesialis_id').on('change', function() {
            let spesialis_id = $('#spesialis_id').val();
            // token
            let csrfName = '<?= csrf_token() ?>';


            $.ajax({
                type: "POST",
                url: "<?= base_url('jadwalpoli/getDokter') ?>",
                data: {
                    "spesialis_id": spesialis_id,
                    "_token": csrfName
                },
                cache: "false",
                success: function(response) {
                    $('#dokter_id').html(response);
                },
                error: function(xhr) {
                    console.log('Error :', xhr);
                }
            });
        });
    });
</script>
<?= $this->endsection() ?>