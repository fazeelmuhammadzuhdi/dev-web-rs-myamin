# Panduan Optimasi View

## Ringkasan

Sistem optimasi view ini dirancang untuk meningkatkan performa, aksesibilitas, dan SEO dari aplikasi CodeIgniter 4. Sistem ini mencakup:

- **Optimasi Performa**: Minifikasi HTML, lazy loading, preloading resources
- **Aksesibilitas**: ARIA labels, skip links, keyboard navigation
- **SEO**: Meta tags, structured data, canonical URLs
- **Caching**: Sistem cache untuk optimasi view
- **Monitoring**: Analisis dan pelaporan otomatis

## Fitur Utama

### 1. Optimasi Performa

#### Critical CSS Inlining
```php
// Menggunakan helper untuk generate critical CSS
$criticalCss = ViewOptimizerHelper::generateCriticalCss();
```

#### Lazy Loading untuk Images
```php
// Otomatis menambahkan loading="lazy" ke semua images
$content = $this->addLazyLoading($content);
```

#### Preloading Resources
```php
// Preload critical resources
$preloadLinks = ViewOptimizerHelper::generatePreloadLinks([
    ['href' => 'assets/css/critical.css', 'as' => 'style'],
    ['href' => 'assets/js/critical.js', 'as' => 'script']
]);
```

### 2. Aksesibilitas

#### Skip Links
```php
// Generate skip links untuk navigasi keyboard
$skipLinks = ViewOptimizerHelper::addSkipLinks([
    ['href' => '#main-content', 'text' => 'Skip to main content'],
    ['href' => '#navigation', 'text' => 'Skip to navigation']
]);
```

#### ARIA Labels
```php
// Otomatis menambahkan ARIA labels ke buttons tanpa text
$content = $this->addAccessibilityEnhancements($content);
```

#### Screen Reader Support
```php
// JavaScript untuk screen reader announcements
window.announceToScreenReader = function(message) {
    // Implementation
};
```

### 3. SEO

#### Meta Tags
```php
// Generate meta tags dinamis
$metaTags = ViewOptimizerHelper::generateMetaTags([
    'title' => 'Halaman Utama',
    'description' => 'Deskripsi halaman',
    'keywords' => 'kata kunci, relevan'
]);
```

#### Structured Data
```php
// Generate JSON-LD structured data
$structuredData = ViewOptimizerHelper::generateStructuredData([
    '@type' => 'Organization',
    'name' => 'Rumah Sakit',
    'url' => base_url()
]);
```

#### Canonical URLs
```php
// Otomatis menambahkan canonical URL
$canonical = '<link rel="canonical" href="' . current_url() . '">';
```

## Penggunaan

### 1. Menggunakan Service

```php
use App\Services\ViewOptimizationService;

$service = new ViewOptimizationService();

// Optimize view dengan caching
$optimizedContent = $service->optimizeView('frontend/main/home', $data);

// Analyze view untuk issues
$analysis = $service->analyzeView('app/Views/frontend/main/home.php');
```

### 2. Menggunakan Helper

```php
use App\Helpers\ViewOptimizerHelper;

// Generate meta tags
$metaTags = ViewOptimizerHelper::generateMetaTags($meta);

// Generate structured data
$structuredData = ViewOptimizerHelper::generateStructuredData($data);

// Generate skip links
$skipLinks = ViewOptimizerHelper::addSkipLinks($links);
```

### 3. Menggunakan Command Line

```bash
# Analyze semua views
php spark optimize:views --analyze

# Generate report
php spark optimize:views --report

# Apply fixes otomatis
php spark optimize:views --fix

# Clear cache
php spark optimize:views --cache

# Optimize specific view
php spark optimize:views --path="app/Views/frontend/main/home.php"
```

## Konfigurasi

### 1. File Konfigurasi

```php
// app/Config/ViewOptimization.php
public bool $enableOptimization = true;
public bool $enableMinification = true;
public bool $enableCriticalCss = true;
public bool $enableLazyLoading = true;
public bool $enablePerformanceMonitoring = true;
public bool $enableAccessibilityEnhancements = true;
public bool $enableSeoOptimizations = true;
```

### 2. Performance Budgets

```php
public array $performanceBudgets = [
    'maxCssSize' => 100000, // 100KB
    'maxJsSize' => 200000,  // 200KB
    'maxImageSize' => 500000, // 500KB
    'maxTotalSize' => 1000000 // 1MB
];
```

### 3. Cache Settings

```php
public array $cacheSettings = [
    'enableCaching' => true,
    'cacheDuration' => 3600, // 1 hour
    'cacheKeyPrefix' => 'view_optimization_'
];
```

## Layout Templates

### 1. Backend Layout

