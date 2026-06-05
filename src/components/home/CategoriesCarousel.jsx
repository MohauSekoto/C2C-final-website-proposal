"use client";

import { useRef } from "react";
import CategoryCard from "../product/CategoryCard.jsx";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default function CategoriesCarousel() {
  const scrollRef = useRef(null);

  const mockCategories = [
    { id: 1, name: "Fashion and accessories", description: "Locally stitched clothing and handmade accessories." },
    { id: 2, name: "Home products", description: "Vibrant art, woven baskets, and bespoke furniture." },
    { id: 3, name: "Food", description: "Farm-fresh preserves, spices, and tea blends." },
    { id: 4, name: "Electronics", description: "Refurbished gadgets, custom PCs, and tech accessories." }
  ];

  // Duplicate categories 10 times to create a massive pseudo-infinite scroll list
  const circularCategories = Array(10).fill(mockCategories).flat();

  const scroll = (direction) => {
    if (scrollRef.current) {
      // 320px is the card min-width + 24px gap = 344px scroll amount
      const scrollAmount = 344; 
      scrollRef.current.scrollBy({ 
        left: direction === 'left' ? -scrollAmount : scrollAmount, 
        behavior: 'smooth' 
      });
    }
  };

  return (
    <div className="relative group px-4 sm:px-12">
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
        className="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 hide-scrollbar scroll-smooth"
      >
        {circularCategories.map((cat, index) => (
          <div key={`${cat.id}-${index}`} className="min-w-[85vw] sm:min-w-[320px] snap-center flex-shrink-0">
            <CategoryCard {...cat} />
          </div>
        ))}
      </div>
    </div>
  );
}
