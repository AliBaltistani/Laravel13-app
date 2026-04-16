# 🎨 DEVOGUE Products LLC — Complete Laravel Blade UI Redesign Prompt

---

## 🌐 Project Overview

**Website:** https://demoecom.buyonlineskd.com/
**Stack:** Laravel + Blade Templates
**Goal:** Full visual overhaul of every page — Modern, Classic, Premium, Well-Structured eCommerce Design.
**Brand:** DEVOGUE Products LLC — sells everyday smart home, pet care, hygiene & electronics products.

---

## 🎨 Design System & Visual Identity

### Color Palette (CSS Variables)
```css
:root {
  /* Primary Brand */
  --color-primary:       #0A0A0A;      /* Deep Charcoal Black */
  --color-primary-soft:  #1C1C1E;      /* Dark Surface */
  --color-accent:        #D4AF37;      /* Premium Gold */
  --color-accent-light:  #F0D060;      /* Light Gold */
  --color-accent-muted:  #B8960C;      /* Dark Gold */

  /* Neutrals */
  --color-white:         #FFFFFF;
  --color-off-white:     #F9F7F4;      /* Warm White Background */
  --color-surface:       #F2F0EB;      /* Card Backgrounds */
  --color-border:        #E5E1D8;      /* Subtle Borders */
  --color-muted:         #9A9590;      /* Muted Text */
  --color-text:          #1A1A1A;      /* Main Text */
  --color-text-light:    #5C5752;      /* Secondary Text */

  /* Status */
  --color-success:       #2D7A4F;
  --color-error:         #C0392B;
  --color-warning:       #D4AF37;
  --color-new-badge:     #0A0A0A;
  --color-sale-badge:    #C0392B;

  /* Gradients */
  --gradient-gold:       linear-gradient(135deg, #D4AF37 0%, #F0D060 50%, #B8960C 100%);
  --gradient-dark:       linear-gradient(180deg, #0A0A0A 0%, #1C1C1E 100%);
  --gradient-hero:       linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(10,10,10,0.4) 100%);

  /* Spacing */
  --space-xs:    4px;
  --space-sm:    8px;
  --space-md:    16px;
  --space-lg:    24px;
  --space-xl:    40px;
  --space-2xl:   64px;
  --space-3xl:   96px;

  /* Radius */
  --radius-sm:   4px;
  --radius-md:   8px;
  --radius-lg:   16px;
  --radius-xl:   24px;
  --radius-full: 999px;

  /* Shadows */
  --shadow-sm:   0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06);
  --shadow-md:   0 4px 16px rgba(0,0,0,0.10);
  --shadow-lg:   0 8px 32px rgba(0,0,0,0.14);
  --shadow-gold: 0 4px 20px rgba(212,175,55,0.35);

  /* Transitions */
  --transition-fast:   0.15s cubic-bezier(0.4,0,0.2,1);
  --transition-normal: 0.3s cubic-bezier(0.4,0,0.2,1);
  --transition-slow:   0.5s cubic-bezier(0.4,0,0.2,1);
}
```

### Typography
```css
/* Import in <head> */
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

/* Usage */
--font-display: 'Cormorant Garamond', Georgia, serif;   /* H1, H2, hero titles */
--font-body:    'DM Sans', system-ui, sans-serif;        /* All body text, nav, UI */
--font-mono:    'DM Mono', monospace;                    /* Prices, SKUs, codes */

/* Scale */
--text-xs:   0.75rem;    /* 12px */
--text-sm:   0.875rem;   /* 14px */
--text-base: 1rem;       /* 16px */
--text-lg:   1.125rem;   /* 18px */
--text-xl:   1.25rem;    /* 20px */
--text-2xl:  1.5rem;     /* 24px */
--text-3xl:  1.875rem;   /* 30px */
--text-4xl:  2.25rem;    /* 36px */
--text-5xl:  3rem;       /* 48px */
--text-6xl:  3.75rem;    /* 60px */
--text-7xl:  4.5rem;     /* 72px */
```

---

## 🏗️ Global Layout Components

### `layouts/app.blade.php` — Master Layout

