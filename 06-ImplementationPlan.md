# Implementation Plan
## MarketHub — E-Commerce Platform Build & Deploy Guide

**Version:** 1.0  
**Date:** 2025-05-23  
**Audience:** Developers, AI Agents (Claude Code / Antigravity)  

---

## 0. Agent Instructions

This document is structured for AI agents executing the build autonomously. Each phase contains discrete, testable tasks. Complete each phase fully before proceeding to the next. All code must match the specifications in `01-PRD.md`, `02-TRD.md`, `03-WebsiteFlow.md`, `04-UIUXBrief.md`, and `05-BackendSchema.md`.

**Technology Stack:**
- PHP 8.2+ (no frameworks — raw PHP with PDO)
- MySQL 8.0+
- HTML5 + CSS3 + Bootstrap 5.3
- JavaScript (jQuery 3.7)
- Apache or Nginx server

---

## Phase 1: Environment Setup

### 1.1 Directory Structure
```bash
# Create full project structure
mkdir -p markethub/{public/{seller,profile},admin/{users,roles,products,orders,categories,sellers,analytics,audit},includes,assets/{css,js,img/uploads/products},config,sql}
```

### 1.2 Configuration File
Create `config/config.php`:
```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'markethub');
define('DB_USER', 'root');          // change for production
define('DB_PASS', '');              // change for production
define('APP_URL', 'http://localhost/markethub');
define('APP_ENV', 'development');
define('UPLOAD_PATH', __DIR__ . '/../assets/img/uploads/products/');
define('MAX_UPLOAD_MB', 5);
define('SESSION_NAME', 'mh_session');
define('ADMIN_SESSION_NAME', 'mh_admin_session');
```

Add to `.gitignore`:
```
config/config.php
assets/img/uploads/
```

### 1.3 Database Initialization
```bash
mysql -u root -p < sql/schema.sql
mysql -u root -p markethub < sql/seed.sql
# Then run seed_passwords.php to hash passwords correctly
php scripts/seed_passwords.php
```

**Agent Task:** Generate `scripts/seed_passwords.php` that updates admin_users hashes:
```php
<?php
require_once '../config/config.php';
require_once '../includes/db.php';
$db = Database::getInstance();
$passwords = [
    'admin@markethub.com'   => 'Admin@1234',
    'mod@markethub.com'     => 'Mod@1234',
    'support@markethub.com' => 'Support@1234',
];
foreach ($passwords as $email => $pass) {
    $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt = $db->prepare("UPDATE admin_users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);
    echo "Updated: $email\n";
}
echo "Done.\n";
```

---

## Phase 2: Core PHP Includes

### 2.1 Database Class (`includes/db.php`)
Implement as singleton PDO — see TRD section 3.

### 2.2 Authentication (`includes/auth.php`)
```php
<?php
require_once __DIR__ . '/../config/config.php';
session_name(SESSION_NAME);
session_start();

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function isSeller(): bool {
    return isLoggedIn() && $_SESSION['user_role'] === 'seller';
}

function requireLogin(string $redirect = '/login.php'): void {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ' . APP_URL . $redirect);
        exit;
    }
}

function requireSeller(): void {
    requireLogin();
    if (!isSeller()) {
        header('Location: ' . APP_URL . '/seller/register.php');
        exit;
    }
}

function currentUser(): ?array {
    if (!isLoggedIn()) return null;
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT id,name,email,role,avatar FROM users WHERE id = ? AND deleted_at IS NULL");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}
```

### 2.3 Admin Auth (`includes/admin_auth.php`)
```php
<?php
require_once __DIR__ . '/../config/config.php';
session_name(ADMIN_SESSION_NAME);
session_start();

function isAdminLoggedIn(): bool {
    return isset($_SESSION['admin_id']);
}

function requireAdminLogin(): void {
    if (!isAdminLoggedIn()) {
        header('Location: ' . APP_URL . '/admin/login.php');
        exit;
    }
}
```

### 2.4 RBAC (`includes/rbac.php`)
Implement `hasPermission()` and `requirePermission()` — see TRD section 5.

