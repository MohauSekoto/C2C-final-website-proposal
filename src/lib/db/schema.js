import {
  mysqlTable,
  varchar,
  int,
  timestamp,
  boolean,
  text,
  decimal,
  json,
  mysqlEnum,
  uniqueIndex,
} from "drizzle-orm/mysql-core";
import { relations } from "drizzle-orm";

export const users = mysqlTable("users", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  email: varchar("email", { length: 255 }).notNull().unique(),
  passwordHash: varchar("password_hash", { length: 255 }).notNull(),
  name: varchar("name", { length: 255 }).notNull(),
  role: mysqlEnum("role", ["buyer", "seller", "admin"]).notNull().default("buyer"),
  avatarUrl: varchar("avatar_url", { length: 255 }),
  emailVerified: boolean("email_verified").notNull().default(false),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
  shippingAddress: json("shipping_address"),
});

export const sellerProfiles = mysqlTable("seller_profiles", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  userId: varchar("user_id", { length: 36 })
    .notNull()
    .unique()
    .references(() => users.id, { onDelete: "cascade" }),
  storeName: varchar("store_name", { length: 255 }).notNull(),
  storeDescription: text("store_description"),
  locationCity: varchar("location_city", { length: 100 }),
  locationProvince: mysqlEnum("location_province", [
    "Eastern Cape",
    "Free State",
    "Gauteng",
    "KwaZulu-Natal",
    "Limpopo",
    "Mpumalanga",
    "Northern Cape",
    "North West",
    "Western Cape",
  ]),
  commissionTier: mysqlEnum("commission_tier", ["standard", "silver", "gold", "platinum"])
    .notNull()
    .default("standard"),
  totalSalesAmount: decimal("total_sales_amount", { precision: 12, scale: 2 })
    .notNull()
    .default("0.00"),
  isVerified: boolean("is_verified").notNull().default(false),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
});

export const categories = mysqlTable("categories", {
  id: int("id").primaryKey().autoincrement(),
  name: varchar("name", { length: 100 }).notNull(),
  slug: varchar("slug", { length: 100 }).notNull().unique(),
  description: text("description"),
  iconUrl: varchar("icon_url", { length: 255 }),
  parentId: int("parent_id"), // Self-referencing FK handled below
  sortOrder: int("sort_order").notNull().default(0),
});

export const products = mysqlTable("products", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  sellerId: varchar("seller_id", { length: 36 })
    .notNull()
    .references(() => users.id, { onDelete: "cascade" }),
  categoryId: int("category_id")
    .notNull()
    .references(() => categories.id),
  title: varchar("title", { length: 255 }).notNull(),
  slug: varchar("slug", { length: 255 }).notNull().unique(),
  description: text("description").notNull(),
  price: decimal("price", { precision: 10, scale: 2 }).notNull(),
  compareAtPrice: decimal("compare_at_price", { precision: 10, scale: 2 }),
  stockQuantity: int("stock_quantity").notNull().default(0),
  weightKg: decimal("weight_kg", { precision: 8, scale: 2 }),
  dimensionsCm: json("dimensions_cm"), // { l: number, w: number, h: number }
  status: mysqlEnum("status", ["draft", "active", "paused", "sold_out", "removed"])
    .notNull()
    .default("draft"),
  images: json("images").notNull(), // JSON array of URLs
  tags: json("tags"), // JSON array
  avgRating: decimal("avg_rating", { precision: 3, scale: 2 }).notNull().default("0.00"),
  reviewCount: int("review_count").notNull().default(0),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
});

export const orders = mysqlTable("orders", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  orderNumber: varchar("order_number", { length: 20 }).notNull().unique(), // KB-XXXXXX
  buyerId: varchar("buyer_id", { length: 36 })
    .notNull()
    .references(() => users.id),
  sellerId: varchar("seller_id", { length: 36 })
    .notNull()
    .references(() => users.id),
  status: mysqlEnum("status", [
    "pending_payment",
    "paid",
    "processing",
    "shipped",
    "delivered",
    "completed",
    "cancelled",
    "refund_requested",
    "refunded",
  ])
    .notNull()
    .default("pending_payment"),
  subtotal: decimal("subtotal", { precision: 12, scale: 2 }).notNull(),
  shippingCost: decimal("shipping_cost", { precision: 10, scale: 2 }).notNull().default("0.00"),
  commissionAmount: decimal("commission_amount", { precision: 10, scale: 2 }).notNull(),
  commissionRate: decimal("commission_rate", { precision: 5, scale: 2 }).notNull(),
  total: decimal("total", { precision: 12, scale: 2 }).notNull(),
  shippingAddress: json("shipping_address").notNull(),
  trackingNumber: varchar("tracking_number", { length: 100 }),
  paymentReference: varchar("payment_reference", { length: 100 }),
  escrowStatus: mysqlEnum("escrow_status", ["held", "released", "refunded"]).notNull().default("held"),
  paidAt: timestamp("paid_at"),
  shippedAt: timestamp("shipped_at"),
  deliveredAt: timestamp("delivered_at"),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
});

