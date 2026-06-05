"use client";

import { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { ShoppingCart, Trash2, Loader2, PackageOpen, Heart } from "lucide-react";
import { useCart } from "../../context/CartContext.jsx";
import { removeFromWishlist, clearWishlist } from "../../app/(buyer)/wishlist/actions.js";

export default function WishlistGrid({ items }) {
  const { addItem } = useCart();
  const [isProcessing, setIsProcessing] = useState(false);
  const [loadingItems, setLoadingItems] = useState(new Set());

  const handleAddToCart = (product) => {
    addItem({
      id: product.id,
      title: product.title,
      price: product.price,
      image: product.images?.[0] || "/images/placeholder.png",
      sellerId: product.sellerId,
      quantity: 1,
    });
  };

  const handleRemove = async (productId) => {
    setLoadingItems((prev) => new Set(prev).add(productId));
    try {
      await removeFromWishlist(productId);
    } catch (error) {
      console.error(error);
    } finally {
      setLoadingItems((prev) => {
        const newSet = new Set(prev);
        newSet.delete(productId);
        return newSet;
      });
    }
  };

  const handleMoveAllToCart = async () => {
    setIsProcessing(true);
    try {
      // Add all to cart
      items.forEach(({ product }) => {
        handleAddToCart(product);
      });
      // Clear wishlist in DB
      await clearWishlist();
    } catch (error) {
      console.error(error);
    } finally {
      setIsProcessing(false);
    }
  };

  if (items.length === 0) {
    return (
      <div className="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl">
        <div className="h-20 w-20 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-500 rounded-full flex items-center justify-center mb-6">
          <Heart size={32} />
        </div>
        <h2 className="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">Your wishlist is empty</h2>
        <p className="text-zinc-500 dark:text-zinc-400 mb-8 max-w-sm text-center">
          Save items you love to your wishlist and move them to your cart when you're ready to buy.
        </p>
        <Link 
          href="/products" 
          className="px-6 py-3 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-medium rounded-xl hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors"
        >
          Explore Products
        </Link>
      </div>
    );
  }

  return (
    <div>
      <div className="flex justify-between items-center mb-6">
        <p className="text-zinc-500 dark:text-zinc-400">
          You have <span className="font-semibold text-zinc-900 dark:text-zinc-100">{items.length}</span> items saved
        </p>
        <button
          onClick={handleMoveAllToCart}
          disabled={isProcessing}
          className="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-colors disabled:opacity-70"
        >
          {isProcessing ? <Loader2 size={18} className="animate-spin" /> : <ShoppingCart size={18} />}
          Move All to Cart
        </button>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {items.map(({ id, product }) => (
          <div key={id} className="group flex flex-col bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition-all">
            <div className="relative aspect-square bg-zinc-50 dark:bg-zinc-800">
              {product.images?.[0] ? (
                <Image
                  src={product.images[0]}
                  alt={product.title}
                  fill
                  className="object-cover"
                />
              ) : (
                <div className="absolute inset-0 flex items-center justify-center text-zinc-300 dark:text-zinc-600">
                  <PackageOpen size={48} />
                </div>
              )}
              <div className="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <button
                  onClick={() => handleRemove(product.id)}
                  disabled={loadingItems.has(product.id)}
                  className="p-2 bg-white/90 dark:bg-zinc-900/90 hover:bg-red-50 hover:text-red-600 text-zinc-500 rounded-full backdrop-blur-sm shadow-sm transition-colors"
                  title="Remove from wishlist"
                >
                  {loadingItems.has(product.id) ? <Loader2 size={18} className="animate-spin" /> : <Trash2 size={18} />}
                </button>
              </div>
            </div>
            
            <div className="p-5 flex flex-col flex-1">
              <Link href={`/product/${product.slug}`} className="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <h3 className="font-semibold text-zinc-900 dark:text-zinc-50 line-clamp-1 mb-1">{product.title}</h3>
              </Link>
              <div className="flex items-end justify-between mt-auto pt-4">
                <p className="font-bold text-lg text-zinc-900 dark:text-zinc-100">
                  R {Number(product.price).toFixed(2)}
                </p>
                <button
                  onClick={() => {
                    handleAddToCart(product);
                    handleRemove(product.id);
                  }}
                  disabled={loadingItems.has(product.id)}
                  className="px-4 py-2 bg-zinc-100 dark:bg-zinc-800 hover:bg-zinc-200 dark:hover:bg-zinc-700 text-zinc-900 dark:text-zinc-100 text-sm font-medium rounded-xl transition-colors"
                >
                  Move to Cart
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
