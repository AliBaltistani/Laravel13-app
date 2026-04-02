# ANTIGRAVITY AGENT PROMPT
## Porto eCommerce → Laravel 13 LTS · Full Web Application
### Step-by-Step Phase Prompt

---

## GLOBAL RULES — Read Before Every Phase

- Stack is Laravel 13 LTS + Blade + Livewire. No other frontend framework.
- Do NOT redesign anything. Use the Porto HTML/CSS/JS + bootstrap/tailwind and vite (if needed as following in template) exactly as provided. Only replace hardcoded dummy content with dynamic data.
- Always prefer Laravel built-in features over third-party packages. Use Queue, Mail, Notifications, Storage, Cache, Gate, Policies, Events, Sanctum before reaching for anything external.
- Every user-facing string, image, link, toggle, and colour must be stored in the `settings` table and editable from the admin panel. Nothing is hardcoded in Blade files.
- Every public page must be SEO-ready with a dynamic title, meta description, canonical URL, Open Graph tags, and JSON-LD structured data.
- All UI blocks are Livewire components or Blade components. Business logic lives in service classes. Validation lives in Form Request classes. Authorization lives in Policies.
- Follow PSR-12, use resource controllers, named routes, and route model binding throughout.

---

---

# PHASE 1 — Project Foundation

**Goal:** Bare Laravel 13 project wired up, all database tables created, Porto assets in place, and the global Settings system working.

---

### 1-A · Project Setup
- Create a new Laravel 13 LTS project.
- Install Livewire v3 as the only required frontend package.
- Install Spatie Laravel Permission for roles and admin access control.
- Install Spatie Laravel Sluggable for automatic slug generation on products, categories, posts, and pages.
- Install Intervention Image v3 for server-side image resizing on upload.
- Install barryvdh/laravel-dompdf for PDF invoice generation.
- Install Laravel Telescope for dev-time debugging (dev dependency only).
- Configure the `.env` file with placeholders for: APP, DB, MAIL, FILESYSTEM, QUEUE, CACHE, STRIPE, and PAYPAL keys.
- Enable the `database` queue driver by default (upgradeable to Redis later).
- Enable the `file` cache driver by default (upgradeable to Redis later).
- Set the default filesystem disk to `public`.

### 1-B · Porto Asset Integration
- Copy the entire contents of the provided `porto_ecommerce/` folder into `public/themes/porto/` preserving all subdirectories: `css/`, `js/`, `fonts/`, `images/`, `vendor/`.
- Never modify any file inside `public/themes/porto/`.
- Reference all Porto assets in Blade using `asset('themes/porto/...')`.
- The `images/` subfolder inside the theme is for template placeholders only. All real content images will be served from Laravel Storage.

### 1-C · Database — Lookup Tables
- Create and run a migration for `countries` with: id, name, ISO 2-letter code, phone code, is_active.
- Create and run a migration for `currencies` with: id, name, ISO 3-letter code, symbol, exchange_rate, is_default, is_active.

### 1-D · Database — Settings Table (Core of Admin Control)
- Create and run a migration for `settings` with: id, key (unique), value (text), group, type, label, description.
- Groups must cover: general, contact, seo, social, payment, shipping, mail, appearance, promo.
- Types must cover: text, textarea, number, boolean, json, image, color, select.
- Create a `Setting` model with a static `get($key, $default)` helper and a static `set($key, $value)` helper.
- Create a `SettingService` that loads all settings into the Laravel Cache on first access and clears that cache whenever any setting is saved.
- Register the `SettingService` as a singleton in `AppServiceProvider`.
- Create a global Blade `@setting('key')` directive that calls `SettingService::get()`.

### 1-E · Database — Users & Addresses
- Extend the default Laravel `users` migration to add: first_name, last_name, phone, avatar, is_active, newsletter_subscribed, preferred_currency_id (FK to currencies), google_id, and soft deletes.
- Create and run a migration for `user_addresses` with: id, user_id (FK), label (home/work/other), first_name, last_name, company, address_line1, address_line2, city, state, postal_code, country_id (FK), phone, is_default_shipping, is_default_billing.

### 1-F · Database — Product Catalog
- Create and run migrations for: `categories` (self-referential parent_id, name, slug, description, image, banner_image, icon css class, meta fields, sort_order, is_active, is_featured).
- Create and run a migration for `brands` (name, slug, logo, description, website, is_active, is_featured, sort_order).
- Create and run a migration for `tags` (name, slug).
- Create and run a migration for `attribute_groups` representing attribute types like Size or Color (name, slug, display type: select/color/button, sort_order).
- Create and run a migration for `attributes` representing individual values like Red or XL (attribute_group_id FK, name, slug, value for hex colors, sort_order).
- Create and run a migration for `products` with all fields: id, category_id, brand_id, name, slug, sku, short_description, description, price, compare_price, cost_price, weight, type (simple/variable), is_active, is_featured, is_new, manage_stock, stock_quantity, low_stock_threshold, allow_backorder, sold_count, view_count, meta_title, meta_description, meta_keywords, soft deletes. Add database indexes on: is_active, category_id, price, slug.
- Create and run a migration for `product_images` (product_id FK, image_path, alt_text, sort_order, is_primary).
- Create and run a migration for `product_variants` (product_id FK, sku, name, price, compare_price, stock_quantity, image, is_active, sort_order).
- Create and run pivot table migrations for: `category_product`, `product_tag`, `product_attribute`, `product_variant_attribute`, `related_products`.

### 1-G · Database — Promotions
- Create and run a migration for `coupons` (code unique, name, type: percent/fixed/free_shipping, value, min_order_amount, max_discount, usage_limit, usage_limit_per_user, used_count, is_active, starts_at, expires_at).
- Create and run a migration for `flash_sales` (name, label, starts_at, expires_at, is_active).
- Create and run a migration for `flash_sale_products` (flash_sale_id FK, product_id FK, product_variant_id FK nullable, sale_price, sale_quantity, sold_count).

### 1-H · Database — Orders
- Create and run a migration for `orders` with: id, user_id (FK nullable for guest checkout), order_number (unique), status, payment_status, payment_method, payment_transaction_id, payment_details (json), currency_id, exchange_rate, subtotal, discount_amount, coupon_code, shipping_amount, shipping_method_name, tax_amount, total, customer_notes, admin_notes, ip_address, full billing address snapshot (8 columns), full shipping address snapshot (8 columns), shipped_at, delivered_at. Add indexes on user_id, status, created_at.
- Create and run a migration for `order_items` (order_id FK, product_id FK, product_variant_id FK, product_name snapshot, product_sku snapshot, variant_name snapshot, product_image snapshot, unit_price, quantity, subtotal, tax_amount, discount_amount, total).
- Create and run a migration for `order_status_histories` (order_id FK, status, payment_status, comment, is_customer_notified, created_by user FK).
- Create and run a migration for `shipments` (order_id FK, tracking_number, carrier, tracking_url, shipped_at, estimated_delivery, notes).

### 1-I · Database — Cart, Wishlist, Reviews
- Create and run a migration for `carts` (user_id FK nullable, session_id, coupon_id FK nullable). This backs the server-side cart for both guests (by session) and logged-in users (by user_id).
- Create and run a migration for `cart_items` (cart_id FK, product_id FK, product_variant_id FK nullable, quantity, unit_price).
- Create and run a migration for `wishlists` (user_id FK, product_id FK, product_variant_id FK nullable, added_at). Add a unique constraint on user_id + product_id + product_variant_id.
- Create and run a migration for `reviews` (product_id FK, user_id FK, order_id FK nullable, rating 1–5, title, body, is_approved, is_verified_purchase, helpful_count). Add index on product_id + is_approved.

