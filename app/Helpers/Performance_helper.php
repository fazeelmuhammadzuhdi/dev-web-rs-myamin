<?php

if (!function_exists('optimizeImage')) {
    /**
     * Optimize image for web delivery
     * 
     * @param string $imagePath Path to image
     * @param array $options Optimization options
     * @return string Optimized image path
     */
    function optimizeImage(string $imagePath, array $options = []): string
    {
        $config = config('Performance');
        $defaultOptions = [
            'width' => $config->image['max_width'],
            'height' => $config->image['max_height'],
            'quality' => $config->image['quality'],
            'format' => $config->image['webp_enabled'] ? 'webp' : 'jpeg',
            'lazy' => $config->image['lazy_loading']
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        // Generate optimized image path
        $pathInfo = pathinfo($imagePath);
        $optimizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '_opt.' . $options['format'];
        
        // Check if optimized image exists
        if (!file_exists(FCPATH . $optimizedPath)) {
            // Create optimized image
            $image = \Config\Services::image();
            $image->withFile(FCPATH . $imagePath);
            $image->resize($options['width'], $options['height'], true, 'center');
            $image->save(FCPATH . $optimizedPath, $options['quality']);
        }
        
        return base_url($optimizedPath);
    }
}

if (!function_exists('generateImageTag')) {
    /**
     * Generate optimized image tag with proper attributes
     * 
     * @param string $src Image source
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML image tag
     */
    function generateImageTag(string $src, string $alt = '', array $attributes = []): string
    {
        $config = config('Performance');
        $defaultAttributes = [
            'src' => $src,
            'alt' => $alt,
            'loading' => $config->image['lazy_loading'] ? 'lazy' : 'eager',
            'width' => $config->image['max_width'],
            'height' => $config->image['max_height'],
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $tag = '<img';
        foreach ($attributes as $key => $value) {
            $tag .= ' ' . $key . '="' . esc($value) . '"';
        }
        $tag .= '>';
        
        return $tag;
    }
}

if (!function_exists('minifyCSS')) {
    /**
     * Minify CSS content
     * 
     * @param string $css CSS content
     * @return string Minified CSS
     */
    function minifyCSS(string $css): string
    {
        // Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
        
        // Remove unnecessary whitespace
        $css = preg_replace('/\s+/', ' ', $css);
        $css = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ' ,', ', '], [';', '{', '{', '}', '}', ':', ',', ','], $css);
        
        return trim($css);
    }
}

if (!function_exists('minifyJS')) {
    /**
     * Minify JavaScript content
     * 
     * @param string $js JavaScript content
     * @return string Minified JavaScript
     */
    function minifyJS(string $js): string
    {
        // Remove single-line comments
        $js = preg_replace('~//[^\r\n]*~', '', $js);
        
        // Remove multi-line comments
        $js = preg_replace('~/\*.*?\*/~s', '', $js);
        
        // Remove unnecessary whitespace
        $js = preg_replace('/\s+/', ' ', $js);
        $js = str_replace(['; ', ' {', '{ ', ' }', '} ', ': ', ' ,', ', '], [';', '{', '{', '}', '}', ':', ',', ','], $js);
        
        return trim($js);
    }
}

if (!function_exists('setCacheHeaders')) {
    /**
     * Set appropriate cache headers
     * 
     * @param int $ttl Time to live in seconds
     * @param string $type Content type (css, js, image, html)
     */
    function setCacheHeaders(int $ttl = 3600, string $type = 'html'): void
    {
        $response = service('response');
        
        // Set cache control
        $response->setHeader('Cache-Control', 'public, max-age=' . $ttl);
        
        // Set expires header
        $response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + $ttl) . ' GMT');
        
        // Set ETag
        $response->setHeader('ETag', '"' . md5(time()) . '"');
        
        // Set content type specific headers
        switch ($type) {
            case 'css':
                $response->setHeader('Content-Type', 'text/css; charset=utf-8');
                break;
            case 'js':
                $response->setHeader('Content-Type', 'application/javascript; charset=utf-8');
                break;
            case 'image':
                $response->setHeader('Content-Type', 'image/webp');
                break;
            default:
                $response->setHeader('Content-Type', 'text/html; charset=utf-8');
        }
    }
}

if (!function_exists('preloadCriticalResources')) {
    /**
     * Generate preload tags for critical resources
     * 
     * @param array $resources Array of critical resources
     * @return string HTML preload tags
     */
    function preloadCriticalResources(array $resources): string
    {
        $tags = '';
        
        foreach ($resources as $resource) {
            $type = $resource['type'] ?? 'style';
            $href = $resource['href'] ?? '';
            $as = $resource['as'] ?? 'style';
            
            if ($href) {
                $tags .= '<link rel="preload" href="' . esc($href) . '" as="' . esc($as) . '" type="' . esc($type) . '">' . "\n";
            }
        }
        
        return $tags;
    }
}

if (!function_exists('deferNonCriticalJS')) {
    /**
     * Add defer attribute to non-critical JavaScript
     * 
     * @param string $script Script tag
     * @return string Modified script tag
     */
    function deferNonCriticalJS(string $script): string
    {
        // Add defer attribute if not already present
        if (strpos($script, 'defer') === false && strpos($script, 'async') === false) {
            $script = str_replace('<script', '<script defer', $script);
        }
        
        return $script;
    }
}

if (!function_exists('optimizeFonts')) {
    /**
     * Generate optimized font loading
     * 
     * @param array $fonts Array of font families
     * @return string Optimized font loading HTML
     */
    function optimizeFonts(array $fonts): string
    {
        $html = '';
        
        foreach ($fonts as $font) {
            $family = $font['family'] ?? '';
            $weights = $font['weights'] ?? ['400'];
            $display = $font['display'] ?? 'swap';
            
            if ($family) {
                $weightsStr = implode(';', array_map(function($weight) use ($family) {
                    return "family={$family}:wght@{$weight}";
                }, $weights));
                
                $html .= '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
                $html .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
                $html .= '<link href="https://fonts.googleapis.com/css2?' . $weightsStr . '&display=' . $display . '" rel="stylesheet">' . "\n";
            }
        }
        
        return $html;
    }
}