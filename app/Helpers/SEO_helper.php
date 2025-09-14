<?php

if (!function_exists('generateMetaTags')) {
    /**
     * Generate SEO meta tags
     * 
     * @param array $data Meta data
     * @return string HTML meta tags
     */
    function generateMetaTags(array $data): string
    {
        $html = '';
        
        // Basic meta tags
        if (!empty($data['title'])) {
            $html .= '<title>' . esc($data['title']) . '</title>' . "\n";
        }
        
        if (!empty($data['description'])) {
            $html .= '<meta name="description" content="' . esc($data['description']) . '">' . "\n";
        }
        
        if (!empty($data['keywords'])) {
            $html .= '<meta name="keywords" content="' . esc($data['keywords']) . '">' . "\n";
        }
        
        if (!empty($data['author'])) {
            $html .= '<meta name="author" content="' . esc($data['author']) . '">' . "\n";
        }
        
        // Open Graph tags
        if (!empty($data['og_title'])) {
            $html .= '<meta property="og:title" content="' . esc($data['og_title']) . '">' . "\n";
        }
        
        if (!empty($data['og_description'])) {
            $html .= '<meta property="og:description" content="' . esc($data['og_description']) . '">' . "\n";
        }
        
        if (!empty($data['og_image'])) {
            $html .= '<meta property="og:image" content="' . esc($data['og_image']) . '">' . "\n";
        }
        
        if (!empty($data['og_url'])) {
            $html .= '<meta property="og:url" content="' . esc($data['og_url']) . '">' . "\n";
        }
        
        if (!empty($data['og_type'])) {
            $html .= '<meta property="og:type" content="' . esc($data['og_type']) . '">' . "\n";
        }
        
        // Twitter Card tags
        if (!empty($data['twitter_card'])) {
            $html .= '<meta name="twitter:card" content="' . esc($data['twitter_card']) . '">' . "\n";
        }
        
        if (!empty($data['twitter_title'])) {
            $html .= '<meta name="twitter:title" content="' . esc($data['twitter_title']) . '">' . "\n";
        }
        
        if (!empty($data['twitter_description'])) {
            $html .= '<meta name="twitter:description" content="' . esc($data['twitter_description']) . '">' . "\n";
        }
        
        if (!empty($data['twitter_image'])) {
            $html .= '<meta name="twitter:image" content="' . esc($data['twitter_image']) . '">' . "\n";
        }
        
        // Canonical URL
        if (!empty($data['canonical'])) {
            $html .= '<link rel="canonical" href="' . esc($data['canonical']) . '">' . "\n";
        }
        
        // Robots meta
        if (!empty($data['robots'])) {
            $html .= '<meta name="robots" content="' . esc($data['robots']) . '">' . "\n";
        }
        
        return $html;
    }
}

if (!function_exists('generateStructuredData')) {
    /**
     * Generate structured data (JSON-LD)
     * 
     * @param array $data Structured data
     * @return string JSON-LD script
     */
    function generateStructuredData(array $data): string
    {
        $jsonLd = [
            '@context' => 'https://schema.org',
            '@type' => $data['type'] ?? 'WebPage'
        ];
        
        // Add properties
        foreach ($data as $key => $value) {
            if ($key !== 'type') {
                $jsonLd[$key] = $value;
            }
        }
        
        return '<script type="application/ld+json">' . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
    }
}

if (!function_exists('generateBreadcrumbs')) {
    /**
     * Generate breadcrumb navigation
     * 
     * @param array $breadcrumbs Breadcrumb items
     * @return string HTML breadcrumbs
     */
    function generateBreadcrumbs(array $breadcrumbs): string
    {
        $html = '<nav aria-label="Breadcrumb">';
        $html .= '<ol class="breadcrumb">';
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $isLast = $index === count($breadcrumbs) - 1;
            
            $html .= '<li class="breadcrumb-item' . ($isLast ? ' active' : '') . '">';
            
            if (!$isLast && !empty($breadcrumb['url'])) {
                $html .= '<a href="' . esc($breadcrumb['url']) . '">' . esc($breadcrumb['text']) . '</a>';
            } else {
                $html .= esc($breadcrumb['text']);
            }
            
            $html .= '</li>';
        }
        
        $html .= '</ol>';
        $html .= '</nav>';
        
        return $html;
    }
}

if (!function_exists('generateSitemap')) {
    /**
     * Generate sitemap XML
     * 
     * @param array $urls Array of URLs
     * @return string Sitemap XML
     */
    function generateSitemap(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        foreach ($urls as $url) {
            $xml .= '<url>';
            $xml .= '<loc>' . esc($url['loc']) . '</loc>';
            
            if (!empty($url['lastmod'])) {
                $xml .= '<lastmod>' . $url['lastmod'] . '</lastmod>';
            }
            
            if (!empty($url['changefreq'])) {
                $xml .= '<changefreq>' . $url['changefreq'] . '</changefreq>';
            }
            
            if (!empty($url['priority'])) {
                $xml .= '<priority>' . $url['priority'] . '</priority>';
            }
            
            $xml .= '</url>';
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
}

if (!function_exists('optimizeUrl')) {
    /**
     * Optimize URL for SEO
     * 
     * @param string $url URL to optimize
     * @return string Optimized URL
     */
    function optimizeUrl(string $url): string
    {
        // Remove trailing slash
        $url = rtrim($url, '/');
        
        // Convert to lowercase
        $url = strtolower($url);
        
        // Remove query parameters if not needed
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            
            // Remove unnecessary parameters
            $unnecessaryParams = ['utm_source', 'utm_medium', 'utm_campaign', 'fbclid', 'gclid'];
            foreach ($unnecessaryParams as $param) {
                unset($queryParams[$param]);
            }
            
            if (empty($queryParams)) {
                $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'];
            } else {
                $url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . http_build_query($queryParams);
            }
        }
        
        return $url;
    }
}

if (!function_exists('generateInternalLinks')) {
    /**
     * Generate internal links with proper attributes
     * 
     * @param string $url URL
     * @param string $text Link text
     * @param array $attributes Additional attributes
     * @return string HTML link
     */
    function generateInternalLinks(string $url, string $text, array $attributes = []): string
    {
        $defaultAttributes = [
            'href' => $url,
            'title' => $text,
        ];
        
        // Check if it's an external link
        $parsedUrl = parse_url($url);
        $currentHost = parse_url(base_url(), PHP_URL_HOST);
        
        if (isset($parsedUrl['host']) && $parsedUrl['host'] !== $currentHost) {
            $defaultAttributes['rel'] = 'noopener noreferrer';
            $defaultAttributes['target'] = '_blank';
        }
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $html = '<a';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . esc($value) . '"';
        }
        $html .= '>' . esc($text) . '</a>';
        
        return $html;
    }
}