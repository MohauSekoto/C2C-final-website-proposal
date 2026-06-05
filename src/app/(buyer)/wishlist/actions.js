"use server";

import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { wishlists } from "../../../lib/db/schema.js";
import { eq, and } from "drizzle-orm";
import { revalidatePath } from "next/cache";

export async function removeFromWishlist(productId) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  await db.delete(wishlists)
    .where(
      and(
        eq(wishlists.userId, session.user.id),
        eq(wishlists.productId, productId)
      )
    );

  revalidatePath("/wishlist");
  revalidatePath(`/product/${productId}`);
  return { success: true };
}

export async function toggleWishlist(productId) {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  const existing = await db.select().from(wishlists).where(
    and(
      eq(wishlists.userId, session.user.id),
      eq(wishlists.productId, productId)
    )
  ).limit(1);

  if (existing.length > 0) {
    await db.delete(wishlists).where(
      and(
        eq(wishlists.userId, session.user.id),
        eq(wishlists.productId, productId)
      )
    );
    revalidatePath("/wishlist");
    revalidatePath(`/product/${productId}`);
    return { added: false };
  } else {
    await db.insert(wishlists).values({
      userId: session.user.id,
      productId: productId,
    });
    revalidatePath("/wishlist");
    revalidatePath(`/product/${productId}`);
    return { added: true };
  }
}

export async function clearWishlist() {
  const session = await auth();
  if (!session?.user) throw new Error("Unauthorized");

  await db.delete(wishlists)
    .where(eq(wishlists.userId, session.user.id));

  revalidatePath("/wishlist");
  return { success: true };
}
