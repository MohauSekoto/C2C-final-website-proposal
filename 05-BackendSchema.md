# Backend Schema Document
## MarketHub — Database & Backend Architecture

**Version:** 1.0  
**Date:** 2025-05-23  

---

## 1. Database Schema Overview

### Entity Relationship Summary

```
users ──────────────── addresses
  │                        
  ├──> seller_profiles ──> products ──> product_images
  │                            │
  │                            ├──> product_categories
  │                            │
  │                            └──> order_items
  │
  ├──> orders ──────────── order_items ──> products
  │       │
  │       └──> order_status_history
  │
  ├──> reviews ──> products
  │
  └──> wishlists ──> products

admin_users ──> roles ──> role_permissions ──> permissions
admin_users ──> audit_logs
```

---

## 2. Full SQL Schema (DDL)

```sql
-- =============================================
-- MarketHub Database Schema
-- Engine: InnoDB | Charset: utf8mb4
-- =============================================

CREATE DATABASE IF NOT EXISTS markethub
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE markethub;

-- ─────────────────────────────────────────────
-- USERS
-- ─────────────────────────────────────────────
CREATE TABLE users (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name            VARCHAR(120)    NOT NULL,
  email           VARCHAR(191)    NOT NULL UNIQUE,
  password_hash   VARCHAR(255)    NOT NULL,
  phone           VARCHAR(20)     NULL,
  avatar          VARCHAR(255)    NULL,
  role            ENUM('buyer','seller') NOT NULL DEFAULT 'buyer',
  status          ENUM('active','suspended','banned') NOT NULL DEFAULT 'active',
  email_verified  TINYINT(1)      NOT NULL DEFAULT 0,
  verify_token    VARCHAR(100)    NULL,
  reset_token     VARCHAR(100)    NULL,
  reset_expires   DATETIME        NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at      DATETIME        NULL,
  INDEX idx_email (email),
  INDEX idx_role  (role),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─────────────────────────────────────────────
-- ADDRESSES
-- ─────────────────────────────────────────────
CREATE TABLE addresses (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NOT NULL,
  label       VARCHAR(50)     NULL DEFAULT 'Home',
  full_name   VARCHAR(120)    NOT NULL,
  line1       VARCHAR(255)    NOT NULL,
  line2       VARCHAR(255)    NULL,
  city        VARCHAR(100)    NOT NULL,
  province    VARCHAR(100)    NOT NULL,
  postal_code VARCHAR(20)     NOT NULL,
  country     VARCHAR(100)    NOT NULL DEFAULT 'South Africa',
  is_default  TINYINT(1)      NOT NULL DEFAULT 0,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- SELLER PROFILES
-- ─────────────────────────────────────────────
CREATE TABLE seller_profiles (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id         BIGINT UNSIGNED NOT NULL UNIQUE,
  store_name      VARCHAR(150)    NOT NULL,
  store_slug      VARCHAR(150)    NOT NULL UNIQUE,
  description     TEXT            NULL,
  logo            VARCHAR(255)    NULL,
  banner          VARCHAR(255)    NULL,
  contact_email   VARCHAR(191)    NOT NULL,
  contact_phone   VARCHAR(20)     NULL,
  address         VARCHAR(255)    NULL,
  status          ENUM('pending','active','suspended','rejected') NOT NULL DEFAULT 'pending',
  rating          DECIMAL(3,2)    NOT NULL DEFAULT 0.00,
  total_sales     INT UNSIGNED    NOT NULL DEFAULT 0,
  approved_by     BIGINT UNSIGNED NULL,
  approved_at     DATETIME        NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (approved_by) REFERENCES admin_users(id) ON DELETE SET NULL,
  INDEX idx_slug   (store_slug),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- CATEGORIES
-- ─────────────────────────────────────────────
CREATE TABLE categories (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)    NOT NULL,
  slug        VARCHAR(100)    NOT NULL UNIQUE,
  parent_id   BIGINT UNSIGNED NULL,
  icon        VARCHAR(100)    NULL,
  image       VARCHAR(255)    NULL,
  sort_order  INT             NOT NULL DEFAULT 0,
  is_active   TINYINT(1)      NOT NULL DEFAULT 1,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL,
  INDEX idx_parent (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- PRODUCTS
-- ─────────────────────────────────────────────
CREATE TABLE products (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  seller_id       BIGINT UNSIGNED NOT NULL,
  category_id     BIGINT UNSIGNED NOT NULL,
  name            VARCHAR(255)    NOT NULL,
  slug            VARCHAR(255)    NOT NULL UNIQUE,
  description     TEXT            NOT NULL,
  short_desc      VARCHAR(500)    NULL,
  price           DECIMAL(12,2)   NOT NULL,
  sale_price      DECIMAL(12,2)   NULL,
  sku             VARCHAR(100)    NOT NULL UNIQUE,
  stock_qty       INT             NOT NULL DEFAULT 0,
  low_stock_alert INT             NOT NULL DEFAULT 5,
  weight_kg       DECIMAL(8,3)    NULL,
  status          ENUM('pending','active','inactive','rejected') NOT NULL DEFAULT 'pending',
  is_featured     TINYINT(1)      NOT NULL DEFAULT 0,
  views           INT UNSIGNED    NOT NULL DEFAULT 0,
  rating          DECIMAL(3,2)    NOT NULL DEFAULT 0.00,
  review_count    INT UNSIGNED    NOT NULL DEFAULT 0,
  moderated_by    BIGINT UNSIGNED NULL,
  moderated_at    DATETIME        NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at      DATETIME        NULL,
  FOREIGN KEY (seller_id) REFERENCES seller_profiles(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  FOREIGN KEY (moderated_by) REFERENCES admin_users(id) ON DELETE SET NULL,
  FULLTEXT INDEX ft_search (name, description, short_desc),
  INDEX idx_seller   (seller_id),
  INDEX idx_category (category_id),
  INDEX idx_status   (status),
  INDEX idx_price    (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- PRODUCT IMAGES
-- ─────────────────────────────────────────────
CREATE TABLE product_images (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id  BIGINT UNSIGNED NOT NULL,
  file_path   VARCHAR(255)    NOT NULL,
  alt_text    VARCHAR(255)    NULL,
  sort_order  INT             NOT NULL DEFAULT 0,
  is_primary  TINYINT(1)      NOT NULL DEFAULT 0,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  INDEX idx_product (product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ORDERS
-- ─────────────────────────────────────────────
CREATE TABLE orders (
  id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_number    VARCHAR(20)     NOT NULL UNIQUE,
  buyer_id        BIGINT UNSIGNED NOT NULL,
  shipping_address_id BIGINT UNSIGNED NOT NULL,
  subtotal        DECIMAL(12,2)   NOT NULL,
  shipping_fee    DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  discount        DECIMAL(12,2)   NOT NULL DEFAULT 0.00,
  total           DECIMAL(12,2)   NOT NULL,
  status          ENUM('pending','confirmed','processing','shipped','delivered','cancelled','refunded') NOT NULL DEFAULT 'pending',
  payment_status  ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  payment_method  VARCHAR(50)     NULL,
  notes           TEXT            NULL,
  created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at      DATETIME        NULL,
  FOREIGN KEY (buyer_id) REFERENCES users(id),
  FOREIGN KEY (shipping_address_id) REFERENCES addresses(id),
  INDEX idx_buyer  (buyer_id),
  INDEX idx_status (status),
  INDEX idx_number (order_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ORDER ITEMS
-- ─────────────────────────────────────────────
CREATE TABLE order_items (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id    BIGINT UNSIGNED NOT NULL,
  product_id  BIGINT UNSIGNED NOT NULL,
  seller_id   BIGINT UNSIGNED NOT NULL,
  product_name VARCHAR(255)   NOT NULL,  -- snapshot at purchase time
  unit_price  DECIMAL(12,2)   NOT NULL,
  quantity    INT UNSIGNED    NOT NULL,
  subtotal    DECIMAL(12,2)   NOT NULL,
  FOREIGN KEY (order_id)   REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (seller_id)  REFERENCES seller_profiles(id),
  INDEX idx_order  (order_id),
  INDEX idx_seller (seller_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ORDER STATUS HISTORY
-- ─────────────────────────────────────────────
CREATE TABLE order_status_history (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id    BIGINT UNSIGNED NOT NULL,
  old_status  VARCHAR(50)     NULL,
  new_status  VARCHAR(50)     NOT NULL,
  notes       TEXT            NULL,
  changed_by  BIGINT UNSIGNED NULL,
  changed_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- REVIEWS
-- ─────────────────────────────────────────────
CREATE TABLE reviews (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id  BIGINT UNSIGNED NOT NULL,
  buyer_id    BIGINT UNSIGNED NOT NULL,
  order_id    BIGINT UNSIGNED NOT NULL,
  rating      TINYINT UNSIGNED NOT NULL CHECK (rating BETWEEN 1 AND 5),
  title       VARCHAR(150)    NULL,
  body        TEXT            NULL,
  is_verified TINYINT(1)      NOT NULL DEFAULT 1,
  is_approved TINYINT(1)      NOT NULL DEFAULT 1,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  FOREIGN KEY (buyer_id)   REFERENCES users(id),
  FOREIGN KEY (order_id)   REFERENCES orders(id),
  UNIQUE KEY uq_review (product_id, buyer_id, order_id),
  INDEX idx_product (product_id),
  INDEX idx_rating  (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- WISHLISTS
-- ─────────────────────────────────────────────
CREATE TABLE wishlists (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     BIGINT UNSIGNED NOT NULL,
  product_id  BIGINT UNSIGNED NOT NULL,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)    REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
  UNIQUE KEY uq_wishlist (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ADMIN USERS (separate from public users)
-- ─────────────────────────────────────────────
CREATE TABLE admin_users (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(120)    NOT NULL,
  email         VARCHAR(191)    NOT NULL UNIQUE,
  password_hash VARCHAR(255)    NOT NULL,
  role_id       BIGINT UNSIGNED NOT NULL,
  is_active     TINYINT(1)      NOT NULL DEFAULT 1,
  last_login    DATETIME        NULL,
  created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  deleted_at    DATETIME        NULL,
  INDEX idx_role (role_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ROLES
-- ─────────────────────────────────────────────
CREATE TABLE roles (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)    NOT NULL UNIQUE,
  slug        VARCHAR(100)    NOT NULL UNIQUE,
  description TEXT            NULL,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- PERMISSIONS
-- ─────────────────────────────────────────────
CREATE TABLE permissions (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)    NOT NULL,
  slug        VARCHAR(100)    NOT NULL UNIQUE,
  group_name  VARCHAR(100)    NOT NULL,
  description VARCHAR(255)    NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- ROLE PERMISSIONS (pivot)
-- ─────────────────────────────────────────────
CREATE TABLE role_permissions (
  role_id       BIGINT UNSIGNED NOT NULL,
  permission_id BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (role_id, permission_id),
  FOREIGN KEY (role_id)       REFERENCES roles(id) ON DELETE CASCADE,
  FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add FK to admin_users after roles table exists
ALTER TABLE admin_users
  ADD CONSTRAINT fk_admin_role FOREIGN KEY (role_id) REFERENCES roles(id);

ALTER TABLE seller_profiles
  ADD CONSTRAINT fk_seller_admin FOREIGN KEY (approved_by) REFERENCES admin_users(id) ON DELETE SET NULL;

-- ─────────────────────────────────────────────
-- AUDIT LOGS
-- ─────────────────────────────────────────────
CREATE TABLE audit_logs (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id    BIGINT UNSIGNED NULL,
  action      VARCHAR(100)    NOT NULL,
  entity_type VARCHAR(100)    NULL,
  entity_id   BIGINT UNSIGNED NULL,
  old_values  JSON            NULL,
  new_values  JSON            NULL,
  ip_address  VARCHAR(45)     NULL,
  user_agent  VARCHAR(255)    NULL,
  created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_admin  (admin_id),
  INDEX idx_entity (entity_type, entity_id),
  INDEX idx_action (action)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

## 3. Seed Data (sql/seed.sql)

```sql
-- Permissions
INSERT INTO permissions (name, slug, group_name) VALUES
('Read Users',       'users.read',        'Users'),
('Create Users',     'users.create',      'Users'),
('Update Users',     'users.update',      'Users'),
('Delete Users',     'users.delete',      'Users'),
('Read Products',    'products.read',     'Products'),
('Update Products',  'products.update',   'Products'),
('Delete Products',  'products.delete',   'Products'),
('Read Orders',      'orders.read',       'Orders'),
('Update Orders',    'orders.update',     'Orders'),
('Read Categories',  'categories.read',   'Categories'),
('Create Categories','categories.create', 'Categories'),
('Update Categories','categories.update', 'Categories'),
('Delete Categories','categories.delete', 'Categories'),
('Manage Roles',     'roles.read',        'Roles'),
('Create Roles',     'roles.create',      'Roles'),
('Update Roles',     'roles.update',      'Roles'),
('Delete Roles',     'roles.delete',      'Roles'),
('Approve Sellers',  'sellers.approve',   'Sellers'),
('View Analytics',   'analytics.read',    'Analytics'),
('View Audit Log',   'audit.read',        'Audit');

