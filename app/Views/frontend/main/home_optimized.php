<?= $this->extend('layouts/optimized_frontend') ?>
<?= $this->section('content') ?>

<?= $this->include('frontend/include/home/style') ?>

<!-- Structured Data for Home Page -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Hospital",
    "name": "RSUD Prof. H. Muhammad Yamin, S.H",
    "description": "Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat yang menyediakan berbagai layanan kesehatan berkualitas dan terpercaya",
    "url": "<?= base_url() ?>",
    "logo": "<?= base_url('assets/images/rsud.png') ?>",
    "image": "<?= base_url('assets/images/rsud.png') ?>",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Jl. Prof. Dr. Hamka No. 1",
        "addressLocality": "Pariaman",
        "addressRegion": "Sumatera Barat",
        "postalCode": "25511",
        "addressCountry": "ID"
    },
    "telephone": "+62-751-91000",
    "email": "info@rsudmyamin.sumbarprov.go.id",
    "openingHours": "Mo-Su 00:00-23:59",
    "medicalSpecialty": [
        "Emergency Medicine",
        "Internal Medicine",
        "Surgery",
        "Pediatrics",
        "Obstetrics and Gynecology"
    ],
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Medical Services",
        "itemListElement": [
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "MedicalProcedure",
                    "name": "Emergency Services"
                }
            },
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "MedicalProcedure",
                    "name": "Outpatient Services"
                }
            }
        ]
    }
}
</script>

<section id="content" role="main">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="row gutter-40 col-mb-80">
                <div class="postcontent col-lg-12">
                    <!-- Hero Section -->
                    <div class="hero-section" data-aos="fade-up">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <h1 class="hero-title">Selamat Datang di RSUD Prof. H. Muhammad Yamin, S.H</h1>
                                <p class="hero-subtitle">Rumah Sakit Umum Daerah terpercaya di Pariaman, Sumatera Barat yang menyediakan layanan kesehatan berkualitas tinggi dengan teknologi modern dan tenaga medis profesional.</p>
                                <div class="hero-actions">
                                    <a href="<?= site_url('dokter-kami') ?>" class="btn btn-primary btn-lg" role="button" aria-label="Lihat daftar dokter kami">
                                        <i class="icon-user-md" aria-hidden="true"></i>
                                        Dokter Kami
                                    </a>
                                    <a href="<?= site_url('kontak') ?>" class="btn btn-outline-primary btn-lg" role="button" aria-label="Hubungi kami">
                                        <i class="icon-phone" aria-hidden="true"></i>
                                        Hubungi Kami
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="hero-image">
                                    <img src="<?= base_url('assets/images/hero-image.jpg') ?>" 
                                         alt="RSUD Prof. H. Muhammad Yamin, S.H - Rumah Sakit Modern" 
                                         class="img-fluid rounded"
                                         loading="lazy">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- About Us Section -->
                    <section class="about-section" data-aos="fade-up" data-aos-delay="100">
                        <div class="section-header">
                            <h2 class="section-title">Tentang Kami</h2>
                            <p class="section-subtitle">Komitmen kami dalam memberikan pelayanan kesehatan terbaik</p>
                        </div>
                        <?= $this->include('frontend/include/home/about-us') ?>
                    </section>

                    <!-- Services Section -->
                    <section class="services-section" data-aos="fade-up" data-aos-delay="200">
                        <div class="section-header">
                            <h2 class="section-title">Layanan Kami</h2>
                            <p class="section-subtitle">Berbagai layanan kesehatan yang kami sediakan</p>
                        </div>
                        <?= $this->include('frontend/include/home/service') ?>
                    </section>

                    <!-- Latest News Section -->
                    <section class="news-section" data-aos="fade-up" data-aos-delay="300">
                        <div class="section-header">
                            <h2 class="section-title">Berita Terkini</h2>
                            <p class="section-subtitle">Informasi terbaru seputar rumah sakit dan kesehatan</p>
                        </div>
                        <?= $this->include('frontend/include/home/berita-terkini-1') ?>
                    </section>

                    <!-- Polyclinic Schedule Section -->
                    <section class="schedule-section" data-aos="fade-up" data-aos-delay="400">
                        <div class="section-header">
                            <h2 class="section-title">Jadwal Poliklinik</h2>
                            <p class="section-subtitle">Jadwal praktik dokter di poliklinik kami</p>
                        </div>
                        <?= $this->include('frontend/include/home/jadwal-poli-1') ?>
                    </section>

                    <!-- Gallery Section -->
                    <section class="gallery-section" data-aos="fade-up" data-aos-delay="500">
                        <div class="section-header">
                            <h2 class="section-title">Galeri</h2>
                            <p class="section-subtitle">Momen-momen penting di rumah sakit kami</p>
                        </div>
                        <?= $this->include('frontend/include/home/gallery') ?>
                    </section>

                    <!-- Video Section -->
                    <section class="video-section" data-aos="fade-up" data-aos-delay="600">
                        <div class="section-header">
                            <h2 class="section-title">Video</h2>
                            <p class="section-subtitle">Video informatif seputar layanan kesehatan</p>
                        </div>
                        <?= $this->include('frontend/include/home/vidio') ?>
                    </section>
                </div>

                <!-- Sidebar Content -->
                <div class="row">
                    <div class="col-lg-8">
                        <section class="awards-section" data-aos="fade-up" data-aos-delay="700">
                            <div class="section-header">
                                <h2 class="section-title">Penghargaan</h2>
                                <p class="section-subtitle">Prestasi dan penghargaan yang telah kami raih</p>
                            </div>
                            <?= $this->include('frontend/include/home/penghargaan-1') ?>
                        </section>
                    </div>

                    <div class="col-lg-4 mt-5 mt-lg-0">
                        <section class="polling-section" data-aos="fade-up" data-aos-delay="800">
                            <div class="section-header">
                                <h2 class="section-title">Hasil Polling</h2>
                                <p class="section-subtitle">Pendapat masyarakat tentang layanan kami</p>
                            </div>
                            <?= $this->include('frontend/include/polling') ?>
                        </section>
                    </div>
                </div>

                <!-- Partners Section -->
                <section class="partners-section" data-aos="fade-up" data-aos-delay="900">
                    <div class="section-header">
                        <h2 class="section-title">Mitra Kami</h2>
                        <p class="section-subtitle">Organisasi dan institusi yang bekerja sama dengan kami</p>
                    </div>
                    <?= $this->include('frontend/include/home/partners-2') ?>
                </section>

                <!-- Contact Section -->
                <section class="contact-section" data-aos="fade-up" data-aos-delay="1000">
                    <div class="section-header">
                        <h2 class="section-title">Hubungi Kami</h2>
                        <p class="section-subtitle">Kami siap membantu dan melayani Anda</p>
                    </div>
                    <?= $this->include('frontend/include/home/pesan-1') ?>
                </section>
            </div>
        </div>
    </div>
