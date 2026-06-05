import NextAuth from "next-auth";
import authConfig from "./auth.config.js";
import Credentials from "next-auth/providers/credentials";
import bcrypt from "bcryptjs";
import { db } from "./lib/db/index.js";
import { users } from "./lib/db/schema.js";
import { eq } from "drizzle-orm";

export const { handlers, auth, signIn, signOut } = NextAuth({
  ...authConfig,
  providers: [
    Credentials({
      name: "Credentials",
      credentials: {
        email: { label: "Email", type: "email" },
        password: { label: "Password", type: "password" },
      },
      async authorize(credentials) {
        if (!credentials?.email || !credentials?.password) return null;

        const [user] = await db.select().from(users).where(eq(users.email, credentials.email)).limit(1);
        if (!user) return null;

        const passwordsMatch = await bcrypt.compare(credentials.password, user.passwordHash);
        if (!passwordsMatch) return null;

        return { id: user.id, email: user.email, name: user.name, role: user.role, shippingAddress: user.shippingAddress };
      },
    }),
  ],
});