-- Roles
INSERT INTO roles (name, slug, description) VALUES
('Super Admin',        'super-admin',   'Full platform access'),
('Content Moderator',  'moderator',     'Product review and seller verification'),
('Support Agent',      'support',       'Customer support access');

-- Role Permissions: Super Admin gets all
INSERT INTO role_permissions (role_id, permission_id)
SELECT 1, id FROM permissions;

-- Moderator permissions
INSERT INTO role_permissions (role_id, permission_id)
SELECT 2, id FROM permissions
WHERE slug IN ('users.read','products.read','products.update','orders.read','sellers.approve','categories.read');

-- Support permissions
INSERT INTO role_permissions (role_id, permission_id)
SELECT 3, id FROM permissions
WHERE slug IN ('users.read','orders.read','orders.update','products.read');

-- Admin users (passwords: Admin@1234, Mod@1234, Support@1234)
INSERT INTO admin_users (name, email, password_hash, role_id) VALUES
('Super Admin',    'admin@markethub.com',   '$2y$12$HASH_PLACEHOLDER_ADMIN',   1),
('Content Mod',    'mod@markethub.com',     '$2y$12$HASH_PLACEHOLDER_MOD',     2),
('Support Agent',  'support@markethub.com', '$2y$12$HASH_PLACEHOLDER_SUPPORT', 3);

