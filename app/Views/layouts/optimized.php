<!DOCTYPE html>
<html lang="<?= $lang ?? 'id' ?>" class="no-js">
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
    
    <!-- DNS prefetch for better performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    
    <!-- Critical CSS inline -->
    <style>
        /* Critical CSS - Above the fold styles */
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        .hero-section { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-align: center; }
        .hero-title { font-size: 3rem; font-weight: 700; margin-bottom: 1rem; line-height: 1.2; }
        .hero-subtitle { font-size: 1.25rem; margin-bottom: 2rem; opacity: 0.9; }
        .loading { opacity: 0; transition: opacity 0.3s; }
        .loaded { opacity: 1; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0; }
        .skip-link { position: absolute; top: -40px; left: 6px; background: #000; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; font-weight: bold; transition: top 0.3s; }
        .skip-link:focus { top: 6px; }
    </style>
    
    <!-- SEO Meta Tags -->
    <?= generateMetaTags($meta ?? []) ?>
    
    <!-- Structured Data -->
    <?php if (isset($structuredData)): ?>
        <?= generateStructuredData($structuredData) ?>
    <?php endif; ?>
    
    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="preload" href="<?= base_url('assets/css/performance.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url('assets/css/performance.css') ?>"></noscript>
    
    <!-- Fonts with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">
    
    <!-- Manifest -->
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url() ?>">
    
    <!-- Robots -->
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="<?= $siteName ?? 'Rumah Sakit' ?>">
    <meta property="og:locale" content="id_ID">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@rumahsakit">
    
    <!-- Additional head content -->
    <?= $head ?? '' ?>
</head>
<body class="<?= $bodyClass ?? '' ?>">
    <!-- Skip Links for Accessibility -->
    <?= addSkipLinks($skipLinks ?? []) ?>
    
    <!-- Screen reader announcements -->
    <div id="announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>
    
    <!-- Header -->
    <header id="header" role="banner">
        <?= $header ?? '' ?>
    </header>
    
    <!-- Navigation -->
    <nav id="navigation" role="navigation" aria-label="Main navigation">
        <?= $navigation ?? '' ?>
    </nav>
    
    <!-- Main Content -->
    <main id="main-content" role="main">
        <!-- Breadcrumbs -->
        <?php if (isset($breadcrumbs)): ?>
            <?= generateBreadcrumbs($breadcrumbs) ?>
        <?php endif; ?>
        
        <!-- Page Content -->
        <?= $content ?? '' ?>
    </main>
    
    <!-- Sidebar -->
    <?php if (isset($sidebar)): ?>
        <aside id="sidebar" role="complementary">
            <?= $sidebar ?>
        </aside>
    <?php endif; ?>
    
    <!-- Footer -->
    <footer id="footer" role="contentinfo">
        <?= $footer ?? '' ?>
    </footer>
    
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
    </script>
    
    <!-- Non-critical JavaScript loaded asynchronously -->
    <script>
        // Load performance.js asynchronously
        (function() {
            const script = document.createElement('script');
            script.src = '<?= base_url('assets/js/performance.js') ?>';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        })();
    </script>
    
    <!-- Additional scripts -->
    <?= $scripts ?? '' ?>
    
    <!-- Analytics (loaded after page load) -->
    <?php if (ENVIRONMENT === 'production' && isset($analytics)): ?>
        <script>
            window.addEventListener('load', function() {
                setTimeout(function() {
                    // Load analytics script
                    const script = document.createElement('script');
                    script.src = '<?= $analytics ?>';
                    script.async = true;
                    document.head.appendChild(script);
                }, 1000);
            });
        </script>
    <?php endif; ?>
</body>
</html>