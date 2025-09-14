<?php
/**
 * Example of Optimized View
 * Demonstrates best practices for view optimization
 */

// Example data
$data = [
    'title' => 'Contoh Halaman Optimized',
    'description' => 'Halaman ini menunjukkan penggunaan sistem optimasi view',
    'keywords' => 'optimasi, view, performa, aksesibilitas, seo',
    'images' => [
        [
            'src' => 'assets/images/hero.jpg',
            'alt' => 'Hero image dengan deskripsi yang jelas',
            'width' => 1920,
            'height' => 1080
        ],
        [
            'src' => 'assets/images/feature.jpg',
            'alt' => 'Feature image dengan deskripsi yang jelas',
            'width' => 800,
            'height' => 600
        ]
    ],
    'articles' => [
        [
            'title' => 'Artikel Pertama',
            'excerpt' => 'Ini adalah excerpt dari artikel pertama...',
            'url' => 'artikel/pertama',
            'image' => 'assets/images/article1.jpg',
            'alt' => 'Gambar artikel pertama'
        ],
        [
            'title' => 'Artikel Kedua',
            'excerpt' => 'Ini adalah excerpt dari artikel kedua...',
            'url' => 'artikel/kedua',
            'image' => 'assets/images/article2.jpg',
            'alt' => 'Gambar artikel kedua'
        ]
    ]
];

// Generate meta tags
$meta = [
    'title' => $data['title'],
    'description' => $data['description'],
    'keywords' => $data['keywords'],
    'og_title' => $data['title'],
    'og_description' => $data['description'],
    'og_image' => base_url('assets/images/og-image.jpg'),
    'og_url' => current_url(),
    'twitter_card' => 'summary_large_image',
    'twitter_title' => $data['title'],
    'twitter_description' => $data['description'],
    'twitter_image' => base_url('assets/images/twitter-image.jpg')
];

// Generate structured data
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => $data['title'],
    'description' => $data['description'],
    'url' => current_url(),
    'mainEntity' => [
        '@type' => 'Organization',
        'name' => 'Rumah Sakit',
        'url' => base_url()
    ]
];

// Generate breadcrumbs
$breadcrumbs = [
    ['url' => base_url(), 'text' => 'Home'],
    ['url' => base_url('contoh'), 'text' => 'Contoh'],
    ['url' => current_url(), 'text' => 'Optimized Example']
];

