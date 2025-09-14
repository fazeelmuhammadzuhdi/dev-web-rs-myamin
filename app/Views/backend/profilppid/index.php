<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>



<style>
    .upload-icon-label {
        cursor: pointer;
        font-size: 24px;
        color: #337ab7;
        margin-right: 10px;
        margin-top: 15px;
    }

    .upload-icon-label:hover {
        color: #23527c;
    }

    .upload-icon-label i {
        margin-right: 5px;
    }
</style>

<?php
$profilppid = $profilppid ?? [
    'visimisi' => null,
    'tugas' => null,
    'fungsi' => null,
    'idprofilppid' => null,
];
?>

<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title"><?= $title ?></h4>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h5 class="card-title">Form Input <?= $title ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profilppid/save'); ?>" method="POST" enctype="multipart/form-data">
                        <?= csrf_field(); ?>

                        <input type="hidden" name="idprofilppid" value="<?= $profilppid['idprofilppid'] ?? '' ?>">

                        <div class="form-group">
                            <label for="inputAddress">Visi PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_visi_input')) ? 'is-invalid' : '' ?>" name="visi_input" autofocus><?= htmlspecialchars(old('visi_input', $profilppid['visi_input'] ?? '')) ?></textarea>
                            <?= (session()->getFlashdata('error_visi_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_visi_input') . "</div>" : ''; ?>
                        </div>


                        <div class="form-group">
                            <label for="inputAddress">Misi PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_misi_input')) ? 'is-invalid' : '' ?>" name="misi_input" autofocus><?= old('misi_input', $profilppid['misi_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_misi_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_visi_input') . "</div>" : ''; ?>

                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Tugas PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_tugas_input')) ? 'is-invalid' : '' ?>" name="tugas_input" autofocus><?= old('tugas_input', $profilppid['tugas_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_tugas_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_tugas_input') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Fungsi PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_fungsi_input')) ? 'is-invalid' : '' ?>" name="fungsi_input" autofocus><?= old('fungsi_input', $profilppid['fungsi_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_fungsi_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_fungsi_input') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Maklumat Pelayanan PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_maklumat_input')) ? 'is-invalid' : '' ?>" name="maklumat_input" autofocus><?= old('maklumat_input', $profilppid['maklumat_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_maklumat_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_maklumat_input') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Hakekat Pelayanan PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_hakekat_input')) ? 'is-invalid' : '' ?>" name="hakekat_input" autofocus><?= old('hakekat_input', $profilppid['hakekat_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_hakekat_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_hakekat_input') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Asas Pelayanan PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_asas_input')) ? 'is-invalid' : '' ?>" name="asas_input" autofocus><?= old('asas_input', $profilppid['asas_input'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_asas_input')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_asas_input') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Keterangan Profil PPID</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_keterangan_profil_ppid')) ? 'is-invalid' : '' ?>" name="keterangan_profil_ppid" autofocus><?= old('keterangan_profil_ppid', $profilppid['keterangan_profil_ppid'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_keterangan_profil_ppid')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_keterangan_profil_ppid') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Regulasi KIP</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_regulasi_kip')) ? 'is-invalid' : '' ?>" name="regulasi_kip" autofocus><?= old('regulasi_kip', $profilppid['regulasi_kip'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_regulasi_kip')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_regulasi_kip') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Sarana & Prasarana</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_sarana_prasarana')) ? 'is-invalid' : '' ?>" name="sarana_prasarana" autofocus><?= old('sarana_prasarana', $profilppid['sarana_prasarana'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_sarana_prasarana')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_sarana_prasarana') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Standar Biaya</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_standar_biaya')) ? 'is-invalid' : '' ?>" name="standar_biaya" autofocus><?= old('standar_biaya', $profilppid['standar_biaya'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_standar_biaya')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_standar_biaya') . "</div>" : ''; ?>
                        </div>

                        <div class="form-group">
                            <label for="inputAddress">Layanan Lansia & Difabel</label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_layanan_lansia_difabel')) ? 'is-invalid' : '' ?>" name="layanan_lansia_difabel" autofocus><?= old('layanan_lansia_difabel', $profilppid['layanan_lansia_difabel'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_layanan_lansia_difabel')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_layanan_lansia_difabel') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Tata Cara Pengaduan & Penyalahgunaan Wewenang </label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_tata_cara_pengaduan')) ? 'is-invalid' : '' ?>" name="tata_cara_pengaduan" autofocus><?= old('tata_cara_pengaduan', $profilppid['tata_cara_pengaduan'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_tata_cara_pengaduan')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_tata_cara_pengaduan') . "</div>" : ''; ?>
                        </div>
                        <div class="form-group">
                            <label for="inputAddress">Prosedur & Evakuasi </label>
                            <textarea class="form-control editor <?= (session()->getFlashdata('error_prosedur_evakuasi')) ? 'is-invalid' : '' ?>" name="prosedur_evakuasi" autofocus><?= old('prosedur_evakuasi', $profilppid['prosedur_evakuasi'] ?? '') ?></textarea>
                            <?= (session()->getFlashdata('error_prosedur_evakuasi')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_prosedur_evakuasi') . "</div>" : ''; ?>
                        </div>




                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End col -->
    </div>
    <!-- End row -->
</div>

<script>
    document.querySelectorAll('[id^="uploadGambar"]').forEach(input => {
        input.addEventListener('change', function(event) {
            var formData = new FormData();
            formData.append('gambar', this.files[0]);
            formData.append('idprofilppid', this.getAttribute('data-id')); // Mengambil ID dari atribut data-id

            // Ambil endpoint URL dari data-type
            var endpoint = '';
            switch (this.getAttribute('data-type')) {
                case 'visimisi':
                    endpoint = '<?= base_url('profilppid/uploadGambarVisimisi'); ?>';
                    break;
                case 'tugas':
                    endpoint = '<?= base_url('profilppid/uploadGambarTugas'); ?>';
                    break;
                case 'fungsi':
                    endpoint = '<?= base_url('profilppid/uploadGambarFungsi'); ?>';
                    break;
            }

            fetch(endpoint, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Berhasil',
                            text: "Berhasil Simpan Data",
                            icon: 'success',
                            timer: 500,
                            timerProgressBar: true,
                            didClose: () => {
                                // Reload halaman setelah 1 detik setelah Swal ditutup
                                setTimeout(() => {
                                    location.reload();
                                }, 100); // 500 milidetik = 0.5 detik
                            }
                        });

                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.error,
                            icon: 'error',
                            timer: 2500,
                            timerProgressBar: true,
                        })
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>

<script src="<?= base_url(); ?>/assets/tinymce/tiny_mce.js"></script>

<script>
    // Menginisialisasi TinyMCE pada semua elemen dengan class 'editor'
    tinymce.init({
        selector: 'textarea.editor',
        width: "100%",
        height: "100",
        theme: "advanced",
        relative_urls: false,
        remove_script_host: false,
        plugins: "autolink,lists,pagebreak,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",
        theme_advanced_buttons1: "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: false,
    });
</script>


<?= $this->endsection() ?>