### 1-J · Database — CMS, Banners & Sliders
- Create and run a migration for `sliders` (name, position, is_active).
- Create and run a migration for `slider_slides` (slider_id FK, title, subtitle, description, button_text, button_url, secondary_button_text, secondary_button_url, image_desktop, image_mobile, text_color, sort_order, is_active, starts_at, expires_at).
- Create and run a migration for `banners` (title, subtitle, button_text, button_url, image, position, sort_order, is_active, starts_at, expires_at).
- Create and run a migration for `pages` (title, slug unique, content longtext, excerpt, image, meta_title, meta_description, is_active, sort_order, template: default/full-width).
- Create and run a migration for `posts` (user_id FK as author, post_category_id FK, title, slug unique, excerpt, content longtext, image, is_published, published_at, meta_title, meta_description, views_count, soft deletes).
- Create and run a migration for `post_categories` (parent_id self FK nullable, name, slug, description, image, sort_order, is_active).
- Create and run a pivot migration for `post_tag`.
- Create and run a migration for `post_comments` (post_id FK, user_id FK nullable, parent_id self FK nullable, name, email, website, body, is_approved).

### 1-K · Database — Shipping & Contacts
- Create and run a migration for `shipping_zones` (name, countries stored as JSON array of ISO codes).
- Create and run a migration for `shipping_methods` (shipping_zone_id FK, name, type: flat/free/weight_based/price_based, price, min_order_amount, max_order_amount, min_weight, max_weight, estimated_days, is_active, sort_order).
- Create and run a migration for `contact_messages` (name, email, phone, subject, message, is_read, replied_at).
- Create and run a migration for `newsletter_subscribers` (email unique, name, token unique for unsubscribe link, is_active, subscribed_at, unsubscribed_at).

### 1-L · Eloquent Models
- Generate a model for every table created above.
- On every model: add correct `$fillable`, `$casts` (especially booleans, decimals, datetimes, and JSON columns), `$dates`, and relationships.
- On `Category`: add a recursive `children()` and `parent()` relationship. Add `scopeActive()`, `scopeFeatured()`, `scopeRoot()` (no parent). Use Spatie Sluggable.
- On `Product`: add `scopeActive()`, `scopeFeatured()`, `scopeNew()`, `scopeInStock()`, `scopeOnSale()`. Add an `effectivePrice()` method that returns the flash sale price if one is active, otherwise the regular price. Add a `primaryImage()` method returning the first is_primary image or the first image. Use Spatie Sluggable.
- On `Order`: add `generateOrderNumber()` as a static method producing ORD-YYYYMMDD-XXXXX. Add a `scopeForUser()`, `scopeByStatus()`. Automatically generate the order number in the `creating` model event.
- On `User`: implement `HasRoles` from Spatie Permission. Add `getFullNameAttribute()`. Add relationships to addresses, orders, wishlist, cart, and reviews.
- On `Setting`: implement the `get()` and `set()` static helpers wired to the `SettingService` cache.

### 1-M · Seeders
- Create a `CountrySeeder` seeding all ~250 countries with ISO codes.
- Create a `CurrencySeeder` seeding major world currencies with symbols and exchange rates.
- Create a `SettingsSeeder` that inserts all default settings keys with sensible defaults covering every group (general, contact, seo, social, payment, shipping, mail, appearance, promo).
- Create a `RolesSeeder` using Spatie Permission to seed roles: super_admin, admin, manager, editor, support.
- Create an `AdminUserSeeder` creating one super_admin user.
- Create a `DatabaseSeeder` that calls all seeders in dependency order.
- Run all seeders.

---

---

# PHASE 2 — Blade Architecture & Template Integration

**Goal:** The full Porto HTML template is converted into a reusable Blade layout and component system. Every page renders with correct Porto markup and all assets load. No dynamic data yet — this phase is purely structural.

---

### 2-A · Master Layout
- Create `resources/views/layouts/app.blade.php` as the main storefront layout.
- The layout must include, in order: the top-notice bar, header-top bar, header-middle (logo + search + icons), main navigation, the `@yield('content')` slot, and the footer — all taken verbatim from `demo1.html`.
- Create `resources/views/layouts/account.blade.php` for the customer dashboard using the two-column structure from `dashboard.html` (col-lg-3 sidebar nav + col-lg-9 `@yield('tab-content')`).
- Create `resources/views/layouts/minimal.blade.php` for auth pages (login, register, forgot password) using the full-width Porto layout without the mega-menu navigation.
- All three layouts must load Porto assets via `asset('themes/porto/...')`. Load: `bootstrap.min.css`, `style.min.css`, `demo1.min.css`, fontawesome, simple-line-icons in `<head>`. Load Porto JS files before `</body>`.

### 2-B · Header Components
- Create `resources/views/components/header/top-bar.blade.php` from the `.header-top` section of `demo1.html`. Replace hardcoded language, currency, and social link values with `@setting()` calls.
- Create `resources/views/components/header/middle.blade.php` from the `.header-middle` section containing the logo, search form, account icon, wishlist icon, and cart dropdown. Replace the logo `src` with `@setting('general.logo')`. Replace the phone number with `@setting('contact.phone')`.
- Create `resources/views/components/header/nav.blade.php` for the main navigation mega-menu. Categories are fetched from the database (active root categories with their children), cached for one hour.
- Create `resources/views/components/header/mobile-menu.blade.php` from the Porto mobile nav markup.
- Create `resources/views/components/header/top-notice.blade.php` from the `.top-notice` bar. Text, visibility, links, and background colour are all controlled by settings keys: `promo.bar_enabled`, `promo.bar_text`, `promo.bar_links`, `promo.bar_bg_color`.

### 2-C · Footer Component
- Create `resources/views/components/footer/main.blade.php` from the `<footer class="footer bg-dark">` block in `demo1.html`.
- Column 1 (About Us): logo from `@setting('general.footer_logo')`, description text from `@setting('general.footer_about')`.
- Column 2 (Contact Info): address, phone, email, working hours, and social icon links — all from settings.
- Column 3 (Customer Service): a JSON-driven list of links stored in `@setting('footer.service_links')` so the admin can add, edit, or remove links without a deploy.
- Column 4 (Newsletter): inline newsletter subscription form wired to a Livewire component. The title and description text come from settings.
- Footer bottom bar: copyright text from `@setting('general.copyright')`.

### 2-D · Shared Blade Components
- Create `resources/views/components/ui/breadcrumb.blade.php` accepting an array of `[label => url]` pairs, rendered in Porto's breadcrumb markup.
- Create `resources/views/components/ui/alert.blade.php` accepting type (success/error/warning/info) and message, rendered in Porto's `.alert` classes.
- Create `resources/views/components/ui/pagination.blade.php` publishing the Laravel pagination view and restyling it to match Porto's `.pagination` class structure.
- Create `resources/views/components/ui/rating-stars.blade.php` accepting a 1–5 rating and rendering Porto's star icon markup.
- Create `resources/views/components/ui/product-badge.blade.php` accepting a product model and rendering the correct Porto badge (NEW, SALE, HOT) based on product flags.
- Create `resources/views/components/ui/empty-state.blade.php` accepting an icon class, title, and message — used for empty cart, empty wishlist, no search results, etc.

