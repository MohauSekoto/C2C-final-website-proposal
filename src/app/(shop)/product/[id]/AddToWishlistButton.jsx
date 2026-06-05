"use client";

import { useState } from "react";
import { Heart } from "lucide-react";
import { toggleWishlist } from "../../../(buyer)/wishlist/actions.js";
import { useRouter } from "next/navigation";

export default function AddToWishlistButton({ productId, initialWishlisted = false, isLoggedIn = false }) {
  const [isWishlisted, setIsWishlisted] = useState(initialWishlisted);
  const [isLoading, setIsLoading] = useState(false);
  const router = useRouter();

  const handleToggle = async () => {
    if (!isLoggedIn) {
      router.push("/login");
      return;
    }

    setIsLoading(true);
    try {
      const result = await toggleWishlist(productId);
      setIsWishlisted(result.added);
    } catch (error) {
      console.error(error);
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <button
      onClick={handleToggle}
      disabled={isLoading}
      className={`p-3 rounded-2xl border transition-all ${
        isWishlisted
          ? "bg-rose-50 border-rose-200 text-rose-500 hover:bg-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20 dark:text-rose-400 dark:hover:bg-rose-500/20"
          : "bg-white border-zinc-200 text-zinc-500 hover:bg-zinc-50 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-800"
      }`}
      title={isWishlisted ? "Remove from wishlist" : "Add to wishlist"}
    >
      <Heart size={24} className={isWishlisted ? "fill-current" : ""} />
    </button>
  );
}
