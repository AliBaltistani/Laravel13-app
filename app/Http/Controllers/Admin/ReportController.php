<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $stats = Order::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->selectRaw('COUNT(*) as total_orders, SUM(total) as total_revenue, AVG(total) as avg_order, COUNT(DISTINCT user_id) as unique_customers')
            ->first();

        $dailyData = Order::whereBetween('created_at', [$from, $to . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total) as revenue'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.sales', compact('stats', 'dailyData', 'from', 'to'));
    }

    public function products(Request $request)
    {
        $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        $topProducts = Product::select('products.id', 'products.name', 'products.sku')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to . ' 23:59:59'])
            ->selectRaw('SUM(order_items.quantity) as units_sold, SUM(order_items.total) as total_revenue')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('units_sold')
            ->take(20)
            ->get();

        return view('admin.reports.products', compact('topProducts', 'from', 'to'));
    }

    public function inventory()
    {
        $products = Product::active()
            ->where('manage_stock', true)
            ->select('id', 'name', 'sku', 'stock_quantity', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->paginate(30);

        return view('admin.reports.inventory', compact('products'));
    }

    public function export(Request $request, $type)
    {
        $from = $request->input('from', now()->subDays(30)->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));

        if ($type === 'sales') {
            $data = Order::whereBetween('created_at', [$from, $to . ' 23:59:59'])
                ->where('payment_status', 'paid')
                ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as orders'), DB::raw('SUM(total) as revenue'))
                ->groupBy('date')->orderBy('date')->get();

            $callback = function () use ($data) {
                $f = fopen('php://output', 'w');
                fputcsv($f, ['Date', 'Orders', 'Revenue']);
                foreach ($data as $d) fputcsv($f, [$d->date, $d->orders, $d->revenue]);
                fclose($f);
            };
            return response()->streamDownload($callback, "sales-report-{$from}-{$to}.csv");
        }

        abort(404);
    }
}
