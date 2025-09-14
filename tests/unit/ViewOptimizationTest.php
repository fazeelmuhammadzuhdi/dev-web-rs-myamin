<?php

namespace Tests\Unit;

use App\Services\ViewOptimizationService;
use App\Helpers\ViewOptimizerHelper;
use App\Config\ViewOptimization;
use CodeIgniter\Test\CIUnitTestCase;

class ViewOptimizationTest extends CIUnitTestCase
{
    protected ViewOptimizationService $service;
    protected ViewOptimization $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ViewOptimizationService();
        $this->config = new ViewOptimization();
    }

    public function testViewOptimizationServiceInitialization()
    {
        $this->assertInstanceOf(ViewOptimizationService::class, $this->service);
        $this->assertInstanceOf(ViewOptimization::class, $this->config);
    }

    public function testGenerateMetaTags()
    {
        $meta = [
            'title' => 'Test Page',
            'description' => 'Test Description',
            'keywords' => 'test, keywords'
        ];

        $metaTags = ViewOptimizerHelper::generateMetaTags($meta);

        $this->assertStringContainsString('<title>Test Page</title>', $metaTags);
        $this->assertStringContainsString('<meta name="description" content="Test Description">', $metaTags);
        $this->assertStringContainsString('<meta name="keywords" content="test, keywords">', $metaTags);
    }

    public function testGenerateStructuredData()
    {
        $data = [
            '@type' => 'Organization',
            'name' => 'Test Organization',
            'url' => 'https://example.com'
        ];

        $structuredData = ViewOptimizerHelper::generateStructuredData($data);

        $this->assertStringContainsString('<script type="application/ld+json">', $structuredData);
        $this->assertStringContainsString('"@type":"Organization"', $structuredData);
        $this->assertStringContainsString('"name":"Test Organization"', $structuredData);
    }

    public function testGenerateSkipLinks()
    {
        $links = [
            ['href' => '#main', 'text' => 'Skip to main'],
            ['href' => '#nav', 'text' => 'Skip to nav']
        ];

        $skipLinks = ViewOptimizerHelper::addSkipLinks($links);

        $this->assertStringContainsString('<a href="#main" class="skip-link">Skip to main</a>', $skipLinks);
        $this->assertStringContainsString('<a href="#nav" class="skip-link">Skip to nav</a>', $skipLinks);
    }

    public function testGenerateBreadcrumbs()
    {
        $breadcrumbs = [
            ['url' => '/', 'text' => 'Home'],
            ['url' => '/about', 'text' => 'About'],
            ['url' => '/contact', 'text' => 'Contact']
        ];

        $breadcrumbHtml = ViewOptimizerHelper::generateBreadcrumbs($breadcrumbs);

        $this->assertStringContainsString('<nav aria-label="breadcrumb"', $breadcrumbHtml);
        $this->assertStringContainsString('<ol class="breadcrumb">', $breadcrumbHtml);
        $this->assertStringContainsString('<a href="/">Home</a>', $breadcrumbHtml);
        $this->assertStringContainsString('<a href="/about">About</a>', $breadcrumbHtml);
        $this->assertStringContainsString('<li class="breadcrumb-item active"', $breadcrumbHtml);
    }

    public function testOptimizeImage()
    {
        $imageHtml = ViewOptimizerHelper::optimizeImage('test.jpg', [
            'alt' => 'Test Image',
            'loading' => 'lazy',
            'class' => 'img-fluid'
        ]);

        $this->assertStringContainsString('src="test.jpg"', $imageHtml);
        $this->assertStringContainsString('alt="Test Image"', $imageHtml);
        $this->assertStringContainsString('loading="lazy"', $imageHtml);
        $this->assertStringContainsString('class="img-fluid"', $imageHtml);
    }

    public function testGeneratePreloadLinks()
    {
        $resources = [
            ['href' => 'style.css', 'as' => 'style'],
            ['href' => 'script.js', 'as' => 'script']
        ];

        $preloadLinks = ViewOptimizerHelper::generatePreloadLinks($resources);

        $this->assertStringContainsString('<link rel="preload" href="style.css" as="style">', $preloadLinks);
        $this->assertStringContainsString('<link rel="preload" href="script.js" as="script">', $preloadLinks);
    }

    public function testGenerateDnsPrefetch()
    {
        $domains = ['//example.com', '//cdn.example.com'];

        $dnsPrefetch = ViewOptimizerHelper::generateDnsPrefetch($domains);

        $this->assertStringContainsString('<link rel="dns-prefetch" href="//example.com">', $dnsPrefetch);
        $this->assertStringContainsString('<link rel="dns-prefetch" href="//cdn.example.com">', $dnsPrefetch);
    }

    public function testGeneratePreconnect()
    {
        $domains = ['https://example.com', 'https://fonts.googleapis.com'];

        $preconnect = ViewOptimizerHelper::generatePreconnect($domains);

        $this->assertStringContainsString('<link rel="preconnect" href="https://example.com" crossorigin>', $preconnect);
        $this->assertStringContainsString('<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>', $preconnect);
    }

    public function testMinifyHtml()
    {
        $html = '  <div>  <p>  Test  </p>  </div>  ';
        $minified = ViewOptimizerHelper::minifyHtml($html);

        $this->assertEquals('<div><p>Test</p></div>', $minified);
    }

    public function testGenerateCriticalCss()
    {
        $criticalCss = ViewOptimizerHelper::generateCriticalCss();

        $this->assertStringContainsString('<style>', $criticalCss);
        $this->assertStringContainsString('body {', $criticalCss);
        $this->assertStringContainsString('.hero-section {', $criticalCss);
        $this->assertStringContainsString('.sr-only {', $criticalCss);
    }

    public function testGeneratePerformanceScript()
    {
        $performanceScript = ViewOptimizerHelper::generatePerformanceScript();

        $this->assertStringContainsString('<script>', $performanceScript);
        $this->assertStringContainsString('performance', $performanceScript);
        $this->assertStringContainsString('addEventListener', $performanceScript);
    }

    public function testGenerateAccessibilityScript()
    {
        $accessibilityScript = ViewOptimizerHelper::generateAccessibilityScript();

        $this->assertStringContainsString('<script>', $accessibilityScript);
        $this->assertStringContainsString('keydown', $accessibilityScript);
        $this->assertStringContainsString('announceToScreenReader', $accessibilityScript);
    }

    public function testNeedsOptimization()
    {
        // Create a temporary test file
        $testFile = sys_get_temp_dir() . '/test_view.php';
        $content = '<div><img src="test.jpg"><button>Click</button></div>';
        file_put_contents($testFile, $content);

        $issues = ViewOptimizerHelper::needsOptimization($testFile);

        $this->assertIsArray($issues);
        $this->assertContains('Images missing alt attributes', $issues);
        $this->assertContains('Buttons missing ARIA labels', $issues);

        // Clean up
        unlink($testFile);
    }

    public function testGetOptimizationRecommendations()
    {
        // Create a temporary test file
        $testFile = sys_get_temp_dir() . '/test_view.php';
        $content = '<div><img src="test.jpg"><button>Click</button></div>';
        file_put_contents($testFile, $content);

        $recommendations = ViewOptimizerHelper::getOptimizationRecommendations($testFile);

        $this->assertIsArray($recommendations);
        $this->assertContains('Add lazy loading for images', $recommendations);
        $this->assertContains('Add preload links for critical resources', $recommendations);

        // Clean up
        unlink($testFile);
    }

    public function testViewOptimizationServiceAnalyzeView()
    {
        // Create a temporary test file
        $testFile = sys_get_temp_dir() . '/test_view.php';
        $content = '<div><img src="test.jpg"><button>Click</button></div>';
        file_put_contents($testFile, $content);

        $analysis = $this->service->analyzeView($testFile);

        $this->assertIsArray($analysis);
        $this->assertArrayHasKey('issues', $analysis);
        $this->assertArrayHasKey('recommendations', $analysis);
        $this->assertArrayHasKey('performance_score', $analysis);
        $this->assertArrayHasKey('accessibility_score', $analysis);
        $this->assertArrayHasKey('seo_score', $analysis);

        $this->assertIsArray($analysis['issues']);
        $this->assertIsArray($analysis['recommendations']);
        $this->assertIsInt($analysis['performance_score']);
        $this->assertIsInt($analysis['accessibility_score']);
        $this->assertIsInt($analysis['seo_score']);

        // Clean up
        unlink($testFile);
    }

    public function testViewOptimizationServiceGetOptimizationStats()
    {
        $stats = $this->service->getOptimizationStats();

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('optimization_enabled', $stats);
        $this->assertArrayHasKey('caching_enabled', $stats);
        $this->assertArrayHasKey('cache_duration', $stats);
        $this->assertArrayHasKey('performance_budgets', $stats);
        $this->assertArrayHasKey('features_enabled', $stats);

        $this->assertIsBool($stats['optimization_enabled']);
        $this->assertIsBool($stats['caching_enabled']);
        $this->assertIsInt($stats['cache_duration']);
        $this->assertIsArray($stats['performance_budgets']);
        $this->assertIsArray($stats['features_enabled']);
    }

    public function testViewOptimizationServicePreloadCriticalResources()
    {
        $preloadResources = $this->service->preloadCriticalResources();

        $this->assertIsString($preloadResources);
        $this->assertStringContainsString('<link rel="preload"', $preloadResources);
        $this->assertStringContainsString('<link rel="dns-prefetch"', $preloadResources);
        $this->assertStringContainsString('<link rel="preconnect"', $preloadResources);
    }

    public function testViewOptimizationServiceGenerateOptimizationReport()
    {
        // Create temporary test files
        $testFile1 = sys_get_temp_dir() . '/test_view1.php';
        $testFile2 = sys_get_temp_dir() . '/test_view2.php';
        
        $content = '<div><img src="test.jpg"><button>Click</button></div>';
        file_put_contents($testFile1, $content);
        file_put_contents($testFile2, $content);

        $viewPaths = [$testFile1, $testFile2];
        $report = $this->service->generateOptimizationReport($viewPaths);

        $this->assertIsArray($report);
        $this->assertArrayHasKey('timestamp', $report);
        $this->assertArrayHasKey('total_views', $report);
        $this->assertArrayHasKey('views_analyzed', $report);
        $this->assertArrayHasKey('issues_found', $report);
        $this->assertArrayHasKey('recommendations', $report);
        $this->assertArrayHasKey('average_scores', $report);
        $this->assertArrayHasKey('view_details', $report);

        $this->assertEquals(2, $report['total_views']);
        $this->assertEquals(2, $report['views_analyzed']);
        $this->assertIsInt($report['issues_found']);
        $this->assertIsInt($report['recommendations']);
        $this->assertIsArray($report['average_scores']);
        $this->assertIsArray($report['view_details']);

        // Clean up
        unlink($testFile1);
        unlink($testFile2);
    }

    public function testViewOptimizationConfig()
    {
        $this->assertIsBool($this->config->enableOptimization);
        $this->assertIsBool($this->config->enableMinification);
        $this->assertIsBool($this->config->enableCriticalCss);
        $this->assertIsBool($this->config->enableLazyLoading);
        $this->assertIsBool($this->config->enablePerformanceMonitoring);
        $this->assertIsBool($this->config->enableAccessibilityEnhancements);
        $this->assertIsBool($this->config->enableSeoOptimizations);

        $this->assertIsString($this->config->criticalCssPath);
        $this->assertIsString($this->config->optimizedCssPath);
        $this->assertIsString($this->config->optimizedJsPath);

        $this->assertIsArray($this->config->defaultMeta);
        $this->assertIsArray($this->config->dnsPrefetchDomains);
        $this->assertIsArray($this->config->preconnectDomains);
        $this->assertIsArray($this->config->criticalResources);
        $this->assertIsArray($this->config->skipLinks);
        $this->assertIsArray($this->config->defaultStructuredData);
        $this->assertIsArray($this->config->performanceBudgets);
        $this->assertIsArray($this->config->cacheSettings);
    }

    public function testViewOptimizationConfigGetViewSettings()
    {
        $backendSettings = $this->config->getViewSettings('backend');
        $frontendSettings = $this->config->getViewSettings('frontend');

        $this->assertIsArray($backendSettings);
        $this->assertIsArray($frontendSettings);

        $this->assertArrayHasKey('enableOptimization', $backendSettings);
        $this->assertArrayHasKey('enableMinification', $backendSettings);
        $this->assertArrayHasKey('enableCriticalCss', $backendSettings);
        $this->assertArrayHasKey('enableLazyLoading', $backendSettings);
        $this->assertArrayHasKey('enablePerformanceMonitoring', $backendSettings);
        $this->assertArrayHasKey('enableAccessibilityEnhancements', $backendSettings);
        $this->assertArrayHasKey('enableSeoOptimizations', $backendSettings);

        // Backend should have different settings than frontend
        $this->assertNotEquals($backendSettings, $frontendSettings);
    }

    public function testViewOptimizationConfigIsOptimizationEnabled()
    {
        $this->assertIsBool($this->config->isOptimizationEnabled('minification'));
        $this->assertIsBool($this->config->isOptimizationEnabled('critical_css'));
        $this->assertIsBool($this->config->isOptimizationEnabled('lazy_loading'));
        $this->assertIsBool($this->config->isOptimizationEnabled('performance_monitoring'));
        $this->assertIsBool($this->config->isOptimizationEnabled('accessibility'));
        $this->assertIsBool($this->config->isOptimizationEnabled('seo'));
        $this->assertIsBool($this->config->isOptimizationEnabled('unknown_feature'));
    }

    public function testViewOptimizationConfigGetPerformanceBudget()
    {
        $cssBudget = $this->config->getPerformanceBudget('maxCssSize');
        $jsBudget = $this->config->getPerformanceBudget('maxJsSize');
        $imageBudget = $this->config->getPerformanceBudget('maxImageSize');
        $totalBudget = $this->config->getPerformanceBudget('maxTotalSize');
        $unknownBudget = $this->config->getPerformanceBudget('unknown');

        $this->assertIsInt($cssBudget);
        $this->assertIsInt($jsBudget);
        $this->assertIsInt($imageBudget);
        $this->assertIsInt($totalBudget);
        $this->assertEquals(0, $unknownBudget);

        $this->assertGreaterThan(0, $cssBudget);
        $this->assertGreaterThan(0, $jsBudget);
        $this->assertGreaterThan(0, $imageBudget);
        $this->assertGreaterThan(0, $totalBudget);
    }

    public function testViewOptimizationConfigGetCacheKey()
    {
        $cacheKey1 = $this->config->getCacheKey('test_view');
        $cacheKey2 = $this->config->getCacheKey('test_view', ['param1' => 'value1']);
        $cacheKey3 = $this->config->getCacheKey('test_view', ['param1' => 'value1', 'param2' => 'value2']);

        $this->assertIsString($cacheKey1);
        $this->assertIsString($cacheKey2);
        $this->assertIsString($cacheKey3);

        $this->assertStringContainsString('view_optimization_', $cacheKey1);
        $this->assertStringContainsString('view_optimization_', $cacheKey2);
        $this->assertStringContainsString('view_optimization_', $cacheKey3);

        $this->assertNotEquals($cacheKey1, $cacheKey2);
        $this->assertNotEquals($cacheKey2, $cacheKey3);
    }

    public function testViewOptimizationConfigGetCacheDuration()
    {
        $duration = $this->config->getCacheDuration();

        $this->assertIsInt($duration);
        $this->assertGreaterThan(0, $duration);
    }

    public function testViewOptimizationConfigIsCachingEnabled()
    {
        $isEnabled = $this->config->isCachingEnabled();

        $this->assertIsBool($isEnabled);
    }
}