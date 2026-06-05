"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { ShoppingBag, Heart, User, Store } from "lucide-react";

const navigation = [
  { name: "My Orders", href: "/orders", icon: ShoppingBag },
  { name: "Wishlist", href: "/wishlist", icon: Heart },
  { name: "My Profile", href: "/profile", icon: User },
];

export default function BuyerSidebar() {
  const pathname = usePathname();

  return (
    <div className="hidden lg:flex w-64 flex-col border-r border-zinc-100 dark:border-zinc-800 bg-white dark:bg-zinc-950 pt-8 px-6 h-full min-h-[calc(100vh-80px)] sticky top-[80px]">
      <div className="mb-8">
        <h2 className="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight">My Account</h2>
      </div>
      
      <nav className="flex-1 space-y-2">
        {navigation.map((item) => {
          const isActive = pathname.startsWith(item.href);
          return (
            <Link
              key={item.name}
              href={item.href}
              className={`flex items-center gap-3 px-4 py-3 rounded-2xl transition-all font-medium text-sm ${
                isActive 
                  ? "bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 shadow-sm" 
                  : "text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-900 hover:text-zinc-900 dark:hover:text-zinc-100"
              }`}
            >
              <item.icon size={18} className={isActive ? "text-indigo-600 dark:text-indigo-400" : "text-zinc-400 dark:text-zinc-500"} />
              {item.name}
            </Link>
          );
        })}
      </nav>

      <div className="pt-8 pb-8 border-t border-zinc-100 dark:border-zinc-800">
        <div className="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-950/30 dark:to-purple-950/30 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-900/50">
          <Store size={24} className="text-indigo-600 dark:text-indigo-400 mb-3" />
          <h3 className="font-semibold text-zinc-900 dark:text-zinc-100 text-sm mb-1">Become a Seller</h3>
          <p className="text-xs text-zinc-600 dark:text-zinc-400 mb-3">Start your own shop and sell to the KasiBuy community.</p>
          <Link
            href="/become-seller"
            className="block w-full text-center px-4 py-2 bg-white dark:bg-zinc-900 text-indigo-600 dark:text-indigo-400 text-xs font-semibold rounded-xl border border-indigo-200 dark:border-indigo-800 hover:bg-indigo-50 dark:hover:bg-zinc-800 transition-colors"
          >
            Open Shop
          </Link>
        </div>
      </div>
    </div>
  );
}
