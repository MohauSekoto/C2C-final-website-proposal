"use client";

import { useState } from "react";
import { becomeSeller } from "./actions.js";
import { useRouter } from "next/navigation";
import { useSession } from "next-auth/react";
import { Store, Loader2, ArrowRight } from "lucide-react";

const PROVINCES = [
  "Eastern Cape",
  "Free State",
  "Gauteng",
  "KwaZulu-Natal",
  "Limpopo",
  "Mpumalanga",
  "Northern Cape",
  "North West",
  "Western Cape",
];

export default function BecomeSellerForm() {
  const [error, setError] = useState(null);
  const [isPending, setIsPending] = useState(false);
  const router = useRouter();
  const { update } = useSession();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    setIsPending(true);

    const formData = new FormData(e.target);

    try {
      const result = await becomeSeller(formData);
      
      if (result.error) {
        setError(result.error);
        setIsPending(false);
      } else if (result.success) {
        // Update the NextAuth session JWT to reflect the new role
        await update({ role: "seller" });
        // Redirect to seller dashboard
        router.push("/seller/dashboard");
        router.refresh();
      }
    } catch (err) {
      setError("An unexpected error occurred.");
      setIsPending(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-6">
      {error && (
        <div className="p-4 bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400 rounded-xl text-sm font-medium">
          {error}
        </div>
      )}

      <div>
        <label htmlFor="storeName" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
          Store Name
        </label>
        <input
          type="text"
          id="storeName"
          name="storeName"
          required
          placeholder="e.g. Kasi Crafts"
          className="w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
        />
      </div>

      <div>
        <label htmlFor="storeDescription" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
          Store Description
        </label>
        <textarea
          id="storeDescription"
          name="storeDescription"
          required
          rows={4}
          placeholder="Tell buyers what you sell and the story behind your products..."
          className="w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all resize-none"
        />
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <label htmlFor="locationCity" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
            City
          </label>
          <input
            type="text"
            id="locationCity"
            name="locationCity"
            required
            placeholder="e.g. Soweto"
            className="w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all"
          />
        </div>

        <div>
          <label htmlFor="locationProvince" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-2">
            Province
          </label>
          <select
            id="locationProvince"
            name="locationProvince"
            required
            defaultValue=""
            className="w-full px-4 py-3 bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all appearance-none"
          >
            <option value="" disabled>Select your province</option>
            {PROVINCES.map((p) => (
              <option key={p} value={p}>{p}</option>
            ))}
          </select>
        </div>
      </div>

      <div className="pt-6">
        <button
          type="submit"
          disabled={isPending}
          className="w-full flex items-center justify-center gap-2 px-6 py-4 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 disabled:opacity-70 disabled:cursor-not-allowed transition-colors"
        >
          {isPending ? (
            <Loader2 size={20} className="animate-spin" />
          ) : (
            <>
              Open My Store <ArrowRight size={20} />
            </>
          )}
        </button>
      </div>
    </form>
  );
}
