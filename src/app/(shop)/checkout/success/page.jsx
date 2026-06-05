"use client";

import { useEffect, useRef } from 'react';
import { useCart } from '../../../../context/CartContext.jsx';
import { CheckCircle } from 'lucide-react';
import Link from 'next/link';

export default function CheckoutSuccessPage() {
  const { clearCart } = useCart();
  const cleared = useRef(false);

  useEffect(() => {
    if (!cleared.current) {
      clearCart();
      cleared.current = true;
    }
  }, [clearCart]);

  return (
    <div className="min-h-[70vh] flex items-center justify-center">
      <div className="text-center max-w-md p-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-sm mx-4">
        <div className="w-20 h-20 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
          <CheckCircle size={40} />
        </div>
        <h1 className="text-3xl font-bold mb-4">Payment Successful!</h1>
        <p className="text-zinc-500 dark:text-zinc-400 mb-8 leading-relaxed">
          Thank you for supporting local business. Your order is currently being processed and you will receive an email confirmation shortly.
        </p>
        <Link href="/products" className="inline-block w-full py-4 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 rounded-xl font-medium hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors">
          Continue Shopping
        </Link>
      </div>
    </div>
  );
}
