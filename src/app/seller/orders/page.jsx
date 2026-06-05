import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { orders, users } from "../../../lib/db/schema.js";
import { eq, desc } from "drizzle-orm";
import { updateOrderStatus } from "../../actions/order-actions.js";
import { PackageOpen, MapPin, Calendar, CreditCard } from "lucide-react";

export default async function SellerOrdersPage() {
  const session = await auth();
  const userId = session.user.id;

  const sellerOrders = await db.select({
    id: orders.id,
    orderNumber: orders.orderNumber,
    status: orders.status,
    total: orders.total,
    shippingAddress: orders.shippingAddress,
    createdAt: orders.createdAt,
    buyerName: users.name,
    buyerEmail: users.email
  })
  .from(orders)
  .leftJoin(users, eq(orders.buyerId, users.id))
  .where(eq(orders.sellerId, userId))
  .orderBy(desc(orders.createdAt));

  const statusColors = {
    pending_payment: "bg-warning/10 text-warning dark:text-warning",
    paid: "bg-blue-100 text-blue-800 dark:bg-blue-500/10 dark:text-blue-400",
    processing: "bg-indigo-100 text-indigo-800 dark:bg-indigo-500/10 dark:text-indigo-400",
    shipped: "bg-purple-100 text-purple-800 dark:bg-purple-500/10 dark:text-purple-400",
    delivered: "bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400",
    completed: "bg-green-100 text-green-800 dark:bg-green-500/10 dark:text-green-400",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-500/10 dark:text-red-400"
  };

  return (
    <div className="max-w-6xl mx-auto space-y-8 animate-in fade-in duration-500">
      <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">Orders</h1>
          <p className="text-zinc-500 dark:text-zinc-400">View and manage orders from your customers.</p>
        </div>
      </div>

      <div className="space-y-6">
        {sellerOrders.length === 0 ? (
          <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-12 text-center shadow-sm">
            <div className="w-16 h-16 bg-zinc-100 dark:bg-zinc-800 rounded-full flex items-center justify-center mx-auto mb-4">
              <PackageOpen size={24} className="text-zinc-400 dark:text-zinc-500" />
            </div>
            <h3 className="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-1">No orders yet</h3>
            <p className="text-zinc-500 dark:text-zinc-400 max-w-sm mx-auto">
              When customers purchase your products, their orders will appear here.
            </p>
          </div>
        ) : (
          sellerOrders.map((order) => (
            <div key={order.id} className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-sm p-6 md:p-8 shadow-sm">
              <div className="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-zinc-100 dark:border-zinc-800">
                <div>
                  <div className="flex items-center gap-3 mb-2">
                    <h2 className="text-lg font-bold text-zinc-900 dark:text-zinc-50">{order.orderNumber}</h2>
                    <span className={`px-2.5 py-1 rounded-full text-xs font-semibold uppercase tracking-wider ${statusColors[order.status] || statusColors.pending_payment}`}>
                      {order.status.replace('_', ' ')}
                    </span>
                  </div>
                  <div className="flex flex-wrap gap-4 text-sm text-zinc-500 dark:text-zinc-400">
                    <span className="flex items-center gap-1.5"><Calendar size={14} /> {new Date(order.createdAt).toLocaleDateString()}</span>
                    <span className="flex items-center gap-1.5"><CreditCard size={14} /> R {Number(order.total).toFixed(2)}</span>
                  </div>
                </div>

                <div className="flex items-center gap-4">
                  {order.status !== 'delivered' && order.status !== 'completed' && order.status !== 'cancelled' && (
                    <form action={updateOrderStatus} className="flex items-center gap-3">
                      <input type="hidden" name="orderId" value={order.id} />
                      <select 
                        name="status" 
                        defaultValue={order.status}
                        className="bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none"
                      >
                        <option value="pending_payment" disabled>Pending Payment</option>
                        <option value="paid">Paid</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                      </select>
                      <button type="submit" className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Update
                      </button>
                    </form>
                  )}
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6">
                <div>
                  <h4 className="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-3 flex items-center gap-2">
                    <MapPin size={16} className="text-zinc-400" /> Shipping Details
                  </h4>
                  <div className="bg-zinc-50 dark:bg-zinc-950 rounded-sm p-4 text-sm text-zinc-600 dark:text-zinc-400">
                    <p className="font-medium text-zinc-900 dark:text-zinc-100 mb-1">{order.buyerName}</p>
                    <p>{order.buyerEmail}</p>
                    <div className="mt-2 pt-2 border-t border-zinc-200 dark:border-zinc-800">
                      {order.shippingAddress ? (
                        <>
                          <p>{order.shippingAddress.street}</p>
                          <p>{order.shippingAddress.city}, {order.shippingAddress.zip}</p>
                        </>
                      ) : (
                        <p className="text-zinc-400 italic">No shipping address provided.</p>
                      )}
                    </div>
                  </div>
                </div>
                
                <div>
                  <h4 className="text-sm font-semibold text-zinc-900 dark:text-zinc-50 mb-3 flex items-center gap-2">
                    <CreditCard size={16} className="text-zinc-400" /> Payment & Escrow
                  </h4>
                  <div className="bg-zinc-50 dark:bg-zinc-950 rounded-sm p-4 text-sm text-zinc-600 dark:text-zinc-400">
                    <div className="flex justify-between mb-2">
                      <span>Total Paid:</span>
                      <span className="font-medium text-zinc-900 dark:text-zinc-100">R {Number(order.total).toFixed(2)}</span>
                    </div>
                    <div className="flex justify-between">
                      <span>Escrow Status:</span>
                      <span className="font-medium text-amber-600 dark:text-amber-500">Held (Pending Delivery)</span>
                    </div>
                    <p className="mt-3 text-xs text-zinc-500 border-t border-zinc-200 dark:border-zinc-800 pt-3">
                      Funds will be released to your earnings account once the buyer confirms delivery.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
}
