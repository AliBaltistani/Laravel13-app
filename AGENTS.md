# AI Agent Instructions — Porto eCommerce Laravel 13

## Project Overview

**Porto eCommerce** is a full-featured Laravel 13 LTS ecommerce platform with Livewire v4, a service-oriented architecture, role-based admin panel, and SEO optimization. See [README.md](README.md) for installation and architecture overview.

---

## Quick Start Commands

### Development Setup
```bash
# Install and configure
composer setup    # Runs install, key:gen, migrate, seed, npm build
npm run dev       # Start Vite (CSS/JS watch)
php artisan serve # HTTP server on localhost:8000

# Run all services (convenient for development)
npm run dev       # Combines: php artisan serve + queue:listen + vite
```

### Database & Seeding
```bash
php artisan migrate --force          # Run all migrations
php artisan migrate:refresh --seed   # Reset DB + seed test data
php artisan db:seed --class=TestDataSeeder
php artisan tinker                   # Interactive shell
```

### Queue & Emails
```bash
php artisan queue:work --queue=emails,notifications       # Process async jobs
php artisan queue:listen --tries=1 --timeout=0            # Non-blocking, shows all output
php artisan cache:clear                                   # Clear Redis/file cache
```

### Testing
```bash
php artisan test                    # Run all tests
php artisan test tests/Feature/Cart # Run specific feature test
php artisan test --coverage         # With code coverage report
```

### Build & Assets
```bash
npm run dev      # Development: watch mode
npm run build    # Production: minified build with Tailwind CSS
```

---

## Architecture & Patterns

### 1. **Service Layer** — Business Logic

All business logic lives in `app/Services/`. Controllers only handle HTTP concerns (routing, validation).

| Service | Purpose | Key Methods |
|---------|---------|------------|
| `CartService` | Cart CRUD, add/remove items, apply coupons, calculate totals | `getCart()`, `addItem()`, `removeItem()`, `applyCoupon()` |
| `OrderService` | Create orders with transaction safety, generate order numbers, status updates | `createOrder()`, `updateStatus()` |
| `SettingService` | Database-driven settings with caching (singleton) | `get($key)`, `set($key, $value)`, `flush()` |
| `SeoService` | Per-page SEO metadata (title, description, JSON-LD, canonical) | `setTitle()`, `setDescription()`, `setJsonLd()` |
| `ImageService` | Upload, resize, WebP optimization | `upload()`, `resize()` |
| `PaymentService` | Stripe/PayPal payment processing | `process()`, `verify()` |
| `DynamicMailService` | SMTP config from settings; runtime email configuration | `sendMail()`, `testConnection()` |

**Example injection in controller:**
```php
class ProductController extends Controller
{
    public function show(string $slug)
    {
        $seo = app(SeoService::class);
        $seo->setTitle($product->name)->setDescription($product->description);
        // ...
    }
}
```

### 2. **Models** — Data & Relationships

37+ models in `app/Models/` with Eloquent relationships. All models use type-safe `casts()` method.

**Naming & Organization:**
- Models are singular: `Product`, `Order`, `User`
- Use `SoftDeletes` for restore capability
- Use `HasSlug` trait (Spatie Sluggable) for URL slugs
- All date columns auto-cast to `Carbon` instances

**Key Models:**
- `Product` (with variants, images, categories, brands)
- `Order` → `OrderItem` (items in an order) → `OrderStatusHistory` (status lifecycle)
- `Cart` → `CartItem` (guest/user-based carts)
- `Category`, `Brand`, `Tag`, `Attribute`, `AttributeGroup`
- `User`, `UserAddress`, `Wishlist`, `Review`
- `Page`, `BlogPost`, `Slider`, `Banner`, `HomepageSection`
- `Setting` (database-driven configuration)

**Relationships to follow:**
- `Product` → `Category` (BelongsTo) → `Brand`, `Images`, `Variants`
- `Order` → `User`, `OrderItems`, `OrderStatusHistory`
- `User` → `Orders`, `Addresses`, `Wishlist`, `Cart`, `Reviews`

### 3. **Controllers** — HTTP Handlers

Lightweight controllers in `app/Http/Controllers/`. They:
1. Validate input (use Form Request classes)
2. Call service methods
3. Return views or JSON
4. Never contain business logic

**Resource controllers follow Laravel conventions:**
- `show($slug)` — Display single resource
- `index()` — List resources with filters/pagination
- Use named routes: `product.show`, `cart.add`, `checkout.index`

**Example pattern:**
```php
class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::where('slug', $slug)->active()->firstOrFail();
        
        app(SeoService::class)->setTitle($product->name);
        
        return view('pages.product', ['product' => $product]);
    }
}
```

