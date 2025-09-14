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



<!-- Start Breadcrumbbar -->
<div class="breadcrumbbar">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h4 class="page-title"><?= $title ?></h4>
            <h3 class="text-primary"><?= $foto[0]['keterangan'] ?? '' ?></h3>
            <div class="breadcrumb-list">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
                </ol>
            </div>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('albumlists') ?>" class="btn btn-primary">Kembali</a>
                <button class="btn btn-success ml-2" onclick="document.getElementById('form-upload-baru').style.display = 'block';">Upload Gambar</button>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumbbar -->
<!-- Start Contentbar -->
<div class="contentbar">
    <div class="row">
        <!-- Start col -->
        <?php foreach ($foto as $item): ?>
            <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="card m-b-30">
                    <img class="card-img-top" src="<?= base_url('albumlist/' . $item['gambar']) ?>" alt="<?= $item['keterangan'] ?>">

                    <div class="card-footer">
                        <div class="row align-items-center">

                            <div class="col-md-6">
                                <div class="blog-link">
                                    <button class="btn btn-primary copy-link" data-clipboard-text="<?= base_url('albumlist/' . $item['gambar']) ?>">
                                        Copy Link File<i class="feather icon-arrow-right ml-2"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="blog-meta">
                                    <ul class="list-inline mb-0">
                                        <li class="mr-2">
                                            <button type="button" class="btn btn-danger btn-sm delete-gambar" data-id="<?= $item['idalbumlist'] ?>" title="Hapus Gambar">
                                                <i class="feather icon-trash"></i>
                                            </button>
                                        </li>
                                        <li>
                                            <input type="file" accept="image/*" class="form-control-file mb-3 <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar[<?= $item['idalbumlist'] ?>]" id="uploadGambar_<?= $item['idalbumlist'] ?>" data-id="<?= $item['idalbumlist'] ?>" style="display: none;">
                                            <label for="uploadGambar_<?= $item['idalbumlist'] ?>" class="upload-icon-label">
                                                <i class="feather icon-upload"></i>
                                            </label>
                                            <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                                                session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
                                        </li>
                                    </ul>

                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="blog-meta d-flex justify-content-end">
                                    <ul class="list-inline mb-0 d-flex align-items-center">
                                        <!-- Tombol Hapus -->
                                        <li class="mr-2">
                                            <button type="button" class="btn btn-danger btn-sm delete-gambar" data-id="<?= $item['idalbumlist'] ?>" title="Hapus Gambar">
                                                <i class="feather icon-trash"></i>
                                            </button>
                                        </li>

                                        <!-- Upload File -->
                                        <li>
                                            <input type="file" accept="image/*"
                                                class="form-control-file mb-3 <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>"
                                                name="gambar[<?= $item['idalbumlist'] ?>]"
                                                id="uploadGambar_<?= $item['idalbumlist'] ?>"
                                                data-id="<?= $item['idalbumlist'] ?>" style="display: none;">
                                            <label for="uploadGambar_<?= $item['idalbumlist'] ?>" class="upload-icon-label" title="Ganti Gambar">
                                                <i class="feather icon-upload"></i>
                                            </label>
                                            <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" . session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <!-- End col -->

    </div>

    <div id="form-upload-baru" style="display: none; margin-top: 1rem;">
        <form action="<?= base_url('albumlists/tambahgambar') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="album_id" value="<?= $albumlists['album_id'] ?>">
            <div class="form-group">
                <label for="gambar">Pilih Gambar</label>
                <input type="file" accept="image/*" class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>" name="gambar[]" value="<?= old('gambar') ?>" multiple>
                <?= (session()->getFlashdata('error_gambar')) ? "<div class='invalid-feedback'>" .
                    session()->getFlashdata('error_gambar') . "</div>" : ''; ?>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>

</div>

<script>
    // document.querySelectorAll('#uploadGambar').forEach(input => {
    //     input.addEventListener('change', function(event) {
    //         var formData = new FormData();
    //         formData.append('gambar', this.files[0]);
    //         formData.append('id', this.getAttribute('data-id')); // Mengambil ID dari atribut data-id

    //         fetch('<?= base_url('albumlists/uploadimage'); ?>', {
    //                 method: 'POST',
    //                 body: formData
    //             })
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     location.reload();
    //                 } else {
    //                     alert('Gagal mengupload gambar. ' + data.error);
    //                 }
    //             })
    //             .catch(error => console.error('Error:', error));
    //     });
    // });

    document.querySelectorAll('[id^="uploadGambar_"]').forEach(input => {
        input.addEventListener('change', function(event) {
            var formData = new FormData();
            formData.append('gambar', this.files[0]);
            formData.append('id', this.getAttribute('data-id')); // Mengambil ID dari atribut data-id

            fetch('<?= base_url('albumlists/uploadimage'); ?>', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Refresh halaman jika upload berhasil
                    } else {
                        Swal.fire({
                            title: 'Gagal!',
                            text: data.error,
                            icon: 'error',
                            timer: 1500,
                        })
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>


<script>
    // JavaScript to copy link to clipboard
    document.querySelectorAll('.copy-link').forEach(button => {
        button.addEventListener('click', function() {
            const link = this.getAttribute('data-clipboard-text');
            const tempInput = document.createElement('input');
            tempInput.value = link;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
        });
    });
</script>



<script>
    $(document).ready(function() {
        $('.delete-gambar').on('click', function() {
            const id = $(this).data('id');
            const gambarElement = $(this).closest('.col-md-12')

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Gambar akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('albumlists/hapusgambar') ?>",
                        type: "POST",
                        data: {
                            id: id
                        },
                        dataType: "json",
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    title: 'Terhapus!',
                                    text: 'Gambar berhasil dihapus.',
                                    icon: 'success',
                                    timer: 1000, // Pesan akan hilang setelah 2 detik (2000 ms)
                                    showConfirmButton: false // Tidak menampilkan tombol OK
                                }).then(() => {
                                    // Menghapus elemen gambar dari DOM
                                    gambarElement.remove();
                                });
                                // Swal.fire(
                                //     'Terhapus!',
                                //     'Gambar berhasil dihapus.',
                                //     'success'
                                // ).then(() => {
                                //     gambarElement.remove();
                                // });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Gagal menghapus gambar: ' + res.message,
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Kesalahan!',
                                'Terjadi kesalahan saat menghubungi server.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>


<?= $this->endsection() ?>