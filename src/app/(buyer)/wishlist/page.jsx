import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { wishlists } from "../../../lib/db/schema.js";
import { eq, desc } from "drizzle-orm";
import WishlistGrid from "../../../components/buyer/WishlistGrid.jsx";

export default async function WishlistPage() {
  const session = await auth();
  
  if (!session?.user) return null;

  const items = await db.query.wishlists.findMany({
    where: eq(wishlists.userId, session.user.id),
    orderBy: [desc(wishlists.createdAt)],
    with: {
      product: true
    }
  });

  return (
    <div className="max-w-5xl">
      <div className="mb-8">
        <h1 className="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">My Wishlist</h1>
        <p className="text-zinc-500 dark:text-zinc-400 mt-1">Items you've saved for later.</p>
      </div>

      <WishlistGrid items={items} />
    </div>
  );
}