### 2-E · Product Card Components
- Create `resources/views/components/product/card.blade.php` for grid view, taken from the Porto product grid item markup. Accept a `$product` model. Replace all hardcoded data: name → `$product->name`, price → formatted effective price, compare price → `$product->compare_price` (conditionally shown), image → `Storage::url($product->primaryImage->image_path)` with a Porto placeholder fallback.
- Create `resources/views/components/product/card-list.blade.php` for list view using Porto's horizontal product card markup.
- Both card components must include the wishlist heart button, quick-view trigger, and add-to-cart button as rendered Porto HTML — the interactivity for these will be wired in Phase 4.

### 2-F · Page Scaffolding (Static Structure Only)
- Create a Blade view file for every public page, all extending `layouts.app`:
  - `pages/home.blade.php`
  - `pages/shop/index.blade.php`
  - `pages/shop/product.blade.php`
  - `pages/cart.blade.php`
  - `pages/checkout.blade.php`
  - `pages/checkout-success.blade.php`
  - `pages/about.blade.php`
  - `pages/contact.blade.php`
  - `pages/blog/index.blade.php`
  - `pages/blog/single.blade.php`
  - `pages/page.blade.php` (generic CMS page)
- Create Blade view files for the account section, all extending `layouts.account`:
  - `pages/account/dashboard.blade.php`
  - `pages/account/orders.blade.php`
  - `pages/account/order-detail.blade.php`
  - `pages/account/wishlist.blade.php`
  - `pages/account/addresses.blade.php`
  - `pages/account/profile.blade.php`
- Create Blade view files for auth, all extending `layouts.minimal`:
  - `auth/login.blade.php`
  - `auth/register.blade.php`
  - `auth/forgot-password.blade.php`
  - `auth/reset-password.blade.php`
- At this stage all pages render the correct Porto HTML structure with asset-linked CSS and JS. No dynamic content yet.

### 2-G · SEO Component
- Create a `resources/views/components/seo/head.blade.php` component that accepts: title, description, canonical URL, og_image, and schema (JSON-LD).
- Include this component inside the `<head>` of all three layouts.
- Default values for title, description, og_image, and site name are pulled from settings so every page has a fallback even if no specific SEO fields are set.
- The component renders: `<title>`, `<meta name="description">`, `<link rel="canonical">`, `<meta property="og:*">`, `<meta name="twitter:*">`, and an inline `<script type="application/ld+json">` block.

---

---

# PHASE 3 — Authentication

**Goal:** Full auth flow working with Porto-styled pages. Customers can register, log in, reset their password, and log out.

---

### 3-A · Auth Routes & Controllers
- Define named routes for: login GET/POST, register GET/POST, logout POST, forgot-password GET/POST, reset-password GET/POST (using the token).
- Create an `AuthController` handling all these actions.
- Apply Laravel's built-in `ThrottleRequests` rate limiting: 5 failed login attempts per minute per IP, 3 registration attempts per minute per IP.
- After login, redirect customers to their account dashboard. After registration, redirect to the dashboard with a welcome flash message.
- On logout, invalidate the session, regenerate the CSRF token, and redirect to home.

### 3-B · Registration
- The register page (`auth/register.blade.php`) uses the Porto `login.html` markup for its form structure. Do not redesign it.
- Create a `RegisterRequest` Form Request validating: first_name, last_name, email (unique), password (confirmed, min 8 characters), and an optional newsletter checkbox.
- On successful registration fire a `UserRegistered` event.
- Create a `UserRegistered` listener that dispatches a `SendWelcomeEmail` job to the queue.

### 3-C · Login
- The login page (`auth/login.blade.php`) uses Porto's login form markup.
- Support login by email and password using Laravel's built-in `Auth::attempt()`.
- After login, merge the guest cart (identified by session_id) into the user's database cart and clear the guest session cart.
- Store the previous URL and redirect back after login where applicable.

### 3-D · Password Reset
- Use Laravel's built-in password reset infrastructure (Password Broker, password_reset_tokens table).
- The forgot-password page uses Porto's `forgot-password.html` markup.
- The reset-password page uses Porto's form markup.
- Send the reset link via Laravel's built-in notification system using a custom Markdown email template styled to match the Porto email aesthetic.

### 3-E · Google OAuth (Optional — enable via setting)
- Add a `google_oauth_enabled` key to settings.
- If enabled (setting = true), show the "Login with Google" button on both login and register pages.
- Install Laravel Socialite only if this setting is enabled in the environment.
- On callback: find or create the user by email. If created, fire the `UserRegistered` event.

### 3-F · Auth Middleware
- Apply Laravel's built-in `auth` middleware to all account routes.
- Apply Laravel's built-in `guest` middleware to all auth pages.
- Apply the `verified` middleware to sensitive account pages (address management, profile password change) if email verification is enabled in settings.
- Create an `IsAdmin` middleware that checks if the authenticated user has any admin role via Spatie Permission. Apply this to all admin routes.

---

---

# PHASE 4 — Shop Frontend

**Goal:** Home page, shop/category listing, and product detail pages are fully dynamic with real data, Livewire-powered filtering, and a working search.

---

### 4-A · Routes
- Define all shop routes with meaningful names: `home`, `shop.index`, `shop.category` (by slug), `shop.brand` (by slug), `shop.search`, `product.show` (by slug), `product.quick-view` (POST, returns partial view for AJAX).

### 4-B · Home Page
- Create a `HomeController` that queries all data the home page needs and passes it to `pages/home.blade.php`.
- Hero Slider: load the active slider with position = 'hero', pass its slides to the `x-hero-slider` component. Slides render Porto's existing slider markup. Slide titles, subtitles, button labels, button URLs, and images are all from the database.
- Top-notice bar: text, visibility, and the two category links come from the `promo.*` settings group.
- Featured Products section: query `Product::active()->featured()->with(['primaryImage'])->limit(setting)`. The limit (e.g. 8) is itself a setting: `home.featured_products_limit`.
- New Arrivals tab: query `Product::active()->isNew()->latest()->limit(setting)`.
- Flash Sale section: query the currently active `FlashSale` with its products. If no active flash sale exists, hide the section entirely. The countdown timer uses Alpine.js with the sale's `expires_at` timestamp.
- Category icons grid: load active, featured root categories. Icon class, image, and name from the database.
- Promo banners: load active banners for position = 'home-mid' and 'home-bottom' from the `banners` table.
- Brand carousel: load `Brand::active()->featured()->ordered()->get()`.
- Popular Tags cloud: load all tags that have at least one active product, ordered by product count.
- Each section that can be shown or hidden has a corresponding boolean settings key (e.g., `home.show_flash_sale`, `home.show_brands`).

