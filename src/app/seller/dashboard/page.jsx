import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { sellerProfiles, products, orders } from "../../../lib/db/schema.js";
import { eq, count } from "drizzle-orm";
import StatsCard from "../../../components/seller/StatsCard.jsx";
import { DollarSign, Package, ShoppingCart, Activity } from "lucide-react";
import Link from "next/link";

export default async function SellerDashboardPage() {
  const session = await auth();
  const userId = session.user.id;

  // Fetch Seller Profile
  const [profile] = await db.select().from(sellerProfiles).where(eq(sellerProfiles.userId, userId));

  // Fetch metrics
  const activeListingsResult = await db.select({ count: count() }).from(products).where(eq(products.sellerId, userId));
  const activeListings = activeListingsResult[0]?.count || 0;

  const pendingOrdersResult = await db.select({ count: count() }).from(orders).where(eq(orders.sellerId, userId));
  const pendingOrders = pendingOrdersResult[0]?.count || 0;

  // Mock revenue for now (you would normally sum the order totals where status=paid/completed)
  const totalRevenue = profile?.totalSalesAmount || "0.00";

  return (
    <div className="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-500">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">
            Welcome back, {profile?.storeName || session.user.name}
          </h1>
          <p className="text-zinc-500 dark:text-zinc-400">
            Here's what's happening with your store today.
          </p>
        </div>
        <div className="flex gap-3">
          <Link href="/seller/products/new" className="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-sm hover:bg-indigo-700 transition-colors shadow-sm">
            Add Product
          </Link>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatsCard 
          title="Total Revenue" 
          value={`R ${Number(totalRevenue).toFixed(2)}`} 
          trend="up" 
          trendValue="12.5%" 
        />
        <StatsCard 
          title="Active Listings" 
          value={activeListings.toString()} 
          trend="up" 
          trendValue="3 new" 
        />
        <StatsCard 
          title="Pending Orders" 
          value={pendingOrders.toString()} 
        />
        <StatsCard 
          title="Commission Tier" 
          value={profile?.commissionTier ? profile.commissionTier.charAt(0).toUpperCase() + profile.commissionTier.slice(1) : "Standard"} 
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Recent Orders Overview */}
        <div className="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-8 shadow-sm">
          <div className="flex justify-between items-center mb-6">
            <h2 className="text-lg font-bold text-zinc-900 dark:text-zinc-50">Recent Orders</h2>
            <Link href="/seller/orders" className="text-sm font-medium text-indigo-600 hover:text-indigo-700">View All</Link>
          </div>
          
          {pendingOrders === 0 ? (
            <div className="text-center py-10 bg-zinc-50 dark:bg-zinc-950 rounded-sm border border-zinc-100 dark:border-zinc-800">
              <p className="text-zinc-500 dark:text-zinc-400">No recent orders to show.</p>
            </div>
          ) : (
            <div className="text-center py-10 bg-zinc-50 dark:bg-zinc-950 rounded-sm border border-zinc-100 dark:border-zinc-800">
              <p className="text-zinc-500 dark:text-zinc-400">You have {pendingOrders} order(s) to process.</p>
              <Link href="/seller/orders" className="inline-block mt-4 text-indigo-600 font-medium hover:underline">Manage Orders</Link>
            </div>
          )}
        </div>

        {/* Quick Tips or Announcements */}
        <div className="bg-zinc-900 dark:bg-zinc-800 rounded-sm p-8 text-white shadow-md">
          <h2 className="text-lg font-bold mb-4 flex items-center gap-2">
            Seller Tips
          </h2>
          <ul className="space-y-4 text-zinc-300 text-sm">
            <li className="flex gap-3">
              <span className="font-bold text-white">1.</span>
              Upload high-quality images to increase your sales by up to 30%.
            </li>
            <li className="flex gap-3">
              <span className="font-bold text-white">2.</span>
              Ship orders within 24 hours to improve your seller rating.
            </li>
            <li className="flex gap-3">
              <span className="font-bold text-white">3.</span>
              Reach Gold Tier to reduce your commission rate to 5%.
            </li>
          </ul>
        </div>
      </div>
    </div>
  );
}
