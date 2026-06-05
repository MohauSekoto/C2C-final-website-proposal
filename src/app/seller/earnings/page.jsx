import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { orders, sellerProfiles } from "../../../lib/db/schema.js";
import { eq, desc } from "drizzle-orm";
import StatsCard from "../../../components/seller/StatsCard.jsx";
import { Wallet, DollarSign, TrendingUp, ShieldCheck } from "lucide-react";

export default async function SellerEarningsPage() {
  const session = await auth();
  const userId = session.user.id;

  const [profile] = await db.select().from(sellerProfiles).where(eq(sellerProfiles.userId, userId));
  
  const sellerOrders = await db.select().from(orders).where(eq(orders.sellerId, userId)).orderBy(desc(orders.createdAt));

  // Calculate earnings metrics
  let totalRevenue = 0;
  let totalCommissionsPaid = 0;
  let pendingEscrow = 0;
  let availablePayout = 0;

  sellerOrders.forEach(order => {
    const subtotal = Number(order.subtotal);
    const comm = Number(order.commissionAmount);
    const net = subtotal - comm;

    totalRevenue += subtotal;
    totalCommissionsPaid += comm;

    if (order.escrowStatus === "held") {
      pendingEscrow += net;
    } else if (order.escrowStatus === "released") {
      availablePayout += net;
    }
  });

  return (
    <div className="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-500">
      <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">Earnings</h1>
          <p className="text-zinc-500 dark:text-zinc-400">Track your revenue, commissions, and escrow funds.</p>
        </div>
        <button className="px-5 py-2.5 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 font-medium rounded-sm shadow-sm opacity-50 cursor-not-allowed">
          Request Payout (R {availablePayout.toFixed(2)})
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <StatsCard 
          title="Total Sales Revenue" 
          value={`R ${totalRevenue.toFixed(2)}`} 
        />
        <StatsCard 
          title="Pending in Escrow" 
          value={`R ${pendingEscrow.toFixed(2)}`} 
        />
        <StatsCard 
          title="Available for Payout" 
          value={`R ${availablePayout.toFixed(2)}`} 
        />
        <StatsCard 
          title="Platform Fees Paid" 
          value={`R ${totalCommissionsPaid.toFixed(2)}`} 
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-2 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-8 shadow-sm">
          <h2 className="text-lg font-bold text-zinc-900 dark:text-zinc-50 mb-6">Recent Transactions</h2>
          
          {sellerOrders.length === 0 ? (
            <div className="text-center py-12 text-zinc-500 dark:text-zinc-400">
              No transactions to display.
            </div>
          ) : (
            <div className="space-y-4">
              {sellerOrders.map(order => {
                const sub = Number(order.subtotal);
                const comm = Number(order.commissionAmount);
                const net = sub - comm;

                return (
                  <div key={order.id} className="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 border border-zinc-100 dark:border-zinc-800 rounded-sm hover:bg-zinc-50 dark:hover:bg-zinc-800/50 transition-colors">
                    <div>
                      <p className="font-semibold text-zinc-900 dark:text-zinc-100">{order.orderNumber}</p>
                      <p className="text-sm text-zinc-500 dark:text-zinc-400">{new Date(order.createdAt).toLocaleDateString()}</p>
                    </div>
                    <div className="text-left sm:text-right mt-2 sm:mt-0">
                      <p className="font-semibold text-indigo-600 dark:text-indigo-400">+ R {net.toFixed(2)}</p>
                      <p className="text-xs text-zinc-500 dark:text-zinc-400">
                        {order.escrowStatus === 'held' ? 'Holding in Escrow' : 'Available'}
                      </p>
                    </div>
                  </div>
                );
              })}
            </div>
          )}
        </div>

        <div>
          <div className="bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-sm p-8">
            <h3 className="text-lg font-bold text-zinc-900 dark:text-zinc-50 mb-4">Commission Tier</h3>
            <div className="flex items-baseline gap-2 mb-2">
              <span className="text-4xl font-extrabold text-blue-600 dark:text-blue-400">
                {profile?.commissionTier === 'gold' ? '5%' : profile?.commissionTier === 'silver' ? '7.5%' : '10%'}
              </span>
              <span className="text-zinc-800 dark:text-zinc-300 font-medium capitalize">{profile?.commissionTier || 'standard'} Tier</span>
            </div>
            <p className="text-sm text-zinc-600 dark:text-zinc-400 mb-6">
              You are currently on the {profile?.commissionTier || 'standard'} tier. Increase your sales volume to drop your commission rate!
            </p>
            
            <div className="space-y-3">
              <div className="flex justify-between text-sm text-zinc-900 dark:text-zinc-100">
                <span>Next Tier: {profile?.commissionTier === 'standard' ? 'Silver (7.5%)' : profile?.commissionTier === 'silver' ? 'Gold (5%)' : 'Maxed out!'}</span>
              </div>
              <div className="w-full h-2 bg-zinc-200 dark:bg-zinc-800 rounded-full overflow-hidden">
                <div className="h-full bg-blue-600 dark:bg-blue-500 rounded-full" style={{ width: '35%' }}></div>
              </div>
              <p className="text-xs text-zinc-500 dark:text-zinc-500 text-right">R 3,450 to next tier</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
