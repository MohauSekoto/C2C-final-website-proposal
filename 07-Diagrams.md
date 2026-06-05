# MarketHub — Required Diagrams
## CRC Cards | EERD | Context Diagram | DFD | Use Case Diagram | Database Schema

---

## 1. Class Responsibility Collaborator (CRC) Cards

---

### CRC Card: User

| **Class:** User | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Store user credentials (email, password hash) | Database |
| Authenticate login | AuthService |
| Maintain session state | Session |
| Track account status (active/suspended) | AdminUser |
| Hold shipping addresses | Address |
| Place orders | Order |
| Write product reviews | Review |
| Manage wishlist | Wishlist |
| Apply to become a seller | SellerProfile |

---

### CRC Card: SellerProfile

| **Class:** SellerProfile | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Extend User with seller identity | User |
| Store store name, description, logo | Database |
| Track approval status (pending/active/rejected) | AdminUser |
| List and manage products | Product |
| View orders containing own products | OrderItem |
| Display public store page | Controller |
| Calculate and expose seller rating | Review |
| Report earnings by period | Order |

---

### CRC Card: Product

| **Class:** Product | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Store product attributes (name, price, description) | Database |
| Maintain stock quantity | SellerProfile |
| Hold multiple images | ProductImage |
| Belong to a category | Category |
| Track status (pending/active/inactive/rejected) | AdminUser |
| Support full-text search | SearchService |
| Aggregate rating from reviews | Review |
| Appear in orders | OrderItem |

---

### CRC Card: Order

| **Class:** Order | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Generate unique order number | System |
| Belong to a buyer | User |
| Reference shipping address | Address |
| Contain one or more order items | OrderItem |
| Calculate totals (subtotal, fees, total) | OrderItem |
| Track fulfillment status | OrderStatusHistory |
| Track payment status | PaymentService |
| Trigger stock decrement on placement | Product |

---

### CRC Card: OrderItem

| **Class:** OrderItem | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Snapshot product name and price at purchase | Product |
| Store quantity and calculated subtotal | Order |
| Reference originating seller | SellerProfile |
| Enable per-seller order views | SellerProfile |

---

### CRC Card: AdminUser

| **Class:** AdminUser | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Store admin credentials | Database |
| Hold assigned role | Role |
| Authenticate admin login | AdminAuthService |
| Perform permitted CRUD actions | RBACService |
| Log all admin actions | AuditLog |
| Approve or reject seller applications | SellerProfile |
| Moderate products (approve/reject) | Product |

---

### CRC Card: Role

| **Class:** Role | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Define a named role (e.g. Super Admin) | Database |
| Associate with a set of permissions | Permission |
| Be assigned to admin users | AdminUser |
| Determine UI menu visibility | AdminUser |

---

### CRC Card: Permission

| **Class:** Permission | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Define a granular action (e.g. users.delete) | Database |
| Belong to a named group (Users, Products, etc.) | Role |
| Be checked at runtime for access control | RBACService |

---

### CRC Card: Category

| **Class:** Category | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Define product classification hierarchy | Database |
| Support parent-child nesting | Category (self) |
| Filter products in browse/search | Product |
| Be managed by admins via CRUD | AdminUser |

---

### CRC Card: Review

| **Class:** Review | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Store star rating and text from verified buyer | User |
| Be associated with a specific order item | Order |
| Belong to a product | Product |
| Update product aggregate rating | Product |

---

### CRC Card: AuditLog

| **Class:** AuditLog | |
|---|---|
| **Responsibilities** | **Collaborators** |
| Record admin action (create/update/delete) | AdminUser |
| Capture entity type and ID affected | Any entity |
| Store old and new values as JSON | Database |
| Record IP address and timestamp | System |

---

## 2. Enhanced Entity Relationship Diagram (EERD)

