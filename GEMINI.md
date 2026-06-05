# KasiBuy — C2C Local Entrepreneur E-Commerce Platform

> **GEMINI.md** — Master instruction file for multi-agent orchestrated development.
> All agents MUST read and follow this document before starting any work.

---

## 1. Project Overview

**KasiBuy** is a consumer-to-consumer (C2C) e-commerce marketplace connecting local South African entrepreneurs with buyers nationwide. The platform enables anyone to list, sell, and buy products across all categories — from handmade crafts to electronics — with built-in escrow payments, shipping calculation, and trust systems.

### Key Facts

| Property | Value |
|---|---|
| **Platform Name** | KasiBuy |
| **Target Market** | South Africa (nationwide) |
| **Currency** | South African Rand (ZAR) |
| **Language** | English only |
| **Project Type** | Academic project with working demo |
| **Timeline** | 3–4 weeks |
| **Design Aesthetic** | Clean & professional corporate marketplace |

### Business Model

- **Revenue:** Tiered commission per sale (lower % for high-volume sellers)
- **Escrow:** Platform holds funds; seller is paid after buyer confirms delivery
- **Seller Verification:** Basic (email verification)
- **Buyer/Seller Interaction:** Orders only — no direct messaging

### Commission Tiers (Simulated)

| Monthly Sales Volume | Commission Rate |
|---|---|
| R0 – R5,000 | 10% |
| R5,001 – R25,000 | 7.5% |
| R25,001 – R100,000 | 5% |
| R100,000+ | 3% |

---

## 2. Tech Stack

### Core Framework
- **Next.js 15** (App Router) — Full-stack React framework
- **JavaScript (ES6+)** — Primary language
- **React 19** — Server and Client Components

### Additional Required Languages (Academic Requirement)
- **HTML** — Standalone pages, email templates, printable documents
- **PHP** — Backend microservices, webhook handlers, data export utilities
- These are **formal course requirements** and must be used in meaningful, architecturally appropriate locations

### Styling
- **Bootstrap 5** — CSS framework via npm (`bootstrap@5`)
- Bootstrap SCSS imported and customized via `src/styles/_custom.scss`
- Override Bootstrap's SCSS variables to match KasiBuy brand colours
- Use Bootstrap utility classes for layout, spacing, typography
- Use Bootstrap components (cards, modals, navbars, forms, badges, etc.)
- Custom CSS in `globals.css` for KasiBuy-specific styles not covered by Bootstrap
- Mobile-first responsive design using Bootstrap's grid and breakpoints

### Database
- **MySQL** — Relational database
- **Drizzle ORM** — Type-safe, lightweight ORM (for Next.js)
- **PDO** — For PHP database access (prepared statements only)
- **drizzle-kit** — Schema migrations

### Authentication
- **NextAuth.js v5 (Auth.js)** — Email/password + optional OAuth
- JWT sessions for stateless auth

### Payments
- **Simulated PayFast integration** — Mock sandbox flow
- Redirect-based payment flow (form POST to sandbox URL)
- ITN (Instant Transaction Notification) webhook handled by **PHP**

### Shipping
- **Simulated zone-based calculator** — PHP microservice
- Weight/distance tiers matching SA courier pricing

### Deployment
- **Vercel** (free tier) for Next.js hosting
- **Railway** (free tier) for MySQL
- **PHP server** — Local XAMPP/WAMP or PHP built-in server for PHP services

---

## 3. Architecture