**Structure:**
```
<html>
  <head> — meta, fonts, CSS, Alpine.js CDN, Swiper CDN </head>
  <body class="antialiased">
    @include('partials.promo-bar')        <!-- Dismissible top bar -->
    @include('partials.header')           <!-- Sticky header -->
    @include('partials.mobile-menu')      <!-- Off-canvas mobile nav -->
    @include('partials.cart-drawer')      <!-- Slide-in cart panel -->
    <main>@yield('content')</main>
    @include('partials.footer')
    @include('partials.back-to-top')
    @stack('scripts')
  </body>
</html>
```

---

### `partials/promo-bar.blade.php`

- Full-width bar: background `var(--color-accent)`, text `var(--color-primary)`, font-weight 600
- Animated text (marquee/scroll for mobile): "GET YOUR $50 COUPON NOW · FREE RETURNS · STANDARD SHIPPING ORDERS $99+"
- Right side: `×` dismiss button (Alpine.js `x-show` / `localStorage` persist)
- Height: 40px desktop, 36px mobile

---

### `partials/header.blade.php`

**Three-row header:**

**Row 1 — Meta bar** (hidden on mobile):
```
[Phone icon] +1 937 856 2072  |  [DEVOGUE LOGO CENTER]  |  [Icons: user, heart, search, cart]
```
- Background: `var(--color-white)`
- Logo: centered, max-height 48px
- Border-bottom: 1px solid `var(--color-border)`

**Row 2 — Main Navigation** (sticky on scroll, adds `box-shadow`):
```
HOME | CATEGORIES ▾ | PRODUCTS | BLOG | ABOUT US | FAQ          SPECIAL OFFER! →
```
- Background: `var(--color-primary)` when scrolled, transparent initially
- Text: `var(--color-white)`, letter-spacing 0.08em, font-size 13px, uppercase
- Hover: gold underline animation (`::after` pseudo-element, `scaleX` from 0 to 1)
- Mega dropdown for CATEGORIES: 3-column grid showing category icons + subcategories

**CATEGORIES Mega Menu:**
```
┌─────────────────────────────────────────────────────────────┐
│  🔌 Electronics         🧴 Face Towels      🏠 Home & Rug   │
│   • Dog Bark             • Disposable         • Anti-Slip   │
│   • Electric Fly          Face Towels           Rug Pads    │
│   • Bug Zapper                                              │
│                    [View All Products →]                    │
└─────────────────────────────────────────────────────────────┘
```

**Cart Icon:**
- Shows item count badge (red circle, position: absolute top-right)
- Click → opens `.cart-drawer` slide panel (Alpine.js)

**Mobile header:**
- Single row: hamburger ☰ | LOGO | [search] [heart] [cart count]
- Hamburger → off-canvas menu slides in from left

---

### `partials/mobile-menu.blade.php`

- Full-height overlay (left 80% width panel)
- Background: `var(--color-primary)`
- Logo at top
- Accordion-style nav links (categories expand with `+` / `−`)
- Bottom: social icons + phone number
- Backdrop click closes menu

---

### `partials/cart-drawer.blade.php`

- Slides in from right (400px width), overlay backdrop
- Header: "Shopping Cart (n items)" + ✕ close
- Scrollable list of cart items: thumbnail | name | qty controls | price | remove
- Sticky footer: Subtotal + "VIEW CART" + "CHECKOUT" buttons
- Empty state: shopping bag icon + "Your cart is empty" + "Continue Shopping" link

---

### `partials/footer.blade.php`

**Layout:** 4 columns + full-width bottom bar

```
┌──────────────────────────────────────────────────────────────┐
│  DEVOGUE LOGO          │ CUSTOMER SERVICE │ ABOUT US │ NEWSLETTER│
│  Premium quality        About Us           About Us   [email input][GO]│
│  products for           Contact Us         Contact Us               │
│  everyday living.       My Account         Our Story                │
│  📞 +1 937 856 2072     Orders History     Privacy Policy           │
│  ✉ contact@porto.com   Advanced Search    Terms of Service          │
│  ⏰ Mon–Fri 9AM–5PM                                               │
│  [fb] [tw] [ig]                                                    │
├──────────────────────────────────────────────────────────────┤
│  © 2025 DEVOGUE Products LLC. All Rights Reserved.     VISA PayPal STRIPE│
└──────────────────────────────────────────────────────────────┘
```

- Background: `var(--color-primary)`, text: `var(--color-off-white)`
- Headings: Cormorant Garamond, gold color, font-size 1rem uppercase letter-spacing
- Links: hover → gold color, smooth transition
- Newsletter input: dark border, gold focus ring, black GO button
- Payment icons: grayscale, hover → full color

