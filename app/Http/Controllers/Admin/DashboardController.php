<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->startOfDay();

        // Stat cards
        $todayRevenue = Order::where('created_at', '>=', $today)
            ->where('payment_status', 'paid')
            ->sum('total');

        $todayOrders = Order::where('created_at', '>=', $today)->count();

        $todayCustomers = User::where('created_at', '>=', $today)
            ->whereHas('roles', fn($q) => $q->where('name', 'customer'))
            ->count();

        $lowStockCount = Product::active()
            ->where('manage_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->count();

        // Revenue chart - last 30 days
        $revenueChart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Low stock products
        $lowStockProducts = Product::active()
            ->where('manage_stock', true)
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->select('id', 'name', 'sku', 'stock_quantity', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'todayRevenue', 'todayOrders', 'todayCustomers', 'lowStockCount',
            'revenueChart', 'ordersByStatus', 'recentOrders', 'lowStockProducts'
        ));
    }
}
