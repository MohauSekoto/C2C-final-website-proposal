import { db } from "../index.js";
import { users } from "../schema.js";
import { eq } from "drizzle-orm";

export async function getUserByEmail(email) {
  const result = await db.select().from(users).where(eq(users.email, email)).limit(1);
  return result[0] || null;
}

export async function createUser(userData) {
  await db.insert(users).values(userData);
  return getUserByEmail(userData.email);
}
