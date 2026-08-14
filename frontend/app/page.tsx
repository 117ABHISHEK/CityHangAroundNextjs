"use client";

import { useState } from "react";
import Topbar from "@/src/components/layout/Topbar/index";
import Navbar from "@/src/components/layout/Navbar/index";
import Footer from "@/src/components/layout/Footer/index";
import HomeFeature from "@/src/features/home";
import CommunitySection from "@/src/features/community";

type TabType = "home" | "community" | "city-guide" | "buy-sell" | "marketplace" | "blog" | "contact";

export default function Home() {
  const [activeTab, setActiveTab] = useState<TabType>("home");

  return (
    <div className="min-h-screen bg-slate-50 text-slate-950">
      <Topbar />
      <Navbar activeTab={activeTab} onTabChange={setActiveTab} />
      
      {activeTab === "home" && <HomeFeature />}
      {activeTab === "community" && <CommunitySection />}
      
      <Footer />
    </div>
  );
}
