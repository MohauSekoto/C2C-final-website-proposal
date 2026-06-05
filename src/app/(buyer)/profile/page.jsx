import { auth } from "../../../auth.js";
import { db } from "../../../lib/db/index.js";
import { users } from "../../../lib/db/schema.js";
import { eq } from "drizzle-orm";
import { updateProfile } from "./actions.js";

export default async function ProfilePage() {
  const session = await auth();
  
  if (!session?.user) return null;

  // Fetch the latest user data including shipping address
  const [userData] = await db.select().from(users).where(eq(users.id, session.user.id));
  const address = userData?.shippingAddress || {};

  return (
    <div className="max-w-3xl">
      <div className="mb-8">
        <h1 className="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">My Profile</h1>
        <p className="text-zinc-500 dark:text-zinc-400 mt-1">Manage your account settings and preferences.</p>
      </div>

      <div className="bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 rounded-3xl p-8 shadow-sm mb-8">
        <h2 className="text-lg font-semibold text-zinc-900 dark:text-zinc-50 mb-6">Personal Information</h2>
        
        <form action={updateProfile} className="space-y-8">
          {/* Basic Info */}
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label htmlFor="name" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Full Name</label>
              <input 
                type="text" 
                id="name" 
                name="name" 
                defaultValue={userData?.name || ''}
                className="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-2.5 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none transition-all"
              />
            </div>
            <div>
              <label htmlFor="email" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Email Address</label>
              <input 
                type="email" 
                id="email" 
                defaultValue={userData?.email || ''}
                disabled
                className="w-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-4 py-2.5 text-zinc-500 dark:text-zinc-400 cursor-not-allowed"
              />
              <p className="text-xs text-zinc-500 mt-1.5">Email cannot be changed.</p>
            </div>
          </div>

          <hr className="border-zinc-100 dark:border-zinc-800" />

          {/* Shipping Address */}
          <div>
            <h3 className="text-md font-semibold text-zinc-900 dark:text-zinc-50 mb-4">Shipping Address</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="md:col-span-2">
                <label htmlFor="street" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Street Address</label>
                <input 
                  type="text" 
                  id="street" 
                  name="street" 
                  defaultValue={address.street || ''}
                  className="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-2.5 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none transition-all"
                />
              </div>
              <div>
                <label htmlFor="city" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">City</label>
                <input 
                  type="text" 
                  id="city" 
                  name="city" 
                  defaultValue={address.city || ''}
                  className="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-2.5 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none transition-all"
                />
              </div>
              <div>
                <label htmlFor="province" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Province</label>
                <select 
                  id="province" 
                  name="province" 
                  defaultValue={address.province || ''}
                  className="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-2.5 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none transition-all"
                >
                  <option value="">Select Province...</option>
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
              <div>
                <label htmlFor="postalCode" className="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">Postal Code</label>
                <input 
                  type="text" 
                  id="postalCode" 
                  name="postalCode" 
                  defaultValue={address.postalCode || ''}
                  className="w-full bg-zinc-50 dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 rounded-xl px-4 py-2.5 text-zinc-900 dark:text-zinc-100 focus:ring-2 focus:ring-indigo-600 outline-none transition-all"
                />
              </div>
            </div>
          </div>

          <div className="pt-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
            <button type="submit" className="px-6 py-2.5 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 font-medium rounded-xl hover:bg-zinc-800 dark:hover:bg-zinc-200 transition-colors shadow-sm">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
