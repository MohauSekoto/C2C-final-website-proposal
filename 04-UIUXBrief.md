# UI/UX Design Brief
## MarketHub — E-Commerce Platform

**Version:** 1.0  
**Date:** 2025-05-23  
**Audience:** Frontend Developers, AI Agents  

---

## 1. Design Philosophy

MarketHub should feel **trustworthy, modern, and frictionless**. The design language draws inspiration from established marketplaces but with a distinctive identity — clean white space anchored by a deep teal primary color and warm amber accents. The admin panel takes a utilitarian, data-forward approach.

**Core Design Principles:**
1. **Clarity** — Users always know where they are and what to do next.
2. **Trust** — Visual signals (reviews, seller badges, secure checkout icons) reduce hesitation.
3. **Speed** — UI is lightweight; no excessive animations that delay perceived performance.
4. **Consistency** — Same components, spacing, and interaction patterns across all pages.

---

## 2. Brand Identity

### Color Palette

| Token | Hex | Usage |
|---|---|---|
| `--color-primary` | `#0D7C66` | Buttons, links, active states |
| `--color-primary-dark` | `#095C4A` | Hover on primary |
| `--color-primary-light` | `#E8F5F0` | Highlights, backgrounds |
| `--color-accent` | `#F5A623` | Badges, sale tags, CTA secondary |
| `--color-accent-dark` | `#D4891A` | Hover on accent |
| `--color-text` | `#1A1A2E` | Body text |
| `--color-text-muted` | `#6C757D` | Secondary text, placeholders |
| `--color-border` | `#DEE2E6` | Card borders, input borders |
| `--color-bg` | `#F8F9FA` | Page background |
| `--color-white` | `#FFFFFF` | Card surfaces |
| `--color-danger` | `#DC3545` | Errors, delete buttons |
| `--color-success` | `#198754` | Success states, stock available |
| `--color-warning` | `#FFC107` | Low stock, pending status |

### Admin Color Scheme (darker variant)

| Token | Hex | Usage |
|---|---|---|
| `--admin-sidebar-bg` | `#1A1A2E` | Sidebar background |
| `--admin-sidebar-text` | `#A8B2D8` | Sidebar links |
| `--admin-sidebar-active` | `#0D7C66` | Active nav item |
| `--admin-header-bg` | `#16213E` | Top header bar |
| `--admin-content-bg` | `#F1F3F5` | Content area background |

---

## 3. Typography

### Font Stack
```css
/* Headings */
font-family: 'Plus Jakarta Sans', 'Segoe UI', sans-serif;

/* Body */
font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;

/* Code / Prices */
font-family: 'JetBrains Mono', 'Courier New', monospace;
```

