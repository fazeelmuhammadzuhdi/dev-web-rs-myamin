<?php

namespace App\Helpers;

use CodeIgniter\HTTP\URI;

/**
 * View Optimizer Helper
 * Provides utilities for optimizing views, performance, and SEO
 */
class ViewOptimizerHelper
{
    /**
     * Generate optimized meta tags
     */
    public static function generateMetaTags(array $meta = []): string
    {
        $defaults = [
            'title' => 'Rumah Sakit',
            'description' => 'Website resmi Rumah Sakit',
            'keywords' => 'rumah sakit, kesehatan, medis',
            'author' => 'Rumah Sakit',
            'robots' => 'index, follow',
            'viewport' => 'width=device-width, initial-scale=1.0',
            'theme-color' => '#2b6cb0',
            'canonical' => current_url()
        ];

        $meta = array_merge($defaults, $meta);
        $tags = [];

        // Basic meta tags
        $tags[] = '<title>' . htmlspecialchars($meta['title']) . '</title>';
        $tags[] = '<meta name="description" content="' . htmlspecialchars($meta['description']) . '">';
        $tags[] = '<meta name="keywords" content="' . htmlspecialchars($meta['keywords']) . '">';
        $tags[] = '<meta name="author" content="' . htmlspecialchars($meta['author']) . '">';
        $tags[] = '<meta name="robots" content="' . htmlspecialchars($meta['robots']) . '">';
        $tags[] = '<meta name="viewport" content="' . htmlspecialchars($meta['viewport']) . '">';
        $tags[] = '<meta name="theme-color" content="' . htmlspecialchars($meta['theme-color']) . '">';

        // Open Graph tags
        if (isset($meta['og_title'])) {
            $tags[] = '<meta property="og:title" content="' . htmlspecialchars($meta['og_title']) . '">';
        }
        if (isset($meta['og_description'])) {
            $tags[] = '<meta property="og:description" content="' . htmlspecialchars($meta['og_description']) . '">';
        }
        if (isset($meta['og_image'])) {
            $tags[] = '<meta property="og:image" content="' . htmlspecialchars($meta['og_image']) . '">';
        }
        if (isset($meta['og_url'])) {
            $tags[] = '<meta property="og:url" content="' . htmlspecialchars($meta['og_url']) . '">';
        }

        // Twitter Card tags
        if (isset($meta['twitter_card'])) {
            $tags[] = '<meta name="twitter:card" content="' . htmlspecialchars($meta['twitter_card']) . '">';
        }
        if (isset($meta['twitter_title'])) {
            $tags[] = '<meta name="twitter:title" content="' . htmlspecialchars($meta['twitter_title']) . '">';
        }
        if (isset($meta['twitter_description'])) {
            $tags[] = '<meta name="twitter:description" content="' . htmlspecialchars($meta['twitter_description']) . '">';
        }
        if (isset($meta['twitter_image'])) {
            $tags[] = '<meta name="twitter:image" content="' . htmlspecialchars($meta['twitter_image']) . '">';
        }

        // Canonical URL
        if (isset($meta['canonical'])) {
            $tags[] = '<link rel="canonical" href="' . htmlspecialchars($meta['canonical']) . '">';
        }

        return implode("\n", $tags);
    }

    /**
     * Generate structured data (JSON-LD)
     */
    public static function generateStructuredData(array $data = []): string
    {
        $defaults = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'Rumah Sakit',
            'url' => base_url(),
            'logo' => base_url('assets/images/logo.png'),
            'description' => 'Website resmi Rumah Sakit'
        ];

        $structuredData = array_merge($defaults, $data);
        
