"use client";

import { useState } from "react";
import Navbar from "@/src/components/layout/Navbar/index";
import Footer from "@/src/components/layout/Footer/index";
import HomeFeature from "@/src/features/home";
import CommunitySection from "@/src/features/community";

type TabType = "home" | "community" | "city-guide" | "buy-sell" | "marketplace" | "blog" | "event";

export default function Home() {
  const [activeTab, setActiveTab] = useState<TabType>("home");

  return (
    <div className="min-h-screen bg-slate-50 text-slate-950">
      <Navbar activeTab={activeTab} onTabChange={setActiveTab} />
      
      {activeTab === "home" && <HomeFeature />}
      {activeTab === "community" && <CommunitySection />}
      
      <Footer />
    </div>
  );
}