</section>

<!-- Emergency Contact Banner -->
<div class="emergency-banner" role="banner" aria-label="Kontak Darurat">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="emergency-title">
                    <i class="icon-phone" aria-hidden="true"></i>
                    Butuh Bantuan Darurat?
                </h3>
                <p class="emergency-subtitle">Hubungi IGD kami 24/7 untuk layanan darurat</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="tel:+62-751-91000" class="btn btn-danger btn-lg emergency-btn" role="button" aria-label="Hubungi nomor darurat">
                    <i class="icon-phone" aria-hidden="true"></i>
                    +62-751-91000
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->include('frontend/include/home/script') ?>

<?= $this->endsection() ?>

<?= $this->section('extra-script') ?>
<script>
// Initialize AOS (Animate On Scroll)
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
});

// Lazy loading for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
});

// Performance monitoring
window.addEventListener('load', function() {
    // Report Core Web Vitals
    if ('web-vital' in window) {
        getCLS(console.log);
        getFID(console.log);
        getFCP(console.log);
        getLCP(console.log);
        getTTFB(console.log);
    }
});

// Accessibility improvements
document.addEventListener('keydown', function(e) {
    // Skip to main content with Enter key
    if (e.key === 'Enter' && e.target.classList.contains('skip-link')) {
        e.preventDefault();
        const target = document.querySelector(e.target.getAttribute('href'));
        if (target) {
            target.focus();
            target.scrollIntoView({ behavior: 'smooth' });
        }
    }
});

// Announce page load to screen readers
if (window.announceToScreenReader) {
    window.announceToScreenReader('Halaman beranda telah dimuat');
}
</script>
<?= $this->endsection() ?>