### 4. **Livewire Components** — Real-Time Interactivity

Real-time functionality uses **Livewire v4** (not Vue/React). Components live in `app/Livewire/`.

**Naming:** PascalCase, e.g., `AddToCart.php`, `CartPage.php`, `CheckoutPage.php`

**Common components:**
- `AddToCart` — Add product to cart (qty selector + validation)
- `CartPage` — Full cart with item management
- `CheckoutPage` — Multi-step checkout form
- `LiveSearch` — Real-time product search
- `WishlistToggle` — Add/remove from wishlist
- `ReviewSection` — Product reviews + ratings
- `NotificationBell` — Real-time notifications
- `MiniCart` — Floating cart preview

**Livewire patterns:**
```php
class AddToCart extends Component
{
    public $productId, $variantId = null, $quantity = 1;
    
    public function addToCart()
    {
        // Validate, call service, return response
        $cartService = app(CartService::class);
        $result = $cartService->addItem($this->productId, $this->variantId, $this->quantity);
        
        if (!$result['success']) {
            $this->dispatch('notify', message: $result['message'], type: 'error');
        }
    }
    
    public function render()
    {
        return view('livewire.add-to-cart');
    }
}
```

### 5. **Events & Listeners** — Decoupled Actions

Events decouple business logic from side effects (emails, notifications).

**Flow:**
1. Service dispatches event: `OrderPlaced::dispatch($order)`
2. Listener handles it: `SendOrderConfirmationEmail` sends email
3. Listeners are registered in `app/Providers/EventServiceProvider.php`

**Core events:**
- `UserRegistered` → `SendWelcomeEmail`
- `OrderPlaced` → `SendOrderConfirmationEmail`, `SendNewOrderAdminNotification`
- `OrderStatusChanged` → `SendOrderStatusEmail`

**Listener pattern:**
```php
class SendOrderConfirmationEmail implements ShouldQueue
{
    public function __construct(private Order $order) {}
    
    public function handle(): void
    {
        Mail::to($this->order->user->email)->send(
            new OrderConfirmationMail($this->order)
        );
    }
}
```

### 6. **Queue Jobs** — Async Processing

Two named queues for isolation:
- `emails` — Email sending (high priority)
- `notifications` — In-app notifications (lower priority)

**Running workers:**
```bash
php artisan queue:work --queue=emails,notifications --sleep=3 --tries=3
```

Jobs live in `app/Jobs/`. Listeners that `implement ShouldQueue` are auto-queued.

### 7. **Routes** — Organized by Feature

Routes in `routes/web.php` follow naming conventions.

**Patterns:**
- Named routes: `route('product.show', $slug)`
- Middleware protection: `Route::middleware('auth')->group(...)`
- Guest-only: `Route::middleware('guest')->group(...)`

**Admin routes** in separate `routes/admin.php` (namespaced under Admin controllers).

### 8. **Settings** — Database-Driven Configuration

All user-facing settings live in the `settings` table, not `.env`.

**Usage:**
- Blade: `@setting('general.site_name')`
- PHP: `app('settings')->get('general.site_name')`
- Set: `app('settings')->set('general.site_name', 'New Name')`

**Admin panel** provides UI to edit all settings without code changes.

**Never hardcode:**
- Site name, URL, email address, phone
- Logo URL, favicon, theme colors
- Feature toggles, payment methods
- Email signatures, footer content

### 9. **SEO** — Per-Page Metadata

Use `SeoService` for all public pages.

**Pattern:**
```php
$seo = app(SeoService::class);
$seo->setTitle('Product Name')
    ->setDescription('Product description...')
    ->setImage(asset('storage/image.jpg'))
    ->setCanonical(route('product.show', $product->slug))
    ->setJsonLd($product->jsonLd());
```

**Required on:**
- Homepage (Organization schema)
- Product pages (Product + AggregateRating schema)
- Category pages (BreadcrumbList schema)
- Blog posts (Article schema)
- Contact page (LocalBusiness schema)

---

## File Organization

```
app/
├── Services/          # Business logic (7 core services)
├── Models/            # Eloquent models (37+)
├── Http/Controllers/  # HTTP handlers
├── Livewire/          # Real-time components
├── Events/            # Domain events (3)
├── Listeners/         # Event handlers (4)
├── Jobs/              # Queue jobs (2)
├── Observers/         # Model observers (4)
├── Mail/              # Mailable classes
├── Helpers/           # Utility functions
├── Notifications/     # In-app notifications
└── Providers/         # Service providers

resources/views/
├── components/        # Blade components
├── livewire/          # Livewire component views
├── emails/            # Email templates
├── pages/             # Page templates
└── admin/             # Admin panel views

database/
├── migrations/        # Schema files (named consistently)
├── factories/         # Model factories for testing
└── seeders/           # Seed data

tests/
├── Feature/           # Feature tests (auth, cart, checkout)
└── Unit/              # Unit tests (services)

routes/
├── web.php            # Frontend routes
└── admin.php          # Admin routes
```

