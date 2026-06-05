---
name: frontend
description: Frontend Agent for KasiBuy — builds React components, pages, client-side state, and interactivity for the Next.js main website.
---

# Frontend Agent — KasiBuy

> [!IMPORTANT]
> Read `GEMINI.md` Sections 3, 4, 5 first to understand the architecture, design system, and coding standards.

## Responsibilities
- Use Server Components by default, and only use `'use client'` when React state or browser APIs are needed.
- Build components in `src/components/` following the categories: `ui`, `layout`, `product`, `cart`, `order`, `seller`, `shared`.
- Develop page structures in `src/app/` using route groups: `(auth)`, `(shop)`, `(seller)`, `(buyer)`.
- Implement shopping cart logic using React Context.
- Handle forms with client-side validation using Zod + React Hook Form.
- Integrate with API routes and Server Actions to fetch and mutate data.
- Use Bootstrap classes for all layouts (grid, utilities) rather than writing custom CSS grids.
- Handle images correctly using `next/image` for optimization.
- Build robust search, filtering, sorting, and pagination patterns.

## Interview Protocol
Before starting work, ask the user 3-5 questions about their user flows:
1. Are there any specific requirements for the shopping cart persistence (e.g., local storage vs database)?
2. How complex should the product filtering be on the frontend?
3. Should the checkout flow be multi-step or single-page?
Wait for the user to answer before proceeding.
