<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

class ViewOptimization extends BaseConfig
{
    /**
     * Enable view optimization
     */
    public bool $enableOptimization = true;

    /**
     * Enable HTML minification
     */
    public bool $enableMinification = true;

    /**
     * Enable critical CSS inlining
     */
    public bool $enableCriticalCss = true;

    /**
     * Enable lazy loading for images
     */
    public bool $enableLazyLoading = true;

    /**
     * Enable performance monitoring
     */
    public bool $enablePerformanceMonitoring = true;

    /**
     * Enable accessibility enhancements
     */
    public bool $enableAccessibilityEnhancements = true;

    /**
     * Enable SEO optimizations
     */
    public bool $enableSeoOptimizations = true;

    /**
     * Critical CSS file path
     */
    public string $criticalCssPath = 'assets/css/critical.css';

    /**
     * Optimized CSS file path
     */
    public string $optimizedCssPath = 'assets/css/optimized.css';

    /**
     * Optimized JS file path
     */
    public string $optimizedJsPath = 'assets/js/optimized.js';

    /**
     * Default meta tags
     */
    public array $defaultMeta = [
        'title' => 'Rumah Sakit',
        'description' => 'Website resmi Rumah Sakit',
        'keywords' => 'rumah sakit, kesehatan, medis',
        'author' => 'Rumah Sakit',
        'robots' => 'index, follow',
        'viewport' => 'width=device-width, initial-scale=1.0',
        'theme-color' => '#2b6cb0'
    ];

    /**
     * External domains for DNS prefetch
     */
    public array $dnsPrefetchDomains = [
        '//fonts.googleapis.com',
        '//fonts.gstatic.com',
        '//cdnjs.cloudflare.com',
        '//stackpath.bootstrapcdn.com'
    ];

    /**
     * External domains for preconnect
     */
    public array $preconnectDomains = [
        'https://fonts.googleapis.com',
        'https://fonts.gstatic.com'
    ];

    /**
     * Critical resources to preload
     */
    public array $criticalResources = [
        [
            'href' => 'assets/css/critical.css',
            'as' => 'style',
            'type' => 'text/css'
        ],
        [
            'href' => 'assets/js/critical.js',
            'as' => 'script',
            'type' => 'application/javascript'
        ]
    ];

    /**
     * Skip links for accessibility
     */
    public array $skipLinks = [
        ['href' => '#main-content', 'text' => 'Skip to main content'],
        ['href' => '#navigation', 'text' => 'Skip to navigation'],
        ['href' => '#footer', 'text' => 'Skip to footer']
    ];

