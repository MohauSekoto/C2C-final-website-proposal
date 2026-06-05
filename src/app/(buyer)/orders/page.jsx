import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { orders, orderItems } from "../../../lib/db/schema.js";
import { eq, desc } from "drizzle-orm";
import Link from "next/link";
import { Package, Truck, CheckCircle2, Clock, AlertCircle } from "lucide-react";

const getStatusDetails = (status) => {
  switch (status) {
    case "pending_payment":
      return { label: "Pending Payment", color: "bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400", icon: Clock, progress: 10 };
    case "paid":
      return { label: "Paid - Awaiting Processing", color: "bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400", icon: Package, progress: 25 };
    case "processing":
      return { label: "Processing", color: "bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400", icon: Package, progress: 50 };
    case "shipped":
      return { label: "Shipped", color: "bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400", icon: Truck, progress: 75 };
    case "delivered":
    case "completed":
      return { label: "Delivered", color: "bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400", icon: CheckCircle2, progress: 100 };
    case "cancelled":
    case "refund_requested":
    case "refunded":
      return { label: status.replace("_", " "), color: "bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400", icon: AlertCircle, progress: 0 };
    default:
      return { label: status, color: "bg-zinc-100 text-zinc-800 dark:bg-zinc-800 dark:text-zinc-400", icon: Clock, progress: 0 };
  }
};

export default async function BuyerOrdersPage() {
  const session = await auth();
  
  if (!session?.user) return null;

  const userOrders = await db.query.orders.findMany({
    where: eq(orders.buyerId, session.user.id),
    orderBy: [desc(orders.createdAt)],
    with: {
      items: {
        with: {
          product: true,
        }
      },
      seller: true
    }
  });

  return (
    <div className="max-w-5xl">
      <div className="mb-8">
        <h1 className="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Order History</h1>
        <p className="text-zinc-500 dark:text-zinc-400 mt-1">Track your recent orders and view order details.</p>
      </div>

      {userOrders.length === 0 ? (
        <div className="flex flex-col items-center justify-center py-20 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl">
          <div className="h-20 w-20 bg-zinc-50 dark:bg-zinc-800 text-zinc-400 rounded-full flex items-center justify-center mb-6">
            <Package size={32} />
          </div>
          <h2 className="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">No orders yet</h2>
          <p className="text-zinc-500 dark:text-zinc-400 mb-8 max-w-sm text-center">
            When you purchase items from sellers, your order tracking will appear here.
          </p>
          <Link 
            href="/products" 
            className="px-6 py-3 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-medium rounded-xl hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors"
          >
            Start Shopping
          </Link>
        </div>
      ) : (
        <div className="space-y-6">
          {userOrders.map((order) => {
            const { label, color, icon: StatusIcon, progress } = getStatusDetails(order.status);
            
            return (
              <div key={order.id} className="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl overflow-hidden shadow-sm">
                <div className="bg-zinc-50 dark:bg-zinc-950/50 px-6 py-4 border-b border-zinc-100 dark:border-zinc-800 flex flex-wrap gap-4 items-center justify-between">
                  <div className="flex flex-wrap gap-6">
                    <div>
                      <p className="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-1">Order Placed</p>
                      <p className="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {new Date(order.createdAt).toLocaleDateString()}
                      </p>
                    </div>
                    <div>
                      <p className="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-1">Total</p>
                      <p className="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        R {Number(order.total).toFixed(2)}
                      </p>
                    </div>
                    <div>
                      <p className="text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider mb-1">Order #</p>
                      <p className="text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                        {order.orderNumber}
                      </p>
                    </div>
                  </div>
                  <div>
                    <span className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold ${color}`}>
                      <StatusIcon size={14} />
                      {label}
                    </span>
                  </div>
                </div>

                <div className="p-6">
                  {/* Timeline progress bar */}
                  {progress > 0 && order.status !== "cancelled" && (
                    <div className="mb-8">
                      <div className="h-2 w-full bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                        <div 
                          className="h-full bg-indigo-500 rounded-full transition-all duration-1000 ease-in-out" 
                          style={{ width: `${progress}%` }}
                        />
                      </div>
                      <div className="flex justify-between text-xs font-medium text-zinc-500 dark:text-zinc-400 mt-2 px-1">
                        <span>Paid</span>
                        <span>Processing</span>
                        <span>Shipped</span>
                        <span>Delivered</span>
                      </div>
                    </div>
                  )}

                  {/* Order Items */}
                  <div className="space-y-4">
                    {order.items.map((item) => (
                      <div key={item.id} className="flex gap-4">
                        <div className="w-20 h-20 bg-zinc-50 dark:bg-zinc-800 rounded-xl overflow-hidden relative border border-zinc-100 dark:border-zinc-800 flex-shrink-0">
                          {item.product.images?.[0] ? (
                            <img src={item.product.images[0]} alt={item.product.title} className="w-full h-full object-cover" />
                          ) : (
                            <div className="absolute inset-0 flex items-center justify-center text-zinc-300">
                              <Package size={24} />
                            </div>
                          )}
                        </div>
                        <div className="flex-1 min-w-0">
                          <Link href={`/product/${item.product.slug}`} className="text-base font-semibold text-zinc-900 dark:text-zinc-100 hover:text-indigo-600 dark:hover:text-indigo-400 line-clamp-1">
                            {item.product.title}
                          </Link>
                          <p className="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Qty: {item.quantity}</p>
                          <p className="text-sm font-medium text-zinc-900 dark:text-zinc-100 mt-1">R {Number(item.unitPrice).toFixed(2)}</p>
                        </div>
                      </div>
                    ))}
                  </div>

                  {/* Seller Info & Tracking */}
                  <div className="mt-6 pt-6 border-t border-zinc-100 dark:border-zinc-800 flex flex-wrap justify-between gap-4 items-center">
                    <p className="text-sm text-zinc-600 dark:text-zinc-400">
                      Sold by: <span className="font-semibold text-zinc-900 dark:text-zinc-100">{order.seller.name}</span>
                    </p>
                    
                    {order.trackingNumber && (
                      <div className="flex items-center gap-2 text-sm">
                        <span className="text-zinc-500">Tracking:</span>
                        <span className="font-mono font-medium text-zinc-900 dark:text-zinc-100 px-2 py-1 bg-zinc-50 dark:bg-zinc-800 rounded">{order.trackingNumber}</span>
                      </div>
                    )}
                  </div>
                </div>
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
}
