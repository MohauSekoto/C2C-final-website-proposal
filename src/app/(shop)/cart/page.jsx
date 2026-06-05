"use client";

import { useCart } from '../../../context/CartContext.jsx';
import Link from 'next/link';
import Image from 'next/image';
import { Trash2, ArrowRight, ShieldCheck, ShoppingCart } from 'lucide-react';
import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import LoginModal from '../../../components/auth/LoginModal.jsx';

export default function CartPage() {
  const { items, cartTotal, removeItem, updateQuantity } = useCart();
  const [session, setSession] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const router = useRouter();

  useEffect(() => {
    fetch('/api/auth/session')
      .then(res => res.json())
      .then(data => {
        if (data && Object.keys(data).length > 0 && data.user) {
          setSession(data);
        }
      });
  }, []);

  const handleCheckoutClick = (e) => {
    e.preventDefault();
    if (!session) {
      setIsModalOpen(true);
    } else {
      router.push('/checkout');
    }
  };

  if (items.length === 0) {
    return (
      <div className="min-h-[70vh] flex flex-col items-center justify-center bg-zinc-50 dark:bg-zinc-950 px-4">
        <div className="w-24 h-24 bg-zinc-100 dark:bg-zinc-900 rounded-full flex items-center justify-center mb-6">
          <ShoppingCart size={32} className="text-zinc-400 dark:text-zinc-600" />
        </div>
        <h1 className="text-2xl font-bold text-zinc-900 dark:text-zinc-50 mb-2">Your cart is empty</h1>
        <p className="text-zinc-500 dark:text-zinc-400 mb-8 text-center max-w-md">
          Looks like you haven't added any local finds to your cart yet.
        </p>
        <Link 
          href="/products" 
          className="px-8 py-4 bg-indigo-600 text-white rounded-full font-medium hover:bg-indigo-700 transition-colors"
        >
          Start Shopping
        </Link>
      </div>
    );
  }

  return (
    <div className="bg-zinc-50 dark:bg-zinc-950 min-h-screen transition-colors duration-300 py-12 md:py-20">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <h1 className="text-3xl font-bold text-zinc-900 dark:text-zinc-50 mb-10">Shopping Cart</h1>
        
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
          {/* Cart Items List */}
          <div className="lg:col-span-8 space-y-6">
            <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl overflow-hidden">
              <div className="hidden sm:grid sm:grid-cols-12 gap-4 px-8 py-6 border-b border-zinc-100 dark:border-zinc-800 text-sm font-medium text-zinc-500 dark:text-zinc-400">
                <div className="col-span-6">Product</div>
                <div className="col-span-3 text-center">Quantity</div>
                <div className="col-span-3 text-right">Total</div>
              </div>
              
              <ul className="divide-y divide-zinc-100 dark:divide-zinc-800">
                {items.map((item) => (
                  <li key={item.id} className="p-6 sm:px-8 sm:py-6 grid grid-cols-1 sm:grid-cols-12 gap-6 items-center">
                    
                    {/* Product Details */}
                    <div className="col-span-1 sm:col-span-6 flex gap-4">
                      <div className="relative w-20 h-20 sm:w-24 sm:h-24 bg-zinc-100 dark:bg-zinc-800 rounded-xl overflow-hidden flex-shrink-0 border border-zinc-200 dark:border-zinc-700">
                        <Image 
                          src={item.imageUrl} 
                          alt={item.title} 
                          fill 
                          style={{ objectFit: 'cover' }}
                          unoptimized
                        />
                      </div>
                      <div className="flex flex-col justify-center">
                        <h3 className="font-semibold text-zinc-900 dark:text-zinc-100 line-clamp-2">
                          <Link href={`/product/${item.id}`} className="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {item.title}
                          </Link>
                        </h3>
                        <p className="text-sm text-zinc-500 dark:text-zinc-400 mt-1">R {Number(item.price).toFixed(2)}</p>
                      </div>
                    </div>

                    {/* Quantity Controls */}
                    <div className="col-span-1 sm:col-span-3 flex sm:justify-center items-center">
                      <div className="flex items-center border border-zinc-200 dark:border-zinc-700 rounded-full bg-zinc-50 dark:bg-zinc-950 overflow-hidden w-28">
                        <button 
                          onClick={() => updateQuantity(item.id, Math.max(1, item.quantity - 1))}
                          className="px-3 py-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                        >
                          -
                        </button>
                        <span className="flex-1 text-center text-sm font-medium text-zinc-900 dark:text-zinc-100">{item.quantity}</span>
                        <button 
                          onClick={() => updateQuantity(item.id, item.quantity + 1)}
                          className="px-3 py-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors"
                        >
                          +
                        </button>
                      </div>
                    </div>

                    {/* Item Total & Remove */}
                    <div className="col-span-1 sm:col-span-3 flex items-center justify-between sm:justify-end gap-4">
                      <span className="font-semibold text-zinc-900 dark:text-zinc-50">
                        R {(Number(item.price) * item.quantity).toFixed(2)}
                      </span>
                      <button 
                        onClick={() => removeItem(item.id)}
                        className="p-2 text-zinc-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-full transition-colors"
                        aria-label="Remove item"
                      >
                        <Trash2 size={18} />
                      </button>
                    </div>

                  </li>
                ))}
              </ul>
            </div>
          </div>

          {/* Order Summary */}
          <div className="lg:col-span-4">
            <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-8 sticky top-28">
              <h2 className="text-xl font-bold text-zinc-900 dark:text-zinc-50 mb-6">Order Summary</h2>
              
              <div className="space-y-4 mb-6 text-sm">
                <div className="flex justify-between text-zinc-600 dark:text-zinc-400">
                  <span>Subtotal</span>
                  <span className="font-medium text-zinc-900 dark:text-zinc-100">R {cartTotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between text-zinc-600 dark:text-zinc-400">
                  <span>Shipping</span>
                  <span className="text-zinc-400 dark:text-zinc-500">Calculated at checkout</span>
                </div>
              </div>
              
              <div className="pt-6 border-t border-zinc-100 dark:border-zinc-800 mb-8">
                <div className="flex justify-between items-center mb-2">
                  <span className="font-bold text-zinc-900 dark:text-zinc-50 text-lg">Total</span>
                  <span className="font-bold text-indigo-600 dark:text-indigo-400 text-2xl">R {cartTotal.toFixed(2)}</span>
                </div>
                <p className="text-xs text-zinc-500 dark:text-zinc-400 text-right">Including VAT</p>
              </div>

              <button 
                onClick={handleCheckoutClick}
                className="w-full flex items-center justify-center gap-2 px-8 py-4 bg-zinc-900 dark:bg-zinc-50 text-white dark:text-zinc-900 rounded-full font-medium hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors shadow-lg shadow-zinc-900/10 dark:shadow-none"
              >
                Proceed to Checkout <ArrowRight size={18} />
              </button>
              
              <div className="mt-6 flex items-center justify-center gap-2 text-xs text-zinc-500 dark:text-zinc-400">
                <ShieldCheck size={16} className="text-green-500 dark:text-green-400" />
                <span>Secure KasiBuy Escrow Payment</span>
              </div>
            </div>
          </div>
          
        </div>
      </div>

      <LoginModal 
        isOpen={isModalOpen} 
        onClose={() => setIsModalOpen(false)} 
        onSuccess={() => {
          setIsModalOpen(false);
          router.push('/checkout');
        }}
      />
    </div>
  );
}
