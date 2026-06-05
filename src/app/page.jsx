import ProductCard from '../components/product/ProductCard.jsx';
import CategoryCard from '../components/product/CategoryCard.jsx';
import CategoriesCarousel from '../components/home/CategoriesCarousel.jsx';
import FeaturedCarousel from '../components/home/FeaturedCarousel.jsx';
import { Sparkles, ArrowRight } from 'lucide-react';
import Link from 'next/link';
import { db } from '../lib/db/index.js';
import { products } from '../lib/db/schema.js';
import { desc } from 'drizzle-orm';

export default async function Home() {
  const dbProducts = await db.select().from(products).orderBy(desc(products.createdAt)).limit(6);

  return (
    <div className="flex flex-col">
      {/* Hero Section */}
      <section className="relative py-16 md:py-24 overflow-hidden bg-white dark:bg-zinc-950 transition-colors duration-300">
        <div className="absolute inset-0 bg-zinc-50/50 dark:bg-zinc-900/50" />
        <div className="container relative mx-auto px-4 sm:px-6 lg:px-8 text-center max-w-4xl">
          <h1 className="text-5xl md:text-7xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 mb-8 leading-tight">
            Support Local. <br/><span className="text-zinc-400 dark:text-zinc-600">Shop KasiBuy.</span>
          </h1>
          <p className="text-lg md:text-xl text-zinc-500 dark:text-zinc-400 mb-12 max-w-2xl mx-auto leading-relaxed">
            Sell or Buy from local vendors on <span className="font-bold text-zinc-900 dark:text-zinc-50">Kasi<span className="text-indigo-600 dark:text-indigo-500">Buy</span></span>
          </p>
          <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
            <Link href="/products" className="w-full sm:w-auto px-8 py-4 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 rounded-full font-medium hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors flex items-center justify-center gap-2">
              Start Exploring <ArrowRight size={18} />
            </Link>
            <Link href="/become-seller" className="w-full sm:w-auto px-8 py-4 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 border border-zinc-200 dark:border-zinc-800 rounded-full font-medium hover:border-zinc-300 dark:hover:border-zinc-700 transition-colors">
              Become a Seller
            </Link>
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16 md:py-24 bg-zinc-50 dark:bg-zinc-900/50 transition-colors duration-300">
        <div className="container mx-auto max-w-[1400px]">
          <div className="text-center mb-16 px-4">
            <h2 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">Categories</h2>
            <p className="text-zinc-500 dark:text-zinc-400 mt-4">Explore our most popular independent collections.</p>
          </div>
          
          <CategoriesCarousel />
        </div>
      </section>

      {/* Featured Products */}
      <section className="py-16 md:py-24 bg-white dark:bg-zinc-950 transition-colors duration-300">
        <div className="container mx-auto max-w-[1400px]">
          <div className="flex justify-between items-end mb-12 px-4">
            <div>
              <h2 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">Featured Goods</h2>
              <p className="text-zinc-500 dark:text-zinc-400 mt-2">Handpicked items from rising sellers.</p>
            </div>
            <Link href="/products" className="hidden sm:flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-medium hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
              View Collection <ArrowRight size={16} />
            </Link>
          </div>
          <FeaturedCarousel products={dbProducts} />
        </div>
      </section>
    </div>
  );
}
