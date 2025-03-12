<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function AdminDashboard(){

        $AdminDashboardData = [
            'totalProducts' => Product::count(),
            'totalProducts' => Product::count(),
            'totalProducts' => Product::count(),
        ];

    }
}
