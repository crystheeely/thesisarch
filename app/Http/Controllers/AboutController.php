<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        return view('about');  // Return the about view (make sure 'about' exists in resources/views)
    }
}
