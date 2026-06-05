import dotenv from "dotenv";
dotenv.config({ path: ".env.local" });
import { db } from "./src/lib/db/index.js";
import { users } from "./src/lib/db/schema.js";
import { eq } from "drizzle-orm";
import bcrypt from "bcryptjs";

async function run() {
  const password = "password123";
  const salt = await bcrypt.genSalt(10);
  const passwordHash = await bcrypt.hash(password, salt);

  // Update Admin
  await db.update(users)
    .set({ passwordHash })
    .where(eq(users.email, "admin@kasibuy.co.za"));

  // Update Seller
  await db.update(users)
    .set({ passwordHash })
    .where(eq(users.email, "mabuza@example.com"));

  console.log("Accounts updated with valid password hashes.");
  process.exit(0);
}

run().catch(console.error);
