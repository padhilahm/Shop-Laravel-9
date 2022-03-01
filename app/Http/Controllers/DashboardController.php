<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = array(
            'url' => 'dashboard', 
        );
        return view('dashboard.index', $data);
    }
}