```
kasibuy/
├── GEMINI.md                    # This file — master instructions
├── .env.local                   # Environment variables (NEVER commit)
├── next.config.ts               # Next.js configuration
├── drizzle.config.ts            # Drizzle ORM config
├── package.json
├── tsconfig.json
│
├── public/                      # Static assets
│   ├── images/
│   ├── icons/
│   ├── fonts/
│   └── uploads/                 # User-uploaded product images
│
│   ============================================
│   MAIN WEBSITE (Next.js — Buyers & Sellers)
│   ============================================
│
├── src/
│   ├── app/                     # Next.js App Router
│   │   ├── layout.tsx           # Root layout
│   │   ├── page.tsx             # Homepage
│   │   ├── globals.css          # Global styles + CSS variables
│   │   │
│   │   ├── (auth)/              # Auth route group
│   │   │   ├── login/
│   │   │   ├── register/
│   │   │   └── forgot-password/
│   │   │
│   │   ├── (shop)/              # Public shopping routes
│   │   │   ├── products/        # Browse & search
│   │   │   ├── product/[id]/    # Product detail
│   │   │   ├── cart/            # Shopping cart
│   │   │   ├── checkout/        # Checkout flow
│   │   │   └── categories/      # Category browsing
│   │   │
│   │   ├── (seller)/            # Seller dashboard (protected)
│   │   │   ├── dashboard/
│   │   │   ├── products/        # Manage listings
│   │   │   ├── orders/          # Manage orders
│   │   │   └── earnings/        # Revenue & payouts
│   │   │
│   │   ├── (buyer)/             # Buyer account (protected)
│   │   │   ├── orders/          # Order history & tracking
│   │   │   ├── wishlist/        # Saved items
│   │   │   └── profile/         # Account settings
│   │   │
│   │   └── api/                 # API routes
│   │       ├── auth/            # NextAuth endpoints
│   │       ├── products/        # Product CRUD
│   │       ├── orders/          # Order management
│   │       ├── payments/        # Payment webhooks
│   │       ├── shipping/        # Proxy to PHP shipping service
│   │       └── admin/           # Admin API (shared data endpoints)
│   │
│   ├── components/              # Reusable UI components
│   │   ├── ui/                  # Primitives (Button, Input, Modal, etc.)
│   │   ├── layout/              # Header, Footer, Sidebar, Navigation
│   │   ├── product/             # ProductCard, ProductGrid, ProductForm
│   │   ├── cart/                # CartItem, CartSummary
│   │   ├── order/               # OrderCard, OrderTimeline
│   │   ├── seller/              # SellerStats, EarningsChart
│   │   └── shared/              # Rating, ReviewCard, SearchBar, Filters
│   │
│   ├── lib/                     # Business logic & utilities
│   │   ├── db/                  # Database connection & queries
│   │   │   ├── index.ts         # DB connection singleton
│   │   │   ├── schema.ts        # Drizzle schema definitions
│   │   │   └── queries/         # Query functions by domain
│   │   ├── auth/                # Auth helpers & config
│   │   ├── payments/            # Payment processing logic
│   │   ├── shipping/            # Shipping client (calls PHP service)
│   │   ├── validators/          # Zod schemas for input validation
│   │   ├── utils/               # General utilities
│   │   └── constants.ts         # App-wide constants
│   │
│   ├── hooks/                   # Custom React hooks
│   ├── context/                 # React context providers
│   ├── types/                   # TypeScript type definitions
│   └── styles/                  # Custom styles & Bootstrap overrides
│       ├── _custom.scss         # Bootstrap SCSS variable overrides
│       ├── globals.css          # KasiBuy-specific custom styles
│       └── animations.css       # Reusable animations
│
│   ============================================
│   ADMIN PANEL (PHP + HTML — Platform Management)
│   Served at /admin via PHP built-in server
│   ============================================
│
├── admin-panel/                 # ===== ADMIN WEBSITE (PHP + HTML) =====
│   ├── index.php                # Admin entry point → redirects to login or dashboard
│   ├── config/
│   │   ├── database.php         # MySQL PDO connection
│   │   ├── auth.php             # Session-based admin auth helpers
│   │   └── constants.php        # Admin-specific constants
│   │
│   ├── includes/                # Reusable PHP partials
│   │   ├── header.php           # HTML header + nav (included in all pages)
│   │   ├── footer.php           # HTML footer (included in all pages)
│   │   ├── sidebar.php          # Admin sidebar navigation
│   │   └── helpers.php          # Utility functions (format currency, dates)
│   │
│   ├── pages/                   # Admin page controllers (PHP)
│   │   ├── login.php            # Admin login form + auth logic
│   │   ├── dashboard.php        # Platform analytics overview
│   │   ├── users.php            # User management (list, view, suspend)
│   │   ├── user-detail.php      # Individual user profile + actions
│   │   ├── products.php         # Product moderation (list, approve, flag)
│   │   ├── product-detail.php   # Individual product review + actions
│   │   ├── orders.php           # All platform orders
│   │   ├── order-detail.php     # Individual order detail + status update
│   │   ├── sellers.php          # Seller management + commission tiers
│   │   ├── settings.php         # Platform settings (commissions, zones)
│   │   └── reports.php          # Revenue reports + CSV/PDF export
│   │
│   ├── actions/                 # Form action handlers (POST endpoints)
│   │   ├── auth-action.php      # Login/logout processing
│   │   ├── user-action.php      # Suspend/activate/role change
│   │   ├── product-action.php   # Approve/flag/remove products
│   │   ├── order-action.php     # Update order status
│   │   └── settings-action.php  # Save platform settings
│   │
│   ├── assets/                  # Admin-specific static assets
│   │   ├── css/
│   │   │   └── admin.css        # Admin-specific custom styles
│   │   ├── js/
│   │   │   └── admin.js         # Admin interactivity (charts, tables)
│   │   └── img/
│   │       └── logo.png         # Admin panel logo
│   │
│   └── .htaccess                # URL rewriting for clean URLs
│
├── php-services/                # ===== PHP BACKEND SERVICES =====
│   ├── config/
│   │   ├── database.php         # MySQL PDO connection (shared)
│   │   └── constants.php        # Shared constants & config
│   │
│   ├── payments/
│   │   ├── payfast-itn.php      # PayFast ITN webhook handler
│   │   ├── verify-payment.php   # Payment signature verification
│   │   └── escrow-release.php   # Escrow release processor
│   │
│   ├── shipping/
│   │   └── calculate-rate.php   # Shipping rate calculator API
│   │
│   ├── exports/
│   │   ├── export-orders.php    # CSV export for seller orders
│   │   ├── export-earnings.php  # CSV export for seller earnings
│   │   └── generate-invoice.php # PDF/HTML invoice generator
│   │
│   ├── uploads/
│   │   └── process-image.php    # Image upload, resize, thumbnail
│   │
│   ├── contact/
│   │   └── send-message.php     # Contact form processor
│   │
│   └── .htaccess                # Apache URL rewriting (if needed)
│
├── html-pages/                  # ===== STANDALONE HTML PAGES =====
│   ├── payment-gateway/
│   │   ├── index.html           # Simulated PayFast payment page
│   │   ├── success.html         # Payment success confirmation
│   │   └── styles.css           # Payment page styling
│   │
│   ├── email-templates/
│   │   ├── welcome.html         # Welcome email after registration
│   │   ├── order-confirmation.html  # Order placed confirmation
│   │   ├── shipping-notification.html # Order shipped notification
│   │   └── styles-inline.css    # Inlined CSS for email clients
│   │
│   ├── static/
│   │   ├── about.html           # About KasiBuy page
│   │   ├── terms.html           # Terms & Conditions
│   │   ├── privacy.html         # Privacy Policy
│   │   ├── faq.html             # Frequently Asked Questions
│   │   └── contact.html         # Contact Us (form posts to PHP)
│   │
│   ├── print/
│   │   ├── invoice.html         # Printable invoice template
│   │   └── receipt.html         # Printable payment receipt
│   │
│   └── shared/
│       ├── header.html          # Reusable HTML header partial
│       ├── footer.html          # Reusable HTML footer partial
│       └── styles.css           # Shared CSS for all HTML pages
│
├── db/                          # Database files
│   ├── migrations/              # Drizzle migration files
│   └── seed.ts                  # Seed data for demo
│
├── agents/                      # Agent skill files
│   ├── ui-ux-design/SKILL.md
│   ├── frontend/SKILL.md
│   ├── backend/SKILL.md
│   ├── database/SKILL.md
│   ├── auth-payments/SKILL.md
│   ├── testing/SKILL.md
│   └── devops/SKILL.md
│
└── tests/                       # Test files
    ├── unit/
    ├── integration/
    └── e2e/
```

