<?php

namespace App\Services;

use App\Config\ViewOptimization;
use App\Helpers\ViewOptimizerHelper;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Config\Services;

/**
 * View Optimization Service
 * Handles view optimization, caching, and performance monitoring
 */
class ViewOptimizationService
{
    protected ViewOptimization $config;
    protected CacheInterface $cache;
    protected bool $optimizationEnabled;

    public function __construct()
    {
        $this->config = new ViewOptimization();
        $this->cache = Services::cache();
        $this->optimizationEnabled = $this->config->enableOptimization;
    }

    /**
     * Optimize a view with caching
     */
    public function optimizeView(string $viewName, array $data = [], bool $useCache = true): string
    {
        if (!$this->optimizationEnabled) {
            return $this->renderView($viewName, $data);
        }

        $cacheKey = $this->config->getCacheKey($viewName, $data);
        
        if ($useCache && $this->config->isCachingEnabled()) {
            $cachedContent = $this->cache->get($cacheKey);
            if ($cachedContent !== null) {
                return $cachedContent;
            }
        }

        $content = $this->renderView($viewName, $data);
        $optimizedContent = $this->applyOptimizations($content, $viewName);

        if ($useCache && $this->config->isCachingEnabled()) {
            $this->cache->save($cacheKey, $optimizedContent, $this->config->getCacheDuration());
        }

        return $optimizedContent;
    }

    /**
     * Render a view
     */
    protected function renderView(string $viewName, array $data = []): string
    {
        $view = Services::renderer();
        return $view->setData($data)->render($viewName);
    }

    /**
     * Apply optimizations to content
     */
    protected function applyOptimizations(string $content, string $viewName): string
    {
        $settings = $this->config->getViewSettings($viewName);

        // Apply minification
        if ($settings['enableMinification'] && $this->config->isOptimizationEnabled('minification')) {
            $content = ViewOptimizerHelper::minifyHtml($content);
        }

        // Apply critical CSS
        if ($settings['enableCriticalCss'] && $this->config->isOptimizationEnabled('critical_css')) {
            $content = $this->injectCriticalCss($content);
        }

        // Apply lazy loading
        if ($settings['enableLazyLoading'] && $this->config->isOptimizationEnabled('lazy_loading')) {
            $content = $this->addLazyLoading($content);
        }

        // Apply performance monitoring
        if ($settings['enablePerformanceMonitoring'] && $this->config->isOptimizationEnabled('performance_monitoring')) {
            $content = $this->injectPerformanceMonitoring($content);
        }

        // Apply accessibility enhancements
        if ($settings['enableAccessibilityEnhancements'] && $this->config->isOptimizationEnabled('accessibility')) {
            $content = $this->addAccessibilityEnhancements($content);
        }

        // Apply SEO optimizations
        if ($settings['enableSeoOptimizations'] && $this->config->isOptimizationEnabled('seo')) {
            $content = $this->addSeoOptimizations($content);
        }

        return $content;
    }

