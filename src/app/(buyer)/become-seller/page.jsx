import { auth } from "../../../auth.js";
import Link from "next/link";
import { Store, CheckCircle } from "lucide-react";
import BecomeSellerForm from "./BecomeSellerForm.jsx";

export default async function BecomeSellerPage() {
  const session = await auth();

  if (!session?.user) return null;

  if (session.user.role === "seller") {
    return (
      <div className="max-w-2xl mx-auto py-12">
        <div className="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl p-8 text-center shadow-sm">
          <div className="mx-auto w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mb-6">
            <CheckCircle size={32} />
          </div>
          <h1 className="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 mb-4">
            You're already a seller!
          </h1>
          <p className="text-zinc-500 dark:text-zinc-400 mb-8 max-w-md mx-auto">
            Your store is open and ready. Head over to your Seller Dashboard to manage your products, track orders, and view your earnings.
          </p>
          <Link
            href="/seller/dashboard"
            className="inline-flex items-center gap-2 px-6 py-3 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-medium rounded-xl hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors"
          >
            Go to Seller Dashboard
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="max-w-3xl mx-auto py-8">
      <div className="mb-10 text-center">
        <div className="mx-auto w-16 h-16 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-2xl flex items-center justify-center mb-6 shadow-sm transform -rotate-3">
          <Store size={32} className="transform rotate-3" />
        </div>
        <h1 className="text-3xl md:text-4xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50 mb-4">
          Become a Seller
        </h1>
        <p className="text-lg text-zinc-500 dark:text-zinc-400 max-w-xl mx-auto leading-relaxed">
          Join KasiBuy and start selling your amazing products to customers all across South Africa. 
          Fill out the details below to open your store.
        </p>
      </div>

      <div className="bg-white dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-800 rounded-3xl p-8 md:p-10 shadow-sm">
        <BecomeSellerForm />
      </div>
    </div>
  );
}
