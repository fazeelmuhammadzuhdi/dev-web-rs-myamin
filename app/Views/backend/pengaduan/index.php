<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>

<!-- Start Breadcrumbbar -->
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

        <div class="col-md-4 col-lg-4 d-flex justify-content-end">
            <!-- <div class="widgetbar mr-2">
                <button onclick="cetakLaporanPermintaanPPID()" class="btn btn-danger">Cetak</button>
            </div> -->

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
                    <h5 class="card-title">List Data <?= $title ?></h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tableBanner">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">No.Telp</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">Kronologi Kejadian</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End row -->
</div>
<!-- End Contentbar -->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    // datatable
    $(document).ready(function() {
        $('#tableBanner').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: '<?= site_url('bunga-seroja/getData') ?>',
            order: [],
            columnDefs: [{
                targets: 0,
                orderable: false,
                width: 1
            }]
        });

    });

    function hapus(id, judul) {
        Swal.fire({
            title: 'Hapus Data Pengaduan Bunga Seroja',
            text: 'Yakin Menghapus Data Pengaduan Bunga Seroja Dengan Nama Pemohon : ' + judul + ' Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            dangerMode: true,
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-danger mr-2',
                cancelButton: 'btn btn-secondary'
            },
            iconHtml: '<i class="feather icon-alert-circle"></i>'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: 'delete',
                    url: '<?= site_url('bunga-seroja/delete/') ?>' + id,
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            Swal.fire({
                                title: 'Error!',
                                text: response.error,
                                icon: 'error',
                            });
                        }

                        if (response.sukses) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.sukses,
                                icon: 'success',
                                timer: 750,
                            }).then((value) => {
                                // hilangkan notif swal2-popup
                                $('#tableBanner').DataTable().ajax.reload();
                            });
                        }
                    },
                    error: function(e) {
                        alert('Error \n' + e.responseText);
                    }
                });
            } else {
                Swal.fire({
                    title: 'Batal Di Hapus',
                    icon: 'info',
                    timer: 750,
                    showConfirmButton: false
                });
            }
        });
    }


    function cetakLaporanPermintaanPPID() {
        window.open("/laporan/permintaan-informasi-ppid", "_blank");
    }
</script>



<?= $this->endsection() ?>