### 2.5 CSRF Protection (`includes/functions.php`)
```php
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrf(): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        die('CSRF validation failed.');
    }
}

function sanitizeOutput(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function generateOrderNumber(): string {
    return 'MH-' . strtoupper(substr(uniqid(), -8));
}

function paginate(int $total, int $perPage, int $currentPage): array {
    $totalPages = (int) ceil($total / $perPage);
    return [
        'total'        => $total,
        'per_page'     => $perPage,
        'current_page' => $currentPage,
        'total_pages'  => $totalPages,
        'offset'       => ($currentPage - 1) * $perPage,
        'has_prev'     => $currentPage > 1,
        'has_next'     => $currentPage < $totalPages,
    ];
}
```

---

## Phase 3: Shared Templates

### 3.1 Header Template (`includes/header.php`)
- HTML5 doctype
- Bootstrap 5.3 CDN
- Bootstrap Icons CDN
- jQuery 3.7 CDN
- Custom `main.css`
- Responsive meta tag
- CSRF token in meta tag for AJAX

### 3.2 Footer Template (`includes/footer.php`)
- 3-column footer: About/Links/Newsletter
- Copyright line
- Custom `main.js`

### 3.3 Admin Header/Sidebar (`admin/includes/header.php`)
- Dark sidebar with nav items
- Active state detection based on current URL
- Permission-gated nav items
- Topbar with user name and logout

---

## Phase 4: Main Website — Build Order

Build pages in this exact order (each depends on the previous):

### Step 4.1: Authentication Pages
1. `public/register.php` — Form + validation + PDO insert + email verify token
2. `public/login.php` — Credential check + session + CSRF
3. `public/logout.php` — Session destroy + redirect

### Step 4.2: Homepage & Product Listing
4. `public/index.php` — Hero, categories grid, featured products (query top 8 by rating)
5. `public/search.php` — Full-text search + category filter + price range + pagination
6. `public/product.php?id=X` — Detail page with image gallery, reviews tab, related products

### Step 4.3: Cart & Checkout
7. `public/cart.php` — Session-based cart display; quantity update via AJAX
8. `public/checkout.php` — Address form (pre-fill if saved), order summary, place order
9. `public/order-success.php` — Order confirmation with order number

### Step 4.4: Buyer Account
10. `public/orders.php` — Order history list with status badges
11. `public/order.php?id=X` — Order detail with items and timeline
12. `public/profile/account.php` — Edit name, email, password, avatar upload
13. `public/profile/wishlist.php` — Wishlist grid with remove button

### Step 4.5: Seller Area
14. `public/seller/register.php` — Seller application form (store name, description, etc.)
15. `public/seller/dashboard.php` — KPI cards: products, orders, revenue
16. `public/seller/products.php` — Product list table with status badges
17. `public/seller/add-product.php` — Multi-image upload, category, price, stock
18. `public/seller/edit-product.php?id=X` — Pre-filled edit form
19. `public/seller/orders.php` — Orders for this seller's products

### Step 4.6: AJAX Endpoints
20. `public/ajax/cart/add.php`
21. `public/ajax/cart/update.php`
22. `public/ajax/cart/remove.php`
23. `public/ajax/wishlist/toggle.php`
24. `public/ajax/review/submit.php`

---

## Phase 5: Admin Website — Build Order

### Step 5.1: Admin Auth
1. `admin/login.php` — Admin credential check + role session
2. `admin/logout.php`

### Step 5.2: Dashboard
3. `admin/index.php` — 4 KPI cards, revenue chart (Chart.js via CDN), recent orders

### Step 5.3: User Management (RBAC gated)
4. `admin/users/index.php` — Searchable data table with role filter (requires: users.read)
5. `admin/users/create.php` — Create buyer/seller/admin user (requires: users.create)
6. `admin/users/edit.php?id=X` — Edit user details and role (requires: users.update)
7. `admin/users/delete.php?id=X` — Soft delete confirmation (requires: users.delete)

### Step 5.4: Role Management (Super Admin only)
8. `admin/roles/index.php` — List roles with permission counts (requires: roles.read)
9. `admin/roles/create.php` — Create role + assign permissions via checkboxes (requires: roles.create)
10. `admin/roles/edit.php?id=X` — Update role permissions (requires: roles.update)

