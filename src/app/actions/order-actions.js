"use server";

import { auth } from "../../auth.js";
import { db } from "../../lib/db/index.js";
import { orders } from "../../lib/db/schema.js";
import { eq, and } from "drizzle-orm";
import { revalidatePath } from "next/cache";

export async function updateOrderStatus(formData) {
  const session = await auth();
  if (!session?.user || session.user.role !== "seller") {
    throw new Error("Unauthorized");
  }

  const orderId = formData.get("orderId");
  const newStatus = formData.get("status");

  if (!orderId || !newStatus) {
    throw new Error("Missing required fields");
  }

  // Ensure the order belongs to this seller before updating
  await db.update(orders)
    .set({ status: newStatus })
    .where(and(eq(orders.id, orderId), eq(orders.sellerId, session.user.id)));

  revalidatePath("/seller/orders");
  revalidatePath("/seller/dashboard");
}
