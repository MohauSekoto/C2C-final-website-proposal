import { db } from '../../../../lib/db/index.js';
import { products, users, wishlists } from '../../../../lib/db/schema.js';
import { eq, and } from 'drizzle-orm';
import Image from 'next/image';
import Link from 'next/link';
import { ChevronRight, ShieldCheck, Truck, Star } from 'lucide-react';
import AddToCartButton from './AddToCartButton.jsx';
import AddToWishlistButton from './AddToWishlistButton.jsx';
import { auth } from '../../../../auth.js';

export default async function ProductPage({ params }) {
  const { id } = await params;
  const session = await auth();
  
  // Fetch product and join seller
  const productData = await db.select({
    product: products,
    seller: {
      id: users.id,
      name: users.name
    }
  }).from(products)
    .leftJoin(users, eq(products.sellerId, users.id))
    .where(eq(products.id, id))
    .limit(1);

  if (!productData || productData.length === 0) {
    return (
      <div className="min-h-[60vh] flex items-center justify-center bg-zinc-50 dark:bg-zinc-950">
        <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Product not found</h1>
      </div>
    );
  }

  const { product, seller } = productData[0];
  const imageUrl = product.images?.[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600';

  let isWishlisted = false;
  if (session?.user) {
    const existing = await db.select().from(wishlists).where(
      and(
        eq(wishlists.userId, session.user.id),
        eq(wishlists.productId, product.id)
      )
    ).limit(1);
    isWishlisted = existing.length > 0;
  }

  return (
    <div className="bg-zinc-50 dark:bg-zinc-950 min-h-screen transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-16">
        
        {/* Breadcrumbs */}
        <nav className="flex items-center text-sm text-zinc-500 dark:text-zinc-400 mb-8 space-x-2">
          <Link href="/" className="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Home</Link>
          <ChevronRight size={14} />
          <Link href="/products" className="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Products</Link>
          <ChevronRight size={14} />
          <span className="text-zinc-900 dark:text-zinc-100 truncate">{product.title}</span>
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24">
          
          {/* Product Images */}
          <div className="space-y-4">
            <div className="relative aspect-square overflow-hidden rounded-3xl bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
              <Image 
                src={imageUrl} 
                alt={product.title} 
                fill 
                style={{ objectFit: 'cover' }} 
                unoptimized
                priority
              />
            </div>
          </div>

          {/* Product Info */}
          <div className="flex flex-col">
            <div className="mb-8">
              <h1 className="text-3xl md:text-5xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-4">
                {product.title}
              </h1>
              <div className="flex items-center gap-4 text-sm mb-6">
                <div className="flex items-center text-amber-400">
                  <Star size={16} className="fill-current" />
                  <Star size={16} className="fill-current" />
                  <Star size={16} className="fill-current" />
                  <Star size={16} className="fill-current" />
                  <Star size={16} className="text-zinc-300 dark:text-zinc-700" />
                  <span className="ml-2 text-zinc-500 dark:text-zinc-400">({product.reviewCount} reviews)</span>
                </div>
                <span className="text-zinc-300 dark:text-zinc-700">|</span>
                <span className="text-zinc-500 dark:text-zinc-400">
                  Sold by <Link href={`/seller/${seller.id}`} className="font-medium text-indigo-600 dark:text-indigo-400 hover:underline">{seller.name}</Link>
                </span>
              </div>
              <div className="text-4xl font-light text-zinc-900 dark:text-zinc-50">
                R {Number(product.price).toFixed(2)}
              </div>
            </div>

            <p className="text-zinc-500 dark:text-zinc-400 leading-relaxed mb-10 text-lg">
              {product.description}
            </p>

            <div className="mb-10 space-y-4">
              <div className="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-300">
                <Truck size={20} className="text-zinc-400 dark:text-zinc-500" />
                <span>Ships nationwide. Delivery estimated in 3-5 working days.</span>
              </div>
              <div className="flex items-center gap-3 text-sm text-zinc-600 dark:text-zinc-300">
                <ShieldCheck size={20} className="text-green-500 dark:text-green-400" />
                <span>Payment held securely in escrow until delivery is confirmed.</span>
              </div>
            </div>

            <div className="mt-auto pt-8 border-t border-zinc-200 dark:border-zinc-800 flex items-center gap-4">
              <div className="flex-1">
                <AddToCartButton product={product} />
              </div>
              <AddToWishlistButton 
                productId={product.id} 
                initialWishlisted={isWishlisted} 
                isLoggedIn={!!session?.user} 
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