---

## 📄 PAGE-BY-PAGE SPECIFICATIONS

---

### 1. `home.blade.php` — Route: `/`

#### Section 1: Hero Slider
- Full-width, 600px height desktop / 400px mobile
- **Swiper.js** carousel with 3 slides from `storage/sliders/`
- Each slide:
  - Background image (cover, centered)
  - Dark overlay gradient from left
  - Text positioned left-center:
    - Eyebrow: "NEW ARRIVALS" — small caps, gold, letter-spacing 0.2em
    - H1: "Discover Premium Products" — Cormorant Garamond 72px, white
    - Subtext: short description, DM Sans 18px, white/80%
    - CTA button: "SHOP NOW →" — gold background, black text, hover scale
- Navigation arrows: minimal, semi-transparent circles
- Pagination dots: gold active dot

#### Section 2: Category Showcase (4 cards)
- **Title:** "Shop by Category" — Cormorant Garamond, centered, 42px
- **Layout:** 4-column grid desktop, 2-column tablet, scroll on mobile
- Each card:
  - Square ratio image with subtle zoom on hover
  - Gradient overlay bottom → top
  - Category name in white, bold, bottom-left
  - "Explore →" link appears on hover
  - Card border-radius: 16px, overflow hidden
- Categories: Electronics, Face Towels, Rug Gripper, Specials

#### Section 3: Featured Products
- **Title:** "Featured Products" — Cormorant Garamond centered + thin decorative line below
- **Grid:** 4 columns desktop, 2 tablet, 1 mobile
- **Product Card Design:**
  ```
  ┌───────────────────────┐
  │  [NEW] [-17%]         │  ← Badges top-left
  │                       │
  │   Product Image       │  ← Aspect ratio 1:1, zoom on hover
  │                       │
  │  [♡ Wishlist]         │  ← Appears on hover top-right
  │  [🔍 Quick View]      │  ← Appears on hover center overlay
  ├───────────────────────┤
  │ Category Name         │  ← Small, gold, uppercase
  │ Product Name          │  ← DM Sans 500, 15px, 2 lines max
  │ ★★★★☆ (24)            │  ← Star rating
  │ £29.00 ~~£39.00~~     │  ← Price: DM Mono, bold / strikethrough muted
  │ [ADD TO CART]         │  ← Full-width on hover slide-up
  └───────────────────────┘
  ```
- Badge styles: NEW = black pill; Sale % = red pill; both: DM Mono 11px

#### Section 4: Promo Banner
- Full-width, dark background with gold text
- "HUGE SALE — 30% OFF Furniture & Garden"
- Animated counter or decorative numeral "30" in large Cormorant Garamond
- "SHOP NOW →" gold button
- Optional: subtle particle/noise texture overlay

#### Section 5: Triple Product Lists
- 3-column layout: "Top Rated" | "Best Selling" | "Latest Products"
- Horizontal product mini-cards inside each column:
  ```
  [img 80x80] | Category · Name · ★★★★ · Price
  ```
- Section header: gold underline, Cormorant Garamond

#### Section 6: Why Choose Us / Mission / Vision
- 3 feature cards (icon + title + text) in a light `var(--color-surface)` background
- Clean icon-forward design with subtle card shadows

#### Section 7: Brand Logos Carousel
- Smooth auto-scroll Swiper loop of brand logos
- Grayscale → color on hover
- Label: "Trusted Brands" above

#### Section 8: Instagram Follow Strip
- Title: "FOLLOW US ON INSTAGRAM @devogue"
- 6-image grid (square thumbnails) with hover overlay + Instagram icon

---

### 2. `shop/index.blade.php` — Routes: `/shop`, `/shop?brand=X`

#### Layout: Sidebar + Product Grid

**Sidebar (desktop 280px, mobile off-canvas filter panel):**
```
[FILTER ICON] Filters                    [Clear All]

CATEGORIES
  ▾ Electronics (5)
    • Dog Bark (2)
    • Electric Fly Swatter (2)
  ▾ Face Towels (1)
  ▾ Rug Gripper (1)

──────────────────

PRICE RANGE
  [$____] — [$____]
  [━━━━━●━━━━━━━━━━━●] slider
  [APPLY FILTER]

──────────────────

BRANDS
  □ DEVOGUE (4)
  □ Nike (2)
  □ Samsung (2)
  □ Apple (2)

──────────────────

POPULAR TAGS
  [Organic] [Handmade] [New Arrival]
  [Bestseller] [Premium] [Trending]
```

