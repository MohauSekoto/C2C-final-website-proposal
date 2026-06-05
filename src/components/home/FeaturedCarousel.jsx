"use client";

import { useRef } from "react";
import ProductCard from "../product/ProductCard.jsx";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function FeaturedCarousel({ products }) {
  const scrollRef = useRef(null);

  // Duplicate items to create a massive pseudo-infinite scroll list
  const circularProducts = Array(10).fill(products).flat();

  const scroll = (direction) => {
    if (scrollRef.current) {
      // Card width is roughly 320px + 32px gap = 352px scroll amount
      const scrollAmount = 352; 
      scrollRef.current.scrollBy({ 
        left: direction === 'left' ? -scrollAmount : scrollAmount, 
        behavior: 'smooth' 
      });
    }
  };

  return (
    <div className="relative group px-4 sm:px-12 -mx-4 sm:-mx-12">
      {/* Left Arrow */}
      <button 
        onClick={() => scroll('left')}
        className="absolute left-0 top-1/2 -translate-y-1/2 z-10 p-3 bg-zinc-900/80 dark:bg-zinc-100/80 backdrop-blur-sm rounded-full shadow-xl text-white dark:text-zinc-900 opacity-0 group-hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 hidden sm:flex items-center justify-center"
        aria-label="Scroll left"
      >
        <ChevronLeft size={24} />
      </button>
      
      {/* Right Arrow */}
      <button 
        onClick={() => scroll('right')}
        className="absolute right-0 top-1/2 -translate-y-1/2 z-10 p-3 bg-zinc-900/80 dark:bg-zinc-100/80 backdrop-blur-sm rounded-full shadow-xl text-white dark:text-zinc-900 opacity-0 group-hover:opacity-100 hover:scale-105 active:scale-95 transition-all duration-300 hidden sm:flex items-center justify-center"
        aria-label="Scroll right"
      >
        <ChevronRight size={24} />
      </button>

      {/* Scroll Container */}
      <div 
        ref={scrollRef}
        className="flex overflow-x-auto snap-x snap-mandatory gap-8 pb-8 pt-4 hide-scrollbar scroll-smooth px-4 sm:px-0"
      >
        {circularProducts.map((product, index) => (
          <div key={`${product.id}-${index}`} className="min-w-[75vw] sm:min-w-[280px] lg:min-w-[320px] snap-center flex-shrink-0">
            <ProductCard 
              id={product.id}
              title={product.title}
              price={product.price}
              category={product.category || "Featured"}
              imageUrl={product.imageUrl || product.images?.[0] || 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?q=80&w=600'} 
            />
          </div>
        ))}
      </div>
    </div>
  );
}
