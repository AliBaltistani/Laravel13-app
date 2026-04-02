<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shipment;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('payment_status')) $query->where('payment_status', $request->payment_status);
        if ($request->filled('from')) $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to')) $query->whereDate('created_at', '<=', $request->to);

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items', 'statusHistories.creator', 'shipments']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'comment' => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'payment_status' => $order->payment_status,
            'comment' => $request->comment,
            'is_customer_notified' => $request->boolean('notify_customer'),
            'created_by' => auth()->id(),
        ]);

        // Fire event for email & in-app notifications (Phase 10)
        event(new OrderStatusChanged(
            $order,
            $oldStatus,
            $request->status,
            $request->boolean('notify_customer'),
            $request->comment ?? ''
        ));

        return back()->with('success', 'Order status updated to ' . ucfirst($request->status) . '.');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:100',
            'carrier' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:500',
        ]);

        Shipment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'tracking_number' => $request->tracking_number,
                'carrier' => $request->carrier,
                'tracking_url' => $request->tracking_url,
                'shipped_at' => now(),
            ]
        );

        if ($order->status !== 'shipped') {
            $oldStatus = $order->status;
            $order->update(['status' => 'shipped', 'shipped_at' => now()]);
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'shipped',
                'payment_status' => $order->payment_status,
                'comment' => 'Tracking: ' . $request->tracking_number,
                'is_customer_notified' => true,
                'created_by' => auth()->id(),
            ]);

            // Fire shipped event (Phase 10)
            event(new OrderStatusChanged(
                $order,
                $oldStatus,
                'shipped',
                true,
                'Your order has been shipped with tracking: ' . $request->tracking_number
            ));
        }

        return back()->with('success', 'Tracking information updated.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items', 'user']);
        // Simple HTML invoice for now, DomPDF can be added later
        return view('admin.orders.invoice', compact('order'));
    }

    public function export(Request $request)
    {
        $orders = Order::with('user')->latest()->get();

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Order#', 'Customer', 'Email', 'Status', 'Payment', 'Total', 'Date']);
            foreach ($orders as $o) {
                fputcsv($file, [
                    $o->order_number, $o->user?->name ?? 'Guest', $o->user?->email ?? $o->billing_email,
                    $o->status, $o->payment_status, $o->total, $o->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'orders-' . now()->format('Y-m-d') . '.csv');
    }
}
