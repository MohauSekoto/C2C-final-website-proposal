import { auth } from "../../auth.js";
import { redirect } from "next/navigation";
import SellerSidebar from "../../components/seller/SellerSidebar.jsx";

export default async function SellerLayout({ children }) {
  const session = await auth();

  if (!session?.user) {
    redirect("/login");
  }

  if (session.user.role !== "seller") {
    redirect("/");
  }

  return (
    <div className="flex min-h-[calc(100vh-73px)] bg-zinc-50 dark:bg-zinc-950 w-full">
      <SellerSidebar />
      <div className="flex-1 p-4 sm:p-6 lg:p-10 overflow-y-auto overflow-x-hidden w-full max-w-full">
        {children}
      </div>
    </div>
  );
}