### Two-Website Summary

| Property | Main Website | Admin Panel |
|---|---|---|
| **Technology** | Next.js 15 + JavaScript + React | PHP + HTML + JavaScript |
| **Styling** | Bootstrap 5 (SCSS) | Bootstrap 5 (CDN) |
| **Auth** | NextAuth.js v5 (JWT) | PHP sessions |
| **Database** | Drizzle ORM | PDO (prepared statements) |
| **Users** | Buyers & Sellers | Admin staff only |
| **Port (dev)** | `localhost:3000` | `localhost:8080/admin` |
| **Deployment** | Vercel | PHP hosting (or same server) |

---

## 4. Design System (Bootstrap 5 + Custom Overrides)

> Bootstrap 5 is the base styling framework. Customise it by overriding SCSS variables in `src/styles/_custom.scss` **before** importing Bootstrap. Use `globals.css` for KasiBuy-specific styles that Bootstrap doesn't cover.

### Bootstrap SCSS Variable Overrides (`src/styles/_custom.scss`)

```scss
// ===== KasiBuy Brand Overrides =====
// This file is imported BEFORE bootstrap's SCSS so these variables take effect.

// --- Colors ---
// Primary — Professional blue with SA warmth
$primary:       #3b82f6;
$secondary:     #eab308;   // Warm gold accent (SA sunshine)
$success:       #22c55e;
$warning:       #f59e0b;
$danger:        #ef4444;
$info:          #60a5fa;
$light:         #fafafa;
$dark:          #171717;

// --- Typography ---
$font-family-sans-serif: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
$font-family-monospace:  'JetBrains Mono', 'Fira Code', monospace;

$font-size-base: 1rem;     // 16px
$h1-font-size:   2.25rem;  // 36px
$h2-font-size:   1.875rem; // 30px
$h3-font-size:   1.5rem;   // 24px
$h4-font-size:   1.25rem;  // 20px
$h5-font-size:   1.125rem; // 18px

$font-weight-normal:  400;
$font-weight-medium:  500;  // custom — not Bootstrap default
$font-weight-semibold: 600;
$font-weight-bold:    700;

// --- Spacing (Bootstrap uses a 0-5 scale by default, extend it) ---
$spacer: 1rem;
$spacers: (
  0: 0,
  1: $spacer * 0.25,   // 4px
  2: $spacer * 0.5,    // 8px
  3: $spacer * 0.75,   // 12px
  4: $spacer,          // 16px
  5: $spacer * 1.5,    // 24px
  6: $spacer * 2,      // 32px   ← extended
  7: $spacer * 2.5,    // 40px   ← extended
  8: $spacer * 3,      // 48px   ← extended
);

// --- Border Radius ---
$border-radius:    0.5rem;
$border-radius-sm: 0.375rem;
$border-radius-lg: 0.75rem;
$border-radius-xl: 1rem;
$border-radius-pill: 9999px;

// --- Shadows ---
$box-shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
$box-shadow:    0 4px 6px -1px rgba(0, 0, 0, 0.1);
$box-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);

// --- Body ---
$body-bg:    #ffffff;
$body-color: #171717;

// --- Cards ---
$card-border-color: #e5e5e5;
$card-cap-bg:       #fafafa;

// Import Bootstrap AFTER overrides
@import "bootstrap/scss/bootstrap";
```

### Additional CSS Custom Properties (`globals.css`)

```css
/* KasiBuy-specific tokens beyond Bootstrap */
:root {
  --kasibuy-text-secondary: #525252;
  --kasibuy-text-muted: #a3a3a3;
  --kasibuy-bg-secondary: #fafafa;
  --kasibuy-bg-tertiary: #f5f5f5;
  --kasibuy-border: #e5e5e5;
}
```

