<!DOCTYPE html>
<html dir="ltr" lang="id" class="no-js">
<head>
    <meta name="google-site-verification" content="IxS7-w6bbVaOnsrA-dgHsaZm7ysXYCuVTr3hxnK029g" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="RSUD Prof. H. Muhammad Yamin, S.H">
    
    <!-- Performance optimizations -->
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#188079">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com">
    
    <!-- DNS prefetch for better performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//unpkg.com">
    
    <!-- Critical CSS inline -->
    <style>
        /* Critical CSS - Above the fold styles */
        body { 
            margin: 0; 
            font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            line-height: 1.6;
        }
        .loading { opacity: 0; transition: opacity 0.3s; }
        .loaded { opacity: 1; }
        .sr-only { 
            position: absolute; 
            width: 1px; 
            height: 1px; 
            padding: 0; 
            margin: -1px; 
            overflow: hidden; 
            clip: rect(0, 0, 0, 0); 
            white-space: nowrap; 
            border: 0; 
        }
        .skip-link { 
            position: absolute; 
            top: -40px; 
            left: 6px; 
            background: #188079; 
            color: #fff; 
            padding: 8px 16px; 
            text-decoration: none; 
            border-radius: 4px; 
            font-weight: bold; 
            transition: top 0.3s; 
            z-index: 9999;
        }
        .skip-link:focus { top: 6px; }
        .keyboard-navigation *:focus { 
            outline: 2px solid #188079; 
            outline-offset: 2px; 
        }
        /* Help button styles */
        .help-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #188079;
            color: white;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .help-button:hover {
            background-color: #145c57;
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.3);
        }
        .help-button:focus {
            outline: 2px solid #188079;
            outline-offset: 2px;
        }
    </style>
    
    <!-- SEO Meta Tags -->
    <title><?= esc($title ?? 'RSUD Prof. H. Muhammad Yamin, S.H') ?> - Rumah Sakit Umum Daerah Pariaman</title>
    <meta name="description" content="<?= esc($metaDescription ?? 'RSUD Prof. H. Muhammad Yamin, S.H - Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat yang menyediakan berbagai layanan kesehatan berkualitas dan terpercaya.') ?>">
    <meta name="keywords" content="<?= esc($metaKeywords ?? 'RSUD Prof. H. Muhammad Yamin, S.H, Rumah Sakit Umum Daerah, Sumatera Barat, Kesehatan, PPID, Dokter, Pariaman, Medical Service, Health Care') ?>">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="RSUD Prof. H. Muhammad Yamin, S.H">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="<?= esc($title ?? 'RSUD Prof. H. Muhammad Yamin, S.H') ?>">
    <meta property="og:description" content="<?= esc($metaDescription ?? 'Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat') ?>">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:image" content="<?= base_url('assets/images/og-image.jpg') ?>">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@rsudmyamin">
    <meta name="twitter:title" content="<?= esc($title ?? 'RSUD Prof. H. Muhammad Yamin, S.H') ?>">
    <meta name="twitter:description" content="<?= esc($metaDescription ?? 'Rumah Sakit Umum Daerah di Pariaman, Sumatera Barat') ?>">
    <meta name="twitter:image" content="<?= base_url('assets/images/og-image.jpg') ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url() ?>">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('logo.png') ?>" />
    <link rel="icon" href="<?= base_url('logo.png') ?>" type="image/png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    
    <!-- Manifest -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    
    <!-- Structured Data -->
    <?php if (isset($structuredData)): ?>
        <script type="application/ld+json">
            <?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
        </script>
    <?php endif; ?>
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="preload" href="<?= base_url('frontend/css/frontend-optimized.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url('frontend/css/frontend-optimized.css') ?>"></noscript>
    
    <!-- Fonts with display=swap for better performance -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Poppins:300,400,500,600,700|PT+Serif:400,400i&display=swap">
    </noscript>
    
    <!-- Additional head content -->
    <?= $head ?? '' ?>
