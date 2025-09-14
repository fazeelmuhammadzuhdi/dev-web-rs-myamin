<!DOCTYPE html>
<html lang="id" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <!-- Performance optimizations -->
    <meta name="format-detection" content="telephone=no">
    <meta name="theme-color" content="#2b6cb0">
    
    <!-- Preconnect to external domains -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- DNS prefetch for better performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="dns-prefetch" href="//cdn.jsdelivr.net">
    
    <!-- Critical CSS inline -->
    <style>
        /* Critical CSS - Above the fold styles */
        body { 
            margin: 0; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background-color: #f8f9fa;
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
            background: #000; 
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
            outline: 2px solid #007bff; 
            outline-offset: 2px; 
        }
    </style>
    
    <!-- SEO Meta Tags -->
    <title><?= esc($title ?? 'Dashboard Admin') ?> - RSUD Prof. H. Muhammad Yamin, S.H</title>
    <meta name="description" content="<?= esc($metaDescription ?? 'Dashboard administrasi RSUD Prof. H. Muhammad Yamin, S.H') ?>">
    <meta name="keywords" content="<?= esc($metaKeywords ?? 'admin, dashboard, rumah sakit, pariaman') ?>">
    <meta name="author" content="RSUD Prof. H. Muhammad Yamin, S.H">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url() ?>">
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="preload" href="<?= base_url('assets/css/backend-optimized.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url('assets/css/backend-optimized.css') ?>"></noscript>
    
    <!-- Fonts with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Additional head content -->
    <?= $head ?? '' ?>
</head>
<body class="<?= $bodyClass ?? 'vertical-layout' ?>">
    <!-- Skip Links for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <a href="#navigation" class="skip-link">Skip to navigation</a>
    
    <!-- Screen reader announcements -->
    <div id="announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>
    
    <!-- Start Infobar Notifications Sidebar -->
    <?= $this->include('include/infobar') ?>
    <!-- End Infobar Setting Sidebar -->

    <!-- Start Containerbar -->
    <div id="containerbar">
        <!-- Start Leftbar -->
        <!-- menu -->
        <?= $this->include('include/menu') ?>
        <!-- End Leftbar -->
        
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Topbar Mobile -->
            <?= $this->include('include/header') ?>
            <!-- End Topbar -->

            <!-- Main Content -->
            <main id="main-content" role="main">
                <!-- Content -->
                <?= $this->renderSection('content') ?>
                <!-- End Content -->
            </main>

            <!-- Start Footerbar -->
            <?= $this->include('include/footer') ?>
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    
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
                
                // Log performance metrics
                console.log('Performance Metrics:', metrics);
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
        
        // CSRF Token for AJAX requests
        window.csrfToken = '<?= csrf_hash() ?>';
        window.csrfName = '<?= csrf_token() ?>';
    </script>
    
    <!-- Non-critical JavaScript loaded asynchronously -->
    <script>
        // Load backend-optimized.js asynchronously
        (function() {
            const script = document.createElement('script');
            script.src = '<?= base_url('assets/js/backend-optimized.js') ?>';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        })();
    </script>
    
    <!-- Additional scripts -->
    <?= $scripts ?? '' ?>
    
    <!-- Page-specific scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>