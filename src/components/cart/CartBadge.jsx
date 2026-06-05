"use client";

import Link from 'next/link';
import { ShoppingCart } from 'lucide-react';
import { useCart } from '../../context/CartContext.jsx';
import { useState, useEffect } from 'react';

export default function CartBadge() {
  const { totalItems } = useCart();
  const [mounted, setMounted] = useState(false);

  useEffect(() => setMounted(true), []);

  return (
    <Link href="/cart" className="relative text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
      <ShoppingCart size={20} />
      {mounted && totalItems > 0 && (
        <span className="absolute -top-2 -right-2 flex h-4 w-4 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white">
          {totalItems}
        </span>
      )}
    </Link>
  );
}
