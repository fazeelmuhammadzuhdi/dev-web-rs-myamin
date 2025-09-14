<?php

if (!function_exists('strip_empty_p_tags')) {
    function strip_empty_p_tags($content)
    {
        $content = preg_replace('/<img[^>]+>/', '', $content); // remove img tags
        $content = strip_tags($content, '<p>'); // remove all tags except <p>
        $content = preg_replace('/<p>\s*?<\/p>/', '', $content); // remove empty <p> tags
        $content = preg_replace('/<p><br\s*?\/?><\/p>/', '', $content); // remove <p> tags with only <br> elements
        return $content;
    }
}
