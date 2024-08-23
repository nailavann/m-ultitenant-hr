<?php

namespace App\Http\Controllers\Central\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('central.dashboard');
    }
}