---

## Naming Conventions

### Routes
- Named routes: `products.show`, `cart.add`, `checkout.index`
- Use named routes, never hardcode URLs
- Admin routes prefixed: `admin.dashboard`, `admin.users.edit`

### Models
- Singular, PascalCase: `Product`, `Category`, `Order`
- Relationships method names: `camelCase`, plural when many
  - `$product->images` (HasMany)
  - `$product->category` (BelongsTo)

### Services
- Suffix with `Service`: `CartService`, `OrderService`
- Method names: verb + noun: `addItem()`, `removeItem()`, `getCart()`

### Controllers
- Suffix with `Controller`: `ProductController`
- Use resource methods: `show()`, `index()`, `create()`, `store()`
- Namespace admin under `Http\Controllers\Admin\`

### Migrations
- Timestamp prefix: `2026_01_15_000001_create_products_table`
- Table names: plural, snake_case
- Foreign keys: `{model}_id` (e.g., `user_id`, `product_id`)

### Livewire Components
- PascalCase class name: `AddToCart`, `CartPage`
- Match view folder: `resources/views/livewire/add-to-cart.blade.php`

---

## Common Patterns & Gotchas

### ✅ Patterns to Follow

1. **Always use scopes for queries:**
   ```php
   $product->active()->with('images')->get()
   ```

2. **Validation in Form Requests, not controllers:**
   ```php
   class StoreProductRequest extends FormRequest {
       public function rules() { ... }
   }
   ```

3. **Use eager loading to prevent N+1:**
   ```php
   Product::with(['images', 'category', 'variants'])->get()
   ```

4. **Dispatch events for side effects:**
   ```php
   OrderPlaced::dispatch($order); // Not Mail::send() directly
   ```

5. **Use Settings service for config:**
   ```php
   app('settings')->get('payment.stripe_key') // Not env()
   ```

### ⚠️ Common Pitfalls

1. **DO NOT hardcode strings.** Use `@setting()` in Blade templates.
2. **DO NOT put business logic in controllers.** Use services.
3. **DO NOT query models without eager loading.** Always use `->with()`.
4. **DO NOT create new cart for guests.** Only auth users can have persistent carts.
5. **DO NOT send emails synchronously.** Use queue jobs.
6. **DO NOT modify .env for runtime settings.** Use Settings service.

---

## Testing

Use Laravel's testing features (`tests/Feature/`, `tests/Unit/`).

```php
// Feature test example
test('user can add product to cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();
    
    $this->actingAs($user)
        ->post(route('cart.add'), ['product_id' => $product->id])
        ->assertSuccessful();
});

// Unit test example
test('cart service calculates total with tax', function () {
    $service = app(CartService::class);
    // ...
});
```

---

## Production Deployment

### Environment Checklist
```bash
APP_ENV=production
APP_DEBUG=false
CACHE_DRIVER=redis    # Or memcached
QUEUE_CONNECTION=database
SESSION_DRIVER=database
```

### Queue Workers (Supervisor)
Configure in `/etc/supervisor/conf.d/porto-worker.conf` (see README.md).

### Cron for Scheduler
```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Cache Strategies
- Query results: `Cache::remember()`
- Settings: Auto-cached by `SettingService`
- Product images: Use CDN for `public/storage/`

---

## Key Files to Understand First

1. [README.md](README.md) — Architecture overview, queue config
2. [app/Services/CartService.php](app/Services/CartService.php) — Core cart logic
3. [app/Services/OrderService.php](app/Services/OrderService.php) — Order placement
4. [app/Models/Product.php](app/Models/Product.php) — Main product model
5. [routes/web.php](routes/web.php) — Frontend routing
6. [app/Http/Controllers/ProductController.php](app/Http/Controllers/ProductController.php) — Product display
7. [app/Providers/EventServiceProvider.php](app/Providers/EventServiceProvider.php) — Event listeners
8. [database/seeders/TestDataSeeder.php](database/seeders/TestDataSeeder.php) — Sample data

---

## Links to Key Documentation

- [Laravel 13 Docs](https://laravel.com/docs/13.x)
- [Livewire v4 Docs](https://livewire.laravel.com/)
- [Spatie Permission Docs](https://spatie.be/docs/laravel-permission)
- [Spatie Sluggable Docs](https://spatie.be/docs/laravel-sluggable)
- [Intervention Image Docs](https://image.intervention.io/)
