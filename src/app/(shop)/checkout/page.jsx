"use client";

import { useState, useEffect } from 'react';
import { useCart } from '../../../context/CartContext.jsx';
import { useRouter } from 'next/navigation';
import { Lock, Loader2 } from 'lucide-react';

export default function CheckoutPage() {
  const { items, cartTotal, totalItems } = useCart();
  const router = useRouter();
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [address, setAddress] = useState({
    street: '',
    city: '',
    province: 'Gauteng',
    zip: ''
  });

  const [session, setSession] = useState(null);
  const [paymentMethod, setPaymentMethod] = useState('payfast'); // 'payfast' or 'mock'

  // Fetch session and prefill address
  useEffect(() => {
    fetch('/api/auth/session')
      .then(res => res.json())
      .then(data => {
        if (data && Object.keys(data).length > 0 && data.user) {
          setSession(data);
          if (data.user.shippingAddress) {
            setAddress(data.user.shippingAddress);
          }
        } else {
          router.push('/cart'); // Redirect if not logged in
        }
      });
  }, [router]);

  // Redirect if cart is empty
  useEffect(() => {
    if (totalItems === 0) {
      router.push('/cart');
    }
  }, [totalItems, router]);

  if (totalItems === 0) return null;

  const shippingCost = 50.00;
  const total = cartTotal + shippingCost;

  const handleCheckout = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    try {
      const res = await fetch('/api/checkout', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          items,
          shippingAddress: address,
          paymentMethod
        })
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.error || 'Failed to initialize checkout');
      }

      // Auto-submit PayFast form
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = data.payfastUrl;
      
      for (const key in data.formData) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = data.formData[key];
        form.appendChild(input);
      }

      document.body.appendChild(form);
      form.submit();

    } catch (err) {
      setError(err.message);
      setLoading(false);
    }
  };

  return (
    <div className="container mx-auto px-4 sm:px-6 lg:px-8 py-12 max-w-7xl">
      <h1 className="text-3xl font-bold mb-8">Secure Checkout</h1>
      
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Checkout Form */}
        <div className="lg:col-span-7">
          <form id="checkout-form" onSubmit={handleCheckout} className="space-y-8">
            <div className="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8">
              <h2 className="text-xl font-semibold mb-6 flex items-center gap-2">
                <Lock size={20} className="text-zinc-400" />
                Shipping Details
              </h2>
              
              {error && (
                <div className="mb-6 p-4 bg-red-50 text-red-600 rounded-lg text-sm border border-red-100">
                  {error}
                </div>
              )}

              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium mb-1.5">Street Address</label>
                  <input required type="text" value={address.street} onChange={e => setAddress({...address, street: e.target.value})} className="w-full px-4 py-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="123 Nelson Mandela Blvd" />
                </div>
                
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-sm font-medium mb-1.5">City</label>
                    <input required type="text" value={address.city} onChange={e => setAddress({...address, city: e.target.value})} className="w-full px-4 py-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Cape Town" />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-1.5">Postal Code</label>
                    <input required type="text" value={address.zip} onChange={e => setAddress({...address, zip: e.target.value})} className="w-full px-4 py-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="8001" />
                  </div>
                </div>

                <div>
                  <label className="block text-sm font-medium mb-1.5">Province</label>
                  <select value={address.province} onChange={e => setAddress({...address, province: e.target.value})} className="w-full px-4 py-3 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800 focus:ring-2 focus:ring-indigo-500 outline-none">
                    <option value="Gauteng">Gauteng</option>
                    <option value="Western Cape">Western Cape</option>
                    <option value="KwaZulu-Natal">KwaZulu-Natal</option>
                    <option value="Eastern Cape">Eastern Cape</option>
                    <option value="Free State">Free State</option>
                    <option value="Mpumalanga">Mpumalanga</option>
                    <option value="Limpopo">Limpopo</option>
                    <option value="North West">North West</option>
                    <option value="Northern Cape">Northern Cape</option>
                  </select>
                </div>
              </div>
            </div>
          </form>
        </div>

        {/* Order Summary */}
        <div className="lg:col-span-5">
          <div className="bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 md:p-8 sticky top-24">
            <h3 className="text-xl font-semibold mb-6">Order Summary</h3>
            
            <div className="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
              {items.map(item => (
                <div key={item.id} className="flex gap-4">
                  <div className="w-16 h-16 relative rounded-lg overflow-hidden bg-zinc-200 shrink-0">
                    <img src={item.imageUrl} alt={item.title} className="object-cover w-full h-full" />
                  </div>
                  <div className="flex-1">
                    <h4 className="text-base font-medium line-clamp-2">{item.title}</h4>
                    <p className="text-sm text-zinc-500 mt-2">Qty: {item.quantity}</p>
                  </div>
                  <div className="text-base font-medium whitespace-nowrap">
                    R {(Number(item.price) * item.quantity).toFixed(2)}
                  </div>
                </div>
              ))}
            </div>

            <div className="border-t border-zinc-200 dark:border-zinc-800 py-4 space-y-3">
              <div className="flex justify-between text-sm text-zinc-500">
                <span>Subtotal</span>
                <span>R {cartTotal.toFixed(2)}</span>
              </div>
              <div className="flex justify-between text-sm text-zinc-500">
                <span>Shipping</span>
                <span>R {shippingCost.toFixed(2)}</span>
              </div>
            </div>

            <div className="border-t border-zinc-200 dark:border-zinc-800 pt-4 mb-8 flex justify-between items-center">
              <span className="font-semibold text-lg">Total</span>
              <span className="font-bold text-2xl text-indigo-600 dark:text-indigo-400">R {total.toFixed(2)}</span>
            </div>

            <div className="mb-8">
              <h3 className="text-base font-medium mb-3">Payment Method</h3>
              <div className="space-y-3">
                <label className="flex items-center gap-3 p-3 border border-zinc-200 dark:border-zinc-800 rounded-xl cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                  <input type="radio" name="paymentMethod" value="payfast" checked={paymentMethod === 'payfast'} onChange={() => setPaymentMethod('payfast')} className="w-4 h-4 text-indigo-600 focus:ring-indigo-500" />
                  <div className="text-sm font-medium">PayFast Sandbox <span className="text-xs text-zinc-500 font-normal ml-1">(Requires Ngrok)</span></div>
                </label>
                <label className="flex items-center gap-3 p-3 border border-zinc-200 dark:border-zinc-800 rounded-xl cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors">
                  <input type="radio" name="paymentMethod" value="mock" checked={paymentMethod === 'mock'} onChange={() => setPaymentMethod('mock')} className="w-4 h-4 text-indigo-600 focus:ring-indigo-500" />
                  <div className="text-sm font-medium">Local Mock Gateway <span className="text-xs text-zinc-500 font-normal ml-1">(No Ngrok needed)</span></div>
                </label>
              </div>
            </div>

            <button 
              type="submit"
              form="checkout-form"
              disabled={loading || !session}
              className="w-full py-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white rounded-xl font-medium transition-colors flex justify-center items-center gap-2"
            >
              {loading ? <Loader2 className="animate-spin" size={20} /> : `Pay R ${total.toFixed(2)}`}
            </button>
            <p className="text-xs text-center text-zinc-500 mt-4">
              You will be securely redirected to {paymentMethod === 'mock' ? 'our simulated gateway' : 'PayFast'} to complete your payment.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
}
