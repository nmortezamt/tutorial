<?php

namespace Tutorial\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function render()
    {
        return view('Dashboard::index');
    }
}
