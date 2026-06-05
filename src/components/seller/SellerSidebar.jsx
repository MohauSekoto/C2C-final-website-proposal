"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { LayoutDashboard, Package, ShoppingBag, WalletCards, Settings } from "lucide-react";

const navigation = [
  { name: "Overview", href: "/seller/dashboard", icon: LayoutDashboard },
  { name: "Products", href: "/seller/products", icon: Package },
  { name: "Orders", href: "/seller/orders", icon: ShoppingBag },
  { name: "Earnings", href: "/seller/earnings", icon: WalletCards },
];

export default function SellerSidebar() {
  const pathname = usePathname();

  return (
    <div className="hidden lg:flex w-64 flex-col border-r border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 pt-8 px-4 h-full min-h-[calc(100vh-73px)] sticky top-[73px]">
      <div className="mb-8 px-4">
        <p className="text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Seller Hub</p>
      </div>
      
      <nav className="flex-1 space-y-2">
        {navigation.map((item) => {
          const isActive = pathname.startsWith(item.href) && (item.href !== '/seller/dashboard' || pathname === '/seller/dashboard');
          return (
            <Link
              key={item.name}
              href={item.href}
              className={`flex items-center gap-3 px-4 py-3 rounded-sm transition-all font-medium text-sm ${
                isActive 
                  ? "bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400" 
                  : "text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 hover:text-zinc-900 dark:hover:text-zinc-100"
              }`}
            >
              <item.icon size={18} className={isActive ? "text-indigo-600 dark:text-indigo-400" : "text-zinc-400 dark:text-zinc-500"} />
              {item.name}
            </Link>
          );
        })}
      </nav>

      <div className="pt-8 pb-4 border-t border-zinc-100 dark:border-zinc-800">
        <Link
          href="/profile"
          className="flex items-center gap-3 px-4 py-3 rounded-sm transition-all font-medium text-sm text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 hover:text-zinc-900 dark:hover:text-zinc-100"
        >
          <Settings size={18} className="text-zinc-400 dark:text-zinc-500" />
          Store Settings
        </Link>
      </div>
    </div>
  );
}
