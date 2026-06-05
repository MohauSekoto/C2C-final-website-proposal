# Technical Requirements Document (TRD)
## MarketHub — Multi-Vendor E-Commerce Platform

**Version:** 1.0  
**Date:** 2025-05-23  
**Audience:** Developers, DevOps, AI Agents  

---

## 1. Technology Stack

| Layer | Technology | Version |
|---|---|---|
| Frontend | HTML5, CSS3, JavaScript (ES6+) | Current |
| CSS Framework | Bootstrap | 5.3.x |
| JS Library | jQuery | 3.7.x |
| Backend | PHP | 8.2+ |
| Database | MySQL | 8.0+ |
| Server | Apache or Nginx | Latest stable |
| Hosting | Shared/VPS with PHP + MySQL | — |

**Prohibited:** WordPress, Wix, Squarespace, Laravel, Symfony, or any PHP framework that abstracts raw SQL away entirely. Raw PHP with PDO is required.

---

## 2. Project Directory Structure

```
markethub/
├── public/                    # Main website (public-facing)
│   ├── index.php              # Homepage / product listing
│   ├── product.php            # Product detail page
│   ├── search.php             # Search results
│   ├── cart.php               # Shopping cart
│   ├── checkout.php           # Checkout flow
│   ├── orders.php             # Order history (buyer)
│   ├── register.php           # Buyer registration
│   ├── login.php              # Buyer/Seller login
│   ├── logout.php
│   ├── seller/
│   │   ├── dashboard.php
│   │   ├── products.php
│   │   ├── add-product.php
│   │   ├── edit-product.php
│   │   ├── orders.php
│   │   └── register.php       # Seller application
│   └── profile/
│       ├── account.php
│       └── wishlist.php
│
├── admin/                     # Admin website (RBAC-protected)
│   ├── index.php              # Admin dashboard
│   ├── login.php
│   ├── logout.php
│   ├── users/
│   │   ├── index.php          # List all users
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── delete.php
│   ├── roles/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── products/
│   │   ├── index.php
│   │   └── moderate.php
│   ├── orders/
│   │   └── index.php
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── sellers/
│   │   └── approvals.php
│   └── analytics/
│       └── index.php
│
├── includes/                  # Shared PHP includes
│   ├── db.php                 # PDO connection singleton
│   ├── auth.php               # Session auth helpers
│   ├── rbac.php               # Role permission checks
│   ├── functions.php          # Utility functions
│   └── mailer.php             # Email helper
│
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   └── admin.css
│   ├── js/
│   │   ├── main.js
│   │   └── admin.js
│   └── img/
│       └── uploads/           # Product images (writeable)
│
├── config/
│   └── config.php             # DB credentials, constants (NOT in version control)
│
└── sql/
    ├── schema.sql             # Full database DDL
    └── seed.sql               # Sample data
```

---

## 3. Database Requirements

- Engine: MySQL 8.0+ with InnoDB
- Character Set: `utf8mb4`
- Collation: `utf8mb4_unicode_ci`
- All tables use `id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY`
- Soft deletes: `deleted_at TIMESTAMP NULL` on User, Product, Order tables
- Timestamps: `created_at`, `updated_at` on all tables

### Connection (PDO)

```php
// includes/db.php
<?php
class Database {
    private static ?PDO $instance = null;
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        }
        return self::$instance;
    }
}
```

---

## 4. Authentication & Session Management

### Buyer / Seller Sessions
```php
// Login flow
session_start();
// After credential verification:
$_SESSION['user_id']   = $user['id'];
$_SESSION['user_role'] = $user['role']; // 'buyer' | 'seller'
$_SESSION['user_name'] = $user['name'];

// Auth guard (top of protected pages)
require_once '../includes/auth.php';
requireLogin(); // redirects to login.php if no session
```

### Admin Sessions
```php
// Admin sessions are separate — stored in admin_sessions or same DB table
$_SESSION['admin_id']   = $admin['id'];
$_SESSION['admin_role'] = $admin['role_id'];

// Guard
requireAdminLogin();
requirePermission('users.read'); // RBAC check
```

### Password Hashing
```php
// Registration
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

// Verification
if (!password_verify($inputPassword, $storedHash)) {
    // Invalid credentials
}
```

---

## 5. RBAC Implementation

