<?= $this->extend('layouts/optimized_backend') ?>
<?= $this->section('content') ?>

<!-- Start Breadcrumbbar -->
<nav class="breadcrumbbar" aria-label="Breadcrumb">
    <div class="row align-items-center">
        <div class="col-md-8 col-lg-8">
            <h1 class="page-title">Form Input Berita</h1>
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
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <a href="<?= site_url('beritas') ?>" itemprop="item">
                        <span itemprop="name">Berita</span>
                    </a>
                    <meta itemprop="position" content="3" />
                </li>
                <li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <span itemprop="name">Tambah Berita</span>
                    <meta itemprop="position" content="4" />
                </li>
            </ol>
        </div>
        <div class="col-md-4 col-lg-4">
            <div class="widgetbar">
                <a href="<?= site_url('beritas') ?>" class="btn btn-warning" role="button" aria-label="Kembali ke daftar berita">
                    <i class="feather icon-arrow-left" aria-hidden="true"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</nav>
<!-- End Breadcrumbbar -->

<!-- Start Contentbar -->
<div class="contentbar">
    <div class="row">
        <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-header">
                    <h2 class="card-title">Form Input Berita</h2>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('beritas/save'); ?>" method="POST" enctype="multipart/form-data" id="beritaForm" novalidate>
                        <?= csrf_field(); ?>

                        <div class="form-group">
                            <label for="kategori_id" class="required">Kategori Berita</label>
                            <select id="kategori_id" 
                                    name="kategori_id" 
                                    class="form-control select2 <?= (session()->getFlashdata('error_kategori_id')) ? 'is-invalid' : '' ?>" 
                                    required
                                    aria-describedby="kategori_id_error"
                                    aria-invalid="<?= (session()->getFlashdata('error_kategori_id')) ? 'true' : 'false' ?>">
                                <option value="" disabled selected>--Pilih Kategori--</option>
                                <?php foreach ($kategori as $item): ?>
                                    <option value="<?= esc($item['idkategori']) ?>" 
                                            <?= (old('kategori_id') == $item['idkategori']) ? 'selected' : '' ?>>
                                        <?= esc($item['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="kategori_id_error" class="invalid-feedback" role="alert">
                                <?= session()->getFlashdata('error_kategori_id') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="judul" class="required">Judul Berita</label>
                            <input type="text" 
                                   id="judul"
                                   name="judul" 
                                   class="form-control <?= (session()->getFlashdata('error_judul')) ? 'is-invalid' : '' ?>" 
                                   value="<?= old('judul') ?>"
                                   required
                                   maxlength="255"
                                   aria-describedby="judul_error judul_help"
                                   aria-invalid="<?= (session()->getFlashdata('error_judul')) ? 'true' : 'false' ?>"
                                   autocomplete="off">
                            <div id="judul_error" class="invalid-feedback" role="alert">
                                <?= session()->getFlashdata('error_judul') ?>
                            </div>
                            <small id="judul_help" class="form-text text-muted">
                                Maksimal 255 karakter. Karakter tersisa: <span id="judul_counter">255</span>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tanggal" class="required">Tanggal Berita</label>
                            <input type="date" 
                                   id="tanggal"
                                   name="tanggal" 
                                   class="form-control <?= (session()->getFlashdata('error_tanggal')) ? 'is-invalid' : '' ?>"
                                   value="<?= old('tanggal') ?>"
                                   required
                                   aria-describedby="tanggal_error"
                                   aria-invalid="<?= (session()->getFlashdata('error_tanggal')) ? 'true' : 'false' ?>">
                            <div id="tanggal_error" class="invalid-feedback" role="alert">
                                <?= session()->getFlashdata('error_tanggal') ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <fieldset>
                                <legend class="required">Status Berita</legend>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" 
                                           type="radio" 
                                           name="status" 
                                           id="status_pb" 
                                           value="PB" 
                                           <?= (old('status', 'PB') == 'PB') ? 'checked' : '' ?>
                                           required
                                           aria-describedby="status_error">
                                    <label class="form-check-label" for="status_pb">
                                        Publish
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input <?= (session()->getFlashdata('error_status')) ? 'is-invalid' : '' ?>" 
                                           type="radio" 
                                           name="status" 
                                           id="status_up" 
                                           value="UP" 
                                           <?= (old('status') == 'UP') ? 'checked' : '' ?>
                                           required
                                           aria-describedby="status_error">
                                    <label class="form-check-label" for="status_up">
                                        Draft
                                    </label>
                                </div>
                                <div id="status_error" class="invalid-feedback" role="alert">
                                    <?= session()->getFlashdata('error_status') ?>
                                </div>
                            </fieldset>
                        </div>

                        <div class="form-group">
                            <label for="konten" class="required">Konten Berita</label>
                            <textarea id="konten"
                                      name="konten" 
                                      class="form-control <?= (session()->getFlashdata('error_konten')) ? 'is-invalid' : '' ?>"
                                      rows="10"
                                      required
                                      aria-describedby="konten_error konten_help"
                                      aria-invalid="<?= (session()->getFlashdata('error_konten')) ? 'true' : 'false' ?>"><?= old('konten') ?></textarea>
                            <div id="konten_error" class="invalid-feedback" role="alert">
                                <?= session()->getFlashdata('error_konten') ?>
                            </div>
                            <small id="konten_help" class="form-text text-muted">
                                Gunakan editor rich text untuk memformat konten
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="gambar" class="required">Gambar Berita</label>
                            <input type="file" 
                                   id="gambar"
                                   name="gambar" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp"
                                   class="form-control-file <?= (session()->getFlashdata('error_gambar')) ? 'is-invalid' : '' ?>"
                                   required
                                   aria-describedby="gambar_error gambar_help"
                                   aria-invalid="<?= (session()->getFlashdata('error_gambar')) ? 'true' : 'false' ?>">
                            <div id="gambar_error" class="invalid-feedback" role="alert">
                                <?= session()->getFlashdata('error_gambar') ?>
                            </div>
                            <small id="gambar_help" class="form-text text-muted">
                                Format yang didukung: JPEG, PNG, JPG, WebP. Maksimal 2MB.
                            </small>
                            <div id="image-preview" class="mt-2" style="display: none;">
                                <img id="preview-img" src="" alt="Preview gambar" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="feather icon-save" aria-hidden="true"></i>
                                Simpan Berita
                            </button>
                            <button type="button" class="btn btn-secondary ml-2" onclick="resetForm()">
                                <i class="feather icon-refresh-cw" aria-hidden="true"></i>
                                Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endsection() ?>

<?= $this->section('scripts') ?>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- TinyMCE -->
<script src="<?= base_url(); ?>/assets/tinymce/tiny_mce.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Pilih kategori',
        allowClear: true,
        width: '100%'
    });

    // Set today's date
    const today = new Date().toISOString().split('T')[0];
    $('#tanggal').val(today);

    // Character counter for title
    $('#judul').on('input', function() {
        const maxLength = 255;
        const currentLength = $(this).val().length;
        const remaining = maxLength - currentLength;
        
        $('#judul_counter').text(remaining);
        
        if (remaining < 0) {
            $(this).addClass('is-invalid');
            $('#judul_error').text('Judul terlalu panjang (maksimal 255 karakter)').show();
        } else {
            $(this).removeClass('is-invalid');
            $('#judul_error').hide();
        }
    });

    // Image preview
    $('#gambar').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                showNotification('Format file tidak didukung. Gunakan JPEG, PNG, JPG, atau WebP.', 'error');
                $(this).val('');
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showNotification('Ukuran file terlalu besar. Maksimal 2MB.', 'error');
                $(this).val('');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#image-preview').show();
            };
            reader.readAsDataURL(file);
        } else {
            $('#image-preview').hide();
        }
    });

    // Form validation
    $('#beritaForm').on('submit', function(e) {
        e.preventDefault();
        
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        let isValid = true;
        
        // Validate required fields
        const requiredFields = ['kategori_id', 'judul', 'tanggal', 'konten', 'gambar'];
        requiredFields.forEach(function(fieldName) {
            const field = $(`[name="${fieldName}"]`);
            if (!field.val() || field.val().trim() === '') {
                field.addClass('is-invalid');
                $(`#${fieldName}_error`).text(`${fieldName.replace('_', ' ')} harus diisi`).show();
                isValid = false;
            }
        });

        // Validate status radio buttons
        if (!$('input[name="status"]:checked').length) {
            $('#status_error').text('Status harus dipilih').show();
            isValid = false;
        }

        if (isValid) {
            // Show loading state
            $('#submitBtn').prop('disabled', true).html('<i class="feather icon-loader" aria-hidden="true"></i> Menyimpan...');
            
            // Submit form
            this.submit();
        } else {
            showNotification('Mohon lengkapi semua field yang wajib diisi', 'error');
            // Focus on first invalid field
            $('.is-invalid').first().focus();
        }
    });

    // Initialize TinyMCE
    tinymce.init({
        selector: '#konten',
        height: 400,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; }',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        },
        // Accessibility improvements
        a11y_advanced_options: true,
        // Performance optimizations
        cache_suffix: '?v=1.0',
        // Security
        verify_html: true,
        cleanup: true,
        cleanup_on_startup: true
    });
});

