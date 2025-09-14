<?= $this->extend('layouts/optimized_backend') ?>
<?= $this->section('content') ?>

<?= $this->include('include/pesan') ?>

<!-- Start Breadcrumbbar -->
<nav class="breadcrumbbar" aria-label="Breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h1 class="page-title"><?= esc($title) ?></h1>
            <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?= site_url('home') ?>" itemprop="item">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1" />
                </li>
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?= site_url('home') ?>" itemprop="item">
                        <span itemprop="name">Dashboard</span>
                    </a>
                    <meta itemprop="position" content="2" />
                </li>
                <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name"><?= esc($title) ?></span>
                    <meta itemprop="position" content="3" />
                </li>
            </ol>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('beritas/create') ?>" class="btn btn-primary" role="button" aria-label="Tambah <?= esc($title) ?> baru">
                    <i class="feather icon-plus" aria-hidden="true"></i>
                    Add <?= esc($title) ?>
                </a>
            </div>
        </div>
    </div>
</nav>
<!-- End Breadcrumbbar -->

<!-- Start Contentbar -->
<div class="contentbar">
    <!-- Start row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h2 class="card-title">List Data <?= esc($title) ?></h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tableBerita" role="table" aria-label="Daftar <?= esc($title) ?>">
                            <caption class="sr-only">Tabel berisi daftar <?= esc($title) ?> dengan kolom ID, Judul, Kategori, Tanggal, Status, Gambar, Upload By, dan Action</caption>
                            <thead>
                                <tr role="row">
                                    <th scope="col" role="columnheader" aria-sort="none">ID</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Judul</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Kategori</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Tanggal</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Status</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Gambar</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Upload By</th>
                                    <th scope="col" role="columnheader" aria-sort="none">Action</th>
                                </tr>
                            </thead>
                            <tbody role="rowgroup">
                                <!-- Data will be loaded via AJAX -->
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

<?= $this->endsection() ?>

<?= $this->section('scripts') ?>
<script>
// Enhanced DataTable with accessibility and performance improvements
$(document).ready(function() {
    // Initialize DataTable with accessibility features
    const table = $('#tableBerita').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?= site_url('beritas/getData') ?>',
            type: 'GET',
            error: function(xhr, error, thrown) {
                console.error('DataTable AJAX Error:', error);
                showNotification('Error loading data', 'error');
            }
        },
        order: [],
        columnDefs: [{
            targets: 0,
            orderable: false,
            width: 5,
            className: 'text-center'
        }, {
            targets: -1, // Last column (Action)
            orderable: false,
            searchable: false,
            className: 'text-center'
        }],
        language: {
            processing: 'Memproses data...',
            lengthMenu: 'Tampilkan _MENU_ data per halaman',
            zeroRecords: 'Tidak ada data yang ditemukan',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
            infoFiltered: '(disaring dari _MAX_ total data)',
            search: 'Cari:',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        },
        // Accessibility improvements
        drawCallback: function() {
            // Add ARIA labels to action buttons
            $('.btn[onclick*="hapus"]').attr('aria-label', 'Hapus data');
            $('.btn[onclick*="edit"]').attr('aria-label', 'Edit data');
            
            // Announce table updates to screen readers
            if (window.announceToScreenReader) {
                window.announceToScreenReader('Tabel berita telah diperbarui');
            }
        }
    });

    // Add loading state
    table.on('processing.dt', function(e, settings, processing) {
        if (processing) {
            $('.table-responsive').addClass('loading');
        } else {
            $('.table-responsive').removeClass('loading');
        }
    });
});

// Enhanced delete function with better UX and accessibility
function hapus(id, judul) {
    // Validate inputs
    if (!id || !judul) {
        showNotification('Data tidak valid', 'error');
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus berita "${judul}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-danger mr-2',
            cancelButton: 'btn btn-secondary'
        },
        iconHtml: '<i class="feather icon-alert-circle" aria-hidden="true"></i>',
        focusConfirm: false,
        allowOutsideClick: false,
        allowEscapeKey: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Menghapus...',
                text: 'Sedang menghapus data',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Make AJAX request
            $.ajax({
                type: 'DELETE',
                url: '<?= site_url('beritas/delete/') ?>' + encodeURIComponent(id),
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                timeout: 10000, // 10 second timeout
                success: function(response) {
                    Swal.close();
                    
                    if (response.error) {
                        showNotification(response.error, 'error');
                        return;
                    }

                    if (response.sukses) {
                        showNotification(response.sukses, 'success');
                        
                        // Reload table
                        $('#tableBerita').DataTable().ajax.reload(null, false);
                        
                        // Announce to screen reader
                        if (window.announceToScreenReader) {
                            window.announceToScreenReader('Data berhasil dihapus');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();
                    
                    let errorMessage = 'Terjadi kesalahan saat menghapus data';
                    
                    if (xhr.status === 404) {
                        errorMessage = 'Data tidak ditemukan';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki izin untuk menghapus data ini';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Terjadi kesalahan server';
                    } else if (status === 'timeout') {
                        errorMessage = 'Request timeout, silakan coba lagi';
                    }
                    
                    showNotification(errorMessage, 'error');
                    console.error('Delete Error:', error);
                }
            });
        } else {
            // Announce cancellation to screen reader
            if (window.announceToScreenReader) {
                window.announceToScreenReader('Penghapusan dibatalkan');
            }
        }
    });
}

// Enhanced edit function
function edit(id) {
    if (!id) {
        showNotification('ID tidak valid', 'error');
        return;
    }
    
    // Add loading state
    const editButton = $(`button[onclick="edit('${id}')"]`);
    const originalText = editButton.html();
    editButton.html('<i class="feather icon-loader" aria-hidden="true"></i> Loading...').prop('disabled', true);
    
    // Navigate to edit page
    window.location.href = '<?= site_url('beritas/edit/') ?>' + encodeURIComponent(id);
}

// Enhanced notification system
function showNotification(message, type = 'info') {
    // Use existing notification system or create new one
    if (typeof PNotify !== 'undefined') {
        new PNotify({
            title: type === 'error' ? 'Error' : type === 'success' ? 'Berhasil' : 'Info',
            text: message,
            type: type,
            delay: 3000,
            styling: 'bootstrap4'
        });
    } else {
        // Fallback to alert
        alert(message);
    }
    
    // Announce to screen reader
    if (window.announceToScreenReader) {
        window.announceToScreenReader(message);
    }
}

// Keyboard navigation support
$(document).on('keydown', function(e) {
    // ESC key to close modals
    if (e.key === 'Escape') {
        $('.modal').modal('hide');
    }
    
    // Enter key on action buttons
    if (e.key === 'Enter' && $(e.target).hasClass('btn')) {
        e.preventDefault();
        $(e.target).click();
    }
});

// Add ARIA live region for dynamic content updates
if (!document.getElementById('aria-live-region')) {
    const liveRegion = document.createElement('div');
    liveRegion.id = 'aria-live-region';
    liveRegion.setAttribute('aria-live', 'polite');
    liveRegion.setAttribute('aria-atomic', 'true');
    liveRegion.className = 'sr-only';
    document.body.appendChild(liveRegion);
}
</script>
<?= $this->endsection() ?>