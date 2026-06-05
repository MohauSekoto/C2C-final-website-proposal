import Link from 'next/link';

export default function Footer() {
  return (
    <footer className="bg-white dark:bg-zinc-950 border-t border-zinc-100 dark:border-zinc-800 mt-auto pt-24 pb-12 transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 md:grid-cols-4 gap-12 lg:gap-24 mb-16">
          <div className="col-span-1 md:col-span-1">
            <Link href="/" className="text-2xl font-bold tracking-tighter text-zinc-900 dark:text-zinc-50 mb-6 block">
              Kasi<span className="text-indigo-600 dark:text-indigo-500">Buy</span>
            </Link>
            <p className="text-zinc-500 dark:text-zinc-400 text-sm leading-relaxed">
              South Africa's premier consumer-to-consumer marketplace. Supporting local entrepreneurs through beautiful digital commerce.
            </p>
          </div>
          
          <div>
            <h3 className="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tracking-wider uppercase mb-6">Shop</h3>
            <ul className="space-y-4">
              <li><Link href="/products" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">All Products</Link></li>
              <li><Link href="/categories" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Categories</Link></li>
              <li><Link href="/deals" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Curated Finds</Link></li>
            </ul>
          </div>

          <div>
            <h3 className="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tracking-wider uppercase mb-6">Sell</h3>
            <ul className="space-y-4">
              <li><Link href="/sell" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Start Selling</Link></li>
              <li><Link href="/resources" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Seller Handbook</Link></li>
              <li><Link href="/fees" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Platform Fees</Link></li>
            </ul>
          </div>

          <div>
            <h3 className="text-sm font-semibold text-zinc-900 dark:text-zinc-100 tracking-wider uppercase mb-6">Support</h3>
            <ul className="space-y-4">
              <li><Link href="/help" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Help Center</Link></li>
              <li><Link href="/contact" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Contact Us</Link></li>
              <li><Link href="/trust" className="text-zinc-500 dark:text-zinc-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-sm transition-colors">Trust & Safety</Link></li>
            </ul>
          </div>
        </div>
        
        <div className="pt-8 border-t border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <p className="text-zinc-400 dark:text-zinc-500 text-sm">
            &copy; {new Date().getFullYear()} KasiBuy. All rights reserved.
          </p>
          <div className="flex space-x-6 text-sm text-zinc-400 dark:text-zinc-500">
            <Link href="/privacy" className="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Privacy Policy</Link>
            <Link href="/terms" className="hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">Terms of Service</Link>
          </div>
        </div>
      </div>
    </footer>
  );
}
