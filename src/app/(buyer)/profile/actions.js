"use server";

import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { users } from "../../../lib/db/schema.js";
import { eq } from "drizzle-orm";
import { revalidatePath } from "next/cache";

export async function updateProfile(formData) {
  const session = await auth();
  if (!session?.user) {
    throw new Error("Unauthorized");
  }

  const name = formData.get("name");
  
  // Extract shipping address
  const shippingAddress = {
    street: formData.get("street") || "",
    city: formData.get("city") || "",
    province: formData.get("province") || "",
    postalCode: formData.get("postalCode") || "",
  };

  await db.update(users)
    .set({ 
      name, 
      shippingAddress 
    })
    .where(eq(users.id, session.user.id));

  revalidatePath("/profile");
  return { success: true };
}
