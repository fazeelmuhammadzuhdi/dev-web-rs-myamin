<?php

if (!function_exists('get_kategori')) {
    function get_kategori()
    {
        // Load model kategori
        $kategoriModel = new \App\Models\Kategori();
        return $kategoriModel->where('status', 'Y')->orderBy('title', 'asc')->findAll();
    }
}