### Breakpoints (Bootstrap 5 Defaults — Mobile-First)

```
/* xs */  < 576px              (default, no media query)
/* sm */  @media (min-width: 576px)   { }
/* md */  @media (min-width: 768px)   { }
/* lg */  @media (min-width: 992px)   { }
/* xl */  @media (min-width: 1200px)  { }
/* xxl */ @media (min-width: 1400px)  { }
```

### Bootstrap Component Mapping for KasiBuy

| KasiBuy Element | Bootstrap Component |
|---|---|
| Product cards | `card`, `card-img-top`, `card-body` |
| Navigation | `navbar`, `navbar-expand-lg`, `offcanvas` |
| Product grid | `row`, `col-*`, `g-4` |
| Buttons | `btn btn-primary`, `btn-outline-*` |
| Forms (login, checkout) | `form-control`, `form-label`, `form-select` |
| Modals (quick view, confirm) | `modal`, `modal-dialog` |
| Badges (stock, status) | `badge bg-success`, `badge bg-warning` |
| Alerts (notifications) | `alert alert-info`, `alert-dismissible` |
| Pagination | `pagination` |
| Breadcrumbs | `breadcrumb` |
| Tabs (product detail) | `nav-tabs`, `tab-content` |
| Toasts (add to cart) | `toast` |
| Offcanvas (mobile menu, filters) | `offcanvas` |
| Tables (admin, orders) | `table table-striped table-hover` |
| Accordion (FAQ) | `accordion` |
| Spinner (loading) | `spinner-border` |

---

## 5. Coding Standards

### JavaScript (ES6+)
- Use modern ES6+ syntax (arrow functions, destructuring)
- Use JSDoc comments where complex object shapes need explanation

### File Naming
- **Files:** `kebab-case.js` / `kebab-case.jsx`
- **Components:** `PascalCase` (e.g., `ProductCard`)
- **Custom CSS:** `globals.css` or `component-name.css` for component-specific overrides
- **Hooks:** `use-hook-name.ts`
- **Constants:** `SCREAMING_SNAKE_CASE`

### Component Patterns
```tsx
// Prefer Server Components (default in App Router)
// Only use 'use client' when you need:
// - useState, useEffect, useRef
// - Event handlers (onClick, onChange)
// - Browser-only APIs

// Component template:
export function ComponentName({ prop1, prop2 }) {
  return (
    <div className="card shadow-sm">
      <div className="card-body">
        <h5 className="card-title">{prop1}</h5>
        <p className="card-text text-muted">{prop2}</p>
      </div>
    </div>
  );
}
```

### Bootstrap Usage Patterns
```tsx
{/* Use Bootstrap grid for layouts */}
<div className="container">
  <div className="row g-4">
    <div className="col-12 col-md-6 col-lg-4">
      <ProductCard />
    </div>
  </div>
</div>

{/* Use Bootstrap components */}
<button className="btn btn-primary">Add to Cart</button>
<span className="badge bg-success">In Stock</span>
<div className="alert alert-info">Order confirmed!</div>

{/* Combine Bootstrap utilities with custom classes */}
<div className="d-flex align-items-center gap-3 kasibuy-hero">
  {/* content */}
</div>
```

### Custom CSS Pattern (for KasiBuy-specific styles)
```css
/* globals.css — Only for styles Bootstrap doesn't cover */
.kasibuy-hero {
  background: linear-gradient(135deg, var(--bs-primary), var(--bs-primary-dark));
  min-height: 60vh;
}

.kasibuy-product-grid {
  /* Custom grid tweaks beyond Bootstrap */
}

/* Override Bootstrap variables via SCSS */
/* src/styles/_custom.scss */
$primary: #3b82f6;
$secondary: #eab308;
$font-family-sans-serif: 'Inter', system-ui, sans-serif;
```

### Database Access
- **ALL** database queries go in `src/lib/db/queries/`
- **NEVER** write raw SQL in components or API routes
- Use Drizzle ORM query builder exclusively
- Validate all inputs with Zod before DB operations

### Security Rules
- **NEVER** hardcode credentials — use `.env.local`
- **ALWAYS** validate on the server side — never trust the client
- **ALWAYS** use parameterized queries (Drizzle handles this)
- **NEVER** expose internal IDs in URLs without authorization checks
- Sanitize all user-generated content before rendering
- Use CSRF protection on all mutation endpoints

### Error Handling
- Use try/catch with typed error responses
- Return structured error objects: `{ error: string, code: string }`
- Log errors server-side, show user-friendly messages client-side
- Use Next.js `error.tsx` boundaries for page-level errors

---

## 6. Database Schema (MySQL + Drizzle)

### Core Tables