// Reset form function
function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua data yang telah diisi akan hilang.')) {
        $('#beritaForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        $('#image-preview').hide();
        $('#judul_counter').text('255');
        
        // Reset TinyMCE
        if (tinymce.get('konten')) {
            tinymce.get('konten').setContent('');
        }
        
        // Reset Select2
        $('.select2').val(null).trigger('change');
        
        // Set today's date
        const today = new Date().toISOString().split('T')[0];
        $('#tanggal').val(today);
        
        // Focus on first field
        $('#kategori_id').focus();
        
        showNotification('Form telah direset', 'info');
    }
}

// Enhanced notification system
function showNotification(message, type = 'info') {
    if (typeof PNotify !== 'undefined') {
        new PNotify({
            title: type === 'error' ? 'Error' : type === 'success' ? 'Berhasil' : 'Info',
            text: message,
            type: type,
            delay: 3000,
            styling: 'bootstrap4'
        });
    } else {
        alert(message);
    }
    
    // Announce to screen reader
    if (window.announceToScreenReader) {
        window.announceToScreenReader(message);
    }
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    // Ctrl+S to save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        $('#beritaForm').submit();
    }
    
    // ESC to reset
    if (e.key === 'Escape') {
        resetForm();
    }
});

// Auto-save draft (optional)
let autoSaveTimer;
function startAutoSave() {
    autoSaveTimer = setInterval(function() {
        // Save form data to localStorage
        const formData = {
            judul: $('#judul').val(),
            kategori_id: $('#kategori_id').val(),
            tanggal: $('#tanggal').val(),
            status: $('input[name="status"]:checked').val(),
            konten: tinymce.get('konten') ? tinymce.get('konten').getContent() : ''
        };
        
        localStorage.setItem('berita_draft', JSON.stringify(formData));
    }, 30000); // Auto-save every 30 seconds
}

// Load draft on page load
function loadDraft() {
    const draft = localStorage.getItem('berita_draft');
    if (draft && !$('#judul').val()) {
        try {
            const formData = JSON.parse(draft);
            $('#judul').val(formData.judul || '');
            $('#kategori_id').val(formData.kategori_id || '').trigger('change');
            $('#tanggal').val(formData.tanggal || '');
            if (formData.status) {
                $(`input[name="status"][value="${formData.status}"]`).prop('checked', true);
            }
            if (formData.konten && tinymce.get('konten')) {
                tinymce.get('konten').setContent(formData.konten);
            }
        } catch (e) {
            console.error('Error loading draft:', e);
        }
    }
}

// Clear draft on successful submit
$(document).on('submit', '#beritaForm', function() {
    localStorage.removeItem('berita_draft');
    clearInterval(autoSaveTimer);
});

// Initialize auto-save and load draft
$(document).ready(function() {
    startAutoSave();
    loadDraft();
});
</script>
<?= $this->endsection() ?>