<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::withCount('products')->latest()->paginate(20);
        return view('admin.flash-sales.index', compact('flashSales'));
    }

    public function create()
    {
        return view('admin.flash-sales.form', ['flashSale' => new FlashSale(), 'isEdit' => false, 'products' => collect()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $flashSale = FlashSale::create($data);

        // Handle products
        if ($request->filled('sale_products')) {
            foreach ($request->sale_products as $sp) {
                if (!empty($sp['product_id']) && !empty($sp['sale_price'])) {
                    FlashSaleProduct::create([
                        'flash_sale_id' => $flashSale->id,
                        'product_id' => $sp['product_id'],
                        'sale_price' => $sp['sale_price'],
                        'sale_quantity' => $sp['sale_quantity'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale created.');
    }

    public function edit(FlashSale $flashSale)
    {
        $products = $flashSale->products()->with('product')->get();
        return view('admin.flash-sales.form', ['flashSale' => $flashSale, 'isEdit' => true, 'products' => $products]);
    }

    public function update(Request $request, FlashSale $flashSale)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'starts_at' => 'required|date',
            'expires_at' => 'required|date|after:starts_at',
            'is_active' => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $flashSale->update($data);

        // Sync products
        $flashSale->products()->delete();
        if ($request->filled('sale_products')) {
            foreach ($request->sale_products as $sp) {
                if (!empty($sp['product_id']) && !empty($sp['sale_price'])) {
                    FlashSaleProduct::create([
                        'flash_sale_id' => $flashSale->id,
                        'product_id' => $sp['product_id'],
                        'sale_price' => $sp['sale_price'],
                        'sale_quantity' => $sp['sale_quantity'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale updated.');
    }

    public function destroy(FlashSale $flashSale)
    {
        $flashSale->products()->delete();
        $flashSale->delete();
        return redirect()->route('admin.flash-sales.index')->with('success', 'Flash sale deleted.');
    }
}
