<?php

namespace App\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Performance Configuration
 * 
 * Configuration untuk optimasi performance website
 */
class Performance extends BaseConfig
{
    /**
     * Cache configuration
     */
    public $cache = [
        'enabled' => true,
        'driver' => 'file', // file, redis, memcached
        'ttl' => 3600, // 1 hour
        'prefix' => 'app_cache_',
    ];

    /**
     * Image optimization
     */
    public $image = [
        'webp_enabled' => true,
        'quality' => 85,
        'max_width' => 1920,
        'max_height' => 1080,
        'thumbnail_width' => 300,
        'thumbnail_height' => 200,
        'lazy_loading' => true,
    ];

    /**
     * CSS/JS optimization
     */
    public $assets = [
        'minify_css' => true,
        'minify_js' => true,
        'combine_files' => true,
        'versioning' => true,
        'cdn_enabled' => false,
    ];

    /**
     * Database optimization
     */
    public $database = [
        'query_cache' => true,
        'connection_pooling' => true,
        'slow_query_log' => true,
        'slow_query_threshold' => 2.0, // seconds
    ];

    /**
     * HTTP optimization
     */
    public $http = [
        'gzip_compression' => true,
        'browser_caching' => true,
        'etag_enabled' => true,
        'preload_critical_resources' => true,
    ];

    /**
     * Third-party optimization
     */
    public $third_party = [
        'lazy_load_analytics' => true,
        'defer_non_critical_js' => true,
        'optimize_fonts' => true,
        'preconnect_domains' => [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
        ],
    ];
}