### 4-C · Shop / Category Listing Page
- Create a `ShopController@index` for `/shop` and `ShopController@category` for `/shop/category/{slug}`.
- Create a `ShopFilter` Livewire component that manages the filter state: selected category, selected brands (checkboxes), selected attribute values (checkboxes with color swatch support), price range (min/max), in-stock only toggle, sort order, view mode (grid/list), and page number.
- `ShopFilter` reads the current URL query string on mount to restore state from bookmarked or shared URLs.
- `ShopFilter` updates the URL query string reactively using `$this->dispatch` + Alpine.js `history.pushState` so filters are bookmarkable without full page reloads.
- Create a `ProductGrid` Livewire component that listens to the filter state from `ShopFilter` and renders the appropriate product cards using `x-product.card` or `x-product.card-list` components.
- The sort bar (showing result count and sort dropdown) is part of the `ProductGrid` component, rendered using Porto's `.toolbox` markup from the category page.
- Pagination uses Laravel's built-in `paginate()` and the custom Porto-styled pagination component.
- The filter sidebar uses Porto's existing `.sidebar` and `.widget` markup from `category.html`. Do not redesign it.
- The category header (banner image + title + description) comes from the `Category` model's `banner_image` and `description` fields.

### 4-D · Product Detail Page
- Create a `ProductController@show` that loads the product by slug with eager-loaded: images, variants, attributes, attributeGroups, brand, category, activeFlashSale, and approved reviews.
- Increment `view_count` on every visit using a queued job so it doesn't slow the page load.
- Use Porto's `product.html` markup verbatim for the page structure.
- Create a `ProductGallery` Livewire component handling the image gallery: thumbnails, main image swap, and zoom. Images come from `product_images` ordered by `sort_order`.
- Create a `VariantSelector` Livewire component rendering color swatches and size buttons from Porto's variant markup. When a variant is selected: update the displayed price, compare price, stock status badge, and swap the main image to the variant's image if one exists. All of this happens without a page reload.
- Create an `AddToCart` Livewire component with quantity input (+/- buttons) and the Add to Cart button. On submit: validate quantity against stock, add to cart (see Phase 5), and emit a toast notification. Use Porto's existing button and quantity markup.
- The product tabs (Description, Reviews, Shipping Info, Return Policy) use Porto's tab markup from `product.html`. The Shipping Info and Return Policy tab content come from settings: `product.tab_shipping_content` and `product.tab_return_content`.
- The Reviews tab renders approved reviews for the product. Each review card uses Porto's review markup. Show the average rating bar and rating distribution (5-star breakdown). A logged-in customer who has purchased the product and has not yet reviewed it sees the review submission form (Livewire component).
- Related Products section: load products from `related_products` pivot. If none are set, fall back to other active products in the same category. Use the existing product carousel Porto markup.
- Recently Viewed: store viewed product IDs in the user's session (or in the database for logged-in users). Render a "Recently Viewed" section at the bottom using product cards.

### 4-E · Search
- Create a `SearchController@index` handling `/shop/search?q=` queries.
- Search runs a `LIKE` query on product `name`, `sku`, `short_description`, and `meta_keywords`. Use Laravel's built-in query builder — no external search package needed unless the project owner later enables Scout.
- The results page uses the same `ProductGrid` Livewire component as the shop page, pre-filtered by the search query.
- Create a `LiveSearch` Livewire component for the header search bar that shows a dropdown of up to 8 matching products + matching categories as the user types, debounced at 300ms. Uses Porto's `.header-search-wrapper` markup.

### 4-F · Quick View
- The quick-view modal renders Porto's `ajax/product-quick-view.html` markup inside a Porto modal.
- When a product card's quick-view button is clicked, Alpine.js fetches the route `product.quick-view` and injects the response into the modal container. The response is a Blade partial (not a full page) rendering the product image, title, price, variant selector, and add-to-cart form.

---

---

# PHASE 5 — Cart & Checkout

**Goal:** Full cart management and a complete checkout flow ending with a placed order and a payment.

---

### 5-A · Cart Service
- Create a `CartService` class (registered as a singleton) that is the single source of truth for all cart operations.
- `CartService` uses the `carts` and `cart_items` database tables for both guests (keyed by session ID) and authenticated users (keyed by user ID).
- `CartService` must implement: `getCart()`, `addItem($product, $variant, $qty)`, `updateItem($cartItemId, $qty)`, `removeItem($cartItemId)`, `applyCoupon($code)`, `removeCoupon()`, `mergeGuestCart($sessionId, $userId)` called on login, `clear()`, `getSubtotal()`, `getDiscount()`, `getShipping($methodId)`, `getTax()`, `getTotal()`.
- On `addItem`: validate stock availability. If the product is already in the cart, increment the quantity (up to stock limit).

### 5-B · Cart Page
- Create a `Cart` Livewire component that renders Porto's `cart.html` markup and manages the full cart UI reactively.
- Each cart item row shows: product image, name, variant name, unit price, quantity input with +/– controls, line subtotal, and a remove button. All using Porto's `.cart-table` markup.
- Quantity changes are debounced and call `CartService::updateItem()`.
- The order summary panel (right column) updates reactively: subtotal, coupon discount (if applied), shipping estimate (collapsible), and total.
- The coupon field calls `CartService::applyCoupon()`. On success show the discount amount; on failure show an error message inline using the Porto alert component — no page reload.
- An empty cart state uses `x-ui.empty-state` with a Porto icon and a "Continue Shopping" button back to the shop.

### 5-C · Checkout Page
- Create a `Checkout` Livewire component implementing a three-step checkout flow using Porto's `checkout.html` `.checkout-steps` markup.
- Step 1 — Address: Show a saved-address selector for logged-in users. Show the full billing/shipping form for guests and as a fallback. A "Ship to different address" toggle reveals the separate shipping address form. Validate all address fields on step advance using inline Livewire validation. Guest users must provide an email address.
- Step 2 — Shipping: Query `ShippingMethod` records matching the destination country's zone. Display each available method as a radio button with name, price, and estimated delivery days. Selecting a method updates the order total in the summary panel.
- Step 3 — Payment & Review: Show a summary of items, totals, and selected address. Render payment method radio buttons. The available payment methods are controlled by settings: `payment.stripe_enabled`, `payment.paypal_enabled`, `payment.cod_enabled`, `payment.bank_transfer_enabled`. Only enabled methods appear. Show the relevant payment form (Stripe card element, PayPal button, or a static info block) based on selection.

### 5-D · Order Placement
- Create an `OrderService` with a `placeOrder(CartService $cart, array $checkoutData)` method.
- `OrderService::placeOrder()` must: validate stock one final time, create the `Order` record (with all address snapshots and pricing), create all `OrderItem` records (with product detail snapshots), decrement product `stock_quantity`, increment product `sold_count`, increment coupon `used_count`, record the first `OrderStatusHistory` entry, clear the cart, and fire an `OrderPlaced` event.
- The `OrderPlaced` event must have listeners for: `SendOrderConfirmationEmail` (queued job), `SendNewOrderAdminNotification` (queued job), and `DecrementProductStock` (sync, already done inside the service — this listener is for any additional stock webhooks).
- If stock has become unavailable between cart add and order placement, abort with a user-friendly error message and redirect back to cart.

### 5-E · Payment — Stripe
- `PaymentService::chargeStripe($order, $paymentMethodId)` calls the Stripe API using Laravel HTTP Client (no Cashier needed for one-time charges).
- On success: update order `payment_status` to `paid`, store `payment_transaction_id`, add an order status history entry.
- On failure: update `payment_status` to `failed`, add status history entry, redirect to a failure page with a retry option.
- Register a Stripe webhook route. Handle at minimum: `payment_intent.succeeded` and `payment_intent.payment_failed` events to keep order payment status in sync.
- Stripe keys are read from config which reads from `.env`. The Stripe publishable key is also stored as a settings value so the admin can rotate it from the panel without a deploy.