-- Sample categories
INSERT INTO categories (name, slug, sort_order) VALUES
('Electronics',  'electronics',  1),
('Clothing',     'clothing',     2),
('Home & Garden','home-garden',  3),
('Books',        'books',        4),
('Sports',       'sports',       5),
('Toys',         'toys',         6);

-- Note: Passwords in seed.sql must be regenerated with password_hash()
-- Run this PHP snippet to generate: php -r "echo password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost'=>12]);"
```

---

## 4. Key Query Patterns

### Product Search (Full-Text)
```sql
SELECT p.*, pi.file_path as primary_image, sp.store_name, c.name as category_name
FROM products p
JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
JOIN seller_profiles sp ON sp.id = p.seller_id
JOIN categories c ON c.id = p.category_id
WHERE p.status = 'active'
  AND p.deleted_at IS NULL
  AND MATCH(p.name, p.description, p.short_desc) AGAINST(? IN BOOLEAN MODE)
ORDER BY p.rating DESC, p.review_count DESC
LIMIT ? OFFSET ?;
```

### Get User Permissions
```sql
SELECT p.slug FROM permissions p
JOIN role_permissions rp ON rp.permission_id = p.id
JOIN admin_users au ON au.role_id = rp.role_id
WHERE au.id = ?;
```

### Seller Dashboard Revenue
```sql
SELECT 
  DATE_FORMAT(o.created_at, '%Y-%m') as month,
  SUM(oi.subtotal) as revenue,
  COUNT(DISTINCT o.id) as orders
FROM order_items oi
JOIN orders o ON o.id = oi.order_id
WHERE oi.seller_id = ?
  AND o.status NOT IN ('cancelled','refunded')
  AND o.created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
GROUP BY month
ORDER BY month ASC;
```