```
Specialization/Generalization:
  USER (supertype)
    ├── BUYER (subtype: role = 'buyer')
    └── SELLER (subtype: role = 'seller') ──has──> SELLER_PROFILE

────────────────────────────────────────────────────────────────

ENTITIES AND RELATIONSHIPS:

┌─────────────────────────────────────────────────────────────────┐
│                          USER                                    │
│  PK: id | name | email | password_hash | role | status          │
│  email_verified | avatar | created_at | deleted_at              │
└──────────────────────────────┬──────────────────────────────────┘
         │ 1                   │ 1                 │ 1
         │ has                 │ places            │ creates
         │ N                   │ N                 │ N
    ┌────┴────┐         ┌──────┴──────┐     ┌─────┴─────┐
    │ADDRESS  │         │   ORDER     │     │  REVIEW   │
    │PK: id   │         │PK: id       │     │PK: id     │
    │user_id  │         │order_number │     │product_id │
    │full_name│         │buyer_id  FK │     │buyer_id FK│
    │line1    │         │address_id FK│     │order_id FK│
    │city     │         │subtotal     │     │rating     │
    │province │         │total        │     │body       │
    │postal   │         │status       │     └─────┬─────┘
    └─────────┘         │payment_status     writes │ N
                        └──────┬──────┘           │ 1
                               │ 1          ┌──────┴──────┐
                               │ contains   │   PRODUCT   │
                               │ N          │  (see below)│
                        ┌──────┴──────┐     └─────────────┘
                        │ ORDER_ITEM  │
                        │PK: id       │
                        │order_id  FK │
                        │product_id FK│
                        │seller_id FK │
                        │product_name │  ← denormalized snapshot
                        │unit_price   │
                        │quantity     │
                        │subtotal     │
                        └─────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                      SELLER_PROFILE                              │
│  PK: id | user_id FK | store_name | store_slug | status         │
│  description | logo | contact_email | rating | approved_by FK   │
└──────────────────────────────┬──────────────────────────────────┘
                               │ 1
                               │ lists
                               │ N
┌──────────────────────────────┴──────────────────────────────────┐
│                          PRODUCT                                 │
│  PK: id | seller_id FK | category_id FK | name | slug           │
│  description | price | sale_price | sku | stock_qty             │
│  status | is_featured | rating | review_count | moderated_by FK │
└───────────┬────────────────────────────────────────────────────-┘
            │                  │ 1                 │ 1
            │ 1                │ belongs to        │ has
            │ has              │ 1                 │ N
            │ N                │            ┌──────┴──────┐
    ┌────────┴───────┐ ┌───────┴───────┐    │ PRODUCT_    │
    │PRODUCT_IMAGE   │ │  CATEGORY     │    │ IMAGE       │
    │PK: id          │ │  PK: id       │    └─────────────┘
    │product_id FK   │ │  name | slug  │
    │file_path       │ │  parent_id FK │ ← self-referencing
    │is_primary      │ │  (hierarchy)  │
    │sort_order      │ └───────────────┘
    └────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                        ADMIN_USER                                │
│  PK: id | name | email | password_hash | role_id FK             │
│  is_active | last_login | deleted_at                            │
└──────────────────────────────┬──────────────────────────────────┘
                               │ N
                               │ assigned
                               │ 1
                    ┌──────────┴──────────┐
                    │       ROLE          │
                    │  PK: id | name      │
                    │  slug | description │
                    └──────────┬──────────┘
                               │ N
                               │ has (M:N via pivot)
                               │ N
                    ┌──────────┴──────────┐
                    │    PERMISSION       │
                    │  PK: id | name      │
                    │  slug | group_name  │
                    └─────────────────────┘
                    [via ROLE_PERMISSIONS pivot: role_id, permission_id]

┌─────────────────────────────────────────────────────────────────┐
│                        AUDIT_LOG                                 │
│  PK: id | admin_id FK | action | entity_type | entity_id        │
│  old_values (JSON) | new_values (JSON) | ip_address | created_at│
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│                         WISHLIST                                 │
│  PK: id | user_id FK | product_id FK | created_at               │
│  [UNIQUE: user_id + product_id]                                 │
└─────────────────────────────────────────────────────────────────┘

EERD CONSTRAINTS:
- USER is a disjoint specialization: role ∈ {buyer, seller}
- SELLER must have exactly one SELLER_PROFILE
- CATEGORY supports recursive self-referencing (parent_id)
- REVIEW requires a completed ORDER (constraint: order item must exist)
- ROLE_PERMISSIONS: M:N relationship resolved via pivot table
- All soft-deleted entities retain FK integrity (deleted_at IS NOT NULL)
```

