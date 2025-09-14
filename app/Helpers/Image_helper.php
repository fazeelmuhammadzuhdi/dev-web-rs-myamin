<?php

if (!function_exists('optimizeImageForWeb')) {
    /**
     * Optimize image for web delivery
     * 
     * @param string $imagePath Path to original image
     * @param array $options Optimization options
     * @return array Optimized image data
     */
    function optimizeImageForWeb(string $imagePath, array $options = []): array
    {
        $config = config('Performance');
        $defaultOptions = [
            'width' => $config->image['max_width'],
            'height' => $config->image['max_height'],
            'quality' => $config->image['quality'],
            'format' => $config->image['webp_enabled'] ? 'webp' : 'jpeg',
            'lazy' => $config->image['lazy_loading'],
            'thumbnail' => false,
            'thumbnail_width' => $config->image['thumbnail_width'],
            'thumbnail_height' => $config->image['thumbnail_height']
        ];
        
        $options = array_merge($defaultOptions, $options);
        
        $pathInfo = pathinfo($imagePath);
        $basePath = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        // Generate optimized image paths
        $optimizedPath = $basePath . '/' . $filename . '_opt.' . $options['format'];
        $thumbnailPath = $basePath . '/' . $filename . '_thumb.' . $options['format'];
        
        $result = [
            'original' => $imagePath,
            'optimized' => $optimizedPath,
            'thumbnail' => $thumbnailPath,
            'webp' => $config->image['webp_enabled'],
            'lazy' => $options['lazy']
        ];
        
        // Create optimized image if it doesn't exist
        if (!file_exists(FCPATH . $optimizedPath)) {
            $image = \Config\Services::image();
            $image->withFile(FCPATH . $imagePath);
            $image->resize($options['width'], $options['height'], true, 'center');
            $image->save(FCPATH . $optimizedPath, $options['quality']);
        }
        
        // Create thumbnail if requested
        if ($options['thumbnail'] && !file_exists(FCPATH . $thumbnailPath)) {
            $image = \Config\Services::image();
            $image->withFile(FCPATH . $imagePath);
            $image->resize($options['thumbnail_width'], $options['thumbnail_height'], true, 'center');
            $image->save(FCPATH . $thumbnailPath, $options['quality']);
        }
        
        return $result;
    }
}

if (!function_exists('generateResponsiveImage')) {
    /**
     * Generate responsive image with multiple sizes
     * 
     * @param string $imagePath Path to original image
     * @param string $alt Alt text
     * @param array $sizes Array of sizes
     * @param array $attributes Additional attributes
     * @return string HTML picture element
     */
    function generateResponsiveImage(string $imagePath, string $alt = '', array $sizes = [], array $attributes = []): string
    {
        $config = config('Performance');
        $defaultSizes = [
            ['width' => 320, 'suffix' => 'sm'],
            ['width' => 768, 'suffix' => 'md'],
            ['width' => 1024, 'suffix' => 'lg'],
            ['width' => 1920, 'suffix' => 'xl']
        ];
        
        $sizes = array_merge($defaultSizes, $sizes);
        
        $pathInfo = pathinfo($imagePath);
        $basePath = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        $html = '<picture>';
        
        // Generate WebP sources if enabled
        if ($config->image['webp_enabled']) {
            foreach ($sizes as $size) {
                $webpPath = $basePath . '/' . $filename . '_' . $size['suffix'] . '.webp';
                $mediaQuery = $size['width'] <= 320 ? '(max-width: 320px)' : 
                            ($size['width'] <= 768 ? '(max-width: 768px)' : 
                            ($size['width'] <= 1024 ? '(max-width: 1024px)' : '(min-width: 1025px)'));
                
                $html .= '<source media="' . $mediaQuery . '" srcset="' . base_url($webpPath) . '" type="image/webp">';
            }
        }
        
        // Generate fallback sources
        foreach ($sizes as $size) {
            $fallbackPath = $basePath . '/' . $filename . '_' . $size['suffix'] . '.jpg';
            $mediaQuery = $size['width'] <= 320 ? '(max-width: 320px)' : 
                        ($size['width'] <= 768 ? '(max-width: 768px)' : 
                        ($size['width'] <= 1024 ? '(max-width: 1024px)' : '(min-width: 1025px)'));
            
            $html .= '<source media="' . $mediaQuery . '" srcset="' . base_url($fallbackPath) . '" type="image/jpeg">';
        }
        
        // Default image
        $defaultAttributes = [
            'src' => base_url($imagePath),
            'alt' => $alt,
            'loading' => $config->image['lazy_loading'] ? 'lazy' : 'eager',
            'width' => $config->image['max_width'],
            'height' => $config->image['max_height']
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $html .= '<img';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . esc($value) . '"';
        }
        $html .= '>';
        
        $html .= '</picture>';
        
        return $html;
    }
}

