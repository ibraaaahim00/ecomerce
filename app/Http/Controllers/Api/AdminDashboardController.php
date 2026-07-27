<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function stats()
    {
        return response()->json([
            'users_count' => User::count(),
            'products_count' => Product::count(),
            'orders_count' => Order::count(),
            'total_sales' => Order::sum('total_price'),
            'latest_orders' => Order::with('user')
                ->latest()
                ->take(5)
                ->get()
        ]);
    }
}