</head>
<body class="stretched">
    <!-- Skip Links for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <a href="#navigation" class="skip-link">Skip to navigation</a>
    
    <!-- Screen reader announcements -->
    <div id="announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>
    
    <!-- Document Wrapper -->
    <div id="wrapper" class="clearfix">
        <!-- Header / Menu Navigation Bar -->
        <header id="header" role="banner">
            <?= $this->include('frontend/include/header') ?>
        </header>
        <!-- end Menu Header -->

        <!-- Main Content -->
        <main id="main-content" role="main">
            <!-- Breadcrumbs -->
            <?php if (isset($breadcrumbs)): ?>
                <nav aria-label="Breadcrumb" class="breadcrumb-nav">
                    <div class="container">
                        <ol class="breadcrumb">
                            <?php foreach ($breadcrumbs as $index => $breadcrumb): ?>
                                <li class="breadcrumb-item <?= $index === count($breadcrumbs) - 1 ? 'active' : '' ?>">
                                    <?php if ($index === count($breadcrumbs) - 1): ?>
                                        <span aria-current="page"><?= esc($breadcrumb['title']) ?></span>
                                    <?php else: ?>
                                        <a href="<?= esc($breadcrumb['url']) ?>"><?= esc($breadcrumb['title']) ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                </nav>
            <?php endif; ?>
            
            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </main>
        <!-- end Content -->

        <!-- Footer -->
        <footer id="footer" role="contentinfo">
            <?= $this->include('frontend/include/footer') ?>
        </footer>
        <!-- end Footer -->
    </div><!-- #wrapper end -->

    <!-- Help Button -->
    <button id="helpButton" class="help-button" style="display: none;" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" aria-label="Buka FAQ">
        <span>Butuh Bantuan</span>
        <span aria-hidden="true">❓</span>
    </button>

    <!-- FAQ Modal -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="faqModalLabel">FAQ (Frequently Asked Questions)</h4>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Tutup modal"></button>
                </div>
                <div class="modal-body">
                    <?php 
                    $faq = (new \App\Models\FAQ())->findAll(10);
                    foreach ($faq as $item): 
                    ?>
                        <div class="toggle faq pb-3 mb-3 faq-marketplace faq-authors">
                            <div class="toggle-header">
                                <div class="toggle-icon" aria-hidden="true">
                                    <i class="toggle-closed icon-question-sign"></i>
                                    <i class="toggle-open icon-question-sign"></i>
                                </div>
                                <div class="toggle-title ps-1">
                                    <?= esc($item['pertanyaan']) ?>
                                </div>
                                <div class="toggle-icon" aria-hidden="true">
                                    <i class="toggle-closed icon-line-chevron-down"></i>
                                    <i class="toggle-open icon-line-chevron-up"></i>
                                </div>
                            </div>
                            <div class="toggle-content ps-4">
                                <?= $item['jawaban'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Go To Top -->
    <div id="gotoTop" class="icon-angle-up" role="button" tabindex="0" aria-label="Kembali ke atas"></div>

    <!-- Critical JavaScript loaded inline -->
    <script>
        // Remove no-js class
        document.documentElement.classList.remove('no-js');
        document.documentElement.classList.add('js');
        
        // Performance monitoring
        window.addEventListener('load', function() {
            // Mark page as loaded
            document.body.classList.add('loaded');
            
            // Report performance metrics
            if ('performance' in window) {
                const navigation = performance.getEntriesByType('navigation')[0];
                const paint = performance.getEntriesByType('paint');
                
                const metrics = {
                    domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                    loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                    firstPaint: paint.find(entry => entry.name === 'first-paint')?.startTime || 0,
                    firstContentfulPaint: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0
                };
                
                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'performance_metrics', {
                        custom_map: {
                            'metric_1': 'dom_content_loaded',
                            'metric_2': 'load_complete',
                            'metric_3': 'first_paint',
                            'metric_4': 'first_contentful_paint'
                        },
                        dom_content_loaded: Math.round(metrics.domContentLoaded),
                        load_complete: Math.round(metrics.loadComplete),
                        first_paint: Math.round(metrics.firstPaint),
                        first_contentful_paint: Math.round(metrics.firstContentfulPaint)
                    });
                }
            }
        });
        
        // Accessibility improvements
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
        
        // Screen reader announcements
        window.announceToScreenReader = function(message) {
            const announcer = document.getElementById('announcer');
            if (announcer) {
                announcer.textContent = message;
                setTimeout(() => {
                    announcer.textContent = '';
                }, 1000);
            }
        };
        
        // Help button functionality
        window.onscroll = function() {
            const helpButton = document.getElementById("helpButton");
            if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                helpButton.style.display = "block";
            } else {
                helpButton.style.display = "none";
            }
        };
        
        // Go to top functionality
        document.getElementById('gotoTop').addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
        
        // Keyboard support for go to top
        document.getElementById('gotoTop').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    </script>
    
    <!-- Non-critical JavaScript loaded asynchronously -->
    <script>
        // Load frontend-optimized.js asynchronously
        (function() {
            const script = document.createElement('script');
            script.src = '<?= base_url('frontend/js/frontend-optimized.js') ?>';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        })();
    </script>
    
    <!-- Additional scripts -->
    <?= $this->renderSection('extra-script') ?>
    <?= $scripts ?? '' ?>
    
    <!-- Analytics (loaded after page load) -->
    <?php if (ENVIRONMENT === 'production'): ?>
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    // Load analytics script
                    const script = document.createElement('script');
                    script.src = 'https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID';
                    script.async = true;
                    document.head.appendChild(script);
                    
                    // Initialize gtag
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('config', 'GA_MEASUREMENT_ID');
                }, 1000);
            });
        </script>
    <?php endif; ?>
</body>
</html>