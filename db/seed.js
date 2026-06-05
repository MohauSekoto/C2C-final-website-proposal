import { db } from "../src/lib/db/index.js";
import { users, sellerProfiles, categories, products, orders, orderItems, reviews, payments } from "../src/lib/db/schema.js";
import crypto from "crypto";
import dotenv from "dotenv";
dotenv.config({ path: ".env.local" });

// We use native crypto to generate UUIDs
const randomUUID = () => crypto.randomUUID();

async function main() {
  console.log("Seeding database...");

  // 1. Create Users
  const adminId = randomUUID();
  const buyer1Id = randomUUID();
  const buyer2Id = randomUUID();
  const seller1Id = randomUUID();
  const seller2Id = randomUUID();
  const seller3Id = randomUUID();

  await db.insert(users).values([
    { id: adminId, email: "admin@kasibuy.co.za", name: "System Admin", passwordHash: "hashed_pass", role: "admin", emailVerified: true },
    { id: buyer1Id, email: "john@example.com", name: "John Doe", passwordHash: "hashed_pass", role: "buyer", emailVerified: true },
    { id: buyer2Id, email: "sarah@example.com", name: "Sarah Smith", passwordHash: "hashed_pass", role: "buyer", emailVerified: true },
    { id: seller1Id, email: "mabuza@example.com", name: "Thabo Mabuza", passwordHash: "hashed_pass", role: "seller", emailVerified: true },
    { id: seller2Id, email: "naidoo@example.com", name: "Priya Naidoo", passwordHash: "hashed_pass", role: "seller", emailVerified: true },
    { id: seller3Id, email: "vanschalkwyk@example.com", name: "Johan van Schalkwyk", passwordHash: "hashed_pass", role: "seller", emailVerified: true },
  ]);
  console.log("✅ Users seeded");

  // 2. Create Seller Profiles
  await db.insert(sellerProfiles).values([
    {
      userId: seller1Id,
      storeName: "Mabuza Crafts",
      storeDescription: "Authentic handmade crafts from Soweto.",
      locationCity: "Soweto",
      locationProvince: "Gauteng",
      commissionTier: "standard",
      isVerified: true,
    },
    {
      userId: seller2Id,
      storeName: "Durban Spices",
      storeDescription: "The finest spices and curries from KZN.",
      locationCity: "Durban",
      locationProvince: "KwaZulu-Natal",
      commissionTier: "silver",
      isVerified: true,
    },
    {
      userId: seller3Id,
      storeName: "Cape Leatherworks",
      storeDescription: "Premium handcrafted leather goods.",
      locationCity: "Cape Town",
      locationProvince: "Western Cape",
      commissionTier: "gold",
      isVerified: true,
    },
  ]);
  console.log("✅ Seller Profiles seeded");

  // 3. Create Categories
  await db.insert(categories).values([
    { id: 1, name: "Fashion & Apparel", slug: "fashion", description: "Clothing, shoes, and accessories", sortOrder: 1 },
    { id: 2, name: "Home & Garden", slug: "home", description: "Furniture, decor, and gardening", sortOrder: 2 },
    { id: 3, name: "Electronics", slug: "electronics", description: "Gadgets and devices", sortOrder: 3 },
    { id: 4, name: "Art & Crafts", slug: "crafts", description: "Handmade local art", sortOrder: 4 },
    { id: 5, name: "Food & Spices", slug: "food", description: "Local ingredients and snacks", sortOrder: 5 },
    { id: 6, name: "Health & Beauty", slug: "beauty", description: "Cosmetics and personal care", sortOrder: 6 },
    { id: 7, name: "Toys & Games", slug: "toys", description: "Kids toys and board games", sortOrder: 7 },
    { id: 8, name: "Books", slug: "books", description: "Local literature and textbooks", sortOrder: 8 },
  ]);
  console.log("✅ Categories seeded");

  // 4. Create Products
  const sampleProducts = [];
  const categoriesIds = [1, 2, 3, 4, 5, 6, 7, 8];
  const sellerIds = [seller1Id, seller2Id, seller3Id];

  for (let i = 1; i <= 30; i++) {
    const price = (Math.random() * 900 + 100).toFixed(2);
    sampleProducts.push({
      id: randomUUID(),
      sellerId: sellerIds[i % 3],
      categoryId: categoriesIds[i % 8],
      title: `Sample Product ${i}`,
      slug: `sample-product-${i}`,
      description: `This is a detailed description for Sample Product ${i}. Highly recommended for buyers seeking quality local goods.`,
      price,
      stockQuantity: Math.floor(Math.random() * 50) + 1,
      weightKg: (Math.random() * 5 + 0.5).toFixed(2),
      status: "active",
      images: ["/uploads/placeholder.jpg"],
      avgRating: (Math.random() * 2 + 3).toFixed(2),
      reviewCount: Math.floor(Math.random() * 20),
    });
  }

  await db.insert(products).values(sampleProducts);
  console.log("✅ 30 Products seeded");

  // 5. Create Orders (just a few samples)
  const order1Id = randomUUID();
  await db.insert(orders).values([
    {
      id: order1Id,
      orderNumber: "KB-100001",
      buyerId: buyer1Id,
      sellerId: seller1Id,
      status: "paid",
      subtotal: "450.00",
      shippingCost: "99.00",
      commissionAmount: "45.00",
      commissionRate: "10.00",
      total: "549.00",
      shippingAddress: { street: "123 Main St", city: "Pretoria", zip: "0001" },
      escrowStatus: "held",
    },
  ]);

  await db.insert(orderItems).values([
    {
      id: randomUUID(),
      orderId: order1Id,
      productId: sampleProducts[0].id,
      quantity: 1,
      unitPrice: "450.00",
      totalPrice: "450.00",
    },
  ]);
  
  await db.insert(payments).values([
    {
      id: randomUUID(),
      orderId: order1Id,
      paymentMethod: "mock",
      amount: "549.00",
      currency: "ZAR",
      status: "completed",
    },
  ]);
  
  console.log("✅ Orders seeded");
  console.log("🎉 Seeding complete!");
  process.exit(0);
}

main().catch((err) => {
  console.error("Seeding failed:", err);
  process.exit(1);
});