---

## 3. Context Diagram (Level 0 DFD)

```
                    ┌───────────────────────────────┐
                    │                               │
  [BUYER] ─────────►│                               │────────► [BUYER]
  Browse, Search,   │                               │  Order confirmation,
  Add to Cart,      │         MARKETHUB             │  Order history,
  Checkout, Review  │      E-COMMERCE SYSTEM        │  Receipts
                    │                               │
  [SELLER] ────────►│                               │────────► [SELLER]
  List products,    │                               │  Sales data,
  Manage inventory, │                               │  Order alerts,
  Update orders     │                               │  Earnings report
                    │                               │
  [ADMIN] ─────────►│                               │────────► [ADMIN]
  Manage users,     │                               │  Reports, Audit logs,
  Approve sellers,  │                               │  Platform analytics
  Moderate products │                               │
                    │                               │
  [EMAIL SERVICE] ──►│                               │────────► [EMAIL SERVICE]
  Delivery status   │                               │  Transactional emails
                    │                               │  (verification, orders,
                    └───────────────────────────────┘   approvals)

External Entities:
  • BUYER — Individual consumer using the main website
  • SELLER — Registered vendor managing a store
  • ADMIN — Platform administrator using the admin website
  • EMAIL SERVICE — SMTP provider for transactional emails
```

---

## 4. Data Flow Diagram (DFD)

### Level 1 — Main System Processes

```
                                    ┌─────────────┐
  BUYER ──[browse request]────────►│ 1.0         │──[product data]──► BUYER
                                    │ PRODUCT     │
  BUYER ──[search query]──────────►│ CATALOG &   │◄──────────────── [D1] PRODUCTS
                                    │ SEARCH      │
  BUYER ──[add to cart]───────────►│             │──[cart updated]──► BUYER
                                    └─────────────┘

                                    ┌─────────────┐
  BUYER ──[checkout data]─────────►│ 2.0         │──[order confirm]─► BUYER
           (address, items)         │ ORDER       │
                                    │ PROCESSING  │──[order record]──► [D2] ORDERS
  BUYER ──[payment info]───────────►│             │
                                    │             │──[stock update]──► [D1] PRODUCTS
                                    │             │
                                    │             │──[seller alert]──► SELLER
                                    └─────────────┘

                                    ┌─────────────┐
  SELLER ──[product data]─────────►│ 3.0         │──[validation OK]─► SELLER
           (name, price, images)    │ PRODUCT     │
                                    │ MANAGEMENT  │──[product saved]─► [D1] PRODUCTS
                                    │             │
  SELLER ──[inventory update]──────►│             │──[pending notice]─► ADMIN
                                    └─────────────┘

                                    ┌─────────────┐
  SELLER ──[order action]──────────►│ 4.0         │──[status update]─► [D2] ORDERS
           (mark as shipped)        │ ORDER       │
                                    │ FULFILMENT  │──[buyer notify]──► BUYER
  ADMIN ──[order update]───────────►│             │
                                    └─────────────┘

                                    ┌─────────────┐
  ADMIN ──[user data]──────────────►│ 5.0         │──[user record]───► [D3] USERS
           (create/edit/delete)     │ USER        │
                                    │ MANAGEMENT  │──[role assigned]─► [D4] ROLES
  ADMIN ──[role assignment]─────────►│             │
                                    │             │──[audit entry]───► [D5] AUDIT
                                    └─────────────┘

                                    ┌─────────────┐
  ADMIN ──[approval decision]───────►│ 6.0         │──[status update]─► [D6] SELLERS
           (approve/reject)         │ SELLER      │
                                    │ APPROVAL    │──[email sent]────► EMAIL SVC
  ADMIN ──[product moderation]──────►│             │──[product status]► [D1] PRODUCTS
                                    └─────────────┘

                                    ┌─────────────┐
  ADMIN ──[login credentials]───────►│ 7.0         │──[role loaded]───► ADMIN
                                    │ ADMIN AUTH  │
                                    │ & RBAC      │──[permission]────► [D4] ROLES
                                    │             │──[audit entry]───► [D5] AUDIT
                                    └─────────────┘

DATA STORES:
  [D1] PRODUCTS     — products, product_images tables
  [D2] ORDERS       — orders, order_items, order_status_history tables
  [D3] USERS        — users, addresses tables
  [D4] ROLES        — roles, permissions, role_permissions, admin_users tables
  [D5] AUDIT        — audit_logs table
  [D6] SELLERS      — seller_profiles table
```