### 5-F · Payment — PayPal
- `PaymentService::createPayPalOrder($order)` calls the PayPal Orders API using Laravel HTTP Client.
- Redirect the customer to the PayPal approval URL returned by the API.
- On return callback: capture the PayPal order, verify the amount matches, update order payment status.
- PayPal mode (sandbox/live) and keys are read from config/settings.

### 5-G · Payment — Cash on Delivery & Bank Transfer
- COD: order is placed with `payment_status = unpaid`, `payment_method = cod`. A settings key `payment.cod_instructions` stores the text shown to the customer on the success page.
- Bank Transfer: order is placed with `payment_status = unpaid`. Bank account details are shown from settings key `payment.bank_transfer_details`.

### 5-H · Order Success & Failure Pages
- The success page (`pages/checkout-success.blade.php`) uses Porto's order confirmation markup. Shows order number, item summary, and a link to the account orders page.
- Pass the order to the view via a signed URL or by storing the order ID in the session so only the placing user can see it.
- The failure page shows the error reason and offers retry payment and contact support buttons.

---

---

# PHASE 6 — Customer Account Dashboard

**Goal:** The full Porto customer dashboard is live and dynamic. Customers manage their entire relationship with the store from here.

---

### 6-A · Account Routes
- All account routes are prefixed with `/account`, named with `account.*`, and protected by the `auth` middleware.
- Routes: dashboard, orders list, order detail (by order_number), cancel order (POST), download invoice (GET), wishlist, addresses list, create address (POST), update address (PUT), delete address (DELETE), profile (GET/PUT), change password (PUT).

### 6-B · Account Layout & Sidebar
- `layouts/account.blade.php` renders the two-column Porto dashboard layout from `dashboard.html`: a `col-lg-3` sidebar with the Porto `.nav-dashboard` navigation list, and a `col-lg-9` main content area.
- The sidebar navigation links (Orders, Downloads, Addresses, Account Details, Wishlist, Logout) are taken verbatim from the Porto HTML.
- The active state on each nav link is set dynamically based on the current named route.

### 6-C · Dashboard Overview Tab
- Renders the Porto dashboard icons grid (Orders, Downloads, Addresses, Account Details, Wishlist) exactly as in `dashboard.html`.
- The greeting "Hello, [Name]" is dynamic. The name links to the profile edit tab.
- Below the icons, show a summary row: total orders count, total amount spent, and wishlist items count — formatted with Porto's `.feature-box` component.

### 6-D · Orders Tab
- Render a paginated table of the customer's orders using Porto's table markup.
- Columns: Order Number, Date, Status (Porto badge with colour), Total, Actions (View, Cancel if pending, Download Invoice).
- Order status badge colours are defined in a config array (e.g., pending → grey, shipped → blue, delivered → green) so the admin can adjust them in settings.
- Filter by status using a tab or dropdown (All, Processing, Shipped, Delivered, Cancelled).

### 6-E · Order Detail Tab
- Show full order breakdown: item list with images, unit prices, and quantities; billing and shipping address; payment method; order timeline (status history as a vertical steps list using Porto markup).
- If a shipment exists and has a tracking number, show a "Track Order" link opening the carrier URL.
- For each delivered order item the customer has not reviewed, show a "Write a Review" button that expands an inline Livewire `ReviewForm` component using Porto form markup.
- Show a "Download Invoice" button that calls `OrderService::generateInvoice($order)` returning a PDF generated with DomPDF, styled as a professional invoice using the store's settings (logo, address, etc.).

### 6-F · Addresses Tab
- List saved addresses as Porto cards with: label badge, name, address lines, default badges.
- A "Set as Default Shipping" and "Set as Default Billing" button per card.
- An "Add New Address" button opens an inline Livewire form using Porto's form markup inside the tab panel — no modal, no page redirect.
- Edit and delete actions on each card.

### 6-G · Wishlist Tab
- List wishlist items as Porto product cards (same `x-product.card` component).
- Each card shows: product name, current price (reflecting any active flash sale), stock status badge, "Add to Cart" button (which adds to cart and removes from wishlist in one action), and a "Remove" button.
- If the item is out of stock, the Add to Cart button is disabled with an "Out of Stock" label.
- Empty state uses `x-ui.empty-state`.

### 6-H · Account Details (Profile) Tab
- Form fields: first name, last name, display name, email, phone. Save with a `ProfileUpdateRequest` Form Request.
- Change password form: current password, new password, confirm new password. Validated with a `PasswordUpdateRequest`. On success, show a Porto success alert.
- Avatar upload: accepts image files, resizes to 200×200 using Intervention Image, stores in `storage/app/public/avatars/`, and updates the `avatar` column.
- Newsletter subscription toggle: a checkbox that updates `newsletter_subscribed` on the user.

---

---

# PHASE 7 — Admin Panel

**Goal:** A fully functional, secure admin panel where the store owner controls every aspect of the site — products, orders, customers, content, and all settings — without touching code.

---

### 7-A · Admin Infrastructure
- All admin routes are prefixed with `/admin` and named `admin.*`. Register them in a separate `routes/admin.php` file included from `RouteServiceProvider`.
- Protect all admin routes with the `IsAdmin` middleware (checks Spatie Permission role).
- Create `resources/views/layouts/admin.blade.php` using Porto's admin/dashboard HTML structure from the provided template. Porto's CSS already contains dashboard/admin styles.
- The admin layout includes: a top navbar (site name from settings, logged-in admin name, logout link), a sidebar navigation (collapsible), and the main content area.
- The admin sidebar navigation is fully dynamic: links are visible per role using Blade `@can` / `@role` directives.

### 7-B · Admin Dashboard (Overview)
- Create `AdminDashboardController@index`.
- Top stats row (4 cards): Today's Revenue, New Orders Today, New Customers Today, Low Stock Items. Use Porto's `.feature-box` or stat-card markup.
- Revenue chart (last 30 days): render as a Blade view using Chart.js loaded from CDN. Data comes from a query grouped by date.
- Orders by status: a simple bar or donut chart using Chart.js.
- Recent Orders table: latest 10 orders with order number, customer, total, status badge, and a "View" link.
- Low Stock table: products where `stock_quantity <= low_stock_threshold`, with product name, SKU, current stock, and a quick "Edit" link.

