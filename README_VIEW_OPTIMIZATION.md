# View Optimization System

## Overview

Sistem optimasi view ini dirancang untuk meningkatkan performa, aksesibilitas, dan SEO dari aplikasi CodeIgniter 4. Sistem ini mencakup berbagai fitur optimasi yang dapat diterapkan secara otomatis atau manual.

## Features

### 🚀 Performance Optimization
- **Critical CSS Inlining**: CSS kritis dimuat inline untuk mengurangi render-blocking
- **Lazy Loading**: Images dimuat secara lazy untuk meningkatkan performa
- **Resource Preloading**: Preload resources kritis untuk loading yang lebih cepat
- **HTML Minification**: Minifikasi HTML untuk mengurangi ukuran file
- **DNS Prefetch**: Prefetch DNS untuk external domains

### ♿ Accessibility Enhancement
- **ARIA Labels**: Otomatis menambahkan ARIA labels ke interactive elements
- **Skip Links**: Navigasi keyboard yang lebih baik
- **Screen Reader Support**: Dukungan untuk screen reader
- **Focus Management**: Manajemen focus yang lebih baik
- **Keyboard Navigation**: Navigasi keyboard yang ditingkatkan

### 🔍 SEO Optimization
- **Meta Tags**: Generate meta tags dinamis
- **Structured Data**: JSON-LD structured data
- **Canonical URLs**: Canonical URLs untuk SEO
- **Open Graph**: Open Graph meta tags
- **Twitter Cards**: Twitter Card meta tags

### 📊 Monitoring & Analytics
- **Performance Monitoring**: Monitor Core Web Vitals
- **Error Tracking**: Track JavaScript errors
- **User Interaction Tracking**: Track user interactions
- **Optimization Reports**: Generate detailed reports

## Quick Start

### 1. Install Dependencies

```bash
# No additional dependencies required
# Uses built-in CodeIgniter 4 features
```

### 2. Basic Usage

```php
use App\Services\ViewOptimizationService;

$service = new ViewOptimizationService();

// Optimize view
$optimizedContent = $service->optimizeView('frontend/main/home', $data);
```

### 3. Command Line Usage

```bash
# Analyze all views
php spark optimize:views --analyze

# Generate report
php spark optimize:views --report

# Apply fixes
php spark optimize:views --fix
```

## File Structure

```
app/
├── Commands/
│   └── OptimizeViews.php          # CLI command untuk optimasi
├── Config/
│   └── ViewOptimization.php       # Konfigurasi optimasi
├── Helpers/
│   └── view_optimizer_helper.php  # Helper functions
├── Services/
│   └── ViewOptimizationService.php # Service utama
└── Views/
    └── layouts/
        ├── optimized.php           # Layout template optimized
        ├── optimized_backend.php   # Backend layout
        └── optimized_frontend.php  # Frontend layout

public/
├── assets/
│   ├── css/
│   │   └── backend-optimized.css  # CSS optimized untuk backend
│   └── js/
│       └── backend-optimized.js   # JS optimized untuk backend
└── frontend/
    ├── css/
    │   └── frontend-optimized.css # CSS optimized untuk frontend
    └── js/
        └── frontend-optimized.js  # JS optimized untuk frontend
```

## Configuration

### Basic Configuration

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

### Performance Budgets

```php
public array $performanceBudgets = [
    'maxCssSize' => 100000,    // 100KB
    'maxJsSize' => 200000,     // 200KB
    'maxImageSize' => 500000,  // 500KB
    'maxTotalSize' => 1000000  // 1MB
];
```

## Usage Examples

### 1. Optimize Single View

```php
use App\Services\ViewOptimizationService;

$service = new ViewOptimizationService();
$optimizedContent = $service->optimizeView('frontend/main/home', [
    'title' => 'Home Page',
    'description' => 'Welcome to our website'
]);
```

### 2. Analyze View

```php
$analysis = $service->analyzeView('app/Views/frontend/main/home.php');

echo "Performance Score: " . $analysis['performance_score'] . "/100\n";
echo "Accessibility Score: " . $analysis['accessibility_score'] . "/100\n";
echo "SEO Score: " . $analysis['seo_score'] . "/100\n";
```

### 3. Generate Report

```php
$viewPaths = [
    'app/Views/frontend/main/home.php',
    'app/Views/backend/berita/index.php'
];

$report = $service->generateOptimizationReport($viewPaths);
```

### 4. Using Helper Functions

```php
use App\Helpers\ViewOptimizerHelper;

// Generate meta tags
$metaTags = ViewOptimizerHelper::generateMetaTags([
    'title' => 'Page Title',
    'description' => 'Page Description'
]);

// Generate structured data
$structuredData = ViewOptimizerHelper::generateStructuredData([
    '@type' => 'Organization',
    'name' => 'Company Name'
]);

// Generate skip links
$skipLinks = ViewOptimizerHelper::addSkipLinks([
    ['href' => '#main-content', 'text' => 'Skip to main content']
]);
```

## Command Line Interface

### Available Commands

```bash
# Analyze all views
php spark optimize:views --analyze

# Generate detailed report
php spark optimize:views --report

# Apply automatic fixes
php spark optimize:views --fix

# Clear optimization cache
php spark optimize:views --cache

# Optimize specific view
php spark optimize:views --path="app/Views/frontend/main/home.php"
```

### Command Options

- `--analyze`: Only analyze views without applying optimizations
- `--fix`: Apply automatic fixes where possible
- `--report`: Generate detailed optimization report
- `--cache`: Clear optimization cache
- `--path`: Specific view path to optimize

## Performance Metrics

### Target Scores

- **Performance**: 90+ / 100
- **Accessibility**: 95+ / 100
- **SEO**: 90+ / 100

### Core Web Vitals

- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1

## Best Practices

### 1. Performance

- Gunakan lazy loading untuk images
- Preload critical resources
- Minify HTML, CSS, dan JavaScript
- Implement caching strategy
- Monitor performance budgets

### 2. Accessibility

- Tambahkan ARIA labels
- Implement skip links
- Gunakan semantic HTML
- Test dengan screen reader
- Ensure keyboard navigation

### 3. SEO

- Gunakan meta tags yang relevan
- Implement structured data
- Gunakan canonical URLs
- Optimize images dengan alt text
- Monitor SEO scores

## Troubleshooting

### Common Issues

1. **Cache tidak berfungsi**
   ```bash
   php spark optimize:views --cache
   ```

2. **Performance score rendah**
   ```bash
   php spark optimize:views --analyze
   ```

3. **Accessibility issues**
   ```bash
   php spark optimize:views --fix
   ```

### Debug Mode

```php
$config = new ViewOptimization();
$config->enableOptimization = false; // Disable untuk debug
```

## Migration Guide

### From Old Layout

```php
// Old
<?= $this->extend('backend/main/layout') ?>

// New
<?= $this->extend('layouts/optimized_backend') ?>
```

### Update CSS/JS Includes

```php
// Old
<link rel="stylesheet" href="assets/css/style.css">
<script src="assets/js/script.js"></script>

// New
<link rel="stylesheet" href="assets/css/backend-optimized.css">
<script src="assets/js/backend-optimized.js" defer></script>
```

## Support

Untuk bantuan lebih lanjut:

1. Check dokumentasi CodeIgniter 4
2. Review log files
3. Use command line tools
4. Check performance budgets
5. Monitor Core Web Vitals

## License

This project is licensed under the MIT License.

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

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