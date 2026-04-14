<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::latest();

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('code', 'like', "%{$s}%")
                  ->orWhere('name', 'like', "%{$s}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)
                        ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
                        ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()));
                    break;
                case 'expired':
                    $query->where('expires_at', '<', now());
                    break;
                case 'scheduled':
                    $query->where('starts_at', '>', now());
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $coupons = $query->paginate(20)->withQueryString();

        // Stats
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))->count(),
            'expired' => Coupon::whereNotNull('expires_at')->where('expires_at', '<', now())->count(),
            'total_usage' => Coupon::sum('used_count'),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    public function create()
    {
        return view('admin.coupons.form', ['coupon' => new Coupon(), 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $this->validateCoupon($request);
        $data['is_active'] = $request->boolean('is_active');
        $data['exclude_sale_items'] = $request->boolean('exclude_sale_items');
        $data['code'] = Str::upper($data['code']);

        // Handle JSON arrays
        $data['product_ids'] = $request->input('product_ids') ? array_filter($request->input('product_ids')) : null;
        $data['category_ids'] = $request->input('category_ids') ? array_filter($request->input('category_ids')) : null;

        Coupon::create($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function show(Coupon $coupon)
    {
        $orders = Order::where('coupon_code', $coupon->code)
            ->with('user')
            ->latest()
            ->paginate(15);

        $totalRevenue = Order::where('coupon_code', $coupon->code)->sum('total');
        $totalDiscount = Order::where('coupon_code', $coupon->code)->sum('discount_amount');

        return view('admin.coupons.show', compact('coupon', 'orders', 'totalRevenue', 'totalDiscount'));
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.form', ['coupon' => $coupon, 'isEdit' => true]);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validateCoupon($request, $coupon->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['exclude_sale_items'] = $request->boolean('exclude_sale_items');
        $data['code'] = Str::upper($data['code']);

        $data['product_ids'] = $request->input('product_ids') ? array_filter($request->input('product_ids')) : null;
        $data['category_ids'] = $request->input('category_ids') ? array_filter($request->input('category_ids')) : null;

        $coupon->update($data);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }

    /**
     * Toggle coupon active status.
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'is_active' => $coupon->is_active]);
        }

        return back()->with('success', "Coupon {$coupon->code} " . ($coupon->is_active ? 'activated' : 'deactivated') . '.');
    }

    /**
     * Duplicate a coupon.
     */
    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '_COPY';
        $newCoupon->used_count = 0;
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon)->with('success', 'Coupon duplicated. Update the code before saving.');
    }

    /**
     * Bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $ids = $request->input('ids', []);
        $action = $request->input('action');

        if (empty($ids)) {
            return back()->with('error', 'No coupons selected.');
        }

        switch ($action) {
            case 'activate':
                Coupon::whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', count($ids) . ' coupon(s) activated.');
            case 'deactivate':
                Coupon::whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', count($ids) . ' coupon(s) deactivated.');
            case 'delete':
                Coupon::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' coupon(s) deleted.');
            default:
                return back()->with('error', 'Invalid action.');
        }
    }

    private function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code' . ($ignoreId ? ',' . $ignoreId : ''),
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:percent,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:0',
            'usage_limit_per_user' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'exclude_sale_items' => 'boolean',
            'applies_to' => 'nullable|in:all,specific_products,specific_categories',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ]);
    }
}
