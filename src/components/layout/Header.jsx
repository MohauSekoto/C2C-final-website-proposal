import Link from 'next/link';
import { auth } from '../../auth.js';
import { Search, Menu } from 'lucide-react';
import { ThemeToggle } from '../ThemeToggle.jsx';
import CartBadge from '../cart/CartBadge.jsx';

export default async function Header() {
  const session = await auth();

  return (
    <header className="sticky top-0 z-50 bg-white/80 dark:bg-zinc-950/80 backdrop-blur-md border-b border-zinc-100 dark:border-zinc-800 transition-colors duration-300">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          
          {/* Brand */}
          <div className="flex-shrink-0 flex items-center">
            <Link href="/" className="text-2xl font-bold tracking-tighter text-zinc-900 dark:text-zinc-50">
              Kasi<span className="text-indigo-600 dark:text-indigo-500">Buy</span>
            </Link>
          </div>

          {/* Desktop Nav */}
          <nav className="hidden md:flex flex-1 justify-center space-x-12">
            <Link href="/products" className="text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
              Shop
            </Link>
            <Link href="/categories" className="text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
              Categories
            </Link>
            <Link href="/about" className="text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
              Our Story
            </Link>
          </nav>

          {/* Actions */}
          <div className="flex items-center justify-end space-x-2 sm:space-x-6">
            <ThemeToggle />
            <button className="text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
              <Search size={20} />
            </button>
            
            <CartBadge />
            
            {session?.user ? (
              <div className="flex items-center gap-3">
                <Link 
                  href={session.user.role === 'seller' ? '/seller/dashboard' : '/orders'}
                  className="hidden md:flex items-center px-4 py-2 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 rounded-full hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-all"
                >
                  Dashboard
                </Link>
                <Link 
                  href="/profile"
                  className="p-2 text-zinc-500 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors rounded-full hover:bg-zinc-100 dark:hover:bg-zinc-800"
                  title="Profile"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </Link>
              </div>
            ) : (
              <Link 
                href="/login" 
                className="hidden md:flex items-center ml-4 px-4 py-2 text-sm font-medium text-zinc-900 dark:text-zinc-100 bg-zinc-100 dark:bg-zinc-800 rounded-full hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all"
              >
                Sign In
              </Link>
            )}

            <button className="md:hidden text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 ml-2">
              <Menu size={24} />
            </button>
          </div>

        </div>
      </div>
    </header>
  );
}
