import Image from 'next/image';
import Link from 'next/link';

export default function ProductCard({ id, title, price, category, imageUrl }) {
  return (
    <div className="group flex flex-col gap-4">
      <Link href={`/product/${id}`} className="relative aspect-[4/5] overflow-hidden rounded-2xl bg-zinc-100 dark:bg-zinc-800">
        <Image 
          src={imageUrl} 
          alt={title} 
          fill 
          style={{ objectFit: 'cover' }} 
          className="transition-transform duration-700 group-hover:scale-105"
          unoptimized
        />
        <div className="absolute top-4 right-4 px-3 py-1 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-sm rounded-full text-xs font-semibold text-zinc-900 dark:text-zinc-100 shadow-sm border border-black/5 dark:border-white/10">
          {category}
        </div>
      </Link>
      
      <div className="flex flex-col items-center text-center space-y-1">
        <h3 className="text-sm font-medium text-zinc-900 dark:text-zinc-100">
          <Link href={`/product/${id}`} className="hover:underline decoration-zinc-300 dark:decoration-zinc-600 underline-offset-4">
            {title}
          </Link>
        </h3>
        <p className="text-sm text-zinc-500 dark:text-zinc-400">R {Number(price).toFixed(2)}</p>
      </div>
    </div>
  );
}