**Product Grid Area:**
- Breadcrumb: Home › Shop
- Top bar: "Showing 1–12 of 16 results" | Sort dropdown | Grid/List view toggle
- Product grid: 3 columns desktop, 2 tablet, 1 mobile
- Same product card design as home
- Pagination: numbered pills, active = gold fill, prev/next arrows

**IMPORTANT — Remove Security Leak:**
> ⚠️ The shop page currently displays a visible server config block (`[perfexcrm] secret = ...`). This must be removed/hidden completely. It is a critical security vulnerability.

---

### 3. `shop/category.blade.php` — Route: `/shop/category/{slug}`

- Same layout as shop/index but:
  - Hero banner at top with category name (dark overlay on category image)
  - Breadcrumb: Home › Shop › {Category}
  - Category description text block below breadcrumb

---

### 4. `products/show.blade.php` — Route: `/product/{slug}`

**Layout:** Breadcrumb → 2-column (images left, details right) → Description/Reviews tabs → Related Products

**Left Column — Image Gallery:**
```
┌─────────────────────┐
│                     │
│   Main Product      │  ← Large image, zoom on hover (CSS transform)
│   Image             │
│   [NEW] [-17%]      │
│                     │
└─────────────────────┘
  [thumb1] [thumb2]      ← Row of small thumbnails, active = gold border
```

**Right Column — Product Details:**
```
HEADPHONES  ← Breadcrumb link, gold, small caps

Black Grey Headset
━━━━━━━━━━━━━━━━━━━━━
★★★★☆  (2 Reviews) ↓ scroll to reviews

~~£59.00~~  £49.00   ← Strike / current, DM Mono, large

──────────────────────────────────────────────
High quality Black Grey Headset. Perfect for everyday use.

SKU:      PORTO-0001
Category: HEADPHONES
Brand:    SONY
Tags:     [TRENDING] [LIMITED EDITION]
Stock:    ✅ In Stock  (or 🔴 Out of Stock)

──────────────────────────────────────────────

Qty:  [–] [1] [+]   [🛒 ADD TO CART]  ← Full width below

[♡ Add to Wishlist]

──────────────────────────────────────────────

Share: [fb] [tw] [✉]
```

**Tabs — Description | Reviews (2):**
- Tab underline animation on active
- Description: rich text with feature bullets (✔ icons)
- Reviews: star breakdown + individual review cards + "Write a Review" form

**Related Products:**
- 4-column grid with same product card

---

### 5. `cart.blade.php` — Route: `/cart`

**Layout:** Wide table + Order Summary sidebar

**Cart Table:**
```
┌──────┬──────────────────┬────────┬──────┬──────────┬───┐
│ IMG  │ Product Name     │  Price │  Qty │ Subtotal │ ✕ │
├──────┼──────────────────┼────────┼──────┼──────────┼───┤
│[img] │ Black Grey Head. │ £49.00 │[–1+] │  £49.00  │ ✕ │
└──────┴──────────────────┴────────┴──────┴──────────┴───┘

[🏷 Have a coupon? APPLY]         [UPDATE CART]
[← CONTINUE SHOPPING]
```

**Order Summary Sidebar:**
```
┌──────────────────────────┐
│  ORDER SUMMARY           │
│  ────────────────────    │
│  Subtotal      £49.00    │
│  Shipping      FREE      │
│  Discount      -£0.00    │
│  ────────────────────    │
│  TOTAL         £49.00    │
│                          │
│  [PROCEED TO CHECKOUT]   │
│  ──────────────────────  │
│  🔒 Secure Checkout      │
│  VISA  PayPal  Stripe    │
└──────────────────────────┘
```

---

### 6. `checkout.blade.php` — Route: `/checkout`

**Multi-step or single-page layout:**

```
Step 1: Shipping Info    Step 2: Payment    Step 3: Review
  [●]─────────────────────[○]──────────────────[○]
```

**Form fields:**
- First Name / Last Name (50/50)
- Email Address (full width)
- Phone Number
- Address Line 1 / 2
- City / State / Postcode (33/33/33)
- Country (select)

