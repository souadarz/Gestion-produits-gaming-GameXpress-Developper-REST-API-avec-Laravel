<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sub_category;
use App\Models\User;
use App\Notifications\stockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class AdminDashboardController extends Controller
{ 
    public function AdminDashboard(){

        if(!auth()->user()->can('view_dashboard')){
            return [ 'message' => "vous n’avez pas la permission de voir le tableau de bord."];
        }
        
        $StockProducts = Product::where('stock', '<=', 10)->get();
        $admin = User::role('super_admin')->get();
        Notification::send($admin, new stockNotification($StockProducts));
        
        $AdminDashboardData = [
            'totalUsers' => 20,
            'totalProducts' => 30,
            'totalCategories' => 10,
            'totalSubCategories' => 60,
        ];

        return [
            'statistique' => $AdminDashboardData
        ];
    }
}