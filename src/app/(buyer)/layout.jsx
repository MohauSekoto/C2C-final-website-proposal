import { auth } from "../../auth.js";
import { redirect } from "next/navigation";
import BuyerSidebar from "../../components/buyer/BuyerSidebar.jsx";

export default async function BuyerLayout({ children }) {
  const session = await auth();

  // Any logged-in user can access the buyer area
  if (!session?.user) {
    redirect('/login?callbackUrl=/profile');
  }

  return (
    <div className="flex min-h-[calc(100vh-80px)] bg-zinc-50/50 dark:bg-zinc-950/50">
      <BuyerSidebar />
      <div className="flex-1 max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        {children}
      </div>
    </div>
  );
}
