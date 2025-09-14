<!-- #content end -->

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flash Data Message -->
<script>
    <?php if (session()->getFlashdata('success')) : ?>
        Swal.fire({
            title: "Terima Kasih!",
            text: "<?= session()->getFlashdata('success'); ?>",
            icon: "success",
            timer: 2000,
        });
    <?php elseif (session()->getFlashdata('error')) : ?>
        Swal.fire({
            title: "Oops...",
            text: "<?= session()->getFlashdata('error'); ?>",
            icon: "error",
            timer: 2000,
        });
    <?php endif; ?>
</script>