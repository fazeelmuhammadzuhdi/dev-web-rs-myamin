<?php

namespace App\Commands;

use App\Services\ViewOptimizationService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Optimize Views Command
 * Analyzes and optimizes view files for performance, accessibility, and SEO
 */
class OptimizeViews extends BaseCommand
{
    protected $group = 'Optimization';
    protected $name = 'optimize:views';
    protected $description = 'Analyze and optimize view files for performance, accessibility, and SEO';
    protected $usage = 'optimize:views [options]';
    protected $arguments = [];
    protected $options = [
        '--analyze' => 'Only analyze views without applying optimizations',
        '--fix' => 'Apply automatic fixes where possible',
        '--report' => 'Generate detailed optimization report',
        '--cache' => 'Clear optimization cache',
        '--path' => 'Specific view path to optimize'
    ];

    public function run(array $params)
    {
        $service = new ViewOptimizationService();
        
        // Handle cache clearing
        if (CLI::getOption('cache')) {
            $this->clearCache($service);
            return;
        }

        // Handle specific path
        if (CLI::getOption('path')) {
            $this->optimizeSpecificPath($service, CLI::getOption('path'));
            return;
        }

        // Handle analysis only
        if (CLI::getOption('analyze')) {
            $this->analyzeViews($service);
            return;
        }

        // Handle report generation
        if (CLI::getOption('report')) {
            $this->generateReport($service);
            return;
        }

        // Default: optimize all views
        $this->optimizeAllViews($service, CLI::getOption('fix'));
    }

    /**
     * Clear optimization cache
     */
    protected function clearCache(ViewOptimizationService $service)
    {
        CLI::write('Clearing optimization cache...', 'yellow');
        
        if ($service->clearCache()) {
            CLI::write('Cache cleared successfully!', 'green');
        } else {
            CLI::write('Cache clearing failed or caching is disabled.', 'red');
        }
    }

    /**
     * Optimize specific view path
     */
    protected function optimizeSpecificPath(ViewOptimizationService $service, string $path)
    {
        CLI::write("Optimizing view: {$path}", 'yellow');
        
        if (!file_exists($path)) {
            CLI::write("View file not found: {$path}", 'red');
            return;
        }

        $analysis = $service->analyzeView($path);
        $this->displayAnalysis($path, $analysis);
        
        if (CLI::getOption('fix')) {
            $this->applyFixes($path, $analysis);
        }
    }

    /**
     * Analyze all views
     */
    protected function analyzeViews(ViewOptimizationService $service)
    {
        CLI::write('Analyzing all view files...', 'yellow');
        
        $viewPaths = $this->getAllViewPaths();
        $totalViews = count($viewPaths);
        $totalIssues = 0;
        $totalRecommendations = 0;
        
        $performanceScores = [];
        $accessibilityScores = [];
        $seoScores = [];

        foreach ($viewPaths as $index => $viewPath) {
            CLI::showProgress($index + 1, $totalViews);
            
            $analysis = $service->analyzeView($viewPath);
            $totalIssues += count($analysis['issues']);
            $totalRecommendations += count($analysis['recommendations']);
            
            $performanceScores[] = $analysis['performance_score'];
            $accessibilityScores[] = $analysis['accessibility_score'];
            $seoScores[] = $analysis['seo_score'];
        }

        CLI::newLine();
        CLI::write("Analysis complete!", 'green');
        CLI::write("Total views analyzed: {$totalViews}", 'white');
        CLI::write("Total issues found: {$totalIssues}", 'red');
        CLI::write("Total recommendations: {$totalRecommendations}", 'yellow');
        
        if (!empty($performanceScores)) {
            CLI::write("Average performance score: " . round(array_sum($performanceScores) / count($performanceScores)), 'white');
            CLI::write("Average accessibility score: " . round(array_sum($accessibilityScores) / count($accessibilityScores)), 'white');
            CLI::write("Average SEO score: " . round(array_sum($seoScores) / count($seoScores)), 'white');
        }
    }

    /**
     * Generate detailed report
     */
    protected function generateReport(ViewOptimizationService $service)
    {
        CLI::write('Generating optimization report...', 'yellow');
        
        $viewPaths = $this->getAllViewPaths();
        $report = $service->generateOptimizationReport($viewPaths);
        
        // Save report to file
        $reportPath = WRITEPATH . 'optimization_report_' . date('Y-m-d_H-i-s') . '.json';
        file_put_contents($reportPath, json_encode($report, JSON_PRETTY_PRINT));
        
        CLI::write("Report saved to: {$reportPath}", 'green');
        
        // Display summary
        CLI::write("\n=== OPTIMIZATION REPORT SUMMARY ===", 'cyan');
        CLI::write("Timestamp: {$report['timestamp']}", 'white');
        CLI::write("Total views: {$report['total_views']}", 'white');
        CLI::write("Views analyzed: {$report['views_analyzed']}", 'white');
        CLI::write("Issues found: {$report['issues_found']}", 'red');
        CLI::write("Recommendations: {$report['recommendations']}", 'yellow');
        CLI::write("Average performance score: {$report['average_scores']['performance']}", 'white');
        CLI::write("Average accessibility score: {$report['average_scores']['accessibility']}", 'white');
        CLI::write("Average SEO score: {$report['average_scores']['seo']}", 'white');
    }