    /**
     * Default structured data
     */
    public array $defaultStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Rumah Sakit',
        'url' => '',
        'logo' => '',
        'description' => 'Website resmi Rumah Sakit',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => '',
            'addressLocality' => '',
            'addressRegion' => '',
            'postalCode' => '',
            'addressCountry' => 'ID'
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '',
            'contactType' => 'customer service'
        ]
    ];

    /**
     * Performance budgets
     */
    public array $performanceBudgets = [
        'maxCssSize' => 100000, // 100KB
        'maxJsSize' => 200000,  // 200KB
        'maxImageSize' => 500000, // 500KB
        'maxTotalSize' => 1000000 // 1MB
    ];

    /**
     * Cache settings
     */
    public array $cacheSettings = [
        'enableCaching' => true,
        'cacheDuration' => 3600, // 1 hour
        'cacheKeyPrefix' => 'view_optimization_'
    ];

    /**
     * Image optimization settings
     */
    public array $imageOptimization = [
        'enableWebp' => true,
        'enableLazyLoading' => true,
        'defaultQuality' => 85,
        'maxWidth' => 1920,
        'maxHeight' => 1080
    ];

    /**
     * Font optimization settings
     */
    public array $fontOptimization = [
        'enableFontDisplay' => true,
        'fontDisplayValue' => 'swap',
        'preloadFonts' => [
            'Inter:wght@400;600',
            'Roboto:wght@300;400;500;700'
        ]
    ];

    /**
     * JavaScript optimization settings
     */
    public array $jsOptimization = [
        'enableMinification' => true,
        'enableTreeShaking' => true,
        'enableCodeSplitting' => true,
        'enableAsyncLoading' => true
    ];

    /**
     * CSS optimization settings
     */
    public array $cssOptimization = [
        'enableMinification' => true,
        'enablePurgeCss' => true,
        'enableCriticalCss' => true,
        'enableAutoprefixer' => true
    ];

    /**
     * SEO optimization settings
     */
    public array $seoOptimization = [
        'enableMetaTags' => true,
        'enableStructuredData' => true,
        'enableCanonicalUrls' => true,
        'enableOpenGraph' => true,
        'enableTwitterCards' => true,
        'enableSitemap' => true,
        'enableRobotsTxt' => true
    ];

    /**
     * Accessibility optimization settings
     */
    public array $accessibilityOptimization = [
        'enableAriaLabels' => true,
        'enableSkipLinks' => true,
        'enableFocusManagement' => true,
        'enableScreenReaderSupport' => true,
        'enableKeyboardNavigation' => true,
        'enableColorContrast' => true
    ];

    /**
     * Security optimization settings
     */
    public array $securityOptimization = [
        'enableCsp' => true,
        'enableHsts' => true,
        'enableXssProtection' => true,
        'enableContentTypeOptions' => true,
        'enableFrameOptions' => true
    ];

    /**
     * Get optimization settings for a specific view
     */
    public function getViewSettings(string $viewName): array
    {
        $settings = [
            'enableOptimization' => $this->enableOptimization,
            'enableMinification' => $this->enableMinification,
            'enableCriticalCss' => $this->enableCriticalCss,
            'enableLazyLoading' => $this->enableLazyLoading,
            'enablePerformanceMonitoring' => $this->enablePerformanceMonitoring,
            'enableAccessibilityEnhancements' => $this->enableAccessibilityEnhancements,
            'enableSeoOptimizations' => $this->enableSeoOptimizations
        ];

        // View-specific overrides
        switch ($viewName) {
            case 'backend':
                $settings['enableCriticalCss'] = false;
                $settings['enableLazyLoading'] = false;
                break;
            case 'frontend':
                $settings['enablePerformanceMonitoring'] = true;
                $settings['enableAccessibilityEnhancements'] = true;
                break;
        }

        return $settings;
    }

    /**
     * Check if optimization is enabled for a specific feature
     */
    public function isOptimizationEnabled(string $feature): bool
    {
        switch ($feature) {
            case 'minification':
                return $this->enableMinification;
            case 'critical_css':
                return $this->enableCriticalCss;
            case 'lazy_loading':
                return $this->enableLazyLoading;
            case 'performance_monitoring':
                return $this->enablePerformanceMonitoring;
            case 'accessibility':
                return $this->enableAccessibilityEnhancements;
            case 'seo':
                return $this->enableSeoOptimizations;
            default:
                return $this->enableOptimization;
        }
    }

    /**
     * Get performance budget for a specific resource type
     */
    public function getPerformanceBudget(string $resourceType): int
    {
        return $this->performanceBudgets[$resourceType] ?? 0;
    }

    /**
     * Get cache key for a specific view
     */
    public function getCacheKey(string $viewName, array $data = []): string
    {
        $key = $this->cacheSettings['cacheKeyPrefix'] . $viewName;
        
        if (!empty($data)) {
            $key .= '_' . md5(serialize($data));
        }
        
        return $key;
    }

    /**
     * Get cache duration
     */
    public function getCacheDuration(): int
    {
        return $this->cacheSettings['cacheDuration'];
    }

    /**
     * Check if caching is enabled
     */
    public function isCachingEnabled(): bool
    {
        return $this->cacheSettings['enableCaching'];
    }
}