**Order Summary (right column):**
- Product list, subtotal, shipping, total
- Payment methods: card fields or PayPal/Stripe buttons
- "Place Order" — large gold CTA

---

### 7. `auth/login.blade.php` — Route: `/login`

**Two-panel layout (desktop):**
```
┌─────────────────────────┬──────────────────────────────┐
│                         │                              │
│  Dark panel with        │  LOGIN FORM                  │
│  DEVOGUE logo +         │  ─────────────               │
│  brand tagline +        │  Email Address *             │
│  decorative gold        │  [                    ]      │
│  geometric pattern      │  Password *                  │
│                         │  [                    ]      │
│  "New here?"            │  □ Remember me  Forgot Pass? │
│  Create an account →    │                              │
│                         │  [         LOGIN           ] │
│                         │                              │
│                         │  ── or continue with ──      │
│                         │  [G Google] [fb Facebook]    │
│                         │                              │
│                         │  Don't have an account?      │
│                         │  [Create Account]            │
└─────────────────────────┴──────────────────────────────┘
```

- Mobile: stacked, form below logo
- Input focus: gold border + subtle glow
- Password: show/hide toggle (eye icon)

---

### 8. `auth/register.blade.php` — Route: `/register`

Same split-panel layout as login with fields:
- First Name / Last Name
- Email Address
- Password / Confirm Password
- "By registering you agree to our [Terms of Service]"
- [CREATE ACCOUNT] button

---

### 9. `account/dashboard.blade.php` — Route: `/account`

**Sidebar + Content panel:**

```
Left Sidebar:
  [Avatar/Initial circle]
  John Doe
  john@example.com
  ─────────────────
  📊 Dashboard
  📦 My Orders
  ♡  Wishlist
  👤 Profile Settings
  🔒 Change Password
  🚪 Logout

Right Content (Dashboard):
  Welcome back, John!
  ──────────────────────────────────────────
  [📦 3 Orders] [♡ 5 Wishlist] [📍 2 Addresses]
  ──────────────────────────────────────────
  Recent Orders (table)
  Recent Wishlist Items (grid)
```

---

### 10. `account/orders.blade.php` — Route: `/account/orders`

Order history table:
```
Order #  │  Date        │  Status    │  Total   │  Actions
──────────┼──────────────┼────────────┼──────────┼──────────
#1001    │  Apr 1, 2025 │  ✅ Delivered│  £49.00  │  [View]
#1002    │  Apr 5, 2025 │  🚚 Shipped │  £22.00  │  [View]
```
Status badge color: delivered=green, shipped=blue, processing=gold, cancelled=red

---

### 11. `wishlist.blade.php` — Route: `/wishlist`

Grid of saved products (same card layout) with:
- "Remove from Wishlist" ✕ button
- "Add to Cart" button per card
- Empty state: heart icon + "Your wishlist is empty" + "Start Shopping"

---

### 12. `about.blade.php` — Route: `/about`

**Sections:**
1. **Hero:** Full-width image with overlay + "About DEVOGUE" headline
2. **Mission & Vision:** Two-column text cards with decorative gold number "01" "02"
3. **What We Offer:** 4 icon cards grid
4. **Stats Bar:** "4 Products | 3 Categories | 100% Satisfaction | Free Returns"
5. **Team (optional):** Card grid with team photos
6. **CTA:** "Shop Our Collection →"

---

### 13. `contact.blade.php` — Route: `/contact`

**Two-column layout:**

Left: Contact Form
```
Full Name *    [                    ]
Email *        [                    ]
Subject        [                    ]
Message *      [                              ]
               [                              ]
               [SEND MESSAGE]
```

Right: Contact Info
```
📞 +1 937 856 2072
✉  contact@porto.com
⏰ Mon – Fri, 9AM – 5PM

[Map embed or decorative illustration]

[fb] [tw] [ig]
```

---

### 14. `blog/index.blade.php` — Route: `/blog`

- Hero: "Our Blog" headline with background
- Grid: 3 columns, blog card = image + category tag + date + title + excerpt + "Read More →"
- Sidebar (optional): Recent posts, Tags, Search

---

### 15. `blog/show.blade.php` — Route: `/blog/{slug}`

- Full-width hero image for post
- Article body with proper typographic styling (blockquotes, lists, headings)
- Author card at bottom
- Social share buttons
- Related posts grid (3 cards)
- Comment section (if applicable)