### Permission Check (includes/rbac.php)
```php
function hasPermission(string $permission): bool {
    if (!isset($_SESSION['admin_id'])) return false;
    $db = Database::getInstance();
    $stmt = $db->prepare("
        SELECT COUNT(*) FROM role_permissions rp
        JOIN permissions p ON rp.permission_id = p.id
        JOIN admin_users au ON au.role_id = rp.role_id
        WHERE au.id = ? AND p.slug = ?
    ");
    $stmt->execute([$_SESSION['admin_id'], $permission]);
    return $stmt->fetchColumn() > 0;
}

function requirePermission(string $permission): void {
    if (!hasPermission($permission)) {
        http_response_code(403);
        include '../views/errors/403.php';
        exit;
    }
}
```

### Permission Slugs
```
users.read       users.create    users.update    users.delete
products.read    products.update products.delete
orders.read      orders.update
categories.read  categories.create categories.update categories.delete
roles.read       roles.create    roles.update    roles.delete
sellers.approve  analytics.read  audit.read
```

---

## 6. File Upload Requirements

- Allowed MIME types: `image/jpeg`, `image/png`, `image/webp`
- Max file size: 5MB per image
- Storage: `assets/img/uploads/products/` (writable, outside webroot is preferred)
- Filename: `{uniqid()}_{sanitized_original}.ext`
- Validate server-side with `getimagesize()` — never trust MIME from client

---

## 7. Security Requirements

| Requirement | Implementation |
|---|---|
| SQL Injection | PDO prepared statements for all queries |
| XSS | `htmlspecialchars()` on all output; CSP headers |
| CSRF | Token per form, validated server-side |
| Session Fixation | `session_regenerate_id(true)` on login |
| Directory Traversal | `basename()` + whitelist for file paths |
| Sensitive Config | `config.php` excluded from version control (in .gitignore) |
| HTTPS | Enforced via `.htaccess` redirect on production |

---

## 8. API Endpoints (Internal AJAX)

| Endpoint | Method | Auth | Description |
|---|---|---|---|
| `/ajax/cart/add.php` | POST | Buyer | Add product to cart |
| `/ajax/cart/update.php` | POST | Buyer | Update cart quantity |
| `/ajax/cart/remove.php` | POST | Buyer | Remove cart item |
| `/ajax/wishlist/toggle.php` | POST | Buyer | Toggle wishlist item |
| `/ajax/review/submit.php` | POST | Buyer | Submit product review |
| `/ajax/admin/user-status.php` | POST | Admin | Toggle user active status |
| `/ajax/admin/product-status.php` | POST | Admin | Approve/reject product |

All AJAX endpoints return JSON: `{"success": bool, "message": string, "data": mixed}`

---

## 9. Performance Guidelines

- Paginate all list views: default 20 items per page
- Index all foreign key columns
- Use `SELECT` only needed columns (no `SELECT *`)
- Cache category list in PHP session to avoid repeated queries
- Compress assets: minified CSS/JS for production

---

## 10. Testing Requirements

### Agent Testing Instructions
When an AI agent runs tests, it should:

1. **Database:** Run `sql/schema.sql` then `sql/seed.sql` on a test MySQL instance
2. **Unit checks:** Verify `hasPermission()` returns `true`/`false` correctly for each role
3. **Integration:** Test login → session → guarded page → logout flow for each role
4. **Form validation:** Test each form with empty inputs, SQL injection strings, XSS payloads
5. **File upload:** Test with valid image, oversized file, non-image file
6. **RBAC:** Login as each admin role and verify forbidden pages return HTTP 403

### Test Credentials (seed data)
```
Super Admin:      admin@markethub.com   / Admin@1234
Content Moderator: mod@markethub.com    / Mod@1234
Support Agent:    support@markethub.com / Support@1234
Buyer:            buyer@markethub.com   / Buyer@1234
Seller:           seller@markethub.com  / Seller@1234
```

---

## 11. Deployment Requirements

### Environment Configuration
```php
// config/config.php (template — actual values set per environment)
define('DB_HOST', 'localhost');
define('DB_NAME', 'markethub');
define('DB_USER', 'mh_user');
define('DB_PASS', 'CHANGE_ME');
define('APP_URL', 'https://yourdomain.com');
define('APP_ENV', 'production'); // 'development' | 'production'
define('UPLOAD_PATH', __DIR__ . '/../assets/img/uploads/');
define('SMTP_HOST', 'smtp.mailtrap.io');
define('SMTP_PORT', 587);
define('SMTP_USER', '');
define('SMTP_PASS', '');
```

### Deployment Checklist (for agents)
1. Upload all files via FTP/SFTP or Git deploy
2. Create MySQL database and user with grants
3. Import `sql/schema.sql` and `sql/seed.sql`
4. Set `config.php` values for production
5. Set `assets/img/uploads/` to writable (chmod 755)
6. Enable HTTPS and update `APP_URL`
7. Test all role logins and CRUD operations
8. Confirm 403 pages appear for unauthorized admin access