```
users
├── id (UUID, PK)
├── email (unique, indexed)
├── password_hash
├── name
├── role (enum: 'buyer', 'seller', 'admin')
├── avatar_url (nullable)
├── email_verified (boolean)
├── created_at
└── updated_at

seller_profiles
├── id (UUID, PK)
├── user_id (FK → users.id, unique)
├── store_name
├── store_description
├── location_city
├── location_province (enum: SA provinces)
├── commission_tier (enum: 'standard', 'silver', 'gold', 'platinum')
├── total_sales_amount (decimal)
├── is_verified (boolean)
├── created_at
└── updated_at

categories
├── id (INT, PK, auto-increment)
├── name
├── slug (unique)
├── description
├── icon_url (nullable)
├── parent_id (FK → categories.id, nullable — for subcategories)
└── sort_order

products
├── id (UUID, PK)
├── seller_id (FK → users.id, indexed)
├── category_id (FK → categories.id)
├── title (indexed)
├── slug (unique)
├── description (text)
├── price (decimal)
├── compare_at_price (decimal, nullable — for sale display)
├── stock_quantity (int)
├── weight_kg (decimal — for shipping calc)
├── dimensions_cm (JSON: {l, w, h})
├── status (enum: 'draft', 'active', 'paused', 'sold_out', 'removed')
├── images (JSON array of URLs)
├── tags (JSON array)
├── avg_rating (decimal, cached)
├── review_count (int, cached)
├── created_at
└── updated_at

orders
├── id (UUID, PK)
├── order_number (unique, human-readable: KB-XXXXXX)
├── buyer_id (FK → users.id)
├── seller_id (FK → users.id)
├── status (enum: 'pending_payment', 'paid', 'processing',
│           'shipped', 'delivered', 'completed', 'cancelled',
│           'refund_requested', 'refunded')
├── subtotal (decimal)
├── shipping_cost (decimal)
├── commission_amount (decimal)
├── commission_rate (decimal — snapshot at time of order)
├── total (decimal)
├── shipping_address (JSON)
├── tracking_number (nullable)
├── payment_reference (nullable)
├── escrow_status (enum: 'held', 'released', 'refunded')
├── paid_at (nullable)
├── shipped_at (nullable)
├── delivered_at (nullable)
├── created_at
└── updated_at

order_items
├── id (UUID, PK)
├── order_id (FK → orders.id)
├── product_id (FK → products.id)
├── quantity (int)
├── unit_price (decimal — snapshot at time of order)
├── total_price (decimal)
└── created_at

reviews
├── id (UUID, PK)
├── product_id (FK → products.id, indexed)
├── buyer_id (FK → users.id)
├── order_id (FK → orders.id)
├── rating (int, 1-5)
├── title (nullable)
├── comment (text)
├── is_verified_purchase (boolean)
├── created_at
└── updated_at

wishlists
├── id (UUID, PK)
├── user_id (FK → users.id)
├── product_id (FK → products.id)
├── created_at
└── UNIQUE(user_id, product_id)

payments
├── id (UUID, PK)
├── order_id (FK → orders.id, unique)
├── payment_method (enum: 'card', 'eft', 'snapscan', 'mock')
├── amount (decimal)
├── currency ('ZAR')
├── status (enum: 'pending', 'completed', 'failed', 'refunded')
├── gateway_reference (nullable)
├── gateway_response (JSON, nullable)
├── created_at
└── updated_at
```

---

## 7. HTML & PHP Integration Guide (Academic Requirement)

> [!IMPORTANT]
> HTML and PHP are **formal course requirements** for ITECA3-12. They are integrated into architecturally appropriate locations where they provide genuine value, not as afterthoughts.

### 7.1 Standalone HTML Pages

These pages are served as **pure HTML + CSS** — no JavaScript frameworks. They demonstrate semantic HTML, accessible markup, and clean CSS styling.

| Page | Path | Purpose | Why HTML? |
|---|---|---|---|
| **Simulated PayFast Gateway** | `html-pages/payment-gateway/index.html` | Realistic mock payment form | Real payment gateways are standalone HTML |
| **Payment Success/Cancel** | `html-pages/payment-gateway/success.html` | Post-payment confirmation | Simple static confirmation page |
| **About KasiBuy** | `html-pages/static/about.html` | Company story & mission | Content-only, no interactivity needed |
| **Terms & Conditions** | `html-pages/static/terms.html` | Legal document | Static legal text |
| **Privacy Policy** | `html-pages/static/privacy.html` | Data privacy policy | Static legal text |
| **FAQ** | `html-pages/static/faq.html` | Frequently asked questions | Collapsible sections with `<details>` |
| **Contact Us** | `html-pages/static/contact.html` | Contact form → PHP processor | Classic HTML form submission to PHP |
| **Welcome Email** | `html-pages/email-templates/welcome.html` | Registration confirmation email | Email clients require pure HTML |
| **Order Confirmation Email** | `html-pages/email-templates/order-confirmation.html` | Order placed notification | Email clients require pure HTML |
| **Shipping Notification Email** | `html-pages/email-templates/shipping-notification.html` | Order shipped notification | Email clients require pure HTML |
| **Printable Invoice** | `html-pages/print/invoice.html` | Downloadable/printable invoice | `@media print` CSS, no JS needed |
| **Printable Receipt** | `html-pages/print/receipt.html` | Payment receipt for buyer | `@media print` CSS, no JS needed |

#### HTML Coding Standards
```html
<!-- All HTML pages MUST: -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Page Title — KasiBuy</title>
  <link rel="stylesheet" href="../shared/styles.css">
</head>
<body>
  <!-- Use semantic elements: <header>, <main>, <nav>, <article>, <footer> -->
  <!-- Use proper heading hierarchy: h1 > h2 > h3 -->
  <!-- All forms must have labels, fieldsets, and proper validation attributes -->
  <!-- Use <details>/<summary> for expandable content (FAQ) -->
  <!-- Include ARIA attributes where needed -->
</body>
</html>
```

