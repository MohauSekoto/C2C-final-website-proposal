import * as dotenv from "dotenv";
import { drizzle } from "drizzle-orm/mysql2";
import { eq } from "drizzle-orm";
import mysql from "mysql2/promise";
import * as schema from "../src/lib/db/schema.js";
import bcrypt from "bcryptjs";

dotenv.config({ path: ".env.local" });

const poolConnection = mysql.createPool(process.env.DATABASE_URL);
const db = drizzle(poolConnection, { schema, mode: "default" });

async function seed() {
  console.log("🌱 Starting seed process...");

  // Seed Categories
  const categoriesData = [
    { name: "Electronics", slug: "electronics", description: "Gadgets and devices", sortOrder: 1 },
    { name: "Handmade Crafts", slug: "handmade-crafts", description: "Local handmade goods", sortOrder: 2 },
    { name: "Fashion", slug: "fashion", description: "Clothing and apparel", sortOrder: 3 },
  ];

  await db.insert(schema.categories).values(categoriesData).onDuplicateKeyUpdate({ set: { id: schema.categories.id } });
  console.log("✅ Categories seeded");

  // Fetch created categories
  const categories = await db.select().from(schema.categories);

  // Seed Admin User
  const adminHash = await bcrypt.hash("Admin@1234", 10);
  const adminEmail = "admin@kasibuy.com";
  
  let [admin] = await db.select().from(schema.users).where(eq(schema.users.email, adminEmail));
  if (!admin) {
    await db.insert(schema.users).values({
      email: adminEmail,
      passwordHash: adminHash,
      name: "Super Admin",
      role: "admin",
      emailVerified: true
    });
    console.log("✅ Admin user seeded");
  } else {
    console.log("✅ Admin user already exists");
  }

  // Seed Mock Seller
  const sellerHash = await bcrypt.hash("Seller@1234", 10);
  const sellerEmail = "seller@kasibuy.com";
  
  let [seller] = await db.select().from(schema.users).where(eq(schema.users.email, sellerEmail));
  if (!seller) {
    await db.insert(schema.users).values({
      email: sellerEmail,
      passwordHash: sellerHash,
      name: "Jane Doe",
      role: "seller",
      emailVerified: true
    });
    console.log("✅ Mock seller seeded");
    [seller] = await db.select().from(schema.users).where(eq(schema.users.email, sellerEmail));
  } else {
    console.log("✅ Mock seller already exists");
  }

  const [sellerProfile] = await db.select().from(schema.sellerProfiles).where(eq(schema.sellerProfiles.userId, seller.id));
  if (!sellerProfile) {
    await db.insert(schema.sellerProfiles).values({
      userId: seller.id,
      storeName: "Jane's Handcrafts",
      storeDescription: "Beautiful authentic South African crafts.",
      locationCity: "Cape Town",
      locationProvince: "Western Cape",
      commissionTier: "standard",
      isVerified: true
    });
    console.log("✅ Mock seller profile seeded");
  }

  // Seed mock products
  const productsList = await db.select().from(schema.products).where(eq(schema.products.sellerId, seller.id));
  if (productsList.length === 0) {
    await db.insert(schema.products).values([
      {
        sellerId: seller.id,
        categoryId: categories.find(c => c.slug === "handmade-crafts").id,
        title: "Beaded Zulu Necklace",
        slug: "beaded-zulu-necklace",
        description: "Handcrafted traditional Zulu beadwork.",
        price: "250.00",
        stockQuantity: 15,
        status: "active",
        images: ["/images/placeholder.jpg"]
      },
      {
        sellerId: seller.id,
        categoryId: categories.find(c => c.slug === "handmade-crafts").id,
        title: "Woven Basket",
        slug: "woven-basket",
        description: "Intricately woven basket from local grass.",
        price: "450.00",
        stockQuantity: 5,
        status: "active",
        images: ["/images/placeholder.jpg"]
      }
    ]);
    console.log("✅ Mock products seeded");
  } else {
    console.log("✅ Mock products already exist");
  }

  console.log("✅ Seed complete");
  process.exit(0);
}

seed().catch((e) => {
  console.error("Seed failed:", e);
  process.exit(1);
});
