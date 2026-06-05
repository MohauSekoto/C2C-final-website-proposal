"use client";

import { useState } from 'react';
import { useCart } from '../../../../context/CartContext.jsx';
import { ShoppingBag, Check } from 'lucide-react';

export default function AddToCartButton({ product }) {
  const { addItem } = useCart();
  const [isAdded, setIsAdded] = useState(false);
  const [quantity, setQuantity] = useState(1);

  const handleAdd = () => {
    addItem({
      id: product.id,
      title: product.title,
      price: product.price,
      quantity: quantity,
      sellerId: product.sellerId,
      imageUrl: product.images ? product.images[0] : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600'
    });
    
    setIsAdded(true);
    setTimeout(() => setIsAdded(false), 2000);
  };

  return (
    <div className="flex flex-col sm:flex-row gap-4">
      <div className="flex items-center border border-zinc-200 dark:border-zinc-800 rounded-full bg-white dark:bg-zinc-900 overflow-hidden w-full sm:w-32">
        <button 
          onClick={() => setQuantity(Math.max(1, quantity - 1))}
          className="px-4 py-4 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
        >
          -
        </button>
        <span className="flex-1 text-center font-medium text-zinc-900 dark:text-zinc-100">{quantity}</span>
        <button 
          onClick={() => setQuantity(quantity + 1)}
          className="px-4 py-4 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 hover:bg-zinc-50 dark:hover:bg-zinc-800 transition-colors"
        >
          +
        </button>
      </div>
      
      <button 
        onClick={handleAdd}
        disabled={isAdded}
        className={`flex-1 flex items-center justify-center gap-2 px-8 py-4 rounded-full font-medium transition-all duration-300 ${
          isAdded 
            ? 'bg-green-500 text-white' 
            : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-600/20'
        }`}
      >
        {isAdded ? (
          <><Check size={20} /> Added to Cart</>
        ) : (
          <><ShoppingBag size={20} /> Add to Cart</>
        )}
      </button>
    </div>
  );
}
