import { auth } from "../../../auth.js";
import { redirect } from "next/navigation";
import Link from 'next/link';

export default async function LoginPage(props) {
  const searchParams = await props.searchParams;
  const session = await auth();
  
  if (session?.user) {
    if (session.user.role === 'seller') redirect('/seller/dashboard');
    if (session.user.role === 'buyer') redirect('/orders');
    redirect('/');
  }

  return (
    <div className="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-zinc-50">
      <div className="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-sm border border-zinc-100">
        <div className="text-center">
          <h2 className="text-3xl font-bold tracking-tight text-zinc-900">
            Welcome Back
          </h2>
          <p className="mt-2 text-sm text-zinc-500">
            Sign in to your KasiBuy account
          </p>
        </div>

        {searchParams?.error && (
          <div className="p-4 text-sm text-red-600 bg-red-50 rounded-xl border border-red-100">
            Invalid email or password. Please try again.
          </div>
        )}

        <form className="mt-8 space-y-6" action="/api/auth/callback/credentials" method="POST">
          <div className="space-y-4">
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-zinc-700 mb-1">Email Address</label>
              <input 
                type="email" 
                id="email" 
                name="email" 
                required 
                className="block w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-zinc-900 placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                placeholder="you@example.com"
              />
            </div>
            <div>
              <label htmlFor="password" className="block text-sm font-medium text-zinc-700 mb-1">Password</label>
              <input 
                type="password" 
                id="password" 
                name="password" 
                required 
                className="block w-full px-4 py-3 bg-zinc-50 border border-zinc-200 rounded-xl text-zinc-900 placeholder-zinc-400 focus:bg-white focus:ring-2 focus:ring-indigo-600 focus:border-transparent outline-none transition-all"
                placeholder="••••••••"
              />
            </div>
          </div>

          <div>
            <button 
              type="submit" 
              className="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-zinc-900 hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-900 transition-all transform active:scale-[0.98]"
            >
              Sign In
            </button>
          </div>
        </form>

        <div className="text-center mt-6">
          <p className="text-sm text-zinc-500">
            Don't have an account?{' '}
            <Link href="/register" className="font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
              Register here
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}
