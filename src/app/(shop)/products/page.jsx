import { db } from '../../../lib/db/index.js';
import { products, categories } from '../../../lib/db/schema.js';
import { eq, desc } from 'drizzle-orm';
import ProductCard from '../../../components/product/ProductCard.jsx';
import Link from 'next/link';
import { Filter, ChevronDown } from 'lucide-react';

export default async function ProductsPage() {
  const allProducts = await db.select().from(products).where(eq(products.status, 'active')).orderBy(desc(products.createdAt));

  return (
    <div className="bg-zinc-50 dark:bg-zinc-950 min-h-screen transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Page Header */}
        <div className="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
          <div>
            <h1 className="text-4xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">All Products</h1>
            <p className="text-zinc-500 dark:text-zinc-400 mt-2">Discover unique items from South African creators.</p>
          </div>
          
          {/* Mobile Filter Button */}
          <button className="md:hidden flex items-center justify-center gap-2 w-full py-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl font-medium text-zinc-900 dark:text-zinc-100">
            <Filter size={18} /> Filters
          </button>
        </div>

        <div className="flex flex-col md:flex-row gap-12">
          {/* Sidebar Filters */}
          <aside className="hidden md:block w-64 flex-shrink-0 space-y-10">
            <div>
              <h3 className="font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Categories</h3>
              <ul className="space-y-3">
                <li><Link href="/products" className="text-indigo-600 dark:text-indigo-400 font-medium text-sm">All Categories</Link></li>
                <li><Link href="/products?category=art" className="text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 text-sm transition-colors">Art & Collectibles</Link></li>
                <li><Link href="/products?category=fashion" className="text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 text-sm transition-colors">Fashion</Link></li>
                <li><Link href="/products?category=home" className="text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 text-sm transition-colors">Home & Living</Link></li>
              </ul>
            </div>
            
            <div>
              <h3 className="font-semibold text-zinc-900 dark:text-zinc-100 mb-4">Price Range</h3>
              <div className="flex items-center gap-2">
                <input type="number" placeholder="Min" className="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none" />
                <span className="text-zinc-400">-</span>
                <input type="number" placeholder="Max" className="w-full px-3 py-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-lg text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none" />
              </div>
            </div>
          </aside>

          {/* Product Grid */}
          <main className="flex-1">
            <div className="flex justify-between items-center mb-6">
              <span className="text-sm text-zinc-500 dark:text-zinc-400">{allProducts.length} results</span>
              <button className="flex items-center gap-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                Sort by: Recommended <ChevronDown size={16} />
              </button>
            </div>
            
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
              {allProducts.map(product => (
                <ProductCard 
                  key={product.id} 
                  id={product.id}
                  title={product.title}
                  price={product.price}
                  category="Goods" // Need to join categories table for actual name
                  imageUrl={product.images?.[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600'} 
                />
              ))}
              
              {allProducts.length === 0 && (
                <div className="col-span-full py-24 text-center border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-3xl">
                  <p className="text-zinc-500 dark:text-zinc-400 text-lg">No products found matching your criteria.</p>
                </div>
              )}
            </div>
          </main>
        </div>
      </div>
    </div>
  );
}