---

### 16. `pages/show.blade.php` — Route: `/page/{slug}`

Pages: `privacy-policy`, `terms-of-service`, `electric-fly-swatter`, `anti-slip-rug-pads`, `barking-device`, `disposable-face-towels`, `bug-zapper`

- Clean reading layout, max-width 800px centered
- TOC sidebar for long policy pages
- Product landing pages (`electric-fly-swatter` etc.): Full product marketing page layout with hero + features + buy section

---

## 🧩 Reusable Component Specs

### Product Card (`components/product-card.blade.php`)
```blade
@props(['product'])
<div class="product-card" x-data="{ hovered: false }" @mouseenter="hovered=true" @mouseleave="hovered=false">
  <!-- Badge -->
  @if($product->is_new) <span class="badge badge--new">NEW</span> @endif
  @if($product->discount_percent) <span class="badge badge--sale">-{{ $product->discount_percent }}%</span> @endif

  <!-- Image -->
  <div class="product-card__image">
    <img src="{{ $product->image }}" alt="{{ $product->name }}" loading="lazy">
    <!-- Hover Actions -->
    <div class="product-card__actions" :class="{ 'is-visible': hovered }">
      <a href="{{ route('product.quickview', $product->slug) }}" class="btn-icon">🔍</a>
      <button class="btn-icon wishlist-btn" data-id="{{ $product->id }}">♡</button>
    </div>
    <!-- Add to Cart Slide-up -->
    <button class="btn-add-to-cart" :class="{ 'is-visible': hovered }"
            hx-post="/cart/add" hx-vals='{"id": "{{ $product->id }}"}'>
      ADD TO CART
    </button>
  </div>

  <!-- Info -->
  <div class="product-card__body">
    <a href="{{ route('shop.category', $product->category->slug) }}" class="product-card__category">
      {{ $product->category->name }}
    </a>
    <h3 class="product-card__name"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>
    <div class="product-card__stars"><!-- star render --></div>
    <div class="product-card__price">
      @if($product->original_price)
        <del>£{{ $product->original_price }}</del>
      @endif
      <strong>£{{ $product->price }}</strong>
    </div>
  </div>
</div>
```

### Breadcrumb (`components/breadcrumb.blade.php`)
```blade
<nav class="breadcrumb">
  @foreach($breadcrumbs as $crumb)
    @if($loop->last)
      <span class="breadcrumb__current">{{ $crumb['label'] }}</span>
    @else
      <a href="{{ $crumb['url'] }}" class="breadcrumb__link">{{ $crumb['label'] }}</a>
      <span class="breadcrumb__sep">›</span>
    @endif
  @endforeach
</nav>
```

### Section Title (`components/section-title.blade.php`)
```blade
@props(['title', 'subtitle' => null, 'align' => 'center'])
<div class="section-title section-title--{{ $align }}">
  <h2 class="section-title__heading">{{ $title }}</h2>
  @if($subtitle) <p class="section-title__sub">{{ $subtitle }}</p> @endif
  <div class="section-title__line"></div>
</div>
```

### Badge (`components/badge.blade.php`)
```blade
@props(['type' => 'default', 'text'])
<span class="badge badge--{{ $type }}">{{ $text }}</span>
```

### Button (`components/button.blade.php`)
```blade
@props(['variant' => 'primary', 'size' => 'md', 'href' => null])
@if($href)
  <a href="{{ $href }}" class="btn btn--{{ $variant }} btn--{{ $size }}">{{ $slot }}</a>
@else
  <button {{ $attributes->merge(['class' => "btn btn--$variant btn--$size"]) }}>{{ $slot }}</button>
@endif
```

---

## 💅 Core CSS Architecture

