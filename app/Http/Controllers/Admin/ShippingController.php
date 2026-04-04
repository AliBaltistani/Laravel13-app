<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::withCount('methods')->get();
        return view('admin.shipping.zones', compact('zones'));
    }

    public function create()
    {
        return view('admin.shipping.zone-form', ['zone' => new ShippingZone(), 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'required|array',
            'countries.*' => 'string|size:2',
        ]);
        $data['countries'] = json_encode($data['countries']);
        ShippingZone::create($data);
        return redirect()->route('admin.shipping-zones.index')->with('success', 'Shipping zone created.');
    }

    public function edit(ShippingZone $shippingZone)
    {
        return view('admin.shipping.zone-form', ['zone' => $shippingZone, 'isEdit' => true]);
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'countries' => 'required|array',
        ]);
        $data['countries'] = json_encode($data['countries']);
        $shippingZone->update($data);
        return redirect()->route('admin.shipping-zones.index')->with('success', 'Shipping zone updated.');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->methods()->delete();
        $shippingZone->delete();
        return redirect()->route('admin.shipping-zones.index')->with('success', 'Shipping zone deleted.');
    }

    public function show(ShippingZone $shippingZone)
    {
        $methods = $shippingZone->methods()->orderBy('sort_order')->get();
        return view('admin.shipping.methods', compact('shippingZone', 'methods'));
    }

    // Shipping Method CRUD
    public function storeMethod(Request $request)
    {
        $data = $request->validate([
            'shipping_zone_id' => 'required|exists:shipping_zones,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat_rate,free,weight_based',
            'price' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        ShippingMethod::create($data);
        return back()->with('success', 'Shipping method added.');
    }

    public function updateMethod(Request $request, ShippingMethod $shippingMethod)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat_rate,free,weight_based',
            'price' => 'nullable|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_order_amount' => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);
        $shippingMethod->update($data);
        return back()->with('success', 'Shipping method updated.');
    }

    public function destroyMethod(ShippingMethod $shippingMethod)
    {
        $zoneId = $shippingMethod->shipping_zone_id;
        $shippingMethod->delete();
        return redirect()->route('admin.shipping-zones.show', $zoneId)->with('success', 'Shipping method deleted.');
    }
}