### 7-C · Product Management
- Create a `ProductController` under `App\Http\Controllers\Admin\`.
- List view: paginated table with search (by name/SKU), filter by category, brand, status, stock. Bulk actions: activate, deactivate, delete. Export to CSV using Laravel's built-in `StreamedResponse`.
- Create/Edit form uses a tabbed layout (Porto tabs markup) with sections:
  - General: name, slug (auto-generated, editable), category, brand, type, status toggles (active, featured, new).
  - Description: short description (textarea), long description (standard textarea — no rich text editor dependency needed, can be plain HTML).
  - Pricing: price, compare_price, cost_price.
  - Inventory: manage_stock toggle, stock_quantity, low_stock_threshold, allow_backorder, SKU, weight.
  - Images: multi-file upload using a standard `<input type="file" multiple>`. Images are stored via Laravel Storage. Admin can set the primary image and drag-to-reorder using Alpine.js (no extra JS library needed).
  - Variants: a dynamic variant builder. Admin selects attribute groups and values; the system generates all combinations. Admin sets price and stock per variant. Uses Alpine.js for the dynamic UI.
  - SEO: meta_title, meta_description, meta_keywords. Live preview of how the title and description will appear in Google search results (character count indicators).
  - Related Products: a live-search select field (Livewire) to find and attach related products.
- A `StoreProductRequest` and `UpdateProductRequest` Form Request handle validation.
- On save, clear any cache related to this product and its category.

### 7-D · Category Management
- List view: a nested tree table showing parent → child relationships. Add, edit, delete.
- Each category form: name, slug, parent category dropdown (only root categories shown), description, image upload, banner image upload, icon CSS class field, sort_order, is_active, is_featured, meta_title, meta_description.
- Reorder categories by sort_order using up/down buttons (Livewire component).

### 7-E · Brand Management
- List, create, edit, delete brands. Fields: name, slug, logo upload, description, website URL, is_active, is_featured, sort_order.

### 7-F · Order Management
- List view: paginated table with filters for status, payment status, date range (from/to), and search by order number or customer email/name. Bulk actions: export selected to CSV.
- Order detail view (admin side):
  - Full order summary: items, totals, addresses, payment info, IP address.
  - Status change dropdown with an optional comment field and a "Notify Customer" checkbox. Saving fires `OrderStatusChanged` event which dispatches a `SendOrderStatusEmail` queued job if notify is checked.
  - Tracking number input + carrier dropdown. Saving updates/creates the shipment record and fires `OrderShipped` event (dispatches tracking notification email).
  - Admin notes textarea (visible to admin only, never to customer).
  - Status history timeline.
  - A "Print Invoice" link generating a DomPDF invoice.
  - A "Refund" button (marks order as refunded, admin handles actual refund in payment gateway — this is a status marker, not an automatic API call, unless Stripe refund is specifically implemented as an enhancement).

### 7-G · Customer Management
- List view: paginated table with search by name/email, filter by status and registration date. Columns: name, email, total orders, total spent, joined date, status.
- Customer detail view: profile info, addresses, full order history, total spent, reviews left.
- Toggle customer `is_active` status (active/banned) with a button.
- Send email to a customer using a simple textarea form — dispatches a `SendAdminMessageToCustomer` queued job.

### 7-H · Review Management
- List view: all reviews with columns: product name, customer, rating stars, review title, status (Pending/Approved), date. Filter by product, rating, approval status.
- Bulk approve action.
- Individual approve/reject toggle.
- Delete review.

### 7-I · Coupon Management
- List, create, edit, delete coupons.
- Fields: code (with a "Generate" button using `Str::upper(Str::random(8))`), name, type (percent/fixed/free_shipping), value, min_order_amount, max_discount, usage_limit, usage_limit_per_user, active toggle, starts_at, expires_at.
- List view shows used_count vs usage_limit and an active/expired/scheduled badge.

### 7-J · Flash Sale Management
- List, create, edit, delete flash sales.
- Form: name, label, starts_at (datetime), expires_at (datetime), is_active.
- A product management sub-section: live search to find products, add with a sale_price and optional sale_quantity. Renders as a table with edit/remove per row.

### 7-K · Blog Management
- Post list: paginated, filterable by category and status. Columns: title, category, author, published date, status.
- Post editor form: title, slug, post category, excerpt, content (textarea, supports HTML), featured image upload, is_published toggle, published_at datetime, meta_title, meta_description.
- Post category CRUD: list, create, edit, delete.
- Comments list: all comments with approve/reject/delete actions. Filter by post and approval status.

### 7-L · CMS Pages Management
- List all CMS pages with title, slug, template, and status.
- Create/edit: title, slug, content (textarea), excerpt, page image, template selector, meta_title, meta_description, is_active, sort_order.
- Delete page (soft delete if referenced in footer links).

### 7-M · Banner & Slider Management
- Slider list → manage slides: create, edit, delete slides. Fields per slide: title, subtitle, description, two button configs (text + URL), desktop image upload, mobile image upload, text_color (dark/light), sort_order, is_active, starts_at, expires_at.
- Banner list: create, edit, delete. Fields: title, subtitle, button_text, button_url, image, position selector (loaded from a config array of all available positions), sort_order, is_active, starts_at, expires_at.

### 7-N · Shipping Configuration
- Shipping zones: list, create, edit, delete. Each zone has a name and a multi-select country picker.
- Shipping methods per zone: add, edit, delete. Fields: name, type, price, min/max order amount, min/max weight, estimated_days, is_active, sort_order.

### 7-O · Admin Settings Panel
- A single grouped settings page accessible at `/admin/settings`. Tabs match the `group` column of the `settings` table.
- **General tab:** site name, site tagline, logo upload, favicon upload, footer logo upload, footer about text, copyright text.
- **Contact tab:** address, phone, email, working hours, Google Maps embed URL.
- **Appearance tab:** primary colour (colour picker, used as a CSS variable injected via a `<style>` tag in the layout head), promo bar enabled toggle, promo bar text, promo bar background colour, promo bar link 1 label/URL, promo bar link 2 label/URL.
- **SEO tab:** default meta title, default meta description, Google Analytics ID (injected as a `<script>` in the layout if set), Google Search Console verification meta tag, robots.txt content (editable textarea, written to `public/robots.txt` on save).
- **Social tab:** Facebook URL, Twitter/X URL, Instagram URL, LinkedIn URL, YouTube URL. These power the footer social icons and the header social icons.
- **Payment tab:** enable/disable toggles per method (Stripe, PayPal, COD, Bank Transfer), COD instructions text, bank transfer details text, Stripe publishable key (displayed as an input so it can be updated without an env change), PayPal mode toggle (sandbox/live).
- **Shipping tab:** free shipping threshold amount, free shipping threshold label text, default weight unit.
- **Mail tab:** mail driver selector (smtp/log/mailgun), SMTP host, SMTP port, SMTP username, SMTP password, SMTP encryption, sender name, sender email, test-email button that dispatches a test mail to the admin's own email address.
- **Promo tab:** homepage section toggles (show/hide featured products, flash sale, new arrivals, brands, testimonials), section title overrides (e.g., custom text for the "Featured Products" heading), limits (e.g., how many products to show per section).
- **Footer tab:** footer service links (JSON editor — a repeater UI built with Alpine.js where admin can add/remove/reorder links with label and URL), popular tags (auto from DB or manual override).
- Saving any setting calls `SettingService::set()` which persists to the database and clears the settings cache.
- All form fields render using Porto's form markup. No custom admin UI framework — pure Blade + Alpine.js + Porto CSS.

### 7-P · Newsletter Management
- List subscribers: paginated, filterable by status. Show email, name, subscribed date.
- Bulk export to CSV.
- Individual unsubscribe/delete action.
- A basic broadcast form: subject, message body (textarea), send to all active subscribers. Dispatches individual `SendNewsletterEmail` jobs to the queue — never sends in a loop synchronously.

### 7-Q · Reports
- Sales Report page: a date range picker (from/to) filters a summary showing total orders, total revenue, average order value, number of unique customers. A grouped-by-day revenue table. Export to CSV.
- Product Report: top 20 best-selling products by sold_count for the selected period. Columns: product name, units sold, total revenue.
- Inventory Report: list of all products with current stock, status (In Stock / Low / Out of Stock), and a quick-edit stock quantity input per row (Livewire).

---

---

# PHASE 8 — CMS, Blog & Contact

**Goal:** Blog, static pages, and contact form are live and dynamic.

---

### 8-A · Blog Routes & Controller
- Routes: `blog.index` (list), `blog.show` (by slug), `blog.category` (by category slug), `blog.tag` (by tag slug). All public, no auth required.
- `BlogController@index`: paginated posts (is_published = true, published_at <= now), filterable by category and tag. Sidebar: recent posts, post categories with counts, popular tags.
- `BlogController@show`: load post by slug. Increment `views_count` via a queued job. Load approved comments with nested replies. Set SEO head data from post's meta fields.

### 8-B · Blog Views
- `pages/blog/index.blade.php`: uses Porto's `blog.html` markup. Post list uses Porto's `.entry` card markup. Sidebar uses Porto's `.widget` markup. Pagination uses the custom Porto-styled component.
- `pages/blog/single.blade.php`: uses Porto's `single.html` markup. Renders: post image, title, meta (author, date, category, views), content body, tags, author bio box, share buttons (static links to Twitter/Facebook/LinkedIn using the post URL — no JS widget), comments section, comment form.
- All hardcoded blog sample content replaced with dynamic data from the Post model.

### 8-C · Comments
- The comment form is a Livewire component. Logged-in users see their name and email pre-filled. Guests fill in name, email, and optional website.
- New comments are saved with `is_approved = false`. Admins approve them from the admin panel (Phase 7-K).
- If `settings.blog.auto_approve_comments` is true, new comments from logged-in users are auto-approved.
- Replies to existing comments are supported via `parent_id`. Nested display goes one level deep (reply to a reply is shown flat under the parent).

### 8-D · CMS Static Pages
- Route: `page.show` mapped to `/page/{slug}`.
- `PageController@show` loads the page by slug (active only) and passes it to `pages/page.blade.php`.
- The page template renders the page's content (raw HTML, stored in the database) inside Porto's standard content wrapper. The page template (default or full-width) controls whether the Porto sidebar is shown.
- Inaccessible slug returns a 404.

### 8-E · About & Contact Pages
- About page (`pages/about.blade.php`) uses Porto's `demo1-about.html` markup. All content (heading, description paragraphs, team section title, stats numbers, map embed URL) comes from settings keys in a dedicated `about.*` group.
- Contact page (`pages/contact.blade.php`) uses Porto's `demo1-contact.html` markup. Address, phone, email, working hours, and map embed URL come from settings.
- Contact form is a Livewire component. Fields: name, email, phone, subject (dropdown — options from `settings.contact.subjects` JSON), message. Validate with a `ContactFormRequest`. On submit: save to `contact_messages` table with `is_read = false`, dispatch `SendContactNotification` job to notify admin via email, and dispatch `SendContactAutoReply` job to send an auto-reply to the submitter. Auto-reply subject and body come from settings.

---

---

# PHASE 9 — SEO, Performance & Caching

**Goal:** Every public page is search-engine optimised. The app is fast with proper caching, query optimisation, and image handling.

---

### 9-A · SEO Service
- Create `App\Services\SeoService` with a fluent interface: `setTitle()`, `setDescription()`, `setImage()`, `setCanonical()`, `setJsonLd()`, `setNoIndex()`.
- `SeoService` automatically appends ` | Site Name` (from settings) to all page titles unless the caller passes `setTitle($title, $withSuffix: false)`.
- All controllers pass a populated `SeoService` instance to the view. The `x-seo.head` component renders it.
- Default fallbacks (when a controller doesn't set a specific value) come from `settings.seo.*`.

### 9-B · JSON-LD Structured Data
- Homepage: `Organization` schema with name, URL, logo, contact, and social profile links from settings.
- Product page: `Product` schema with name, description, brand, image, offers (price, availability, currency), and `AggregateRating` if the product has approved reviews.
- Category/Shop page: `BreadcrumbList` schema.
- Blog post page: `Article` schema with headline, author, publisher, datePublished, dateModified, image.
- Contact page: `LocalBusiness` schema with address, phone, email, opening hours from settings.
- All structured data is generated in dedicated model methods (e.g., `Product::jsonLd()`) and injected via the SEO head component.

### 9-C · Sitemap
- Create a `SitemapController` at `/sitemap.xml`.
- Using Laravel's built-in response and a Blade XML view (no package required), generate a sitemap containing: home, shop, all active product URLs, all active category URLs, all published blog post URLs, all active CMS page URLs, about, contact.
- Products and posts include `<lastmod>` from their `updated_at` timestamps.
- High-priority pages (home, shop) get `<priority>1.0</priority>`; products get `0.8`; categories get `0.7`; blog posts get `0.6`.
- The sitemap is cached for 24 hours and cleared whenever a product, category, post, or page is created, updated, or deleted (via model observers firing a `SitemapCacheCleared` event).

### 9-D · Robots.txt
- Serve `public/robots.txt` through a controller (not a static file) so the admin can edit its content from the Settings panel (Phase 7-O). The controller caches the content and writes it to the static file on settings save.
- Default rules: allow all crawlers for public pages; disallow: `/admin/`, `/account/`, `/cart`, `/checkout`.

### 9-E · Query Optimisation
- Review every controller and Livewire component. Ensure all relationships are eager-loaded with `with([...])` to eliminate N+1 queries. Use Laravel Debugbar (dev only) to verify zero N+1 issues on every page.
- Use `select([...])` to fetch only required columns on listing queries (never `SELECT *` on wide tables like `products`).
- Add database indexes to all foreign key columns and all columns used in `WHERE`, `ORDER BY`, and `GROUP BY` clauses.
- Wrap all `ORDER BY` + `LIMIT` shop queries in `->when()` conditions so no unnecessary subqueries run when filters are not applied.

### 9-F · Application Caching
- Cache navigation categories for 1 hour. Clear this cache when any category is saved or deleted (Category model observer).
- Cache homepage section data (featured products, flash sale, brand carousel) for 30 minutes. Clear when relevant products/flash sales change.
- Cache the Settings data indefinitely (until a setting is saved). This is already handled by `SettingService`.
- Cache the sitemap XML for 24 hours (see 9-C).
- Use `Cache::tags()` if using Redis, for grouped cache invalidation. Fall back to named cache keys for file cache.
- Cache all product listing pages at the HTTP layer using `response()->header('Cache-Control', ...)` for logged-out users. Never cache cart, checkout, or account pages.

### 9-G · Image Handling
- All images uploaded through the admin panel go through an `ImageService`.
- `ImageService::store($file, $directory)` resizes and saves three versions: thumbnail (300×300, cropped), medium (600×600, fitted), and original (max 1200px on longest side, quality 80). All stored via Laravel Storage.
- Store webp versions alongside originals where the GD/Imagick driver supports it.
- Product card components use the thumbnail version. Product gallery uses the medium version. The full lightbox uses the original.
- All `<img>` tags include `width` and `height` attributes (for Cumulative Layout Shift) and `loading="lazy"` (except above-the-fold images on the homepage).

### 9-H · Response Compression & Asset Versioning
- Enable gzip compression at the web server level (document in `README.md`).
- Use `mix()` or `vite()` for cache-busted CSS/JS. For Porto's pre-built assets under `public/themes/porto/`, use `asset()` with a global version query string set in config, refreshable from admin settings.

---

---

# PHASE 10 — Email & Notifications

**Goal:** Every important customer and admin event triggers a properly formatted, queued email. Everything is configurable from the admin settings.

---

### 10-A · Mailable Classes
- Create the following Mailable classes in `App\Mail\`, all using `queue()` via the `ShouldQueue` interface:
  - `OrderConfirmedMail` — sent to customer after order placement.
  - `OrderStatusUpdatedMail` — sent to customer when admin changes order status (only if "Notify Customer" is checked).
  - `OrderShippedMail` — sent to customer with tracking number.
  - `OrderDeliveredMail` — sent to customer when order marked delivered.
  - `OrderCancelledMail` — sent to customer on cancellation.
  - `PaymentFailedMail` — sent to customer on payment failure.
  - `NewOrderAdminMail` — sent to the admin email from settings on each new order.
  - `LowStockAlertMail` — sent to admin when product stock drops below threshold.
  - `WelcomeMail` — sent to new registrants.
  - `PasswordResetMail` — uses Laravel's built-in password reset notification restyled.
  - `NewsletterWelcomeMail` — sent to new newsletter subscribers.
  - `NewsletterBroadcastMail` — sent per-subscriber for admin newsletter broadcasts.
  - `ContactAutoReplyMail` — sent to the contact form submitter.
  - `ContactNotificationMail` — sent to admin on new contact message.

### 10-B · Email Templates
- Create a master email layout in `resources/views/emails/layouts/master.blade.php`.
- The layout uses inline CSS table-based HTML email markup styled consistently with the Porto brand colours (loaded from settings: primary colour, logo).
- Each Mailable has its own Blade view in `resources/views/emails/` extending the master layout.
- Emails include: store logo at top, greeting, dynamic body content, a CTA button (where applicable), footer with store name, address, and unsubscribe link (for marketing emails).

### 10-C · Queue Management
- All mail is dispatched to the `emails` queue.
- Admin notifications (new order, low stock) go to the `notifications` queue.
- Run queue workers with: `php artisan queue:work --queue=emails,notifications,default`.
- Failed jobs are retried 3 times with exponential backoff. After 3 failures, they land in the `failed_jobs` table.
- Create an admin panel page at `/admin/queues` showing: pending jobs count by queue, and a table of recent failed jobs with a "Retry" button per job.

### 10-D · In-App Notifications (Laravel Notifications)
- Use Laravel's built-in database notification channel (`notifiable` trait on the User model, `notifications` table).
- Notify the customer (database notification, visible in account) when: order status changes, order is shipped (with tracking), order is delivered.
- The account dashboard shows a notification bell icon (in the sidebar) with unread count. A Livewire component renders the notifications list as a Porto-styled dropdown. Clicking a notification marks it as read.

---

---

# PHASE 11 — Testing & Quality Assurance

**Goal:** The critical user journeys are covered by automated tests. The codebase passes static analysis.

---

### 11-A · Feature Tests
- Write feature tests covering the full happy path for: user registration, user login, browse shop (category filter + product view), add to cart (guest and logged-in), apply coupon, complete checkout with Stripe (mock the Stripe API with `Http::fake()`), order confirmation email is queued, customer views order in dashboard, admin logs in, admin updates order status, status email is queued.
- Write feature tests covering failure paths: login with wrong password (rate limit after 5), add out-of-stock item to cart, apply invalid/expired coupon, checkout fails Stripe (mock 402 response), attempt to view another user's order (403 expected).

### 11-B · Unit Tests
- Write unit tests for: `CartService` totals calculation (subtotal, coupon discount, shipping, tax, grand total), `CouponValidator` (valid coupon, expired coupon, usage limit exceeded, not enough cart total, already used by user), `OrderService::generateOrderNumber()` format, `SettingService::get()` cache hit and miss, `ImageService` resize dimensions, `SeoService` title suffix logic.

### 11-C · Livewire Component Tests
- Write Livewire tests for: `ShopFilter` — applying a price range updates the product grid, `Cart` — updating quantity recalculates total, `VariantSelector` — selecting a variant updates displayed price, `Checkout` — advancing steps validates each step's data.

### 11-D · Static Analysis
- Install PHPStan at level 6 (no external package conflicts). Fix all reported errors before considering the phase complete.
- Run `php artisan route:list` and verify every named route resolves to an existing controller method.
- Run `php artisan view:cache` and fix any Blade compilation errors.

---

---

# PHASE 12 — Deployment Preparation

**Goal:** The project is production-ready with documentation, environment hardening, and a clear deployment checklist.

---

### 12-A · README & Documentation
- Write a `README.md` covering: prerequisites (PHP 8.3, MySQL 8, Redis), installation steps, `.env` variable reference (every key explained), queue worker setup, scheduled task setup (`php artisan schedule:run`), running seeders, and admin login credentials.
- Document the Settings groups and keys with a table in the README so future developers know what each setting does.

### 12-B · Scheduled Tasks
- Register in `app/Console/Kernel.php` (or the new `routes/console.php` for Laravel 13):
  - Hourly: clear expired flash sales (set `is_active = false` when `expires_at` has passed).
  - Hourly: clear expired coupons (same pattern).
  - Daily at midnight: prune `telescope_entries` older than 7 days.
  - Daily at 6am: send low-stock report email to admin if any products are below threshold.
  - Weekly: prune read `contact_messages` older than 90 days.

### 12-C · Production Environment Hardening
- Set `APP_ENV=production`, `APP_DEBUG=false` in production `.env`.
- Run `php artisan config:cache`, `route:cache`, `view:cache`, `event:cache` as part of the deploy script.
- Set `SESSION_SECURE_COOKIE=true` and `SANCTUM_STATEFUL_DOMAINS` correctly for the production domain.
- Ensure `storage/` and `bootstrap/cache/` are writable by the web server user.
- Run `php artisan storage:link` on first deploy.
- Generate a new `APP_KEY` with `php artisan key:generate` if not already set.
- Set `TELESCOPE_ENABLED=false` in production or protect the Telescope route behind the `IsAdmin` middleware.

### 12-D · Final QA Checklist
- Walk every page of the Porto template (`demo1.html`, `demo1-shop.html`, `demo1-product.html`, `cart.html`, `checkout.html`, `dashboard.html`, `login.html`, `blog.html`, `single.html`, `about.html`, `contact.html`) and compare against the live Laravel equivalent. Every section must render correctly with real data.
- Verify all Porto CSS classes are intact — no accidental class name changes in Blade.
- Test on mobile viewport (Porto is responsive — maintain all responsive breakpoints).
- Verify no `dd()`, `dump()`, `var_dump()`, `console.log()`, or debug code exists in any production file.
- Run `php artisan telescope:clear` to wipe dev request logs before final delivery.
- Run all tests: `php artisan test --parallel`. All must pass.
- Check Debugbar is disabled (or removed) in production.
- Verify the `/sitemap.xml` and `/robots.txt` routes respond correctly.
- Place a test order end-to-end in Stripe test mode and confirm: order created, inventory decremented, confirmation email queued, order appears in admin and customer dashboard.

---

**END OF PROMPT**

---

*Summary: 12 Phases · ~120 step-points · Laravel 13 LTS · Blade + Livewire v3 · Porto HTML as-is · Full admin settings control · SEO-ready · Queue-backed emails · Production-hardened*