### 7.2 PHP Backend Services

PHP runs as a **separate microservice layer** alongside Next.js. The Next.js app calls PHP endpoints via `fetch()` for specific backend operations.

| Service | File | Endpoint | Purpose |
|---|---|---|---|
| **PayFast ITN Handler** | `php-services/payments/payfast-itn.php` | `POST /payments/payfast-itn.php` | Receives & verifies PayFast webhook notifications |
| **Payment Verification** | `php-services/payments/verify-payment.php` | `POST /payments/verify-payment.php` | Validates payment signatures & amounts |
| **Escrow Release** | `php-services/payments/escrow-release.php` | `POST /payments/escrow-release.php` | Processes escrow fund release after delivery |
| **Shipping Calculator** | `php-services/shipping/calculate-rate.php` | `GET /shipping/calculate-rate.php?from=&to=&weight=` | Calculates zone-based shipping rates |
| **Order Export (CSV)** | `php-services/exports/export-orders.php` | `GET /exports/export-orders.php?seller_id=` | Generates downloadable CSV of seller orders |
| **Earnings Export (CSV)** | `php-services/exports/export-earnings.php` | `GET /exports/export-earnings.php?seller_id=` | Generates downloadable CSV of earnings |
| **Invoice Generator** | `php-services/exports/generate-invoice.php` | `GET /exports/generate-invoice.php?order_id=` | Generates printable HTML invoice |
| **Image Processor** | `php-services/uploads/process-image.php` | `POST /uploads/process-image.php` | Resizes uploaded images, creates thumbnails |
| **Contact Form** | `php-services/contact/send-message.php` | `POST /contact/send-message.php` | Processes contact form submissions |

#### PHP Coding Standards
```php
<?php
// ALL PHP files MUST:
// 1. Use strict types
declare(strict_types=1);

// 2. Use PDO with prepared statements (NEVER raw SQL interpolation)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $userId]);

// 3. Validate ALL input
$weight = filter_input(INPUT_GET, 'weight', FILTER_VALIDATE_FLOAT);
if ($weight === false || $weight === null) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid weight parameter']);
    exit;
}

// 4. Return JSON responses with proper headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');

// 5. Use try/catch for error handling
try {
    // ... logic
    echo json_encode(['success' => true, 'data' => $result]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error']);
    error_log($e->getMessage());
}
```

#### PHP Database Connection Pattern
```php
<?php
// php-services/config/database.php
declare(strict_types=1);

function getDBConnection(): PDO {
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3306';
    $name = getenv('DB_NAME') ?: 'kasibuy';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
    
    return new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}
```

#### How Next.js Calls PHP Services
```typescript
// src/lib/shipping/index.ts — Example: calling PHP shipping calculator
const PHP_SERVICE_URL = process.env.PHP_SERVICE_URL || 'http://localhost:8080';

export async function calculateShippingRate(
  fromCity: string,
  toCity: string,
  weightKg: number
): Promise<ShippingRate> {
  const params = new URLSearchParams({
    from: fromCity,
    to: toCity,
    weight: weightKg.toString(),
  });

  const response = await fetch(
    `${PHP_SERVICE_URL}/shipping/calculate-rate.php?${params}`
  );

  if (!response.ok) throw new Error('Shipping calculation failed');
  return response.json();
}
```

### 7.3 Running PHP Services Locally

PHP services run on a separate port using PHP's built-in development server:

```bash
# Start PHP services (runs on port 8080)
cd php-services
php -S localhost:8080

# Start Next.js (runs on port 3000)
npm run dev
```

Both servers run simultaneously during development. Next.js proxies requests to PHP via `fetch()`.

---

## 8. Shipping Calculator Logic

### Zone-Based Pricing (Simulated — Implemented in PHP)

```php
// php-services/shipping/calculate-rate.php
$SHIPPING_ZONES = [
    'MAIN_CENTRES' => ['Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth'],
    'SECONDARY' => ['Bloemfontein', 'East London', 'Nelspruit', 'Polokwane', 'Kimberley'],
    'REMOTE' => [] // Everything else
];

$SHIPPING_RATES = [
    'LOCAL' => ['base' => 50, 'perKg' => 5],            // Same city
    'MAIN_TO_MAIN' => ['base' => 99, 'perKg' => 10],    // Between main centres
    'MAIN_TO_SECONDARY' => ['base' => 130, 'perKg' => 12],
    'MAIN_TO_REMOTE' => ['base' => 180, 'perKg' => 15],
    'REMOTE_TO_REMOTE' => ['base' => 250, 'perKg' => 20],
];

$FREE_SHIPPING_THRESHOLD = 500; // ZAR
```

### Chargeable Weight
```
chargeableWeight = MAX(actualWeight, volumetricWeight)
volumetricWeight = (L × W × H in cm) / 5000
```

---

## 9. Payment Flow (Simulated PayFast)