if (!function_exists('generateLazyImage')) {
    /**
     * Generate lazy-loaded image
     * 
     * @param string $src Image source
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML image tag with lazy loading
     */
    function generateLazyImage(string $src, string $alt = '', array $attributes = []): string
    {
        $config = config('Performance');
        
        // Generate placeholder
        $placeholder = 'data:image/svg+xml;base64,' . base64_encode(
            '<svg width="' . ($attributes['width'] ?? 400) . '" height="' . ($attributes['height'] ?? 300) . '" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#f0f0f0"/></svg>'
        );
        
        $defaultAttributes = [
            'src' => $placeholder,
            'data-src' => $src,
            'alt' => $alt,
            'class' => 'lazy-image',
            'loading' => 'lazy',
            'width' => $attributes['width'] ?? $config->image['max_width'],
            'height' => $attributes['height'] ?? $config->image['max_height']
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        $html = '<img';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . esc($value) . '"';
        }
        $html .= '>';
        
        return $html;
    }
}

if (!function_exists('generateImageWithFallback')) {
    /**
     * Generate image with WebP fallback
     * 
     * @param string $imagePath Path to original image
     * @param string $alt Alt text
     * @param array $attributes Additional attributes
     * @return string HTML image tag with fallback
     */
    function generateImageWithFallback(string $imagePath, string $alt = '', array $attributes = []): string
    {
        $config = config('Performance');
        $pathInfo = pathinfo($imagePath);
        
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        $fallbackPath = $imagePath;
        
        $defaultAttributes = [
            'alt' => $alt,
            'loading' => $config->image['lazy_loading'] ? 'lazy' : 'eager',
            'width' => $attributes['width'] ?? $config->image['max_width'],
            'height' => $attributes['height'] ?? $config->image['max_height']
        ];
        
        $attributes = array_merge($defaultAttributes, $attributes);
        
        if ($config->image['webp_enabled'] && file_exists(FCPATH . $webpPath)) {
            $html = '<picture>';
            $html .= '<source srcset="' . base_url($webpPath) . '" type="image/webp">';
            $html .= '<img src="' . base_url($fallbackPath) . '"';
            foreach ($attributes as $key => $value) {
                $html .= ' ' . $key . '="' . esc($value) . '"';
            }
            $html .= '>';
            $html .= '</picture>';
        } else {
            $html = '<img src="' . base_url($fallbackPath) . '"';
            foreach ($attributes as $key => $value) {
                $html .= ' ' . $key . '="' . esc($value) . '"';
            }
            $html .= '>';
        }
        
        return $html;
    }
}

if (!function_exists('compressImage')) {
    /**
     * Compress image file
     * 
     * @param string $sourcePath Source image path
     * @param string $destinationPath Destination path
     * @param int $quality Compression quality (1-100)
     * @return bool Success status
     */
    function compressImage(string $sourcePath, string $destinationPath, int $quality = 85): bool
    {
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            return false;
        }
        
        $mimeType = $imageInfo['mime'];
        
        switch ($mimeType) {
            case 'image/jpeg':
                $source = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $source = imagecreatefrompng($sourcePath);
                break;
            case 'image/gif':
                $source = imagecreatefromgif($sourcePath);
                break;
            case 'image/webp':
                $source = imagecreatefromwebp($sourcePath);
                break;
            default:
                return false;
        }
        
        if (!$source) {
            return false;
        }
        
        // Create destination directory if it doesn't exist
        $destDir = dirname($destinationPath);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        $result = false;
        $extension = strtolower(pathinfo($destinationPath, PATHINFO_EXTENSION));
        
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($source, $destinationPath, $quality);
                break;
            case 'png':
                $result = imagepng($source, $destinationPath, 9 - ($quality / 10));
                break;
            case 'webp':
                $result = imagewebp($source, $destinationPath, $quality);
                break;
        }
        
        imagedestroy($source);
        
        return $result;
    }
}

if (!function_exists('generateImageSizes')) {
    /**
     * Generate multiple image sizes
     * 
     * @param string $sourcePath Source image path
     * @param array $sizes Array of sizes to generate
     * @return array Generated image paths
     */
    function generateImageSizes(string $sourcePath, array $sizes): array
    {
        $pathInfo = pathinfo($sourcePath);
        $basePath = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        $generated = [];
        
        foreach ($sizes as $size) {
            $width = $size['width'];
            $height = $size['height'] ?? $width;
            $suffix = $size['suffix'] ?? $width . 'x' . $height;
            
            $destinationPath = $basePath . '/' . $filename . '_' . $suffix . '.' . $extension;
            
            if (!file_exists(FCPATH . $destinationPath)) {
                $image = \Config\Services::image();
                $image->withFile(FCPATH . $sourcePath);
                $image->resize($width, $height, true, 'center');
                $image->save(FCPATH . $destinationPath, 85);
            }
            
            $generated[] = $destinationPath;
        }
        
        return $generated;
    }
}