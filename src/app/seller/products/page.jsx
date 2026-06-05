import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { products, categories } from "../../../lib/db/schema.js";
import { eq, desc } from "drizzle-orm";
import Link from "next/link";
import { Plus, Edit2, Trash2, Package, Image as ImageIcon } from "lucide-react";
import Image from "next/image";

export default async function SellerProductsPage() {
  const session = await auth();
  const userId = session.user.id;

  const sellerProducts = await db.select({
    id: products.id,
    title: products.title,
    price: products.price,
    stockQuantity: products.stockQuantity,
    status: products.status,
    images: products.images,
    category: categories.name,
    createdAt: products.createdAt
  })
  .from(products)
  .leftJoin(categories, eq(products.categoryId, categories.id))
  .where(eq(products.sellerId, userId))
  .orderBy(desc(products.createdAt));

  return (
    <div className="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-500">
      <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">Products</h1>
          <p className="text-zinc-500 dark:text-zinc-400">Manage your store listings and inventory.</p>
        </div>
        <Link 
          href="/seller/products/new" 
          className="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-sm hover:bg-indigo-700 transition-colors shadow-sm"
        >
          <Plus size={18} />
          Add Product
        </Link>
      </div>

      <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm overflow-hidden shadow-sm">
        {sellerProducts.length === 0 ? (
          <div className="flex flex-col items-center justify-center py-20 px-4">
            <div className="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mb-4">
              <Package size={24} className="text-zinc-400 dark:text-zinc-500" />
            </div>
            <h3 className="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-1">No products yet</h3>
            <p className="text-zinc-500 dark:text-zinc-400 text-center max-w-sm mb-6">
              Get started by adding your first product to your store.
            </p>
            <Link 
              href="/seller/products/new" 
              className="flex items-center gap-2 px-6 py-3 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 font-medium rounded-sm hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors"
            >
              <Plus size={18} />
              Add Product
            </Link>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-sm text-left">
              <thead className="bg-zinc-50 dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 dark:text-zinc-400 font-medium">
                <tr>
                  <th className="px-6 py-4 font-medium rounded-tl-3xl">Product</th>
                  <th className="px-6 py-4 font-medium">Category</th>
                  <th className="px-6 py-4 font-medium text-right">Price</th>
                  <th className="px-6 py-4 font-medium text-center">Stock</th>
                  <th className="px-6 py-4 font-medium text-center">Status</th>
                  <th className="px-6 py-4 font-medium text-right rounded-tr-3xl">Actions</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-zinc-100 dark:divide-zinc-800">
                {sellerProducts.map((product) => (
                  <tr key={product.id} className="hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-4">
                        <div className="relative w-12 h-12 rounded-lg bg-zinc-100 dark:bg-zinc-800 overflow-hidden flex-shrink-0 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center">
                          {product.images && product.images[0] ? (
                            <Image src={product.images[0]} alt={product.title} fill style={{objectFit: 'cover'}} unoptimized />
                          ) : (
                            <ImageIcon size={20} className="text-zinc-400" />
                          )}
                        </div>
                        <div className="font-medium text-zinc-900 dark:text-zinc-100 max-w-[200px] truncate" title={product.title}>
                          {product.title}
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 text-zinc-500 dark:text-zinc-400">
                      {product.category || "Uncategorized"}
                    </td>
                    <td className="px-6 py-4 text-right font-medium">
                      R {Number(product.price).toFixed(2)}
                    </td>
                    <td className="px-6 py-4 text-center">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                        product.stockQuantity > 10 ? 'bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400' :
                        product.stockQuantity > 0 ? 'bg-warning/10 text-warning dark:text-warning' :
                        'bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400'
                      }`}>
                        {product.stockQuantity}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-center">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize ${
                        product.status === 'active' ? 'bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400' :
                        'bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-300'
                      }`}>
                        {product.status.replace('_', ' ')}
                      </span>
                    </td>
                    <td className="px-6 py-4 text-right">
                      <div className="flex items-center justify-end gap-2">
                        <button className="p-2 text-zinc-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                          <Edit2 size={16} />
                        </button>
                        <button className="p-2 text-zinc-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                          <Trash2 size={16} />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}
