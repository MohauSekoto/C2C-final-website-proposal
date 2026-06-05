# Website Flow Document
## MarketHub — Page & User Journey Flows

**Version:** 1.0  
**Date:** 2025-05-23  

---

## 1. Main Website Flows

### 1.1 Guest / Buyer Journey

```
[Homepage]
  │
  ├──> [Browse Products] ──> [Product Detail] ──> [Add to Cart]
  │         │                                           │
  │         └──> [Search / Filter]                [Cart Page]
  │                    │                               │
  │                    └──> [Product Detail]     [Checkout]
  │                                                    │
  │                                         ┌──────────┴──────────┐
  │                                    [Guest?]              [Logged In?]
  │                                         │                     │
  │                                   [Register/Login]      [Enter Address]
  │                                         │                     │
  │                                    [Enter Address]     [Order Summary]
  │                                         │                     │
  │                                    [Order Summary]    [Place Order]
  │                                         │                     │
  │                                    [Place Order]  [Order Confirmation]
  │                                         │
  │                                  [Order Confirmation]
  │
  ├──> [Register] ──> [Email Verification] ──> [Profile / Dashboard]
  │
  └──> [Login] ──> [Profile / Dashboard]
                          │
                    ┌─────┼──────┐
                    │     │      │
              [Orders] [Wishlist] [Account Settings]
```

### 1.2 Buyer — Order History Flow

```
[Login] ──> [My Orders]
               │
               ├──> [Order Detail] ──> [Track Status]
               │
               └──> [Leave Review] (if order = Delivered)
                         │
                    [Product Review Form]
                         │
                    [Review Submitted] ──> [Product Detail updated]
```

### 1.3 Seller Registration & Onboarding Flow

```
[Homepage] ──> [Become a Seller] ──> [Seller Registration Form]
                                              │
                                  ┌───────────┴──────────┐
                               [Submit]             [Incomplete]
                                  │                     │
                         [Pending Review]         [Validation Errors]
                                  │
                     [Admin Reviews Application]
                         │               │
                    [Approve]        [Reject]
                         │               │
                 [Seller Activated]  [Email Sent]
                         │
                 [Seller Dashboard]
                     │       │       │
              [Products] [Orders] [Earnings]
```

### 1.4 Seller — Product Management Flow

```
[Seller Dashboard]
  │
  └──> [My Products]
            │
    ┌───────┼───────────┐
    │       │           │
  [Add]  [Edit]      [Delete]
    │       │           │
[Product  [Form      [Confirm]
  Form]   Prefilled]    │
    │       │       [Removed]
[Validate] [Validate]
    │       │
[Submit] [Submit]
    │       │
[Pending Admin Approval]
    │
[Admin Moderates]
    │           │
[Active]    [Rejected]
    │
[Visible in Store]
```

---

## 2. Admin Website Flows

### 2.1 Admin Login & Role Resolution

```
[Admin Login Page] ──> [Enter Email + Password]
                                │
                       [Credentials Valid?]
                         │           │
                        No          Yes
                         │           │
                  [Error Message]  [Load Role]
                                     │
                           ┌─────────┼─────────┐
                           │         │         │
                     [Super Admin] [Moderator] [Support]
                           │         │         │
                      [Full Menu] [Limited]  [Limited]
                           │
                    [Admin Dashboard]
```

### 2.2 User Management CRUD Flow (RBAC)

```
[Admin Dashboard] ──> [Users Menu] (requires: users.read)
                              │
                    ┌─────────┼─────────┐
                    │         │         │
               [Create]    [Edit]   [Delete]
          (users.create) (users.update) (users.delete)
                    │         │         │
               [User Form] [Edit Form] [Confirm Dialog]
                    │         │         │
               [Validate]  [Validate]  [Soft Delete]
                    │         │         │
               [Save User] [Update]  [Redirect to List]
                    │
             [Role Assigned]
                    │
             [User can Login]
```

### 2.3 Product Moderation Flow (Moderator role)

```
[Admin] ──> [Products] ──> [Pending Products List]
                                     │
                           [Select Product]
                                     │
                         [Product Detail View]
                                     │
                          ┌──────────┼──────────┐
                          │          │          │
                      [Approve]  [Reject]   [Remove]
                          │          │          │
                    [Status: Active] [Email Seller] [Soft Delete]
                          │
                    [Product Live in Store]
```

