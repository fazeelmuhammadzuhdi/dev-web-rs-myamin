<?= $this->extend('backend/main/layout') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>

<!-- <style>
    #tableBanner th,
    #tableBanner td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }
</style> -->

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
            <?php if (!empty($tipe) && $tipe == 'dip') : ?>
                <div class="widgetbar mr-2">
                    <button onclick="cetakLaporanDIP()" class="btn btn-danger">Cetak DIP</button>
                </div>
            <?php endif; ?>

            <div class="widgetbar">
                <a href="<?= site_url('beritappid/create/' . $tipe) ?>" class="btn btn-primary">Add <?= $title ?></a>
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
                    <h5 class="card-title">List Data <?= $title ?></h5>
                </div>
                <div class="card-body">
                    <!-- Add filtering options here -->
                    <div class="mb-3">
                        <label for="startYear" class="form-label">Filter Berdasarkan Tahun</label>
                        <div class="d-flex">
                            <input type="number" class="form-control" id="startYear" placeholder="Start Year">
                            <input type="number" class="form-control ms-2" id="endYear" placeholder="End Year">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="tableBanner">
                            <thead>
                                <tr>
                                    <th scope="col">Id </th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Judul</th>
                                    <th scope="col">Kategori</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Link</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Table rows will be added dynamically -->
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
            ajax: '<?= site_url('beritappid/getData/' . $tipe) ?>',
            order: [],
            columnDefs: [{
                targets: 0,
                orderable: false,
                width: 5
            }]
        });

    });

    function hapus(id, judul) {
        Swal.fire({
            title: 'Hapus Data Berita',
            text: 'Yakin Menghapus Berita Dengan Judul : ' + judul + ' Ini?',
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
                    url: '<?= site_url('beritappid/delete/') ?>' + id,
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


    function edit(id, tipe) {
        window.location = '<?= site_url('beritappid/edit/') ?>' + id + '/' + tipe;
    }

    // function cetakLaporanDIP() {
    //     window.open("/laporan/daftar-informasi-publik", "_blank");
    // }

    function cetakLaporanDIP() {
        // Get the year range from the input fields
        var startYear = document.getElementById('startYear').value;
        var endYear = document.getElementById('endYear').value;

        // Build the URL with the filter parameters if available
        var url = '/laporan/daftar-informasi-publik';
        if (startYear || endYear) {
            url += '?startYear=' + startYear + '&endYear=' + endYear;
        }

        // Open the filtered report page
        window.open(url, "_blank");
    }
</script>



<?= $this->endsection() ?>