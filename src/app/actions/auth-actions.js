"use server";

import bcrypt from "bcryptjs";
import { getUserByEmail, createUser } from "../../lib/db/queries/users.js";

export async function registerUser(formData) {
  const name = formData.get("name");
  const email = formData.get("email");
  const password = formData.get("password");
  const role = formData.get("role") || "buyer";

  if (!name || !email || !password) {
    return { error: "Missing required fields" };
  }

  try {
    const existingUser = await getUserByEmail(email);
    if (existingUser) {
      return { error: "User with this email already exists" };
    }

    const salt = await bcrypt.genSalt(10);
    const passwordHash = await bcrypt.hash(password, salt);

    await createUser({
      name,
      email,
      passwordHash,
      role
    });

    return { success: true };
  } catch (error) {
    console.error("Registration error:", error);
    return { error: "Failed to register user. Please try again." };
  }
}