```php
// app/Views/layouts/optimized_backend.php
<?= $this->extend('layouts/optimized') ?>

<?= $this->section('content') ?>
    <!-- Backend content -->
<?= $this->endSection() ?>
```

### 2. Frontend Layout

```php
// app/Views/layouts/optimized_frontend.php
<?= $this->extend('layouts/optimized') ?>

<?= $this->section('content') ?>
    <!-- Frontend content -->
<?= $this->endSection() ?>
```

## CSS dan JavaScript Optimized

### 1. Backend CSS

```css
/* public/assets/css/backend-optimized.css */
/* Consolidated dan optimized CSS untuk backend */
```

### 2. Frontend CSS

```css
/* public/frontend/css/frontend-optimized.css */
/* Consolidated dan optimized CSS untuk frontend */
```

### 3. JavaScript

```javascript
// public/assets/js/backend-optimized.js
// public/frontend/js/frontend-optimized.js
// Consolidated dan optimized JavaScript
```

## Monitoring dan Analisis

### 1. Performance Monitoring

```javascript
// Otomatis mengukur Core Web Vitals
window.addEventListener('load', function() {
    // Measure performance metrics
    const metrics = {
        domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
        loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
        firstPaint: paint.find(entry => entry.name === 'first-paint')?.startTime || 0,
        firstContentfulPaint: paint.find(entry => entry.name === 'first-contentful-paint')?.startTime || 0
    };
});
```

### 2. Error Tracking

```javascript
// Global error handler
window.addEventListener('error', (e) => {
    console.error('JavaScript Error:', e.error);
    // Send to analytics
});
```

### 3. User Interaction Tracking

```javascript
// Track user interactions
let interactionCount = 0;
document.addEventListener('click', () => {
    interactionCount++;
});
```

## Best Practices

### 1. Performance

- Gunakan lazy loading untuk images
- Preload critical resources
- Minify HTML, CSS, dan JavaScript
- Gunakan DNS prefetch untuk external domains
- Implement caching strategy

### 2. Aksesibilitas

- Tambahkan ARIA labels ke semua interactive elements
- Implement skip links untuk keyboard navigation
- Gunakan semantic HTML
- Pastikan color contrast ratio minimal 4.5:1
- Test dengan screen reader

### 3. SEO

- Gunakan meta tags yang relevan
- Implement structured data
- Gunakan canonical URLs
- Optimize images dengan alt text
- Implement sitemap

### 4. Security

- Sanitize semua user input
- Gunakan CSP headers
- Implement HSTS
- Validate file uploads
- Use HTTPS

## Troubleshooting

### 1. Common Issues

#### Cache tidak berfungsi
```bash
# Clear cache
php spark optimize:views --cache
```

#### Performance score rendah
```bash
# Analyze views
php spark optimize:views --analyze
```

#### Accessibility issues
```bash
# Apply fixes
php spark optimize:views --fix
```

### 2. Debug Mode

```php
// Enable debug mode
$config = new ViewOptimization();
$config->enableOptimization = false; // Disable untuk debug
```

### 3. Log Files

```bash
# Check log files
tail -f writable/logs/log-$(date +%Y-%m-%d).php
```

## Migration Guide

### 1. Dari Layout Lama

```php
// Old layout
<?= $this->extend('backend/main/layout') ?>

// New optimized layout
<?= $this->extend('layouts/optimized_backend') ?>
```

### 2. Update CSS/JS Includes

```php
// Old includes
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js"></script>

// New optimized includes
<link rel="stylesheet" href="assets/css/backend-optimized.css">
<script src="assets/js/backend-optimized.js" defer></script>
```

### 3. Update View Files

```php
// Old view
<img src="image.jpg" alt="">

// New optimized view
<img src="image.jpg" alt="Description" loading="lazy" decoding="async">
```

## Performance Metrics

### 1. Target Scores

- **Performance**: 90+ / 100
- **Accessibility**: 95+ / 100
- **SEO**: 90+ / 100

### 2. Core Web Vitals

- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

### 3. Lighthouse Scores

- **Performance**: 90+
- **Accessibility**: 95+
- **Best Practices**: 90+
- **SEO**: 90+

## Support

Untuk bantuan lebih lanjut:

1. Check dokumentasi CodeIgniter 4
2. Review log files
3. Use command line tools untuk debugging
4. Check performance budgets
5. Monitor Core Web Vitals

## Changelog

### v1.0.0
- Initial release
- Basic optimization features
- Performance monitoring
- Accessibility enhancements
- SEO optimizations

### v1.1.0
- Added caching system
- Improved error handling
- Enhanced monitoring
- Better documentation

### v1.2.0
- Added command line tools
- Improved analysis
- Better performance metrics
- Enhanced accessibility features