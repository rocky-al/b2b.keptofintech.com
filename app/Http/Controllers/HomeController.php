<?php

namespace App\Http\Controllers;
class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        return view('clear-cache');
    }
   
}
