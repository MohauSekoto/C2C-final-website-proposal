import dotenv from "dotenv";
dotenv.config({ path: ".env.local" });
import { db } from "./src/lib/db/index.js";
import { users } from "./src/lib/db/schema.js";
import bcrypt from "bcryptjs";
import crypto from "crypto";

async function run() {
  const password = "password123";
  const salt = await bcrypt.genSalt(10);
  const passwordHash = await bcrypt.hash(password, salt);

  await db.insert(users).values({
    id: crypto.randomUUID(),
    email: "admin@examp.com",
    name: "Admin User",
    role: "admin",
    passwordHash: passwordHash,
    emailVerified: true
  });

  console.log("Admin account admin@examp.com created successfully.");
  process.exit(0);
}

run().catch(console.error);