### Step 5.5: Product Moderation
11. `admin/products/index.php` — All products with status filter (requires: products.read)
12. `admin/products/moderate.php?id=X` — Approve/reject product (requires: products.update)

### Step 5.6: Seller Approvals
13. `admin/sellers/approvals.php` — Pending seller applications (requires: sellers.approve)
    - Approve → set seller_profiles.status = 'active'
    - Reject → set status = 'rejected', send email

### Step 5.7: Order Management
14. `admin/orders/index.php` — All orders with filter by status/date (requires: orders.read)

### Step 5.8: Category Management
15. `admin/categories/index.php` — Category tree list (requires: categories.read)
16. `admin/categories/create.php` — New category with parent selection
17. `admin/categories/edit.php?id=X` — Edit category

### Step 5.9: Analytics
18. `admin/analytics/index.php` — Revenue over time, top products, top sellers (requires: analytics.read)

### Step 5.10: Audit Log
19. `admin/audit/index.php` — Paginated audit log with filter (requires: audit.read)

### Step 5.11: Admin AJAX Endpoints
20. `admin/ajax/user-status.php` — Toggle user active/suspended
21. `admin/ajax/product-status.php` — Approve/reject product inline

---

## Phase 6: Assets & Styling

### 6.1 CSS (`assets/css/main.css`)
Implement all CSS variables, component styles, and responsive rules from `04-UIUXBrief.md`.

Key sections:
- `:root` variables (all `--color-*` tokens)
- Product card styles
- Button variants
- Form styles
- Badge/status pills
- Footer layout
- Hero section

### 6.2 Admin CSS (`assets/css/admin.css`)
- Sidebar layout (fixed left, content offset)
- Dark sidebar colors
- Admin stat cards
- Responsive sidebar collapse

### 6.3 JavaScript (`assets/js/main.js`)
```javascript
// Cart operations via AJAX
function addToCart(productId, qty = 1) { ... }
function updateCartQty(itemId, qty) { ... }
function removeFromCart(itemId) { ... }

// Wishlist toggle
function toggleWishlist(productId) { ... }

// Image gallery (product detail)
function initGallery() { ... }

// Form validation helper
function validateForm($form) { ... }
```

### 6.4 Admin JS (`assets/js/admin.js`)
```javascript
// Sidebar toggle
$('#sidebarToggle').on('click', function() { ... });

// Confirm dialogs for delete
$('.btn-delete').on('click', function(e) {
    if (!confirm('Are you sure? This cannot be undone.')) e.preventDefault();
});

// Inline status toggle
function toggleUserStatus(userId, status) { ... }
```

---

## Phase 7: Testing

### 7.1 Manual Test Checklist

**Authentication**
- [ ] Buyer can register with valid email + password (min 8 chars)
- [ ] Registration fails with duplicate email
- [ ] Login succeeds with correct credentials
- [ ] Login fails with wrong password (shows generic error — no email enumeration)
- [ ] Logout destroys session

**Buyer Flows**
- [ ] Homepage loads with products
- [ ] Search returns relevant results
- [ ] Add to cart works when logged out (session cart)
- [ ] Cart persists across page navigation
- [ ] Checkout requires login; redirects back after login
- [ ] Order is saved in database after checkout
- [ ] Order appears in buyer's order history
- [ ] Review can only be submitted for delivered orders

