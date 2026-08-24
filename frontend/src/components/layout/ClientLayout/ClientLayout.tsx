"use client";

import { usePathname, useRouter } from "next/navigation";
import Navbar from "@/src/components/layout/Navbar/index";
import Footer from "@/src/components/layout/Footer/index";
import type { TabType } from "@/src/routes";
import { pathToTab, tabToPath } from "@/src/routes";

export default function ClientLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const router = useRouter();

  const activeTab = pathToTab[pathname] || "home";

  const handleTabChange = (tab: TabType) => {
    router.push(tabToPath[tab]);
  };

  return (
    <>
      <Navbar activeTab={activeTab} onTabChange={handleTabChange} />
      {children}
      <Footer />
    </>
  );
}
