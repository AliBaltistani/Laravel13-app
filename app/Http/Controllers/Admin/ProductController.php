<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->where('manage_stock', true)
                      ->whereColumn('stock_quantity', '<=', 'low_stock_threshold');
            } elseif ($request->stock === 'out') {
                $query->where('manage_stock', true)->where('stock_quantity', '<=', 0);
            }
        }

        $sortField = $request->input('sort', 'created_at');
        $sortDir = $request->input('dir', 'desc');
        $query->orderBy($sortField, $sortDir);

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => $categories,
            'brands' => $brands,
            'tags' => $tags,
            'isEdit' => false,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        // Handle tags
        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        }

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'tags', 'variants.attributes', 'relatedProducts']);
        $categories = Category::active()->ordered()->get();
        $brands = Brand::active()->ordered()->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.products.form', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
            'tags' => $tags,
            'isEdit' => true,
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        if ($request->filled('tags')) {
            $product->tags()->sync($request->tags);
        } else {
            $product->tags()->detach();
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $maxSort = $product->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'alt_text' => $product->name,
                    'sort_order' => $maxSort + $index + 1,
                    'is_primary' => $product->images()->count() === 0 && $index === 0,
                ]);
            }
        }

        // Handle primary image change
        if ($request->filled('primary_image_id')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $request->primary_image_id)->update(['is_primary' => true]);
        }

        // Handle image deletions
        if ($request->filled('delete_images')) {
            $imagesToDelete = $product->images()->whereIn('id', $request->delete_images)->get();
            foreach ($imagesToDelete as $img) {
                Storage::disk('public')->delete($img->image_path);
                $img->delete();
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'activate':
                $products->update(['is_active' => true]);
                $msg = 'Products activated.';
                break;
            case 'deactivate':
                $products->update(['is_active' => false]);
                $msg = 'Products deactivated.';
                break;
            case 'delete':
                $products->delete();
                $msg = 'Products deleted.';
                break;
        }

        return back()->with('success', $msg);
    }

    public function export(Request $request)
    {
        $products = Product::with(['category', 'brand'])->get();

        $callback = function () use ($products) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'SKU', 'Category', 'Brand', 'Price', 'Stock', 'Status']);
            foreach ($products as $p) {
                fputcsv($file, [
                    $p->id, $p->name, $p->sku,
                    $p->category?->name, $p->brand?->name,
                    $p->price, $p->stock_quantity,
                    $p->is_active ? 'Active' : 'Inactive',
                ]);
            }
            fclose($file);
        };

        return response()->streamDownload($callback, 'products-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
