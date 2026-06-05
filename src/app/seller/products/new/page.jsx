import { auth } from "../../../../auth.js";
import { db } from "../../../../lib/db/index.js";
import { categories } from "../../../../lib/db/schema.js";
import { addProduct } from "../../../actions/product-actions.js";
import Link from "next/link";
import { ArrowLeft, Save } from "lucide-react";

export default async function NewProductPage() {
  const session = await auth();
  const cats = await db.select().from(categories).orderBy(categories.name);

  return (
    <div className="max-w-4xl mx-auto space-y-8 animate-in fade-in duration-500">
      <div className="flex items-center gap-4 mb-8">
        <Link 
          href="/seller/products" 
          className="p-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-50 transition-colors shadow-sm"
        >
          <ArrowLeft size={20} />
        </Link>
        <div>
          <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50">Add New Product</h1>
          <p className="text-sm text-zinc-500 dark:text-zinc-400">Create a new listing for your store.</p>
        </div>
      </div>

      <form action={addProduct} className="space-y-6">
        <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-6 md:p-8 shadow-sm">
          <h2 className="text-lg font-semibold mb-6">Basic Information</h2>
          <div className="space-y-5">
            <div>
              <label htmlFor="title" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Product Title</label>
              <input 
                type="text" 
                id="title" 
                name="title" 
                required 
                className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                placeholder="e.g. Handmade Ceramic Mug"
              />
            </div>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label htmlFor="price" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Price (ZAR)</label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <span className="text-zinc-500 sm:text-sm">R</span>
                  </div>
                  <input 
                    type="number" 
                    step="0.01"
                    id="price" 
                    name="price" 
                    required 
                    className="block w-full pl-8 pr-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                    placeholder="0.00"
                  />
                </div>
              </div>
              
              <div>
                <label htmlFor="stockQuantity" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Stock Quantity</label>
                <input 
                  type="number" 
                  id="stockQuantity" 
                  name="stockQuantity" 
                  required 
                  min="0"
                  className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                  placeholder="e.g. 10"
                />
              </div>
            </div>

            <div>
              <label htmlFor="categoryId" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Category</label>
              <select 
                id="categoryId" 
                name="categoryId" 
                required 
                className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all appearance-none"
              >
                <option value="">Select a category...</option>
                {cats.map((cat) => (
                  <option key={cat.id} value={cat.id}>{cat.name}</option>
                ))}
              </select>
            </div>
            
            <div>
              <label htmlFor="description" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Description</label>
              <textarea 
                id="description" 
                name="description" 
                rows="4"
                className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all resize-y"
                placeholder="Describe your product in detail..."
              ></textarea>
            </div>
          </div>
        </div>

        <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-6 md:p-8 shadow-sm">
          <h2 className="text-lg font-semibold mb-6">Shipping & Media</h2>
          <div className="space-y-5">
            <div>
              <label htmlFor="weightKg" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Weight (kg) - Used for shipping calc</label>
              <input 
                type="number" 
                step="0.01"
                id="weightKg" 
                name="weightKg" 
                defaultValue="1.00"
                className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
              />
            </div>
            
            <div>
              <label htmlFor="imageUrl" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Image URL (Optional for MVP)</label>
              <input 
                type="url" 
                id="imageUrl" 
                name="imageUrl" 
                className="block w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 focus:bg-white dark:focus:bg-zinc-900 focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                placeholder="https://example.com/image.jpg"
              />
              <p className="mt-2 text-xs text-zinc-500">Leave blank to use a default placeholder image.</p>
            </div>
          </div>
        </div>

        <div className="flex justify-end gap-4">
          <Link 
            href="/seller/products" 
            className="px-6 py-3 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm font-medium hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
          >
            Cancel
          </Link>
          <button 
            type="submit" 
            className="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white rounded-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm"
          >
            <Save size={18} />
            Publish Product
          </button>
        </div>
      </form>
    </div>
  );
}