### 2.4 Role & Permission Management Flow (Super Admin only)

```
[Admin Dashboard] ──> [Roles & Permissions]
                               │
                    ┌──────────┼──────────┐
                    │          │          │
               [Create Role] [Edit Role] [Delete Role]
                    │          │
             [Name Role]  [Toggle Permissions]
                    │          │
             [Save Role]  [Save Role]
                    │
             [Assign to Admin User]
```

---

## 3. Page Inventory

### 3.1 Main Website Pages

| Page | URL | Auth Required | Notes |
|---|---|---|---|
| Homepage | `/` | No | Featured products, hero banner |
| Product Listing | `/products` | No | Paginated grid |
| Product Detail | `/product.php?id=X` | No | Full product info |
| Search | `/search.php?q=X` | No | Full-text search |
| Cart | `/cart.php` | No (session) | Persisted in session |
| Checkout | `/checkout.php` | Buyer | Redirect to login if guest |
| Order Confirmation | `/order-success.php?id=X` | Buyer | Post-checkout |
| Order History | `/orders.php` | Buyer | — |
| Order Detail | `/order.php?id=X` | Buyer | — |
| Register | `/register.php` | No | Redirect if logged in |
| Login | `/login.php` | No | Redirect if logged in |
| Account Settings | `/profile/account.php` | Buyer | — |
| Wishlist | `/profile/wishlist.php` | Buyer | — |
| Seller Apply | `/seller/register.php` | Buyer | Upgrade to seller |
| Seller Dashboard | `/seller/dashboard.php` | Seller | — |
| Seller Products | `/seller/products.php` | Seller | — |
| Add Product | `/seller/add-product.php` | Seller | — |
| Edit Product | `/seller/edit-product.php?id=X` | Seller | Own products only |
| Seller Orders | `/seller/orders.php` | Seller | — |
| Seller Store | `/store.php?id=X` | No | Public seller profile |

### 3.2 Admin Website Pages

| Page | URL | Permission |
|---|---|---|
| Admin Login | `/admin/login.php` | — |
| Dashboard | `/admin/` | Any admin role |
| User List | `/admin/users/` | users.read |
| Create User | `/admin/users/create.php` | users.create |
| Edit User | `/admin/users/edit.php?id=X` | users.update |
| Delete User | `/admin/users/delete.php?id=X` | users.delete |
| Role List | `/admin/roles/` | roles.read |
| Create Role | `/admin/roles/create.php` | roles.create |
| Edit Role | `/admin/roles/edit.php?id=X` | roles.update |
| Product Moderation | `/admin/products/` | products.read |
| Order Overview | `/admin/orders/` | orders.read |
| Category List | `/admin/categories/` | categories.read |
| Seller Approvals | `/admin/sellers/approvals.php` | sellers.approve |
| Analytics | `/admin/analytics/` | analytics.read |
| Audit Log | `/admin/audit/` | audit.read |

---

## 4. Navigation Structure

### 4.1 Main Site Header Nav
```
Logo | [Home] [Shop ▼] [Sellers] | [Search Bar] | [Cart 🛒] [Login] [Register]

Shop Dropdown:
  - All Products
  - [Dynamic Categories]
  - New Arrivals
  - Top Rated

Logged-in Buyer replaces Login/Register with:
  [Hi, {Name} ▼]
    - My Orders
    - Wishlist
    - Account Settings
    - Seller Dashboard (if seller)
    - Logout
```

### 4.2 Admin Sidebar Nav

```
📊 Dashboard
👥 Users ▼
   - All Users
   - Create User
🔐 Roles & Permissions ▼ (Super Admin only)
   - All Roles
   - Create Role
📦 Products ▼
   - All Products
   - Pending Approval
🛒 Orders
🗂️ Categories ▼
   - All Categories
   - Create Category
🏪 Seller Approvals
📈 Analytics
📋 Audit Log (Super Admin only)
```

---

## 5. Error & Edge Case Flows

| Scenario | Behavior |
|---|---|
| Guest tries to checkout | Redirect to login, return to checkout after |
| Buyer tries to access seller pages | Redirect to seller registration |
| Admin access forbidden page | HTTP 403 page with message |
| Product not found | HTTP 404 page |
| Out of stock item in cart | Warning shown, cannot proceed to checkout |
| Session expired | Redirect to login page |
| Failed payment (mock) | Display error, cart preserved |
