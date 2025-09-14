<?php

namespace App\Controllers;

use App\Models\PageVisit;
use App\Controllers\BaseController;

/**
 * Home controller handles basic home page functionality
 * 
 * This controller provides basic home page functionality
 * and can be extended for additional home page features.
 */
class Home extends BaseController
{
    /**
     * Display home page
     */
    public function index()
    {
        // Basic home page implementation
        // Can be extended with additional functionality as needed
        return view('home');
    }
}