### Checkout Sequence
1. Buyer clicks "Pay Now" on checkout page (Next.js)
2. Server generates payment form with order details
3. Form POSTs to **simulated PayFast gateway** (`html-pages/payment-gateway/index.html`)
4. Buyer completes payment on the **standalone HTML gateway page** (mocked)
5. Gateway redirects back to `/checkout/success` or `/checkout/cancel`
6. Simulated ITN webhook hits **PHP handler** (`php-services/payments/payfast-itn.php`)
7. PHP verifies payment signature, updates order status to `paid` via MySQL
8. Escrow status set to `held`

### Escrow Release
1. Seller marks order as `shipped` + adds tracking number
2. Buyer confirms delivery OR auto-release after 7 days
3. **PHP escrow-release script** calculates commission, releases remainder to seller
4. Payment record updated, seller earnings incremented

---

## 9. Agent Roles & Responsibilities

This project uses **8 specialized agents**, each conducting their own discovery interview with the user before starting work.

### Agent 1: 🏗️ Architect / Orchestrator
**Scope:** Project planning, task breakdown, dependency management, code reviews
**Responsibilities:**
- Break features into atomic tasks with clear acceptance criteria
- Assign tasks to appropriate agents
- Review completed work for consistency and quality
- Maintain the project task board
- Resolve cross-agent conflicts and dependencies

### Agent 2: 🎨 UI/UX Design Agent
**Scope:** Visual design, design system, component styling, responsive layouts
**Interview Focus:** Deep-dive into visual preferences, brand identity, layout patterns
**Responsibilities:**
- Configure Bootstrap 5 SCSS overrides for KasiBuy branding
- Create custom CSS for KasiBuy-specific components
- Ensure mobile-first responsive design across both websites
- Implement micro-animations and transitions
- Ensure accessibility (WCAG 2.1 AA compliance)
- Design all page layouts (main site + admin panel)
- Style the admin panel HTML pages with Bootstrap 5

### Agent 3: ⚛️ Frontend Agent
**Scope:** React components, pages, client-side state, interactivity
**Interview Focus:** User flows, interaction patterns, component behavior
**Responsibilities:**
- Build all React Server and Client Components
- Implement page routing and navigation
- Build search, filtering, and sorting functionality
- Implement shopping cart (client-side state)
- Build all forms with client-side validation
- Integrate with API routes and Server Actions

### Agent 4: 🔧 Backend / API Agent
**Scope:** API routes, Server Actions, business logic, middleware, **PHP admin panel**
**Interview Focus:** Business rules, data flows, edge cases, admin workflows
**Responsibilities:**
- Implement all Next.js API routes (`/api/*`)
- Write Server Actions for data mutations
- Implement business logic (commission calculation, escrow, shipping)
- Build middleware for auth protection and rate limiting
- Handle file uploads (product images)
- Implement search and pagination logic
- **Build the entire PHP admin panel** (pages, actions, auth, includes)
- Write all admin PHP page controllers with PDO queries
- Implement admin session-based authentication

### Agent 5: 🗄️ Database Agent
**Scope:** Schema design, migrations, seed data, query optimization
**Interview Focus:** Data relationships, reporting needs, seed data requirements
**Responsibilities:**
- Define Drizzle ORM schema (all tables, relations, indexes)
- Write and manage migrations
- Create comprehensive seed data for demo (30+ products, 5+ sellers)
- Write optimized query functions in `src/lib/db/queries/`
- Ensure referential integrity and proper indexing

### Agent 6: 🔐 Auth & Payments Agent
**Scope:** Authentication, authorization, payment integration, escrow
**Interview Focus:** User roles, permission matrix, payment edge cases
**Responsibilities:**
- Configure NextAuth.js v5 (email/password registration & login) for main site
- Implement role-based access control (buyer, seller) on main site
- **Implement PHP session auth for admin panel** (separate from NextAuth)
- Build the simulated PayFast payment flow
- Implement escrow hold/release logic
- Handle payment error states and retry logic
- Secure all protected routes (both Next.js and PHP admin)

### Agent 7: 🧪 Testing Agent
**Scope:** Unit tests, integration tests, E2E tests, quality assurance
**Interview Focus:** Critical paths to test, acceptable coverage, test data
**Responsibilities:**
- Write unit tests for utility functions and business logic
- Write integration tests for API routes
- Write E2E tests for critical user flows (browse → cart → checkout)
- Set up testing framework (Vitest + Testing Library)
- Validate accessibility compliance
- Performance testing for key pages

### Agent 8: 🚀 DevOps Agent
**Scope:** Build configuration, deployment, CI/CD, environment setup
**Interview Focus:** Hosting preferences, deployment targets, CI requirements
**Responsibilities:**
- Configure Next.js build and optimization
- Set up environment variables and secrets management
- Configure Vercel deployment
- Set up MySQL database hosting
- Configure CI pipeline (GitHub Actions if applicable)
- Write deployment documentation

---

## 10. Agent Interview Protocol

Each agent MUST conduct a focused discovery interview with the user before starting work. The interview should:

1. **Introduce themselves** — State their role and what they're responsible for
2. **Confirm scope** — Verify their understanding of requirements from this GEMINI.md
3. **Ask domain-specific questions** — 3-8 targeted questions relevant to their specialty
4. **Present their plan** — Summarize what they will build and in what order
5. **Get approval** — Wait for user confirmation before writing any code