```css
/* ===== BUTTONS ===== */
.btn {
  display: inline-flex;
  align-items: center;
  gap: var(--space-sm);
  font-family: var(--font-body);
  font-size: var(--text-sm);
  font-weight: 600;
  letter-spacing: 0.06em;
  text-transform: uppercase;
  border: none;
  cursor: pointer;
  transition: all var(--transition-normal);
  border-radius: var(--radius-sm);
  padding: 14px 28px;
}

.btn--primary {
  background: var(--color-primary);
  color: var(--color-white);
}
.btn--primary:hover {
  background: var(--color-primary-soft);
  transform: translateY(-1px);
  box-shadow: var(--shadow-md);
}

.btn--gold {
  background: var(--gradient-gold);
  color: var(--color-primary);
}
.btn--gold:hover {
  box-shadow: var(--shadow-gold);
  transform: translateY(-2px);
}

.btn--outline {
  background: transparent;
  border: 2px solid var(--color-primary);
  color: var(--color-primary);
}
.btn--outline:hover {
  background: var(--color-primary);
  color: var(--color-white);
}

/* ===== PRODUCT CARD ===== */
.product-card {
  background: var(--color-white);
  border-radius: var(--radius-lg);
  overflow: hidden;
  box-shadow: var(--shadow-sm);
  transition: box-shadow var(--transition-normal), transform var(--transition-normal);
}
.product-card:hover {
  box-shadow: var(--shadow-lg);
  transform: translateY(-4px);
}
.product-card__image {
  position: relative;
  aspect-ratio: 1 / 1;
  overflow: hidden;
  background: var(--color-surface);
}
.product-card__image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform var(--transition-slow);
}
.product-card:hover .product-card__image img {
  transform: scale(1.06);
}
.product-card__actions {
  position: absolute;
  top: var(--space-md);
  right: var(--space-md);
  display: flex;
  flex-direction: column;
  gap: var(--space-sm);
  opacity: 0;
  transform: translateX(8px);
  transition: all var(--transition-normal);
}
.product-card__actions.is-visible {
  opacity: 1;
  transform: translateX(0);
}
.btn-add-to-cart {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: var(--color-primary);
  color: var(--color-white);
  font-size: var(--text-xs);
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  padding: 12px;
  transform: translateY(100%);
  transition: transform var(--transition-normal);
  border: none;
  cursor: pointer;
}
.btn-add-to-cart.is-visible {
  transform: translateY(0);
}
.product-card__body {
  padding: var(--space-md) var(--space-lg) var(--space-lg);
}
.product-card__category {
  font-size: var(--text-xs);
  color: var(--color-accent);
  text-transform: uppercase;
  letter-spacing: 0.1em;
  font-weight: 600;
  text-decoration: none;
}
.product-card__name {
  font-family: var(--font-body);
  font-size: var(--text-base);
  font-weight: 500;
  margin: var(--space-xs) 0;
  line-height: 1.4;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.product-card__price {
  font-family: var(--font-mono);
  font-weight: 700;
  font-size: var(--text-lg);
  color: var(--color-primary);
}
.product-card__price del {
  font-size: var(--text-sm);
  color: var(--color-muted);
  font-weight: 400;
  margin-right: var(--space-xs);
}

/* ===== BADGES ===== */
.badge {
  display: inline-block;
  padding: 3px 8px;
  border-radius: var(--radius-full);
  font-size: 10px;
  font-family: var(--font-mono);
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}
.badge--new    { background: var(--color-primary); color: white; }
.badge--sale   { background: var(--color-sale-badge); color: white; }
.badge--gold   { background: var(--gradient-gold); color: var(--color-primary); }

/* ===== SECTION TITLE ===== */
.section-title--center { text-align: center; }
.section-title__heading {
  font-family: var(--font-display);
  font-size: var(--text-4xl);
  font-weight: 600;
  color: var(--color-text);
  margin-bottom: var(--space-sm);
}
.section-title__line {
  width: 60px;
  height: 2px;
  background: var(--gradient-gold);
  margin: var(--space-md) auto 0;
}

/* ===== BREADCRUMB ===== */
.breadcrumb {
  display: flex;
  align-items: center;
  gap: var(--space-sm);
  font-size: var(--text-sm);
  color: var(--color-muted);
  padding: var(--space-md) 0;
}
.breadcrumb__link { color: var(--color-muted); text-decoration: none; }
.breadcrumb__link:hover { color: var(--color-accent); }
.breadcrumb__current { color: var(--color-text); font-weight: 500; }

/* ===== FORMS ===== */
.form-group { margin-bottom: var(--space-lg); }
.form-label {
  display: block;
  font-size: var(--text-sm);
  font-weight: 600;
  color: var(--color-text);
  margin-bottom: var(--space-xs);
  letter-spacing: 0.02em;
}
.form-input {
  width: 100%;
  padding: 14px 16px;
  border: 1.5px solid var(--color-border);
  border-radius: var(--radius-md);
  font-family: var(--font-body);
  font-size: var(--text-base);
  color: var(--color-text);
  background: var(--color-white);
  transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
}
.form-input:focus {
  outline: none;
  border-color: var(--color-accent);
  box-shadow: 0 0 0 3px rgba(212,175,55,0.15);
}

/* ===== RESPONSIVE GRID ===== */
.products-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: var(--space-lg);
}
@media (max-width: 1200px) { .products-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .products-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .products-grid { grid-template-columns: 1fr; } }

/* ===== CONTAINER ===== */
.container {
  width: 100%;
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 var(--space-xl);
}
@media (max-width: 768px) { .container { padding: 0 var(--space-md); } }
```