Load via Google Fonts:
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
```

### Type Scale

| Element | Size | Weight | Line Height |
|---|---|---|---|
| Hero H1 | 2.5rem | 800 | 1.2 |
| Page H1 | 2rem | 700 | 1.25 |
| Section H2 | 1.5rem | 700 | 1.3 |
| Card Title | 1.125rem | 600 | 1.4 |
| Body | 1rem | 400 | 1.6 |
| Small / Caption | 0.875rem | 400 | 1.5 |
| Price Display | 1.25rem | 700 | 1 |

---

## 4. Component Library

### 4.1 Product Card
```
┌─────────────────────────────┐
│  [Product Image 4:3 ratio]  │  ← Hover: quick view overlay
│  [SALE badge if discounted] │
├─────────────────────────────┤
│  Category tag               │
│  Product Name (2-line clamp)│
│  ★★★★☆ (4.2) · 128 reviews  │
│  Seller: @storename         │
│  R 299.00  ~~R 399.00~~     │  ← strikethrough if on sale
│  [Add to Cart ▶]  [♡]       │
└─────────────────────────────┘
```

**Specs:**
- Width: fluid (Bootstrap col)
- Image: aspect-ratio 4/3, object-fit: cover
- Card: white bg, border-radius 12px, box-shadow: 0 2px 8px rgba(0,0,0,0.08)
- Hover: translateY(-4px), shadow increases
- Add to Cart: primary button, full width

### 4.2 Buttons

| Variant | Style | Usage |
|---|---|---|
| Primary | `bg: --color-primary, text: white, radius: 8px` | Main CTAs |
| Secondary | `border: --color-primary, text: --color-primary` | Secondary actions |
| Danger | `bg: --color-danger, text: white` | Delete/Remove |
| Ghost | `transparent border, text: --color-text-muted` | Cancel actions |
| Icon | `Round, 40px` | Wishlist, quick actions |

Padding: `12px 24px` (large), `8px 16px` (medium), `6px 12px` (small)

### 4.3 Forms

- All inputs: `border: 1px solid --color-border`, `border-radius: 8px`, `padding: 10px 14px`
- Focus: `border-color: --color-primary`, `box-shadow: 0 0 0 3px rgba(13,124,102,0.15)`
- Error state: `border-color: --color-danger`, error message below in red
- Labels: above input, `font-weight: 600`, `margin-bottom: 6px`
- Placeholder: `--color-text-muted`

### 4.4 Badges / Status Pills

```css
/* Status pills */
.badge-active   { background: #D1FAE5; color: #065F46; }
.badge-pending  { background: #FEF3C7; color: #92400E; }
.badge-rejected { background: #FEE2E2; color: #991B1B; }
.badge-new      { background: #DBEAFE; color: #1E40AF; }
```
- Border-radius: 999px (pill shape)
- Padding: `4px 10px`
- Font: 0.75rem, weight 600, uppercase

### 4.5 Data Tables (Admin)

```
┌────────────┬──────────────┬────────────┬────────────┬─────────┐
│  ID        │  Name        │  Email     │  Role      │ Actions │
├────────────┼──────────────┼────────────┼────────────┼─────────┤
│  #1001     │  Jane Doe    │  jane@...  │  [Buyer]   │ ✏️ 🗑️  │
│  #1002     │  John Smith  │  john@...  │  [Seller]  │ ✏️ 🗑️  │
└────────────┴──────────────┴────────────┴────────────┴─────────┘
```
- Striped rows: even rows `#F8F9FA`
- Hover row: `#E8F5F0`
- Header: `--admin-sidebar-bg`, white text
- Responsive: horizontal scroll on mobile
- Pagination below table

---

## 5. Layout Specifications

### 5.1 Main Website Layout

```
[HEADER: Logo | Nav | Search | Cart | Auth]  ← sticky, height: 64px
[HERO BANNER (homepage only)]                ← 400px tall, full-width
[BREADCRUMB]                                 ← thin bar, 40px
[MAIN CONTENT]                               ← max-width: 1280px, auto margin
  ├── [SIDEBAR: Filters] (240px) + [PRODUCT GRID] (fluid)  ← listing pages
  └── [FULL WIDTH CONTENT]                                   ← other pages
[FOOTER]                                     ← 3-col: links, social, newsletter
```

### Grid System (Bootstrap 5)

| Breakpoint | Columns | Products per row |
|---|---|---|
| xs (< 576px) | 1 | 1 |
| sm (≥ 576px) | 2 | 2 |
| md (≥ 768px) | 2 | 2 |
| lg (≥ 992px) | 3 | 3 |
| xl (≥ 1200px) | 4 | 4 |

### 5.2 Admin Layout

```
[TOPBAR: Hamburger | Logo | User Info | Logout]  ← height: 56px
┌──────────────┬───────────────────────────────┐
│  SIDEBAR     │  MAIN CONTENT                 │
│  (240px)     │  [Page Title + Breadcrumb]    │
│  Navigation  │  [Stats Cards (dashboard)]    │
│  menu items  │  [Data Table / Form]          │
│              │                               │
└──────────────┴───────────────────────────────┘
```

---

## 6. Page-Specific UX Notes

### Homepage
- Hero: Search bar prominently centered, headline "Find everything you need"
- Featured categories: icon grid (6-8 categories)
- Featured products: 4-column grid, 8 items
- New arrivals section
- Trust badges bar: "Secure Checkout | Free Returns | Verified Sellers"

### Product Detail Page
```
[Image Gallery Left (60%)] | [Product Info Right (40%)]
  - Thumbnail strip         |  Name, Rating, Review count
  - Zoom on hover           |  Price (large, bold)
                            |  Seller info + rating
                            |  Quantity selector
                            |  [Add to Cart] [♡ Wishlist]
                            |  Category, SKU, Stock status
[Tab bar: Description | Reviews | Seller Info]
[Related Products]
```

### Cart Page
- Line items with product image, name, price, quantity stepper
- Order summary sidebar (sticky on desktop)
- Promo code field
- Clear call-to-action: "Proceed to Checkout"

### Checkout Page
- 3-step progress indicator: Details → Review → Confirm
- No sidebar, focused single-column layout
- Address autofill-friendly field order

### Admin Dashboard
- 4 KPI cards: Total Revenue, Total Orders, Active Products, New Users (today)
- Revenue chart (line chart, last 30 days)
- Recent orders table (last 5)
- Pending seller approvals alert box

---

## 7. Accessibility Requirements

- All images: descriptive `alt` text
- Color contrast: minimum 4.5:1 for body text
- Focus indicators visible for keyboard navigation
- Form errors: not only indicated by color (include icon + text)
- Admin tables: `<th scope="col">` for screen reader compatibility
- ARIA labels on icon-only buttons

---

## 8. Responsive Design Rules

- Mobile-first: base styles for mobile, breakpoints add complexity
- Sidebar (filter) collapses to off-canvas drawer on mobile
- Admin sidebar collapses to icon rail on tablet, full drawer on mobile
- Product cards: never smaller than 280px wide
- Touch targets: minimum 44×44px
- No horizontal scroll on any page (except data tables with explicit scroll container)

---

## 9. Iconography

Use **Bootstrap Icons** (already included with Bootstrap 5):
```html
<!-- Example usage -->
<i class="bi bi-cart3"></i>       <!-- Cart -->
<i class="bi bi-heart"></i>       <!-- Wishlist -->
<i class="bi bi-star-fill"></i>   <!-- Rating star -->
<i class="bi bi-person-circle"></i> <!-- User avatar -->
<i class="bi bi-pencil-square"></i> <!-- Edit -->
<i class="bi bi-trash3"></i>      <!-- Delete -->
<i class="bi bi-check-circle"></i>  <!-- Approve -->
<i class="bi bi-x-circle"></i>    <!-- Reject -->
```
