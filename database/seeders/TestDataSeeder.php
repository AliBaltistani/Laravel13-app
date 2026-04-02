<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\FlashSale;
use App\Models\FlashSaleProduct;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Page;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Models\Product;
use App\Models\Review;
use App\Models\Shipment;
use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Models\Slider;
use App\Models\SliderSlide;
use App\Models\Tag;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSliders();
        $this->createBanners();
        $this->createCustomers();
        $this->createShippingZones();
        $this->createCoupons();
        $this->createFlashSale();
        $this->createReviews();
        $this->createOrders();
        $this->createBlogContent();
        $this->createCmsPages();
        $this->createContactMessages();
        $this->createNewsletterSubscribers();
        $this->createWishlists();

        $this->command->info('✅ TestDataSeeder: All test data created successfully!');
    }

    private function createSliders(): void
    {
        $slider = Slider::create([
            'name' => 'Hero Slider',
            'position' => 'hero',
            'is_active' => true,
        ]);

        $slides = [
            [
                'title' => 'Summer Sale',
                'subtitle' => 'Find the Boundaries. Push Through!',
                'description' => '<span class="ls-10">70% Off</span>',
                'button_text' => 'Shop Now!',
                'button_url' => '/shop',
                'image_desktop' => null,
                'text_color' => 'light',
                'sort_order' => 0,
            ],
            [
                'title' => 'Great Deals',
                'subtitle' => 'Over 200 products with discounts',
                'description' => null,
                'button_text' => 'Get Yours!',
                'button_url' => '/shop',
                'image_desktop' => null,
                'text_color' => 'dark',
                'sort_order' => 1,
            ],
            [
                'title' => 'New Arrivals',
                'subtitle' => 'Up to 70% off',
                'description' => null,
                'button_text' => 'Get Yours!',
                'button_url' => '/shop',
                'image_desktop' => null,
                'text_color' => 'dark',
                'sort_order' => 2,
            ],
        ];

        foreach ($slides as $slide) {
            SliderSlide::create(array_merge($slide, [
                'slider_id' => $slider->id,
                'is_active' => true,
            ]));
        }

        $this->command->info('  → Created hero slider with 3 slides');
    }

    private function createBanners(): void
    {
        $banners = [
            ['title' => 'Porto Watches', 'subtitle' => '30% OFF', 'button_text' => 'Shop Now', 'button_url' => '/shop', 'position' => 'home-mid', 'sort_order' => 0],
            ['title' => 'Deal Promos', 'subtitle' => 'Starting at $99', 'button_text' => 'Shop Now', 'button_url' => '/shop', 'position' => 'home-mid', 'sort_order' => 1],
            ['title' => 'Handbags', 'subtitle' => 'Starting at $99', 'button_text' => 'Shop Now', 'button_url' => '/shop', 'position' => 'home-mid', 'sort_order' => 2],
            ['title' => 'Spring Collection', 'subtitle' => 'Up to 50% Off', 'button_text' => 'Discover', 'button_url' => '/shop', 'position' => 'home-bottom', 'sort_order' => 0],
            ['title' => 'Accessories Sale', 'subtitle' => 'Save Big', 'button_text' => 'Shop Now', 'button_url' => '/shop', 'position' => 'home-bottom', 'sort_order' => 1],
        ];

        foreach ($banners as $banner) {
            Banner::create(array_merge($banner, ['is_active' => true]));
        }

        $this->command->info('  → Created 5 banners');
    }

    private function createCustomers(): void
    {
        $customers = [
            ['name' => 'John Smith', 'first_name' => 'John', 'last_name' => 'Smith', 'email' => 'john@example.com', 'phone' => '+1-555-0101'],
            ['name' => 'Jane Doe', 'first_name' => 'Jane', 'last_name' => 'Doe', 'email' => 'jane@example.com', 'phone' => '+1-555-0102'],
            ['name' => 'Bob Wilson', 'first_name' => 'Bob', 'last_name' => 'Wilson', 'email' => 'bob@example.com', 'phone' => '+1-555-0103'],
            ['name' => 'Alice Brown', 'first_name' => 'Alice', 'last_name' => 'Brown', 'email' => 'alice@example.com', 'phone' => '+1-555-0104'],
            ['name' => 'Charlie Davis', 'first_name' => 'Charlie', 'last_name' => 'Davis', 'email' => 'charlie@example.com', 'phone' => '+1-555-0105'],
            ['name' => 'Emma Johnson', 'first_name' => 'Emma', 'last_name' => 'Johnson', 'email' => 'emma@example.com', 'phone' => '+1-555-0106'],
            ['name' => 'David Lee', 'first_name' => 'David', 'last_name' => 'Lee', 'email' => 'david@example.com', 'phone' => '+1-555-0107'],
            ['name' => 'Sarah Miller', 'first_name' => 'Sarah', 'last_name' => 'Miller', 'email' => 'sarah@example.com', 'phone' => '+1-555-0108'],
            ['name' => 'Tom Anderson', 'first_name' => 'Tom', 'last_name' => 'Anderson', 'email' => 'tom@example.com', 'phone' => '+1-555-0109'],
            ['name' => 'Lisa Taylor', 'first_name' => 'Lisa', 'last_name' => 'Taylor', 'email' => 'lisa@example.com', 'phone' => '+1-555-0110'],
        ];

        foreach ($customers as $data) {
            $user = User::create(array_merge($data, [
                'password' => Hash::make('password'),
                'is_active' => true,
                'newsletter_subscribed' => rand(0, 1),
            ]));
            $user->assignRole('customer');

            // Create address for each customer
            UserAddress::create([
                'user_id' => $user->id,
                'label' => 'home',
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'address_line1' => rand(100, 9999) . ' Main Street',
                'city' => collect(['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix'])->random(),
                'state' => collect(['NY', 'CA', 'IL', 'TX', 'AZ'])->random(),
                'postal_code' => str_pad(rand(10000, 99999), 5, '0', STR_PAD_LEFT),
                'country_id' => 1,
                'phone' => $data['phone'],
                'is_default_shipping' => true,
                'is_default_billing' => true,
            ]);
        }

        $this->command->info('  → Created 10 customer accounts with addresses');
    }

    private function createShippingZones(): void
    {
        $domestic = ShippingZone::create([
            'name' => 'Domestic (US)',
            'countries' => json_encode(['US']),
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $domestic->id,
            'name' => 'Standard Shipping',
            'type' => 'flat',
            'price' => 5.99,
            'estimated_days' => 7,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $domestic->id,
            'name' => 'Express Shipping',
            'type' => 'flat',
            'price' => 14.99,
            'estimated_days' => 3,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $domestic->id,
            'name' => 'Free Shipping',
            'type' => 'free',
            'price' => 0,
            'min_order_amount' => 99.00,
            'estimated_days' => 10,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $international = ShippingZone::create([
            'name' => 'International',
            'countries' => json_encode(['CA', 'GB', 'AU', 'DE', 'FR']),
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $international->id,
            'name' => 'International Standard',
            'type' => 'flat',
            'price' => 24.99,
            'estimated_days' => 14,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        ShippingMethod::create([
            'shipping_zone_id' => $international->id,
            'name' => 'International Express',
            'type' => 'flat',
            'price' => 49.99,
            'estimated_days' => 5,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->command->info('  → Created 2 shipping zones with 5 methods');
    }

    private function createCoupons(): void
    {
        Coupon::create([
            'code' => 'WELCOME20',
            'name' => 'Welcome Discount',
            'type' => 'percent',
            'value' => 20,
            'min_order_amount' => 50,
            'max_discount' => 100,
            'usage_limit' => 1000,
            'usage_limit_per_user' => 1,
            'used_count' => 45,
            'is_active' => true,
            'starts_at' => now()->subMonths(1),
            'expires_at' => now()->addMonths(3),
        ]);

        Coupon::create([
            'code' => 'FLAT10',
            'name' => '$10 Off',
            'type' => 'fixed',
            'value' => 10,
            'min_order_amount' => 30,
            'usage_limit' => 500,
            'usage_limit_per_user' => 3,
            'used_count' => 120,
            'is_active' => true,
            'starts_at' => now()->subWeeks(2),
            'expires_at' => now()->addMonths(2),
        ]);

        Coupon::create([
            'code' => 'FREESHIP',
            'name' => 'Free Shipping',
            'type' => 'free_shipping',
            'value' => 0,
            'min_order_amount' => 75,
            'usage_limit' => 200,
            'usage_limit_per_user' => 2,
            'used_count' => 30,
            'is_active' => true,
            'starts_at' => now()->subWeek(),
            'expires_at' => now()->addMonth(),
        ]);

        $this->command->info('  → Created 3 coupons');
    }

    private function createFlashSale(): void
    {
        $flashSale = FlashSale::create([
            'name' => 'Spring Super Sale',
            'label' => 'FLASH DEAL',
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDays(5),
            'is_active' => true,
        ]);

        $products = Product::active()->inRandomOrder()->take(4)->get();
        foreach ($products as $product) {
            FlashSaleProduct::create([
                'flash_sale_id' => $flashSale->id,
                'product_id' => $product->id,
                'sale_price' => round($product->price * 0.7, 2),
                'sale_quantity' => rand(10, 50),
                'sold_count' => rand(0, 10),
            ]);
        }

        $this->command->info('  → Created flash sale with 4 products');
    }

    private function createReviews(): void
    {
        $customers = User::role('customer')->get();
        $products = Product::all();

        $reviewTexts = [
            5 => ['Absolutely love this product! Exceeded my expectations.', 'Best purchase I\'ve made this year. Highly recommend!', 'Perfect quality and fast delivery. Five stars!', 'Outstanding product, exactly as described.'],
            4 => ['Great product, very good quality. Minor improvements possible.', 'Really happy with this purchase. Good value for money.', 'Nice product, works well. Would buy again.'],
            3 => ['Decent product, nothing special but does the job.', 'It\'s okay. Expected a bit more for the price.', 'Average quality. Meets basic needs.'],
            2 => ['Not what I expected. Quality could be better.', 'Disappointing purchase. Wouldn\'t recommend.'],
            1 => ['Poor quality product. Very unsatisfied.'],
        ];

        $count = 0;
        foreach ($products->take(12) as $product) {
            $numReviews = rand(1, 4);
            $reviewCustomers = $customers->random(min($numReviews, $customers->count()));

            foreach ($reviewCustomers as $customer) {
                $rating = collect([5, 5, 4, 4, 4, 3, 3, 2, 5])->random();
                $texts = $reviewTexts[$rating];
                $body = $texts[array_rand($texts)];

                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                    'rating' => $rating,
                    'title' => $rating >= 4 ? 'Great Product!' : ($rating >= 3 ? 'Decent Product' : 'Not Satisfied'),
                    'body' => $body,
                    'is_approved' => rand(0, 4) > 0, // 80% approved
                    'is_verified_purchase' => rand(0, 1),
                    'helpful_count' => rand(0, 20),
                ]);
                $count++;
            }
        }

        $this->command->info("  → Created {$count} product reviews");
    }

    private function createOrders(): void
    {
        $customers = User::role('customer')->get();
        $products = Product::all();
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentMethods = ['cod', 'bank_transfer'];
        $count = 0;

        foreach ($customers->take(8) as $customer) {
            $numOrders = rand(1, 3);
            $address = $customer->addresses()->first();

            for ($i = 0; $i < $numOrders; $i++) {
                $status = $statuses[array_rand($statuses)];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                $orderProducts = $products->random(rand(1, 4));
                $subtotal = 0;
                $orderItems = [];

                foreach ($orderProducts as $op) {
                    $qty = rand(1, 3);
                    $lineTotal = $op->price * $qty;
                    $subtotal += $lineTotal;

                    $orderItems[] = [
                        'product_id' => $op->id,
                        'product_name' => $op->name,
                        'product_sku' => $op->sku,
                        'product_image' => $op->images->first()?->image_path,
                        'unit_price' => $op->price,
                        'quantity' => $qty,
                        'subtotal' => $lineTotal,
                        'tax_amount' => 0,
                        'discount_amount' => 0,
                        'total' => $lineTotal,
                    ];
                }

                $shippingAmount = $subtotal >= 99 ? 0 : 5.99;
                $total = $subtotal + $shippingAmount;

                $order = Order::create([
                    'user_id' => $customer->id,
                    'order_number' => 'ORD-' . now()->subDays(rand(0, 60))->format('Ymd') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT),
                    'status' => $status,
                    'payment_status' => in_array($status, ['delivered', 'shipped', 'processing']) ? 'paid' : ($status === 'cancelled' ? 'refunded' : 'unpaid'),
                    'payment_method' => $paymentMethod,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'shipping_amount' => $shippingAmount,
                    'shipping_method_name' => $shippingAmount == 0 ? 'Free Shipping' : 'Standard Shipping',
                    'tax_amount' => 0,
                    'total' => $total,
                    'billing_first_name' => $address?->first_name ?? $customer->first_name,
                    'billing_last_name' => $address?->last_name ?? $customer->last_name,
                    'billing_address_line1' => $address?->address_line1 ?? '123 Main St',
                    'billing_city' => $address?->city ?? 'New York',
                    'billing_state' => $address?->state ?? 'NY',
                    'billing_postal_code' => $address?->postal_code ?? '10001',
                    'billing_country' => 'US',
                    'shipping_first_name' => $address?->first_name ?? $customer->first_name,
                    'shipping_last_name' => $address?->last_name ?? $customer->last_name,
                    'shipping_address_line1' => $address?->address_line1 ?? '123 Main St',
                    'shipping_city' => $address?->city ?? 'New York',
                    'shipping_state' => $address?->state ?? 'NY',
                    'shipping_postal_code' => $address?->postal_code ?? '10001',
                    'shipping_country' => 'US',
                    'created_at' => now()->subDays(rand(0, 60)),
                ]);

                foreach ($orderItems as $item) {
                    OrderItem::create(array_merge($item, ['order_id' => $order->id]));
                }

                // Status history
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'comment' => 'Order placed',
                    'is_customer_notified' => true,
                    'created_at' => $order->created_at,
                ]);

                if (in_array($status, ['processing', 'shipped', 'delivered'])) {
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => 'processing',
                        'payment_status' => 'paid',
                        'comment' => 'Payment confirmed, order being processed',
                        'is_customer_notified' => true,
                        'created_at' => $order->created_at->addHours(rand(1, 24)),
                    ]);
                }

                if (in_array($status, ['shipped', 'delivered'])) {
                    $shippedAt = $order->created_at->addDays(rand(1, 3));
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => 'shipped',
                        'payment_status' => 'paid',
                        'comment' => 'Order shipped',
                        'is_customer_notified' => true,
                        'created_at' => $shippedAt,
                    ]);

                    Shipment::create([
                        'order_id' => $order->id,
                        'tracking_number' => strtoupper(Str::random(12)),
                        'carrier' => collect(['UPS', 'FedEx', 'USPS', 'DHL'])->random(),
                        'tracking_url' => 'https://track.example.com/' . Str::random(10),
                        'shipped_at' => $shippedAt,
                        'estimated_delivery' => $shippedAt->addDays(rand(3, 7)),
                    ]);

                    $order->update(['shipped_at' => $shippedAt]);
                }

                if ($status === 'delivered') {
                    $deliveredAt = $order->created_at->addDays(rand(5, 10));
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'status' => 'delivered',
                        'payment_status' => 'paid',
                        'comment' => 'Order delivered successfully',
                        'is_customer_notified' => true,
                        'created_at' => $deliveredAt,
                    ]);
                    $order->update(['delivered_at' => $deliveredAt]);
                }

                $count++;
            }
        }

        $this->command->info("  → Created {$count} orders with items and status history");
    }

    private function createBlogContent(): void
    {
        // Post Categories
        $fashionCat = PostCategory::create(['name' => 'Fashion & Style', 'is_active' => true, 'sort_order' => 0]);
        $techCat = PostCategory::create(['name' => 'Tech & Gadgets', 'is_active' => true, 'sort_order' => 1]);
        $lifestyleCat = PostCategory::create(['name' => 'Lifestyle', 'is_active' => true, 'sort_order' => 2]);

        $admin = User::role('super_admin')->first();
        $tags = Tag::all();

        $posts = [
            [
                'title' => 'Top 10 Fashion Trends for This Season',
                'excerpt' => 'Discover the latest fashion trends that are dominating the runways and street style this season.',
                'content' => '<p>Fashion is always evolving, and this season brings exciting new trends that blend comfort with style. From oversized silhouettes to bold color palettes, there\'s something for everyone.</p><h3>1. Oversized Blazers</h3><p>The oversized blazer trend continues to dominate. Pair it with slim-fit pants for a balanced look that\'s both professional and trendy.</p><h3>2. Sustainable Fashion</h3><p>Eco-friendly fashion is no longer just a trend—it\'s a movement. More brands are committing to sustainable practices, and consumers are responding positively.</p><h3>3. Bold Colors</h3><p>This season, don\'t shy away from bold, vibrant colors. Electric blue, fiery red, and sunshine yellow are making waves in the fashion world.</p><p>Stay ahead of the curve by incorporating these trends into your wardrobe. Remember, fashion is about expressing yourself!</p>',
                'post_category_id' => $fashionCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'How to Choose the Perfect Headphones',
                'excerpt' => 'A comprehensive guide to finding headphones that match your lifestyle, budget, and audio preferences.',
                'content' => '<p>With so many headphones on the market, choosing the right pair can be overwhelming. This guide will help you navigate through the options.</p><h3>Consider Your Use Case</h3><p>Are you looking for headphones for commuting, working out, or studio use? Each use case has different requirements for comfort, sound quality, and durability.</p><h3>Wired vs. Wireless</h3><p>Wireless headphones offer convenience and freedom of movement, while wired headphones typically provide better audio quality at a lower price point.</p><h3>Noise Cancellation</h3><p>Active noise cancellation (ANC) is essential if you frequently travel or work in noisy environments. Look for headphones with adjustable ANC levels.</p><p>Take your time to research and test before making a purchase. Your ears will thank you!</p>',
                'post_category_id' => $techCat->id,
                'is_published' => true,
            ],
            [
                'title' => '5 Tips for a More Organized Home',
                'excerpt' => 'Simple yet effective strategies to declutter your living space and maintain a tidy home.',
                'content' => '<p>A clean, organized home can significantly improve your mental well-being and productivity. Here are five practical tips to get started.</p><h3>1. The One-In, One-Out Rule</h3><p>For every new item you bring home, remove one. This simple rule prevents clutter from accumulating.</p><h3>2. Designate a Place for Everything</h3><p>Every item in your home should have a designated spot. When you\'re done using something, return it to its place immediately.</p><h3>3. Declutter Regularly</h3><p>Set aside time each month to go through your belongings and donate or discard items you no longer need.</p><h3>4. Use Storage Solutions</h3><p>Invest in quality storage solutions like baskets, bins, and shelving units to keep things organized.</p><h3>5. Start Small</h3><p>Don\'t try to organize your entire home in one day. Start with one room or even one drawer at a time.</p>',
                'post_category_id' => $lifestyleCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'The Ultimate Guide to Smartwatches',
                'excerpt' => 'Everything you need to know about smartwatches, from health tracking to productivity features.',
                'content' => '<p>Smartwatches have evolved from simple notification devices to powerful health and productivity tools. Let\'s explore what modern smartwatches offer.</p><h3>Health & Fitness Tracking</h3><p>Modern smartwatches can monitor heart rate, blood oxygen levels, sleep patterns, and even detect irregular heart rhythms.</p><h3>Stay Connected</h3><p>Receive calls, messages, and app notifications right on your wrist. Some models even support cellular connectivity.</p><p>Whether you\'re a fitness enthusiast or a busy professional, there\'s a smartwatch out there for you.</p>',
                'post_category_id' => $techCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'Summer Accessories Every Woman Needs',
                'excerpt' => 'Elevate your summer looks with these must-have accessories that combine style and functionality.',
                'content' => '<p>Summer is the perfect time to experiment with accessories. From statement sunglasses to versatile bags, here are the essentials.</p><h3>Statement Sunglasses</h3><p>Oversized frames and colored lenses are trending this summer. They not only protect your eyes but also add a stylish touch to any outfit.</p><h3>Crossbody Bags</h3><p>Practical and fashionable, crossbody bags keep your hands free while carrying all your essentials.</p><h3>Layered Jewelry</h3><p>Stack delicate necklaces, rings, and bracelets for an effortlessly chic look.</p>',
                'post_category_id' => $fashionCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'Sustainable Shopping: A Beginner\'s Guide',
                'excerpt' => 'Learn how to make more environmentally conscious purchasing decisions without sacrificing style.',
                'content' => '<p>Sustainable shopping doesn\'t mean you have to sacrifice style or quality. Here\'s how to get started on your eco-friendly shopping journey.</p><h3>Buy Less, Choose Well</h3><p>Focus on quality over quantity. Invest in well-made pieces that will last for years instead of buying cheap, disposable items.</p><h3>Research Brands</h3><p>Look for brands that prioritize sustainability, fair labor practices, and transparency in their supply chain.</p><p>Every small change in our shopping habits can make a big difference for the planet.</p>',
                'post_category_id' => $lifestyleCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'Building the Perfect Home Office Setup',
                'excerpt' => 'Create a productive and comfortable workspace at home with these expert recommendations.',
                'content' => '<p>With remote work becoming the norm, having a well-designed home office is more important than ever.</p><h3>Ergonomic Chair</h3><p>Invest in a good ergonomic chair that supports your posture during long work sessions.</p><h3>Proper Lighting</h3><p>Natural light is ideal, but a good desk lamp with adjustable brightness can help reduce eye strain.</p><h3>Cable Management</h3><p>Keep your workspace clean and organized with proper cable management solutions.</p>',
                'post_category_id' => $techCat->id,
                'is_published' => true,
            ],
            [
                'title' => 'Upcoming: Fall/Winter Collection Preview',
                'excerpt' => 'Get a sneak peek at what\'s coming in our fall/winter collection.',
                'content' => '<p>We\'re excited to give you an early look at our upcoming fall/winter collection. Stay tuned for more details!</p>',
                'post_category_id' => $fashionCat->id,
                'is_published' => false, // Draft
            ],
        ];

        $customers = User::role('customer')->get();

        foreach ($posts as $i => $postData) {
            $post = Post::create(array_merge($postData, [
                'user_id' => $admin->id,
                'published_at' => $postData['is_published'] ? now()->subDays(rand(1, 60)) : null,
                'views_count' => rand(50, 500),
            ]));

            // Attach random tags
            if ($tags->count()) {
                $post->tags()->attach($tags->random(min(rand(1, 3), $tags->count()))->pluck('id'));
            }

            // Add comments to published posts
            if ($postData['is_published'] && $i < 5) {
                $commentCount = rand(1, 4);
                for ($c = 0; $c < $commentCount; $c++) {
                    $isLoggedIn = rand(0, 1);
                    $customer = $customers->random();

                    $comment = PostComment::create([
                        'post_id' => $post->id,
                        'user_id' => $isLoggedIn ? $customer->id : null,
                        'name' => $isLoggedIn ? $customer->full_name : 'Guest ' . rand(100, 999),
                        'email' => $isLoggedIn ? $customer->email : 'guest' . rand(100, 999) . '@example.com',
                        'body' => collect([
                            'Great article! Very informative and well-written.',
                            'Thanks for sharing this. I found it really helpful.',
                            'Interesting perspective. I\'d love to read more on this topic.',
                            'This is exactly what I was looking for. Bookmarked!',
                            'Well researched content. Keep up the good work!',
                        ])->random(),
                        'is_approved' => rand(0, 3) > 0, // 75% approved
                    ]);

                    // Add a reply to some comments
                    if (rand(0, 1)) {
                        PostComment::create([
                            'post_id' => $post->id,
                            'parent_id' => $comment->id,
                            'user_id' => $admin->id,
                            'name' => $admin->full_name,
                            'email' => $admin->email,
                            'body' => collect([
                                'Thank you for your kind words!',
                                'Glad you found it helpful!',
                                'Thanks for reading! Stay tuned for more.',
                            ])->random(),
                            'is_approved' => true,
                        ]);
                    }
                }
            }
        }

        $this->command->info('  → Created 8 blog posts with comments');
    }

    private function createCmsPages(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'Learn more about our company and mission.',
                'content' => '<h2>Our Story</h2><p>Founded in 2020, Porto Shop has grown from a small online boutique to a leading eCommerce destination. We believe in providing high-quality products at affordable prices.</p><h3>Our Mission</h3><p>To make premium products accessible to everyone while delivering an exceptional shopping experience.</p><h3>Our Values</h3><ul><li><strong>Quality First</strong> - We never compromise on quality</li><li><strong>Customer Satisfaction</strong> - Your happiness is our priority</li><li><strong>Sustainability</strong> - We care about our planet</li><li><strong>Innovation</strong> - We continuously improve our services</li></ul><h3>Our Team</h3><p>Our diverse team of passionate individuals works tirelessly to bring you the best shopping experience. From product curation to customer support, every team member plays a vital role.</p>',
                'template' => 'default',
                'is_active' => true,
                'sort_order' => 0,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'excerpt' => 'Read our privacy policy to understand how we handle your data.',
                'content' => '<h2>Privacy Policy</h2><p>Last updated: ' . now()->format('F d, Y') . '</p><h3>Information We Collect</h3><p>We collect information you provide directly, such as your name, email, and shipping address when you create an account or place an order.</p><h3>How We Use Your Information</h3><p>We use the information to process orders, send order updates, improve our services, and send promotional communications (with your consent).</p><h3>Data Protection</h3><p>We implement industry-standard security measures to protect your personal information from unauthorized access, disclosure, or destruction.</p><h3>Contact Us</h3><p>If you have questions about our privacy practices, please contact us.</p>',
                'template' => 'full-width',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'excerpt' => 'Our terms and conditions for using this website.',
                'content' => '<h2>Terms & Conditions</h2><p>By using our website and services, you agree to these terms and conditions.</p><h3>Use of Service</h3><p>You must be at least 18 years old to use our services. By placing an order, you confirm that you meet this requirement.</p><h3>Orders & Payments</h3><p>All orders are subject to availability. We reserve the right to cancel orders in cases of pricing errors or product unavailability.</p><h3>Returns & Refunds</h3><p>We accept returns within 30 days of delivery. Items must be in their original condition with tags attached.</p>',
                'template' => 'full-width',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'excerpt' => 'Frequently asked questions about our products and services.',
                'content' => '<h2>Frequently Asked Questions</h2><h3>How long does shipping take?</h3><p>Standard shipping takes 5-7 business days. Express shipping takes 2-3 business days.</p><h3>What is your return policy?</h3><p>We offer free returns within 30 days of purchase. Items must be unworn and in original packaging.</p><h3>Do you ship internationally?</h3><p>Yes! We ship to over 50 countries worldwide. International shipping typically takes 7-14 business days.</p><h3>How can I track my order?</h3><p>Once your order ships, you\'ll receive a tracking number via email. You can also track your order from your account dashboard.</p><h3>What payment methods do you accept?</h3><p>We accept all major credit cards, PayPal, and cash on delivery (select regions).</p>',
                'template' => 'default',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        $this->command->info('  → Created 4 CMS pages');
    }

    private function createContactMessages(): void
    {
        $messages = [
            ['name' => 'Michael Brown', 'email' => 'michael@example.com', 'subject' => 'Order Inquiry', 'message' => 'I placed an order 3 days ago but haven\'t received a confirmation email. Can you help?', 'is_read' => true],
            ['name' => 'Jennifer White', 'email' => 'jennifer@example.com', 'phone' => '+1-555-0201', 'subject' => 'Product Question', 'message' => 'Is the Wireless Speaker available in different colors? I\'m interested in a white version.', 'is_read' => true],
            ['name' => 'Robert Garcia', 'email' => 'robert@example.com', 'subject' => 'Wholesale Inquiry', 'message' => 'We\'re interested in bulk purchasing. Do you offer wholesale pricing for businesses?', 'is_read' => false],
            ['name' => 'Amanda Chen', 'email' => 'amanda@example.com', 'phone' => '+1-555-0202', 'subject' => 'Return Request', 'message' => 'I received a product in the wrong size. How can I initiate a return or exchange?', 'is_read' => false],
            ['name' => 'Kevin Park', 'email' => 'kevin@example.com', 'subject' => 'Partnership Opportunity', 'message' => 'I run a fashion blog and would love to collaborate. Please let me know if you\'re interested in a partnership.', 'is_read' => false],
        ];

        foreach ($messages as $i => $msg) {
            ContactMessage::create(array_merge($msg, [
                'created_at' => now()->subDays(rand(0, 15)),
            ]));
        }

        $this->command->info('  → Created 5 contact messages');
    }

    private function createNewsletterSubscribers(): void
    {
        $emails = [
            'subscriber1@example.com', 'subscriber2@example.com', 'fashionlover@example.com',
            'techenthusiast@example.com', 'dealhunter@example.com', 'shopper99@example.com',
            'styles2024@example.com', 'trendsetter@example.com',
        ];

        foreach ($emails as $email) {
            NewsletterSubscriber::create([
                'email' => $email,
                'name' => Str::before($email, '@'),
                'token' => Str::random(64),
                'is_active' => rand(0, 5) > 0,
                'subscribed_at' => now()->subDays(rand(1, 90)),
            ]);
        }

        $this->command->info('  → Created 8 newsletter subscribers');
    }

    private function createWishlists(): void
    {
        $customers = User::role('customer')->take(5)->get();
        $products = Product::all();
        $count = 0;

        foreach ($customers as $customer) {
            $wishProducts = $products->random(rand(2, 5));
            foreach ($wishProducts as $product) {
                Wishlist::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'added_at' => now()->subDays(rand(1, 30)),
                ]);
                $count++;
            }
        }

        $this->command->info("  → Created {$count} wishlist items");
    }
}
