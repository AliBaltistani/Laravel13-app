<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeGroup;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Create Categories
        $categories = [
            ['name' => 'Electronics', 'icon' => 'icon-laptop', 'children' => [
                'Smart TVs', 'Cameras', 'Headphones', 'Speakers', 'Gaming',
            ]],
            ['name' => 'Fashion', 'icon' => 'icon-tshirt', 'children' => [
                'Men', 'Women', 'Kids', 'Accessories', 'Shoes',
            ]],
            ['name' => 'Home & Garden', 'icon' => 'icon-home', 'children' => [
                'Furniture', 'Kitchen', 'Decor', 'Lighting', 'Tools',
            ]],
            ['name' => 'Sports', 'icon' => 'icon-futbol', 'children' => [
                'Fitness', 'Outdoor', 'Cycling', 'Swimming',
            ]],
        ];

        foreach ($categories as $i => $catData) {
            $parent = Category::create([
                'name' => $catData['name'],
                'icon' => $catData['icon'],
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => $i,
            ]);
            foreach ($catData['children'] as $j => $childName) {
                Category::create([
                    'name' => $childName,
                    'parent_id' => $parent->id,
                    'is_active' => true,
                    'sort_order' => $j,
                ]);
            }
        }

        // Create Brands
        $brandNames = ['Porto', 'Nike', 'Samsung', 'Apple', 'Sony', 'Adidas', 'LG', 'Canon'];
        foreach ($brandNames as $i => $name) {
            Brand::create([
                'name' => $name,
                'is_active' => true,
                'is_featured' => true,
                'sort_order' => $i,
            ]);
        }

        // Create Tags
        $tagNames = ['Bestseller', 'New Arrival', 'Sale', 'Premium', 'Trending', 'Limited Edition', 'Eco-Friendly', 'Wireless', 'Organic', 'Handmade'];
        foreach ($tagNames as $name) {
            Tag::create(['name' => $name]);
        }

        // Create Attribute Groups & Attributes
        $colorGroup = AttributeGroup::create(['name' => 'Color', 'display_type' => 'color', 'sort_order' => 0]);
        $colors = ['Black' => '#000000', 'White' => '#FFFFFF', 'Red' => '#FF0000', 'Blue' => '#0088CC', 'Grey' => '#999999'];
        foreach ($colors as $name => $value) {
            Attribute::create(['attribute_group_id' => $colorGroup->id, 'name' => $name, 'value' => $value]);
        }

        $sizeGroup = AttributeGroup::create(['name' => 'Size', 'display_type' => 'select', 'sort_order' => 1]);
        foreach (['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size) {
            Attribute::create(['attribute_group_id' => $sizeGroup->id, 'name' => $size, 'value' => $size]);
        }

        // Create Products (using Porto demo images)
        $products = [
            ['name' => 'Black Grey Headset', 'price' => 49.00, 'compare_price' => 59.00, 'category' => 'Headphones', 'brand' => 'Sony', 'featured' => true, 'new' => true, 'img' => 'product-1'],
            ['name' => 'Battery Charger', 'price' => 29.00, 'category' => 'Electronics', 'brand' => 'Samsung', 'featured' => true, 'img' => 'product-2'],
            ['name' => 'Brown Leather Bag', 'price' => 89.00, 'compare_price' => 120.00, 'category' => 'Accessories', 'brand' => 'Porto', 'featured' => true, 'new' => true, 'img' => 'product-3'],
            ['name' => 'Casual Note Bag', 'price' => 65.00, 'category' => 'Accessories', 'brand' => 'Porto', 'featured' => true, 'img' => 'product-4'],
            ['name' => 'Porto Extended Camera', 'price' => 299.00, 'compare_price' => 399.00, 'category' => 'Cameras', 'brand' => 'Canon', 'featured' => true, 'img' => 'product-5'],
            ['name' => 'Blue BackPack', 'price' => 55.00, 'category' => 'Accessories', 'brand' => 'Nike', 'featured' => true, 'new' => true, 'img' => 'product-6'],
            ['name' => 'Computer Mouse', 'price' => 19.00, 'category' => 'Electronics', 'brand' => 'Apple', 'featured' => true, 'img' => 'product-7'],
            ['name' => 'Casual Blue Shoes', 'price' => 79.00, 'compare_price' => 99.00, 'category' => 'Shoes', 'brand' => 'Nike', 'featured' => true, 'img' => 'product-8'],
            ['name' => 'Wireless Speaker', 'price' => 99.00, 'compare_price' => 129.00, 'category' => 'Speakers', 'brand' => 'Sony', 'new' => true, 'img' => 'product-9'],
            ['name' => 'Smart Watch Pro', 'price' => 199.00, 'compare_price' => 249.00, 'category' => 'Electronics', 'brand' => 'Apple', 'featured' => true, 'img' => 'product-10'],
            ['name' => 'Running Shoes Elite', 'price' => 120.00, 'category' => 'Shoes', 'brand' => 'Adidas', 'new' => true, 'img' => 'product-11'],
            ['name' => 'Vintage Desk Lamp', 'price' => 45.00, 'category' => 'Lighting', 'brand' => 'Porto', 'img' => 'product-12'],
            ['name' => 'Fitness Tracker Band', 'price' => 39.00, 'compare_price' => 59.00, 'category' => 'Fitness', 'brand' => 'Samsung', 'featured' => true, 'new' => true, 'img' => 'product-1'],
            ['name' => 'Portable Bluetooth Speaker', 'price' => 35.00, 'category' => 'Speakers', 'brand' => 'Sony', 'img' => 'product-2'],
            ['name' => 'Classic Leather Wallet', 'price' => 42.00, 'category' => 'Accessories', 'brand' => 'Porto', 'img' => 'product-3'],
            ['name' => 'Gaming Headset RGB', 'price' => 89.00, 'compare_price' => 120.00, 'category' => 'Gaming', 'brand' => 'Sony', 'featured' => true, 'img' => 'product-4'],
        ];

        $allBrands = Brand::all()->keyBy('name');
        $allCategories = Category::all()->keyBy('name');
        $allTags = Tag::all();

        foreach ($products as $i => $data) {
            $cat = $allCategories[$data['category']] ?? $allCategories->first();
            $brand = $allBrands[$data['brand']] ?? $allBrands->first();

            $product = Product::create([
                'category_id' => $cat->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'sku' => 'PORTO-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'short_description' => "High quality {$data['name']}. Perfect for everyday use.",
                'description' => "<p>{$data['name']} is crafted with premium materials and designed for modern living. Experience superior quality and elegant design.</p><ul><li>Premium build quality</li><li>Modern design</li><li>1 Year warranty</li></ul>",
                'price' => $data['price'],
                'compare_price' => $data['compare_price'] ?? null,
                'type' => 'simple',
                'is_active' => true,
                'is_featured' => $data['featured'] ?? false,
                'is_new' => $data['new'] ?? false,
                'manage_stock' => true,
                'stock_quantity' => rand(5, 100),
                'sold_count' => rand(0, 200),
                'view_count' => rand(10, 1000),
            ]);

            // Create product image using Porto demo images
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => "products/{$data['img']}.jpg", // Reference to demo images
                'alt_text' => $data['name'],
                'is_primary' => true,
                'sort_order' => 0,
            ]);

            // Assign random tags
            $product->tags()->attach($allTags->random(rand(1, 3))->pluck('id'));
        }

        $this->command->info('✅ Seeded: 4 categories (+ children), 8 brands, 10 tags, 16 products');
    }
}