export const orderItems = mysqlTable("order_items", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  orderId: varchar("order_id", { length: 36 })
    .notNull()
    .references(() => orders.id, { onDelete: "cascade" }),
  productId: varchar("product_id", { length: 36 })
    .notNull()
    .references(() => products.id),
  quantity: int("quantity").notNull(),
  unitPrice: decimal("unit_price", { precision: 10, scale: 2 }).notNull(),
  totalPrice: decimal("total_price", { precision: 12, scale: 2 }).notNull(),
  createdAt: timestamp("created_at").notNull().defaultNow(),
});

export const reviews = mysqlTable("reviews", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  productId: varchar("product_id", { length: 36 })
    .notNull()
    .references(() => products.id, { onDelete: "cascade" }),
  buyerId: varchar("buyer_id", { length: 36 })
    .notNull()
    .references(() => users.id),
  orderId: varchar("order_id", { length: 36 })
    .notNull()
    .references(() => orders.id),
  rating: int("rating").notNull(), // 1-5
  title: varchar("title", { length: 255 }),
  comment: text("comment").notNull(),
  isVerifiedPurchase: boolean("is_verified_purchase").notNull().default(true),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
});

export const wishlists = mysqlTable(
  "wishlists",
  {
    id: varchar("id", { length: 36 })
      .primaryKey()
      .$defaultFn(() => crypto.randomUUID()),
    userId: varchar("user_id", { length: 36 })
      .notNull()
      .references(() => users.id, { onDelete: "cascade" }),
    productId: varchar("product_id", { length: 36 })
      .notNull()
      .references(() => products.id, { onDelete: "cascade" }),
    createdAt: timestamp("created_at").notNull().defaultNow(),
  },
  (t) => ({
    userProductUniqueIndex: uniqueIndex("user_product_idx").on(t.userId, t.productId),
  })
);

export const payments = mysqlTable("payments", {
  id: varchar("id", { length: 36 })
    .primaryKey()
    .$defaultFn(() => crypto.randomUUID()),
  orderId: varchar("order_id", { length: 36 })
    .notNull()
    .unique()
    .references(() => orders.id, { onDelete: "cascade" }),
  paymentMethod: mysqlEnum("payment_method", ["card", "eft", "snapscan", "mock"]).notNull(),
  amount: decimal("amount", { precision: 12, scale: 2 }).notNull(),
  currency: varchar("currency", { length: 3 }).notNull().default("ZAR"),
  status: mysqlEnum("status", ["pending", "completed", "failed", "refunded"])
    .notNull()
    .default("pending"),
  gatewayReference: varchar("gateway_reference", { length: 255 }),
  gatewayResponse: json("gateway_response"),
  createdAt: timestamp("created_at").notNull().defaultNow(),
  updatedAt: timestamp("updated_at").notNull().defaultNow().onUpdateNow(),
});

// Relations
export const usersRelations = relations(users, ({ one, many }) => ({
  sellerProfile: one(sellerProfiles, {
    fields: [users.id],
    references: [sellerProfiles.userId],
  }),
  products: many(products),
  ordersAsBuyer: many(orders, { relationName: "buyer_orders" }),
  ordersAsSeller: many(orders, { relationName: "seller_orders" }),
  reviews: many(reviews),
  wishlists: many(wishlists),
}));

export const categoriesRelations = relations(categories, ({ one, many }) => ({
  parent: one(categories, {
    fields: [categories.parentId],
    references: [categories.id],
    relationName: "parent_category",
  }),
  subCategories: many(categories, { relationName: "parent_category" }),
  products: many(products),
}));

export const productsRelations = relations(products, ({ one, many }) => ({
  seller: one(users, {
    fields: [products.sellerId],
    references: [users.id],
  }),
  category: one(categories, {
    fields: [products.categoryId],
    references: [categories.id],
  }),
  orderItems: many(orderItems),
  reviews: many(reviews),
  wishlists: many(wishlists),
}));

export const ordersRelations = relations(orders, ({ one, many }) => ({
  buyer: one(users, {
    fields: [orders.buyerId],
    references: [users.id],
    relationName: "buyer_orders",
  }),
  seller: one(users, {
    fields: [orders.sellerId],
    references: [users.id],
    relationName: "seller_orders",
  }),
  items: many(orderItems),
  payment: one(payments, {
    fields: [orders.id],
    references: [payments.orderId],
  }),
  reviews: many(reviews),
}));

export const orderItemsRelations = relations(orderItems, ({ one }) => ({
  order: one(orders, {
    fields: [orderItems.orderId],
    references: [orders.id],
  }),
  product: one(products, {
    fields: [orderItems.productId],
    references: [products.id],
  }),
}));

export const reviewsRelations = relations(reviews, ({ one }) => ({
  product: one(products, {
    fields: [reviews.productId],
    references: [products.id],
  }),
  buyer: one(users, {
    fields: [reviews.buyerId],
    references: [users.id],
  }),
  order: one(orders, {
    fields: [reviews.orderId],
    references: [orders.id],
  }),
}));

export const wishlistsRelations = relations(wishlists, ({ one }) => ({
  user: one(users, {
    fields: [wishlists.userId],
    references: [users.id],
  }),
  product: one(products, {
    fields: [wishlists.productId],
    references: [products.id],
  }),
}));

export const paymentsRelations = relations(payments, ({ one }) => ({
  order: one(orders, {
    fields: [payments.orderId],
    references: [orders.id],
  }),
}));