    /**
     * Optimize all views
     */
    protected function optimizeAllViews(ViewOptimizationService $service, bool $applyFixes = false)
    {
        CLI::write('Optimizing all view files...', 'yellow');
        
        $viewPaths = $this->getAllViewPaths();
        $totalViews = count($viewPaths);
        $optimizedCount = 0;
        $errorCount = 0;

        foreach ($viewPaths as $index => $viewPath) {
            CLI::showProgress($index + 1, $totalViews);
            
            try {
                $analysis = $service->analyzeView($viewPath);
                
                if ($applyFixes && !empty($analysis['issues'])) {
                    $this->applyFixes($viewPath, $analysis);
                }
                
                $optimizedCount++;
            } catch (\Exception $e) {
                CLI::write("\nError optimizing {$viewPath}: " . $e->getMessage(), 'red');
                $errorCount++;
            }
        }

        CLI::newLine();
        CLI::write("Optimization complete!", 'green');
        CLI::write("Views optimized: {$optimizedCount}", 'white');
        CLI::write("Errors: {$errorCount}", 'red');
    }

    /**
     * Get all view file paths
     */
    protected function getAllViewPaths(): array
    {
        $viewPaths = [];
        $viewDir = APPPATH . 'Views';
        
        if (is_dir($viewDir)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($viewDir)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile() && $file->getExtension() === 'php') {
                    $viewPaths[] = $file->getPathname();
                }
            }
        }
        
        return $viewPaths;
    }

    /**
     * Display analysis results
     */
    protected function displayAnalysis(string $viewPath, array $analysis)
    {
        CLI::write("\n=== ANALYSIS RESULTS: " . basename($viewPath) . " ===", 'cyan');
        
        // Display scores
        CLI::write("Performance Score: {$analysis['performance_score']}/100", 'white');
        CLI::write("Accessibility Score: {$analysis['accessibility_score']}/100", 'white');
        CLI::write("SEO Score: {$analysis['seo_score']}/100", 'white');
        
        // Display issues
        if (!empty($analysis['issues'])) {
            CLI::write("\nIssues found:", 'red');
            foreach ($analysis['issues'] as $issue) {
                CLI::write("  - {$issue}", 'red');
            }
        }
        
        // Display recommendations
        if (!empty($analysis['recommendations'])) {
            CLI::write("\nRecommendations:", 'yellow');
            foreach ($analysis['recommendations'] as $recommendation) {
                CLI::write("  - {$recommendation}", 'yellow');
            }
        }
    }

    /**
     * Apply automatic fixes
     */
    protected function applyFixes(string $viewPath, array $analysis)
    {
        CLI::write("Applying fixes to: " . basename($viewPath), 'yellow');
        
        $content = file_get_contents($viewPath);
        $originalContent = $content;
        
        // Apply fixes based on issues
        foreach ($analysis['issues'] as $issue) {
            switch ($issue) {
                case 'Images missing alt attributes':
                    $content = $this->fixMissingAltAttributes($content);
                    break;
                case 'Buttons missing ARIA labels':
                    $content = $this->fixMissingAriaLabels($content);
                    break;
                case 'Synchronous script loading detected':
                    $content = $this->fixSynchronousScripts($content);
                    break;
            }
        }
        
        // Apply recommendations
        foreach ($analysis['recommendations'] as $recommendation) {
            switch ($recommendation) {
                case 'Add lazy loading for images':
                    $content = $this->addLazyLoading($content);
                    break;
                case 'Add preload links for critical resources':
                    $content = $this->addPreloadLinks($content);
                    break;
                case 'Add DNS prefetch for external domains':
                    $content = $this->addDnsPrefetch($content);
                    break;
            }
        }
        
        // Save changes if content was modified
        if ($content !== $originalContent) {
            file_put_contents($viewPath, $content);
            CLI::write("  ✓ Fixes applied successfully", 'green');
        } else {
            CLI::write("  - No automatic fixes available", 'yellow');
        }
    }

    /**
     * Fix missing alt attributes
     */
    protected function fixMissingAltAttributes(string $content): string
    {
        return preg_replace(
            '/<img(?![^>]*alt=)([^>]*)>/i',
            '<img alt=""$1>',
            $content
        );
    }

    /**
     * Fix missing ARIA labels
     */
    protected function fixMissingAriaLabels(string $content): string
    {
        return preg_replace_callback(
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
    }

    /**
     * Fix synchronous script loading
     */
    protected function fixSynchronousScripts(string $content): string
    {
        return preg_replace(
            '/<script([^>]*src=[^>]*)>/i',
            '<script$1 defer>',
            $content
        );
    }

    /**
     * Add lazy loading to images
     */
    protected function addLazyLoading(string $content): string
    {
        return preg_replace(
            '/<img(?![^>]*loading=)([^>]*)>/i',
            '<img loading="lazy"$1>',
            $content
        );
    }

    /**
     * Add preload links
     */
    protected function addPreloadLinks(string $content): string
    {
        $preloadLinks = '<link rel="preload" href="assets/css/critical.css" as="style">';
        
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $preloadLinks . '</head>', $content);
        }
        
        return $content;
    }

    /**
     * Add DNS prefetch
     */
    protected function addDnsPrefetch(string $content): string
    {
        $dnsPrefetch = '<link rel="dns-prefetch" href="//fonts.googleapis.com">';
        
        if (strpos($content, '</head>') !== false) {
            $content = str_replace('</head>', $dnsPrefetch . '</head>', $content);
        }
        
        return $content;
    }
}