---

## ⚙️ JavaScript Dependencies (CDN)

Add to `<head>` or before `</body>`:
```html
<!-- Alpine.js for reactivity -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Swiper for sliders/carousels -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- HTMX for cart/wishlist interactions without full page reload -->
<script src="https://unpkg.com/htmx.org@1.9.12"></script>

<!-- Toastify for toast notifications -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
```

---

## 🚨 Critical Fixes to Implement

1. **SECURITY:** Remove the exposed `[perfexcrm] secret = Theush@463732 ...` config block visible on the `/shop` page. This is a critical security leak. Ensure no raw server config is echoed to the frontend.

2. **SEO:** Add proper `<meta name="description">`, `<meta property="og:*">`, canonical URLs, and structured data (`application/ld+json` Product schema) to product pages.

3. **Performance:**
   - Add `loading="lazy"` to all images below the fold
   - Use `srcset` and WebP format where possible
   - Minify CSS/JS via Laravel Mix or Vite

4. **Accessibility:**
   - All interactive elements must have `aria-label`
   - Color contrast ratios: minimum 4.5:1 for body text
   - Keyboard navigable modals and dropdowns (`focus-trap`)
   - `alt` text on all product images

5. **Mobile:**
   - Touch-friendly tap targets (min 44×44px)
   - No horizontal scrolling
   - Swipeable product image galleries on mobile

---

## 📁 Suggested Blade File Structure

```
resources/views/
├── layouts/
│   └── app.blade.php
├── partials/
│   ├── promo-bar.blade.php
│   ├── header.blade.php
│   ├── mobile-menu.blade.php
│   ├── cart-drawer.blade.php
│   └── footer.blade.php
├── components/
│   ├── product-card.blade.php
│   ├── breadcrumb.blade.php
│   ├── section-title.blade.php
│   ├── badge.blade.php
│   └── button.blade.php
├── home.blade.php
├── shop/
│   ├── index.blade.php
│   ├── category.blade.php
├── products/
│   └── show.blade.php
├── cart.blade.php
├── checkout.blade.php
├── wishlist.blade.php
├── auth/
│   ├── login.blade.php
│   └── register.blade.php
├── account/
│   ├── dashboard.blade.php
│   └── orders.blade.php
├── about.blade.php
├── contact.blade.php
├── blog/
│   ├── index.blade.php
│   └── show.blade.php
└── pages/
    └── show.blade.php
```

---

## ✅ Design Checklist

- [ ] CSS custom properties (variables) used throughout — no hard-coded values
- [ ] Cormorant Garamond for display headings, DM Sans for UI, DM Mono for prices/codes
- [ ] Gold accent (`#D4AF37`) used sparingly but consistently for CTAs, active states, highlights
- [ ] All product cards have hover states (image zoom + action reveal + add-to-cart slide)
- [ ] Sticky header changes style on scroll (add shadow + background)
- [ ] Mobile-first responsive breakpoints at 480px, 768px, 1024px, 1280px
- [ ] Cart drawer opens without page reload (Alpine.js)
- [ ] Promo bar is dismissible and persists via localStorage
- [ ] Toast notifications for "Added to Cart", "Added to Wishlist" actions
- [ ] Loading skeleton screens for async product sections
- [ ] All form inputs have gold focus ring
- [ ] Footer is full-width dark with organized 4-column grid
- [ ] Security vulnerability on `/shop` page FIXED
- [ ] No external tracking/ads injected from site
- [ ] Payment icons in footer (VISA, PayPal, Stripe)
- [ ] Social media links working (Facebook, Twitter, Instagram)
