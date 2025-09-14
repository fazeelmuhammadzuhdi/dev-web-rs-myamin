<?php

if (!function_exists('generateAltText')) {
    /**
     * Generate appropriate alt text for images
     * 
     * @param string $imagePath Path to image
     * @param string $context Context of the image
     * @return string Alt text
     */
    function generateAltText(string $imagePath, string $context = ''): string
    {
        // Extract filename without extension
        $filename = pathinfo($imagePath, PATHINFO_FILENAME);
        
        // Clean filename
        $altText = str_replace(['_', '-'], ' ', $filename);
        $altText = ucwords($altText);
        
        // Add context if provided
        if ($context) {
            $altText = $context . ' - ' . $altText;
        }
        
        return $altText;
    }
}

if (!function_exists('checkColorContrast')) {
    /**
     * Check color contrast ratio
     * 
     * @param string $foreground Foreground color (hex)
     * @param string $background Background color (hex)
     * @return array Contrast information
     */
    function checkColorContrast(string $foreground, string $background): array
    {
        // Convert hex to RGB
        $fg = hexToRgb($foreground);
        $bg = hexToRgb($background);
        
        // Calculate relative luminance
        $fgLuminance = getRelativeLuminance($fg);
        $bgLuminance = getRelativeLuminance($bg);
        
        // Calculate contrast ratio
        $lighter = max($fgLuminance, $bgLuminance);
        $darker = min($fgLuminance, $bgLuminance);
        $contrast = ($lighter + 0.05) / ($darker + 0.05);
        
        // Determine compliance
        $aa = $contrast >= 4.5;
        $aaa = $contrast >= 7;
        
        return [
            'ratio' => round($contrast, 2),
            'aa_compliant' => $aa,
            'aaa_compliant' => $aaa,
            'level' => $aaa ? 'AAA' : ($aa ? 'AA' : 'Fail')
        ];
    }
}

if (!function_exists('hexToRgb')) {
    /**
     * Convert hex color to RGB
     * 
     * @param string $hex Hex color
     * @return array RGB values
     */
    function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2))
        ];
    }
}

if (!function_exists('getRelativeLuminance')) {
    /**
     * Calculate relative luminance
     * 
     * @param array $rgb RGB values
     * @return float Relative luminance
     */
    function getRelativeLuminance(array $rgb): float
    {
        $r = $rgb['r'] / 255;
        $g = $rgb['g'] / 255;
        $b = $rgb['b'] / 255;
        
        // Apply gamma correction
        $r = $r <= 0.03928 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.03928 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.03928 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
        
        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }
}

if (!function_exists('generateAriaLabel')) {
    /**
     * Generate ARIA label for interactive elements
     * 
     * @param string $text Text content
     * @param string $type Element type
     * @return string ARIA label
     */
    function generateAriaLabel(string $text, string $type = 'button'): string
    {
        $ariaLabel = trim($text);
        
        // Add context based on element type
        switch ($type) {
            case 'button':
                if (!preg_match('/\b(click|press|select|choose)\b/i', $ariaLabel)) {
                    $ariaLabel = 'Click ' . strtolower($ariaLabel);
                }
                break;
            case 'link':
                if (!preg_match('/\b(go to|visit|open)\b/i', $ariaLabel)) {
                    $ariaLabel = 'Go to ' . strtolower($ariaLabel);
                }
                break;
        }
        
        return $ariaLabel;
    }
}

if (!function_exists('addSkipLinks')) {
    /**
     * Generate skip links for keyboard navigation
     * 
     * @param array $links Array of skip links
     * @return string HTML skip links
     */
    function addSkipLinks(array $links = []): string
    {
        $defaultLinks = [
            ['href' => '#main-content', 'text' => 'Skip to main content'],
            ['href' => '#navigation', 'text' => 'Skip to navigation'],
            ['href' => '#search', 'text' => 'Skip to search'],
        ];
        
        $links = array_merge($defaultLinks, $links);
        
        $html = '<div class="skip-links">';
        foreach ($links as $link) {
            $html .= '<a href="' . esc($link['href']) . '" class="skip-link">' . esc($link['text']) . '</a>';
        }
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('validateFormAccessibility')) {
    /**
     * Validate form accessibility
     * 
     * @param array $fields Form fields
     * @return array Validation results
     */
    function validateFormAccessibility(array $fields): array
    {
        $errors = [];
        
        foreach ($fields as $field) {
            // Check for required labels
            if (empty($field['label']) && !empty($field['required'])) {
                $errors[] = "Field '{$field['name']}' is required but has no label";
            }
            
            // Check for proper input types
            if (empty($field['type'])) {
                $errors[] = "Field '{$field['name']}' has no input type specified";
            }
            
            // Check for error messages
            if (empty($field['error_message']) && !empty($field['required'])) {
                $errors[] = "Required field '{$field['name']}' has no error message";
            }
        }
        
        return $errors;
    }
}