---

## 5. Use Case Diagram

```
╔═══════════════════════════════════════════════════════════════════╗
║                    MARKETHUB SYSTEM                               ║
║                                                                   ║
║  ┌─────────────────────────────────────────────┐                  ║
║  │           MAIN WEBSITE                      │                  ║
║  │                                             │                  ║
║  │   (Browse Products)      ◄──────────────────┼── «Guest»       ║
║  │   (Search Products)      ◄──────────────────┼── «Guest»       ║
║  │   (View Product Detail)  ◄──────────────────┼── «Guest»       ║
║  │   (Register Account)     ◄──────────────────┼── «Guest»       ║
║  │   (Login)                ◄──────────────────┼── «Guest»       ║
║  │                                             │                  ║
║  │   (Add to Cart)          ◄──────────────────┼── Buyer         ║
║  │   (Checkout)             ◄──────────────────┼── Buyer         ║
║  │   (View Order History)   ◄──────────────────┼── Buyer         ║
║  │   (Write Review)         ◄──────────────────┼── Buyer         ║
║  │   (Manage Wishlist)      ◄──────────────────┼── Buyer         ║
║  │   (Edit Account)         ◄──────────────────┼── Buyer         ║
║  │   (Apply as Seller)      ◄──────────────────┼── Buyer         ║
║  │                                             │                  ║
║  │   (Seller Dashboard)     ◄──────────────────┼── Seller        ║
║  │   (List Product)         ◄──────────────────┼── Seller        ║
║  │   (Edit Product)         ◄──────────────────┼── Seller        ║
║  │   (Manage Inventory)     ◄──────────────────┼── Seller        ║
║  │   (View Seller Orders)   ◄──────────────────┼── Seller        ║
║  │   (View Earnings)        ◄──────────────────┼── Seller        ║
║  └─────────────────────────────────────────────┘                  ║
║                                                                   ║
║  ┌─────────────────────────────────────────────┐                  ║
║  │           ADMIN WEBSITE                     │                  ║
║  │                                             │                  ║
║  │   (Admin Login)          ◄──────────────────┼── Any Admin     ║
║  │   (View Dashboard)       ◄──────────────────┼── Any Admin     ║
║  │                                             │                  ║
║  │   (View Users)           ◄──────────────────┼── Moderator     ║
║  │   (View Users)           ◄──────────────────┼── Support       ║
║  │   (Create User)          ◄──────────────────┼── Super Admin   ║
║  │   (Edit User)            ◄──────────────────┼── Super Admin   ║
║  │   (Delete User)          ◄──────────────────┼── Super Admin   ║
║  │                                             │                  ║
║  │   (Manage Roles)         ◄──────────────────┼── Super Admin   ║
║  │   (Assign Permissions)   ◄──────────────────┼── Super Admin   ║
║  │                                             │                  ║
║  │   (Moderate Products)    ◄──────────────────┼── Moderator     ║
║  │   (Approve/Reject Seller)◄──────────────────┼── Moderator     ║
║  │   (Manage Categories)    ◄──────────────────┼── Super Admin   ║
║  │                                             │                  ║
║  │   (View All Orders)      ◄──────────────────┼── Support       ║
║  │   (Update Order Status)  ◄──────────────────┼── Support       ║
║  │                                             │                  ║
║  │   (View Analytics)       ◄──────────────────┼── Super Admin   ║
║  │   (View Audit Log)       ◄──────────────────┼── Super Admin   ║
║  └─────────────────────────────────────────────┘                  ║
╚═══════════════════════════════════════════════════════════════════╝

ACTORS:
  Guest        — Unauthenticated visitor
  Buyer        — Registered customer (extends Guest)
  Seller       — Approved vendor (extends Buyer)
  Support      — Admin role: customer service
  Moderator    — Admin role: content review (extends Support)
  Super Admin  — Admin role: full access (extends Moderator)

INCLUDE RELATIONSHIPS:
  (Checkout) «include» (Login)
  (List Product) «include» (Login as Seller)
  (Admin Actions) «include» (Admin Login)
  (Admin Actions) «include» (Check Permission)

EXTEND RELATIONSHIPS:
  (Checkout) «extend» (Apply Promo Code)
  (View Product) «extend» (Add to Wishlist)
  (Manage Products) «extend» (Upload Images)
```