### Interview Question Guidelines
- Keep questions concise and use multiple-choice where possible
- Focus on decisions that materially affect implementation
- Don't re-ask questions already answered in this document
- Document their findings in their agent-specific section below

---

## 11. Feature Priority (MoSCoW)

### Must Have (Week 1-2)
- [ ] Project setup (Next.js, MySQL, Drizzle, Auth)
- [ ] User registration & login (buyer/seller roles)
- [ ] Product CRUD (seller can list, edit, delete products)
- [ ] Product browsing with search & category filters
- [ ] Product detail page with images and reviews
- [ ] Shopping cart functionality
- [ ] Basic checkout with simulated payment
- [ ] Order creation and status tracking
- [ ] Seller dashboard (products, orders, basic earnings)
- [ ] **Admin panel setup** (PHP + HTML: login, dashboard, user management)
- [ ] Mobile-responsive design

### Should Have (Week 3)
- [ ] Wishlist / favorites
- [ ] Product reviews & ratings (post-purchase)
- [ ] Advanced filtering (price range, rating, location)
- [ ] Escrow payment flow (hold → release)
- [ ] Shipping calculator integration
- [ ] Order timeline / tracking visualization
- [ ] Seller earnings breakdown with commission tiers
- [ ] **Admin product moderation** (PHP panel)
- [ ] **Admin order oversight** (PHP panel)
- [ ] **Admin reports & CSV exports** (PHP panel)

### Could Have (Week 4 / Polish)
- [ ] Promotional banners / featured sellers on homepage
- [ ] Seller performance analytics charts
- [ ] **Admin analytics with charts** (Chart.js in PHP panel)
- [ ] **Admin platform settings page** (PHP panel)
- [ ] Email notifications (simulated)
- [ ] PWA capabilities (installable, offline fallback)
- [ ] Image optimization and lazy loading
- [ ] SEO optimization (meta tags, structured data)

### Won't Have (Out of Scope)
- Real payment processing (sandbox only)
- Real courier API integration
- Direct buyer-seller messaging
- Multi-language support
- Mobile native app
- Real-time notifications (WebSocket)

---

## 12. Seed Data Requirements

The demo must include pre-populated data that showcases all features:

### Users
- **3 Sellers:** Different cities (JHB, CPT, DBN), varying sales volumes
- **2 Buyers:** With order history, reviews, wishlists
- **1 Admin:** Platform administrator

### Products
- **30+ products** across 6+ categories
- Mix of price ranges (R50 – R5,000)
- Varying stock levels (including some sold-out)
- Products with multiple images
- Products with reviews and ratings

### Orders
- **10+ orders** in various states (pending, paid, shipped, delivered, completed)
- At least 1 cancelled order and 1 refund request

### Categories
- Electronics & Gadgets
- Fashion & Clothing
- Home & Living
- Food & Beverages
- Arts & Crafts
- Beauty & Health
- Sports & Outdoors
- Books & Stationery

---

## 13. Global Rules (ALL Agents)

> [!CAUTION]
> These rules apply to ALL agents at ALL times. Violations will require rework.

1. **Read this GEMINI.md first** — Before writing any code
2. **Conduct your interview** — Before starting implementation
3. **Follow the architecture** — Files go where the structure says they go
4. **TypeScript strict mode** — No `any`, no `@ts-ignore`
5. **Bootstrap 5 for styling** — Use Bootstrap classes first, custom CSS only when needed
6. **Server Components by default** — Only use `'use client'` when necessary
7. **Validate everything** — Zod on server, HTML validation on client
8. **No hardcoded secrets** — Everything in `.env.local`
9. **Meaningful commits** — Descriptive messages, atomic changes
10. **Ask before changing schema** — Database changes need Architect approval
11. **Mobile-first** — Design for mobile, enhance for desktop
12. **Accessible** — Semantic HTML, ARIA labels, keyboard navigation
13. **Performance** — Optimize images, lazy load below-fold content, minimize JS bundle

---

## 15. Environment Variables Template

```env
# Database (shared by Next.js and PHP)
DATABASE_URL=mysql://user:password@localhost:3306/kasibuy
DB_HOST=localhost
DB_PORT=3306
DB_NAME=kasibuy
DB_USER=root
DB_PASS=

# NextAuth
NEXTAUTH_URL=http://localhost:3000
NEXTAUTH_SECRET=your-secret-key-here

# PHP Services
PHP_SERVICE_URL=http://localhost:8080

# PayFast Sandbox
PAYFAST_MERCHANT_ID=10000100
PAYFAST_MERCHANT_KEY=46f0cd694581a
PAYFAST_PASSPHRASE=
PAYFAST_SANDBOX_URL=https://sandbox.payfast.co.za/eng/process
PAYFAST_RETURN_URL=http://localhost:3000/checkout/success
PAYFAST_CANCEL_URL=http://localhost:3000/checkout/cancel
PAYFAST_NOTIFY_URL=http://localhost:8080/payments/payfast-itn.php

# App
NEXT_PUBLIC_APP_NAME=KasiBuy
NEXT_PUBLIC_APP_URL=http://localhost:3000
```

---

*Last updated: 2026-06-04*
*Version: 1.3.0 — Separated admin into standalone PHP + HTML panel*
*Status: Awaiting user approval*