    /**
     * Inject critical CSS
     */
    protected function injectCriticalCss(string $content): string
    {
        $criticalCss = ViewOptimizerHelper::generateCriticalCss();
        
        // Inject before closing head tag
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $criticalCss . '</head>', $content);
        }

        return $content;
    }

    /**
     * Add lazy loading to images
     */
    protected function addLazyLoading(string $content): string
    {
        // Add loading="lazy" to images that don't have it
        $content = preg_replace(
            '/<img(?![^>]*loading=)([^>]*)>/i',
            '<img loading="lazy"$1>',
            $content
        );

        // Add decoding="async" to images
        $content = preg_replace(
            '/<img(?![^>]*decoding=)([^>]*)>/i',
            '<img decoding="async"$1>',
            $content
        );

        return $content;
    }

    /**
     * Inject performance monitoring
     */
    protected function injectPerformanceMonitoring(string $content): string
    {
        $performanceScript = ViewOptimizerHelper::generatePerformanceScript();
        
        // Inject before closing body tag
        if (strpos($content, '</body>') !== false) {
            $content = str_replace('</body>', $performanceScript . '</body>', $content);
        }

        return $content;
    }

    /**
     * Add accessibility enhancements
     */
    protected function addAccessibilityEnhancements(string $content): string
    {
        $accessibilityScript = ViewOptimizerHelper::generateAccessibilityScript();
        
        // Inject before closing body tag
        if (strpos($content, '</body>') !== false) {
            $content = str_replace('</body>', $accessibilityScript . '</body>', $content);
        }

        // Add ARIA labels to buttons without text
        $content = preg_replace_callback(
            '/<button(?![^>]*(aria-label|aria-labelledby))([^>]*)>([^<]*)<\/button>/i',
            function ($matches) {
                $attributes = $matches[2];
                $text = trim($matches[3]);
                
                if (empty($text)) {
                    // Try to extract label from icon class
                    if (preg_match('/class="[^"]*icon-([^"\s]+)/', $attributes, $iconMatches)) {
                        $iconName = $iconMatches[1];
                        $label = ucfirst(str_replace('-', ' ', $iconName));
                        return '<button' . $attributes . ' aria-label="' . $label . '">' . $text . '</button>';
                    }
                }
                
                return $matches[0];
            },
            $content
        );

        return $content;
    }

    /**
     * Add SEO optimizations
     */
    protected function addSeoOptimizations(string $content): string
    {
        // Add meta tags if missing
        if (strpos($content, '<meta name="description"') === false) {
            $metaTags = ViewOptimizerHelper::generateMetaTags($this->config->defaultMeta);
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $metaTags . '</head>', $content);
            }
        }

        // Add structured data if missing
        if (strpos($content, 'application/ld+json') === false) {
            $structuredData = ViewOptimizerHelper::generateStructuredData($this->config->defaultStructuredData);
            
            if (strpos($content, '</head>') !== false) {
                $content = str_replace('</head>', $structuredData . '</head>', $content);
            }
        }

        return $content;
    }

    /**
     * Analyze view for optimization opportunities
     */
    public function analyzeView(string $viewPath): array
    {
        $analysis = [
            'issues' => [],
            'recommendations' => [],
            'performance_score' => 0,
            'accessibility_score' => 0,
            'seo_score' => 0
        ];

        if (!file_exists($viewPath)) {
            $analysis['issues'][] = 'View file not found';
            return $analysis;
        }

        $content = file_get_contents($viewPath);
        
        // Check for issues
        $analysis['issues'] = ViewOptimizerHelper::needsOptimization($viewPath);
        
        // Get recommendations
        $analysis['recommendations'] = ViewOptimizerHelper::getOptimizationRecommendations($viewPath);
        
        // Calculate scores
        $analysis['performance_score'] = $this->calculatePerformanceScore($content);
        $analysis['accessibility_score'] = $this->calculateAccessibilityScore($content);
        $analysis['seo_score'] = $this->calculateSeoScore($content);

        return $analysis;
    }

    /**
     * Calculate performance score
     */
    protected function calculatePerformanceScore(string $content): int
    {
        $score = 100;
        
        // Deduct points for issues
        if (strpos($content, 'preload') === false) $score -= 10;
        if (strpos($content, 'dns-prefetch') === false) $score -= 5;
        if (strpos($content, 'loading="lazy"') === false) $score -= 15;
        if (preg_match('/<script[^>]*(?!async|defer)[^>]*src=/i', $content)) $score -= 20;
        if (preg_match('/<style[^>]*>.*?<\/style>/s', $content)) $score -= 10;
        
        return max(0, $score);
    }

    /**
     * Calculate accessibility score
     */
    protected function calculateAccessibilityScore(string $content): int
    {
        $score = 100;
        
        // Deduct points for issues
        if (preg_match('/<img(?![^>]*alt=)[^>]*>/i', $content)) $score -= 20;
        if (preg_match('/<button(?![^>]*(aria-label|aria-labelledby))[^>]*>/i', $content)) $score -= 15;
        if (strpos($content, 'role=') === false) $score -= 10;
        if (strpos($content, 'aria-live') === false) $score -= 5;
        if (strpos($content, 'skip-link') === false) $score -= 10;
        
        return max(0, $score);
    }

    /**
     * Calculate SEO score
     */
    protected function calculateSeoScore(string $content): int
    {
        $score = 100;
        
        // Deduct points for issues
        if (strpos($content, '<meta name="description"') === false) $score -= 20;
        if (strpos($content, '<meta name="keywords"') === false) $score -= 10;
        if (strpos($content, 'canonical') === false) $score -= 15;
        if (strpos($content, 'og:') === false) $score -= 15;
        if (strpos($content, 'twitter:') === false) $score -= 10;
        if (strpos($content, 'application/ld+json') === false) $score -= 10;
        
        return max(0, $score);
    }

    /**
     * Get optimization statistics
     */
    public function getOptimizationStats(): array
    {
        return [
            'optimization_enabled' => $this->optimizationEnabled,
            'caching_enabled' => $this->config->isCachingEnabled(),
            'cache_duration' => $this->config->getCacheDuration(),
            'performance_budgets' => $this->config->performanceBudgets,
            'features_enabled' => [
                'minification' => $this->config->isOptimizationEnabled('minification'),
                'critical_css' => $this->config->isOptimizationEnabled('critical_css'),
                'lazy_loading' => $this->config->isOptimizationEnabled('lazy_loading'),
                'performance_monitoring' => $this->config->isOptimizationEnabled('performance_monitoring'),
                'accessibility' => $this->config->isOptimizationEnabled('accessibility'),
                'seo' => $this->config->isOptimizationEnabled('seo')
            ]
        ];
    }

    /**
     * Clear optimization cache
     */
    public function clearCache(): bool
    {
        if ($this->config->isCachingEnabled()) {
            return $this->cache->clean();
        }
        return false;
    }

    /**
     * Preload critical resources
     */
    public function preloadCriticalResources(): string
    {
        $preloadLinks = ViewOptimizerHelper::generatePreloadLinks($this->config->criticalResources);
        $dnsPrefetch = ViewOptimizerHelper::generateDnsPrefetch($this->config->dnsPrefetchDomains);
        $preconnect = ViewOptimizerHelper::generatePreconnect($this->config->preconnectDomains);
        
        return $preloadLinks . $dnsPrefetch . $preconnect;
    }

    /**
     * Generate optimization report
     */
    public function generateOptimizationReport(array $viewPaths = []): array
    {
        $report = [
            'timestamp' => date('Y-m-d H:i:s'),
            'total_views' => count($viewPaths),
            'views_analyzed' => 0,
            'issues_found' => 0,
            'recommendations' => 0,
            'average_scores' => [
                'performance' => 0,
                'accessibility' => 0,
                'seo' => 0
            ],
            'view_details' => []
        ];

        $totalPerformance = 0;
        $totalAccessibility = 0;
        $totalSeo = 0;

        foreach ($viewPaths as $viewPath) {
            $analysis = $this->analyzeView($viewPath);
            $report['view_details'][] = [
                'path' => $viewPath,
                'analysis' => $analysis
            ];

            $report['views_analyzed']++;
            $report['issues_found'] += count($analysis['issues']);
            $report['recommendations'] += count($analysis['recommendations']);

            $totalPerformance += $analysis['performance_score'];
            $totalAccessibility += $analysis['accessibility_score'];
            $totalSeo += $analysis['seo_score'];
        }

        if ($report['views_analyzed'] > 0) {
            $report['average_scores']['performance'] = round($totalPerformance / $report['views_analyzed']);
            $report['average_scores']['accessibility'] = round($totalAccessibility / $report['views_analyzed']);
            $report['average_scores']['seo'] = round($totalSeo / $report['views_analyzed']);
        }

        return $report;
    }
}