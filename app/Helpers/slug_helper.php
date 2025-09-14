<?php
function createSlug($string)
{
    // Convert all characters to lowercase
    $string = strtolower($string);
    // Remove any character that is not alphanumeric, whitespace, or a dash
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    // Replace multiple whitespace or dash with a single dash
    $string = preg_replace('/[\s-]+/', ' ', $string);
    // Convert whitespaces and underscore to a single dash
    $string = preg_replace('/[\s_]/', '-', $string);
    return $string;
}
