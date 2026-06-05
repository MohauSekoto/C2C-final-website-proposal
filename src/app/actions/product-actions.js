"use server";

import { auth } from "../../auth.js";
import { db } from "../../lib/db/index.js";
import { products } from "../../lib/db/schema.js";
import crypto from "crypto";
import { revalidatePath } from "next/cache";
import { redirect } from "next/navigation";

export async function addProduct(formData) {
  const session = await auth();
  if (!session?.user || session.user.role !== "seller") {
    throw new Error("Unauthorized");
  }

  const title = formData.get("title");
  const description = formData.get("description");
  const price = formData.get("price");
  const stockQuantity = parseInt(formData.get("stockQuantity"), 10);
  const categoryId = parseInt(formData.get("categoryId"), 10);
  const weightKg = formData.get("weightKg") || "1.00";
  
  // For the MVP, we will just use a placeholder image if none is provided.
  // In a full implementation, you would upload the file to an S3 bucket or local dir.
  const imageUrl = formData.get("imageUrl") || "/images/placeholder.jpg";

  // Basic validation
  if (!title || !price || isNaN(stockQuantity) || isNaN(categoryId)) {
    throw new Error("Missing required fields");
  }

  // Create slug from title
  const slug = title.toLowerCase().replace(/[^a-z0-9]+/g, '-') + '-' + crypto.randomBytes(4).toString('hex');

  await db.insert(products).values({
    id: crypto.randomUUID(),
    sellerId: session.user.id,
    categoryId,
    title,
    slug,
    description,
    price,
    stockQuantity,
    weightKg,
    status: "active",
    images: [imageUrl]
  });

  revalidatePath("/seller/products");
  revalidatePath("/seller/dashboard");
  redirect("/seller/products");
}
