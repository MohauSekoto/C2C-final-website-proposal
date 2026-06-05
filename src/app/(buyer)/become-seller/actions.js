"use server";

import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { users, sellerProfiles } from "../../../lib/db/schema.js";
import { eq } from "drizzle-orm";

export async function becomeSeller(formData) {
  const session = await auth();
  if (!session?.user) {
    throw new Error("Unauthorized");
  }

  // Double check they aren't already a seller
  if (session.user.role === "seller") {
    return { success: true };
  }

  const storeName = formData.get("storeName");
  const storeDescription = formData.get("storeDescription");
  const locationCity = formData.get("locationCity");
  const locationProvince = formData.get("locationProvince");

  if (!storeName || !storeDescription || !locationCity || !locationProvince) {
    return { error: "All fields are required" };
  }

  try {
    // 1. Insert into sellerProfiles
    await db.insert(sellerProfiles).values({
      userId: session.user.id,
      storeName,
      storeDescription,
      locationCity,
      locationProvince,
    });

    // 2. Update user role to seller
    await db.update(users)
      .set({ role: "seller" })
      .where(eq(users.id, session.user.id));

    return { success: true };
  } catch (error) {
    console.error("Error creating seller profile:", error);
    // Handle unique constraint error if they somehow already have a profile
    if (error.code === 'ER_DUP_ENTRY') {
      await db.update(users).set({ role: "seller" }).where(eq(users.id, session.user.id));
      return { success: true };
    }
    return { error: "Failed to create seller profile. Please try again." };
  }
}