---

## 6. Database Design Summary

Full DDL is in `05-BackendSchema.md` and `sql/schema.sql`.

### Table Summary

| Table | Purpose | Key Columns |
|---|---|---|
| `users` | Platform buyers and sellers | id, email, role, status, deleted_at |
| `addresses` | Shipping addresses for users | user_id FK, city, province, is_default |
| `seller_profiles` | Seller store data | user_id FK, store_name, status, approved_by FK |
| `categories` | Product taxonomy (hierarchical) | name, slug, parent_id FK (self) |
| `products` | Product listings | seller_id FK, category_id FK, price, stock_qty, status |
| `product_images` | Product photos | product_id FK, file_path, is_primary |
| `orders` | Purchase orders | buyer_id FK, total, status, payment_status |
| `order_items` | Line items within orders | order_id FK, product_id FK, unit_price (snapshot) |
| `order_status_history` | Order status audit trail | order_id FK, old_status, new_status |
| `reviews` | Product ratings and text reviews | product_id FK, buyer_id FK, rating (1–5) |
| `wishlists` | Saved products per buyer | user_id FK, product_id FK (unique pair) |
| `admin_users` | Admin panel accounts | email, role_id FK, is_active |
| `roles` | Admin role definitions | name, slug |
| `permissions` | Granular action rights | name, slug, group_name |
| `role_permissions` | M:N pivot: roles ↔ permissions | role_id FK, permission_id FK |
| `audit_logs` | Admin action log | admin_id FK, action, entity_type, old_values JSON |

### Indexing Strategy

| Table | Indexed Columns | Reason |
|---|---|---|
| `users` | email, role, status | Login lookup, role filter, status filter |
| `products` | seller_id, category_id, status, price | Browse, filter, moderation |
| `products` | FULLTEXT(name, description, short_desc) | Search |
| `orders` | buyer_id, status, order_number | Buyer history, status filter, lookup |
| `order_items` | order_id, seller_id | Join performance |
| `reviews` | product_id, rating | Rating aggregate |
| `audit_logs` | admin_id, entity_type, action | Audit queries |

### Normalization
- Schema is **3NF** throughout
- `order_items.product_name` and `unit_price` are intentional denormalization (snapshot) to preserve order history after product edits/deletion
- `products.rating` and `review_count` are maintained denormalized for query performance; recalculated on each new review via UPDATE trigger or application logic
