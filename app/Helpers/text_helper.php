<?php

if (!function_exists('limit_words')) {
    function limit_words($text, $limit = 100)
    {
        $words = explode(' ', $text);
        if (count($words) > $limit) {
            $text = implode(' ', array_slice($words, 0, $limit)) . '...';
        }
        return $text;
    }
}