// Generate skip links
$skipLinks = [
    ['href' => '#main-content', 'text' => 'Skip to main content'],
    ['href' => '#navigation', 'text' => 'Skip to navigation'],
    ['href' => '#footer', 'text' => 'Skip to footer']
];
?>

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

    <!-- DNS prefetch for better performance -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Critical CSS inline -->
    <style>
        /* Critical CSS - Above the fold styles */
        body { 
            margin: 0; 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            line-height: 1.6;
        }
        .hero-section { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            color: white; 
            text-align: center; 
        }
        .hero-title { 
            font-size: 3rem; 
            font-weight: 700; 
            margin-bottom: 1rem; 
            line-height: 1.2; 
        }
        .hero-subtitle { 
            font-size: 1.25rem; 
            margin-bottom: 2rem; 
            opacity: 0.9; 
        }
        .loading { 
            opacity: 0; 
            transition: opacity 0.3s; 
        }
        .loaded { 
            opacity: 1; 
        }
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
        }
        .skip-link:focus { 
            top: 6px; 
        }
        .keyboard-navigation *:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
        }
    </style>

    <!-- SEO Meta Tags -->
    <title><?= htmlspecialchars($meta['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($meta['description']) ?>">
    <meta name="keywords" content="<?= htmlspecialchars($meta['keywords']) ?>">
    <meta name="author" content="Rumah Sakit">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2b6cb0">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Rumah Sakit">
    <meta property="og:locale" content="id_ID">
    <meta property="og:title" content="<?= htmlspecialchars($meta['og_title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($meta['og_description']) ?>">
    <meta property="og:image" content="<?= htmlspecialchars($meta['og_image']) ?>">
    <meta property="og:url" content="<?= htmlspecialchars($meta['og_url']) ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="<?= htmlspecialchars($meta['twitter_card']) ?>">
    <meta name="twitter:site" content="@rumahsakit">
    <meta name="twitter:title" content="<?= htmlspecialchars($meta['twitter_title']) ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($meta['twitter_description']) ?>">
    <meta name="twitter:image" content="<?= htmlspecialchars($meta['twitter_image']) ?>">

    <!-- Structured Data -->
    <script type="application/ld+json">
    <?= json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>

    <!-- Non-critical CSS loaded asynchronously -->
    <link rel="preload" href="<?= base_url('assets/css/optimized.css') ?>" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?= base_url('assets/css/optimized.css') ?>"></noscript>

    <!-- Fonts with display=swap for better performance -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('apple-touch-icon.png') ?>">

    <!-- Canonical URL -->
    <link rel="canonical" href="<?= current_url() ?>">
</head>
<body class="loading">
    <!-- Skip Links for Accessibility -->
    <?php foreach ($skipLinks as $link): ?>
        <a href="<?= htmlspecialchars($link['href']) ?>" class="skip-link"><?= htmlspecialchars($link['text']) ?></a>
    <?php endforeach; ?>

    <!-- Screen reader announcements -->
    <div id="announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>

    <!-- Header -->
    <header id="header" role="banner">
        <nav id="navigation" role="navigation" aria-label="Main navigation">
            <div class="container">
                <div class="nav-brand">
                    <a href="<?= base_url() ?>" aria-label="Home">
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo Rumah Sakit" width="150" height="50">
                    </a>
                </div>
                <ul class="nav-menu" role="menubar">
                    <li role="none"><a href="<?= base_url() ?>" role="menuitem">Home</a></li>
                    <li role="none"><a href="<?= base_url('about') ?>" role="menuitem">About</a></li>
                    <li role="none"><a href="<?= base_url('services') ?>" role="menuitem">Services</a></li>
                    <li role="none"><a href="<?= base_url('contact') ?>" role="menuitem">Contact</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main id="main-content" role="main">
        <!-- Breadcrumbs -->
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index === count($breadcrumbs) - 1): ?>
                        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($crumb['text']) ?></li>
                    <?php else: ?>
                        <li class="breadcrumb-item">
                            <a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['text']) ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section" aria-labelledby="hero-title">
            <div class="container">
                <h1 id="hero-title" class="hero-title"><?= htmlspecialchars($data['title']) ?></h1>
                <p class="hero-subtitle"><?= htmlspecialchars($data['description']) ?></p>
                <a href="#content" class="btn btn-primary" aria-label="Learn more about our services">Learn More</a>
            </div>
        </section>

        <!-- Content Section -->
        <section id="content" class="content-section" aria-labelledby="content-title">
            <div class="container">
                <h2 id="content-title">Optimized Content</h2>
                
                <!-- Images with lazy loading -->
                <div class="image-gallery">
                    <?php foreach ($data['images'] as $image): ?>
                        <img src="<?= base_url($image['src']) ?>" 
                             alt="<?= htmlspecialchars($image['alt']) ?>" 
                             loading="lazy" 
                             decoding="async"
                             width="<?= $image['width'] ?>" 
                             height="<?= $image['height'] ?>"
                             class="img-fluid">
                    <?php endforeach; ?>
                </div>

                <!-- Articles -->
                <div class="articles-grid">
                    <?php foreach ($data['articles'] as $article): ?>
                        <article class="article-card">
                            <img src="<?= base_url($article['image']) ?>" 
                                 alt="<?= htmlspecialchars($article['alt']) ?>" 
                                 loading="lazy" 
                                 decoding="async"
                                 class="article-image">
                            <div class="article-content">
                                <h3 class="article-title">
                                    <a href="<?= base_url($article['url']) ?>" aria-label="Read article: <?= htmlspecialchars($article['title']) ?>">
                                        <?= htmlspecialchars($article['title']) ?>
                                    </a>
                                </h3>
                                <p class="article-excerpt"><?= htmlspecialchars($article['excerpt']) ?></p>
                                <a href="<?= base_url($article['url']) ?>" class="btn btn-outline-primary" aria-label="Read full article: <?= htmlspecialchars($article['title']) ?>">
                                    Read More
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="footer" role="contentinfo">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <address>
                        <p>Jl. Contoh No. 123</p>
                        <p>Kota Contoh, 12345</p>
                        <p>Phone: <a href="tel:+62123456789" aria-label="Call us at +62 123 456 789">+62 123 456 789</a></p>
                        <p>Email: <a href="mailto:info@rumahsakit.com" aria-label="Email us at info@rumahsakit.com">info@rumahsakit.com</a></p>
                    </address>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="<?= base_url('about') ?>">About Us</a></li>
                        <li><a href="<?= base_url('services') ?>">Services</a></li>
                        <li><a href="<?= base_url('contact') ?>">Contact</a></li>
                        <li><a href="<?= base_url('privacy') ?>">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Rumah Sakit. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Go to Top Button -->
    <button id="gotoTop" class="goto-top" aria-label="Go to top of page" style="display: none;">
        <span class="sr-only">Go to top</span>
        ↑
    </button>

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

        // Go to top functionality
        const goToTopBtn = document.getElementById('gotoTop');
        if (goToTopBtn) {
            window.addEventListener('scroll', () => {
                if (window.pageYOffset > 300) {
                    goToTopBtn.style.display = 'flex';
                } else {
                    goToTopBtn.style.display = 'none';
                }
            });

            goToTopBtn.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            goToTopBtn.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            });
        }
    </script>

    <!-- Non-critical JavaScript loaded asynchronously -->
    <script>
        // Load optimized.js asynchronously
        (function() {
            const script = document.createElement('script');
            script.src = '<?= base_url('assets/js/optimized.js') ?>';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        })();
    </script>

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
                }, 1000);
            });
        </script>
    <?php endif; ?>
</body>
</html>