**Seller Flows**
- [ ] Seller application submits and shows "pending" status
- [ ] Seller cannot access dashboard until approved
- [ ] After admin approval, seller can log in to dashboard
- [ ] Seller can add product with images
- [ ] Product appears as "pending" until admin approves
- [ ] Seller can edit/delete own products only (not other sellers')

**Admin RBAC**
- [ ] Super Admin can access all pages
- [ ] Moderator sees 403 on `/admin/roles/` and `/admin/users/create.php`
- [ ] Support Agent sees 403 on `/admin/products/moderate.php`
- [ ] Deleting a user redirects to list with success message
- [ ] Audit log records every admin CRUD action

**Security**
- [ ] `' OR 1=1 --` in search/login does not cause SQL error or bypass
- [ ] `<script>alert('xss')</script>` in product name is escaped on display
- [ ] CSRF token missing → 403 on form submissions
- [ ] Direct URL access to seller pages without login → redirect to login
- [ ] Admin pages without admin session → redirect to admin login

### 7.2 Agent Automated Test Script

Create `tests/run_tests.php` that:
1. Connects to DB and verifies all tables exist
2. Checks seed data (3 admin users, 3 roles, 20 permissions)
3. Verifies `hasPermission()` for each role against expected permissions
4. Sends mock HTTP requests to guarded pages without session (expect redirect)
5. Outputs pass/fail summary

---

## Phase 8: Deployment

### 8.1 Pre-Deployment Checklist
- [ ] `APP_ENV` set to `'production'` in config.php
- [ ] `APP_URL` set to live domain with HTTPS
- [ ] Database credentials updated for production MySQL
- [ ] `assets/img/uploads/` is writable (chmod 755 or 775)
- [ ] `config/config.php` is NOT publicly accessible (place above webroot or protect with .htaccess)
- [ ] PHP error display OFF (`display_errors = Off` in php.ini)
- [ ] PHP error logging ON (log to file, not browser)

### 8.2 Apache `.htaccess` (project root)
```apache
Options -Indexes
RewriteEngine On

# Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Protect config directory
<Directory "config">
    Order Allow,Deny
    Deny from all
</Directory>

# Security headers
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-XSS-Protection "1; mode=block"
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
```

### 8.3 Deployment Steps (Agent Execution)

```bash
# Step 1: Upload files
rsync -avz --exclude '.git' --exclude 'config/config.php' \
  markethub/ user@server:/var/www/html/markethub/

# Step 2: Set config
scp config/config.production.php user@server:/var/www/html/markethub/config/config.php

# Step 3: Database setup
ssh user@server "mysql -u root -p < /var/www/html/markethub/sql/schema.sql"
ssh user@server "mysql -u root -p markethub < /var/www/html/markethub/sql/seed.sql"
ssh user@server "php /var/www/html/markethub/scripts/seed_passwords.php"

# Step 4: Permissions
ssh user@server "chmod -R 755 /var/www/html/markethub/assets/img/uploads/"

# Step 5: Verify
curl -I https://yourdomain.com/markethub/
curl -I https://yourdomain.com/markethub/admin/
```

### 8.4 Post-Deploy Verification
1. Open main site — homepage products load
2. Open admin — login with `admin@markethub.com` / `Admin@1234`
3. Navigate to Users → verify list loads
4. Navigate to Roles → verify permission matrix loads
5. Log in as `mod@markethub.com` → verify `/admin/roles/` shows 403
6. Log in as `support@markethub.com` → verify only orders and users readable
7. Register a buyer account on main site
8. Add product to cart and complete checkout (mock)

---

## Phase 9: Diagrams Reference

All required academic diagrams are in `diagrams/`:

| Diagram | File | Tool |
|---|---|---|
| CRC Cards | `diagrams/CRC-Cards.md` | Text/Markdown |
| EERD | `diagrams/EERD.md` | Mermaid / Draw.io |
| Context Diagram | `diagrams/ContextDiagram.md` | Mermaid |
| DFD Level 0 & 1 | `diagrams/DFD.md` | Mermaid |
| Use Case Diagram | `diagrams/UseCaseDiagram.md` | PlantUML / Mermaid |
| Database Schema | `sql/schema.sql` | MySQL DDL |

---

## Appendix: Agent Execution Order Summary

```
Phase 1  → Environment & Config
Phase 2  → PHP Core Includes (db, auth, rbac, functions)
Phase 3  → Shared Templates (header, footer, admin layout)
Phase 4  → Main Website (auth → products → cart → checkout → accounts → seller)
Phase 5  → Admin Website (auth → dashboard → users → roles → products → orders → categories → analytics)
Phase 6  → Assets (CSS variables, responsive styles, JS handlers)
Phase 7  → Testing (manual checklist + automated script)
Phase 8  → Deploy (upload → DB setup → config → verify)
```

Each phase is idempotent where possible. If the agent encounters an error, it should log the error, skip to the next file, and report all failures at the end rather than halting entirely.