        return '<script type="application/ld+json">' . json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }

    /**
     * Generate skip links for accessibility
     */
    public static function addSkipLinks(array $links = []): string
    {
        $defaults = [
            ['href' => '#main-content', 'text' => 'Skip to main content'],
            ['href' => '#navigation', 'text' => 'Skip to navigation'],
            ['href' => '#footer', 'text' => 'Skip to footer']
        ];

        $links = array_merge($defaults, $links);
        $html = '';

        foreach ($links as $link) {
            $html .= '<a href="' . htmlspecialchars($link['href']) . '" class="skip-link">' . htmlspecialchars($link['text']) . '</a>';
        }

        return $html;
    }

    /**
     * Generate breadcrumbs
     */
    public static function generateBreadcrumbs(array $breadcrumbs = []): string
    {
        if (empty($breadcrumbs)) {
            return '';
        }

        $html = '<nav aria-label="breadcrumb" class="breadcrumb-nav">';
        $html .= '<ol class="breadcrumb">';

        foreach ($breadcrumbs as $index => $crumb) {
            $isLast = $index === count($breadcrumbs) - 1;
            
            if ($isLast) {
                $html .= '<li class="breadcrumb-item active" aria-current="page">' . htmlspecialchars($crumb['text']) . '</li>';
            } else {
                $html .= '<li class="breadcrumb-item">';
                $html .= '<a href="' . htmlspecialchars($crumb['url']) . '">' . htmlspecialchars($crumb['text']) . '</a>';
                $html .= '</li>';
            }
        }

        $html .= '</ol>';
        $html .= '</nav>';

        return $html;
    }

    /**
     * Optimize image attributes
     */
    public static function optimizeImage(string $src, array $attributes = []): string
    {
        $defaults = [
            'alt' => '',
            'loading' => 'lazy',
            'decoding' => 'async',
            'class' => 'img-fluid'
        ];

        $attributes = array_merge($defaults, $attributes);
        $html = '<img src="' . htmlspecialchars($src) . '"';

        foreach ($attributes as $key => $value) {
            if ($value !== '') {
                $html .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }

        $html .= '>';

        return $html;
    }

    /**
     * Generate preload links for critical resources
     */
    public static function generatePreloadLinks(array $resources = []): string
    {
        $html = '';
        
        foreach ($resources as $resource) {
            $type = $resource['type'] ?? 'style';
            $href = $resource['href'] ?? '';
            $as = $resource['as'] ?? $type;
            
            if ($href) {
                $html .= '<link rel="preload" href="' . htmlspecialchars($href) . '" as="' . htmlspecialchars($as) . '">';
            }
        }

        return $html;
    }

    /**
     * Generate DNS prefetch links
     */
    public static function generateDnsPrefetch(array $domains = []): string
    {
        $defaults = [
            '//fonts.googleapis.com',
            '//fonts.gstatic.com',
            '//cdnjs.cloudflare.com'
        ];

        $domains = array_merge($defaults, $domains);
        $html = '';

        foreach ($domains as $domain) {
            $html .= '<link rel="dns-prefetch" href="' . htmlspecialchars($domain) . '">';
        }

        return $html;
    }

    /**
     * Generate preconnect links
     */
    public static function generatePreconnect(array $domains = []): string
    {
        $defaults = [
            'https://fonts.googleapis.com',
            'https://fonts.gstatic.com'
        ];

        $domains = array_merge($defaults, $domains);
        $html = '';

        foreach ($domains as $domain) {
            $html .= '<link rel="preconnect" href="' . htmlspecialchars($domain) . '" crossorigin>';
        }

        return $html;
    }

    /**
     * Minify HTML content
     */
    public static function minifyHtml(string $html): string
    {
        // Remove comments
        $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
        
        // Remove extra whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove whitespace around tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        return trim($html);
    }

    /**
     * Generate critical CSS inline
     */
    public static function generateCriticalCss(): string
    {
        return '
        <style>
            /* Critical CSS - Above the fold styles */
            body { 
                margin: 0; 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
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
        </style>';
    }

    /**
     * Generate performance monitoring script
     */
    public static function generatePerformanceScript(): string
    {
        return '
        <script>
            // Performance monitoring
            window.addEventListener("load", function() {
                if ("performance" in window) {
                    const navigation = performance.getEntriesByType("navigation")[0];
                    const paint = performance.getEntriesByType("paint");
                    
                    const metrics = {
                        domContentLoaded: navigation.domContentLoadedEventEnd - navigation.domContentLoadedEventStart,
                        loadComplete: navigation.loadEventEnd - navigation.loadEventStart,
                        firstPaint: paint.find(entry => entry.name === "first-paint")?.startTime || 0,
                        firstContentfulPaint: paint.find(entry => entry.name === "first-contentful-paint")?.startTime || 0
                    };
                    
                    // Send to analytics if available
                    if (typeof gtag !== "undefined") {
                        gtag("event", "performance_metrics", {
                            custom_map: {
                                "metric_1": "dom_content_loaded",
                                "metric_2": "load_complete",
                                "metric_3": "first_paint",
                                "metric_4": "first_contentful_paint"
                            },
                            dom_content_loaded: Math.round(metrics.domContentLoaded),
                            load_complete: Math.round(metrics.loadComplete),
                            first_paint: Math.round(metrics.firstPaint),
                            first_contentful_paint: Math.round(metrics.firstContentfulPaint)
                        });
                    }
                }
            });
        </script>';
    }

    /**
     * Generate accessibility enhancements
     */
    public static function generateAccessibilityScript(): string
    {
        return '
        <script>
            // Accessibility improvements
            document.addEventListener("keydown", function(e) {
                if (e.key === "Tab") {
                    document.body.classList.add("keyboard-navigation");
                }
            });

            document.addEventListener("mousedown", function() {
                document.body.classList.remove("keyboard-navigation");
            });

            // Screen reader announcements
            window.announceToScreenReader = function(message) {
                const announcer = document.getElementById("announcer");
                if (announcer) {
                    announcer.textContent = message;
                    setTimeout(() => {
                        announcer.textContent = "";
                    }, 1000);
                }
            };
        </script>';
    }

    /**
     * Check if view needs optimization
     */
    public static function needsOptimization(string $viewPath): array
    {
        $issues = [];
        
        if (!file_exists($viewPath)) {
            return $issues;
        }
        
        $content = file_get_contents($viewPath);
        
        // Check for inline styles
        if (preg_match('/<style[^>]*>.*?<\/style>/s', $content)) {
            $issues[] = 'Contains inline styles';
        }
        
        // Check for inline scripts
        if (preg_match('/<script[^>]*>.*?<\/script>/s', $content)) {
            $issues[] = 'Contains inline scripts';
        }
        
        // Check for missing alt attributes
        if (preg_match('/<img(?![^>]*alt=)[^>]*>/i', $content)) {
            $issues[] = 'Images missing alt attributes';
        }
        
        // Check for missing ARIA labels
        if (preg_match('/<button(?![^>]*(aria-label|aria-labelledby))[^>]*>/i', $content)) {
            $issues[] = 'Buttons missing ARIA labels';
        }
        
        // Check for synchronous script loading
        if (preg_match('/<script[^>]*(?!async|defer)[^>]*src=/i', $content)) {
            $issues[] = 'Synchronous script loading detected';
        }
        
        return $issues;
    }

    /**
     * Get optimization recommendations
     */
    public static function getOptimizationRecommendations(string $viewPath): array
    {
        $recommendations = [];
        
        if (!file_exists($viewPath)) {
            return $recommendations;
        }
        
        $content = file_get_contents($viewPath);
        
        // Performance recommendations
        if (strpos($content, 'preload') === false) {
            $recommendations[] = 'Add preload links for critical resources';
        }
        
        if (strpos($content, 'dns-prefetch') === false) {
            $recommendations[] = 'Add DNS prefetch for external domains';
        }
        
        if (strpos($content, 'loading="lazy"') === false) {
            $recommendations[] = 'Add lazy loading for images';
        }
        
        // Accessibility recommendations
        if (strpos($content, 'aria-label') === false) {
            $recommendations[] = 'Add ARIA labels for better accessibility';
        }
        
        if (strpos($content, 'role=') === false) {
            $recommendations[] = 'Add ARIA roles for semantic structure';
        }
        
        // SEO recommendations
        if (strpos($content, 'canonical') === false) {
            $recommendations[] = 'Add canonical URL for SEO';
        }
        
        if (strpos($content, 'og:') === false) {
            $recommendations[] = 'Add Open Graph meta tags';
        }
        
        return $recommendations;
    }
}