# Product Requirements Document (PRD)
## MarketHub — Multi-Vendor E-Commerce Platform

**Version:** 1.0  
**Date:** 2025-05-23  
**Status:** Approved  
**Owner:** Product Team  

---

## 1. Executive Summary

Kis a full-featured, multi-vendor e-commerce platform enabling individuals and businesses to buy and sell goods online. It consists of two surfaces:

1. **Main Website** — Public-facing storefront where customers browse, buy, and sellers list products.
2. **Admin Website** — Internal management dashboard with Role-Based Access Control (RBAC) for platform administrators.

The platform is built with PHP, MySQL, HTML, CSS, JavaScript (jQuery), and Bootstrap. It must be hosted on a live server accessible via the public internet.

---

## 2. Background & Problem Statement

### Interview Findings

During stakeholder interviews, the following pain points and goals were identified:

| Stakeholder | Pain Point | Goal |
|---|---|---|
| Buyers | Difficulty finding trusted sellers in one place | One-stop marketplace with reviews |
| Sellers | No affordable platform to list products | Low-barrier seller onboarding |
| Admin | Manual user management is error-prone | Role-based automated controls |
| Platform Owner | No visibility into platform health | Central analytics dashboard |

### Interview Questions Asked
1. Who are your primary users and what do they need most?
2. What actions must buyers be able to perform without an account?
3. What is the seller verification process?
4. What admin roles are needed and what are their permissions?
5. What reporting and analytics are required?
6. What are the non-functional requirements (performance, security, uptime)?

---

## 3. Goals & Objectives

### Primary Goals
- Enable buyers to discover, evaluate, and purchase products safely.
- Enable sellers to list, manage, and fulfill product orders.
- Provide admins with tools to manage users, products, and orders.
- Enforce RBAC across all admin functions.

### Success Metrics
| Metric | Target |
|---|---|
| Page load time | < 2 seconds |
| Seller onboarding completion rate | > 80% |
| Admin task completion (CRUD) | 100% functional |
| Uptime | 99.5% |

---

## 4. User Personas

### 4.1 Buyer (Guest & Registered)
- **Description:** Individual consumer shopping for goods.
- **Needs:** Search products, view details, add to cart, checkout, track orders, leave reviews.
- **Frustrations:** Complicated checkout, lack of trust signals, no order history.

### 4.2 Seller
- **Description:** Individual or small business listing products for sale.
- **Needs:** Easy product listing, inventory tracking, order management, earnings view.
- **Frustrations:** High fees, complex dashboards, delayed payouts.

### 4.3 Super Admin
- **Description:** Platform owner / technical lead.
- **Permissions:** Full CRUD on all entities including users, roles, products, orders, categories.

### 4.4 Content Moderator
- **Description:** Staff responsible for product review and seller verification.
- **Permissions:** Read all, update product status, approve/reject sellers.

### 4.5 Support Agent
- **Description:** Customer service representative.
- **Permissions:** Read orders, buyers, sellers; update order status; cannot delete.

---

## 5. Feature Requirements

### 5.1 Main Website — Buyer Features

| ID | Feature | Priority | Description |
|---|---|---|---|
| B-01 | Product Browse | Must Have | Grid/list view of all active products |
| B-02 | Search & Filter | Must Have | Search by keyword, filter by category, price, rating |
| B-03 | Product Detail | Must Have | Images, description, price, seller info, reviews |
| B-04 | User Registration | Must Have | Email + password with email verification |
| B-05 | User Login | Must Have | Session-based authentication |
| B-06 | Shopping Cart | Must Have | Add, remove, update quantities |
| B-07 | Checkout | Must Have | Shipping address, order summary, payment (mock) |
| B-08 | Order History | Must Have | List of past orders with status |
| B-09 | Product Reviews | Should Have | Star rating + text review for purchased items |
| B-10 | Wishlist | Could Have | Save products for later |

### 5.2 Main Website — Seller Features

| ID | Feature | Priority | Description |
|---|---|---|---|
| S-01 | Seller Registration | Must Have | Apply to become a seller with business details |
| S-02 | Seller Dashboard | Must Have | Overview of sales, products, orders |
| S-03 | Product Management | Must Have | Create, edit, delete, activate/deactivate products |
| S-04 | Inventory Management | Must Have | Stock levels, low-stock alerts |
| S-05 | Order Management | Must Have | View orders, update fulfillment status |
| S-06 | Earnings Report | Should Have | Revenue breakdown by period |
| S-07 | Store Profile | Should Have | Public seller page with bio and ratings |

### 5.3 Admin Website

| ID | Feature | Priority | Description |
|---|---|---|---|
| A-01 | Admin Login | Must Have | Separate login page with role resolution |
| A-02 | User Management | Must Have | CRUD for buyers, sellers, admins |
| A-03 | Role Management | Must Have | Create/assign roles and permissions |
| A-04 | Product Moderation | Must Have | Approve, reject, or remove products |
| A-05 | Order Overview | Must Have | All platform orders with filter/export |
| A-06 | Category Management | Must Have | CRUD for product categories |
| A-07 | Analytics Dashboard | Should Have | Revenue, GMV, user growth charts |
| A-08 | Seller Approval | Must Have | Review and approve/reject seller applications |
| A-09 | Audit Log | Should Have | Log of all admin actions with timestamps |

---

## 6. Non-Functional Requirements

| Category | Requirement |
|---|---|
| Performance | Pages load under 2s on standard broadband |
| Security | Passwords hashed (bcrypt), SQL injection prevention (PDO), XSS protection |
| Scalability | Database indexed for query performance |
| Accessibility | WCAG 2.1 AA compliance |
| Hosting | Live public URL; PHP 8.x + MySQL 8.x |
| Browser Support | Chrome, Firefox, Edge, Safari (latest 2 versions) |

---

## 7. Constraints & Assumptions

- No CMS tools (WordPress, Wix) may be used.
- Bootstrap is the only permitted CSS framework.
- Payment is mocked (no real payment gateway integration required for prototype).
- Email verification can use PHP mail() or SMTP.
- The platform does not require mobile native apps; responsive web is sufficient.

---

## 8. Out of Scope (v1.0)

- Real payment processing (Stripe, PayPal)
- Native mobile apps
- Multi-language / i18n
- AI-powered recommendations
- Shipping carrier API integration
