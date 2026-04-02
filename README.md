# Porto eCommerce — Laravel 13 LTS

A full-featured eCommerce platform built with **Laravel 13 LTS**, **Livewire v4**, and the **Porto HTML Template**, following a service-oriented architecture with role-based admin panel.

---

## Requirements

- **PHP:** 8.2+
- **MySQL:** 8.0+ / MariaDB 10.6+
- **Composer:** 2.x
- **Node.js:** 18+ (for asset compilation)
- **PHP Extensions:** OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, GD/Imagick

## Installation

```bash
# 1. Clone the repository
git clone <repo-url> porto-ecommerce
cd porto-ecommerce

# 2. Install PHP dependencies
composer install

# 3. Create environment file
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
# DB_DATABASE=porto_ecommerce
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Run migrations and seeders
php artisan migrate --seed

# 6. Create storage symlink
php artisan storage:link

# 7. Start development server
php artisan serve
```

## Architecture Overview

### Service Layer
All business logic is centralized in `app/Services/`:

| Service | Purpose |
|---------|---------|
| `CartService` | Cart management (guest/user), merge, pricing |
| `OrderService` | Order placement, stock management, invoices |
| `PaymentService` | Stripe & PayPal payment processing |
| `SettingService` | Database-driven settings with caching |
| `SeoService` | Fluent SEO metadata per page |
| `ImageService` | Multi-size image upload with WebP support |

### Frontend Stack
- **Blade Templates** — Porto HTML theme integration
- **Livewire v4** — Real-time interactivity (cart, filters, checkout)
- **Porto CSS/JS** — No custom build step needed

### Events & Listeners
| Event | Listeners |
|-------|-----------|
| `UserRegistered` | `SendWelcomeEmail` |
| `OrderPlaced` | `SendOrderConfirmationEmail`, `SendNewOrderAdminNotification` |
| `OrderStatusChanged` | `SendOrderStatusEmail` (+ in-app notification) |

### Queue Configuration
Emails and notifications use two named queues:

```bash
# Run queue workers
php artisan queue:work --queue=emails,notifications
```

For production, use Supervisor with the config below:

```ini
[program:porto-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work database --queue=emails,notifications --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

### Scheduled Tasks
The scheduler runs the following tasks (add to crontab):

```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

| Task | Schedule |
|------|----------|
| Expire flash sales | Hourly |
| Expire coupons | Hourly |
| Low stock alert email | Daily at 6:00 AM |
| Prune old contact messages | Weekly (Sunday 2:00 AM) |

## Admin Panel

Access at `/admin` with admin credentials. Features include:

- **Dashboard** — Revenue, orders, customers, product analytics
- **Products** — CRUD with variants, images, attributes, bulk actions
- **Categories** — Nested categories with icons
- **Brands** — Full brand management
- **Orders** — Status management, tracking, invoice generation
- **Customers** — User management with status toggle
- **Coupons** — Percentage/fixed with conditions and limits
- **Flash Sales** — Time-limited deals with countdown
- **Blog** — Posts, categories, comments moderation
- **CMS Pages** — Dynamic page management
- **Banners & Sliders** — Homepage visual management
- **Shipping Zones** — Zone-based shipping rates
- **Reviews** — Moderation with bulk approve
- **Newsletter** — Subscriber management and broadcasts
- **Settings** — General, SEO, payment, email, social, appearance
- **Reports** — Sales, products, inventory with CSV export

## SEO Features

- Dynamic meta title/description with site name suffix
- Open Graph and Twitter Card tags
- JSON-LD structured data (Product, Article, Organization, BreadcrumbList, LocalBusiness)
- XML Sitemap at `/sitemap.xml` (cached 24h, auto-invalidated)
- Editable `robots.txt` from admin settings
- Google Analytics integration from settings
- Canonical URLs

## Payment Methods

| Method | Status |
|--------|--------|
| Cash on Delivery | Built-in |
| Bank Transfer | Built-in |
| Stripe | Via Payment Intents API (requires API keys in `.env`) |
| PayPal | Via Orders API v2 (requires API keys in `.env`) |

### Payment Configuration (.env)
```env
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
PAYPAL_CLIENT_ID=...
PAYPAL_SECRET=...
PAYPAL_MODE=sandbox
```

## Email Templates

All emails use a Porto-branded inline CSS layout. Configure SMTP in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=secret
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Testing

```bash
# Run full test suite
php artisan test

# Run with parallel execution
php artisan test --parallel

# Verify routes resolve
php artisan route:list

# Verify Blade compilation
php artisan view:cache
php artisan view:clear
```

## Production Deployment

```bash
# 1. Set APP_ENV=production, APP_DEBUG=false in .env

# 2. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 3. Run migrations
php artisan migrate --force

# 4. Set proper file permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 5. Ensure storage link exists
php artisan storage:link
```

## Directory Structure

```
app/
├── Events/           # UserRegistered, OrderPlaced, OrderStatusChanged
├── Http/
│   ├── Controllers/  # Web + Admin controllers
│   ├── Middleware/    # IsAdmin
│   └── Requests/     # Form request validation
├── Jobs/             # SendContactNotification, SendContactAutoReply
├── Listeners/        # SendWelcomeEmail, SendOrderConfirmationEmail, etc.
├── Livewire/         # CartPage, CheckoutPage, ShopFilter, AddToCart, etc.
├── Mail/             # 9 Mailable classes with Blade templates
├── Models/           # Eloquent models with relationships
├── Notifications/    # OrderStatusNotification (database channel)
├── Observers/        # Product, Category, Post, Page (cache invalidation)
├── Providers/        # AppServiceProvider, EventServiceProvider
└── Services/         # CartService, OrderService, PaymentService, etc.
```

## License

Proprietary. All rights reserved.
